$(function() {
	let loading = document.getElementById('loading');
	let payment_options_form = document.getElementById('payment_options');
	let payment_options_submit = document.getElementById('payment_options_submit');
	let billing_info_form = document.getElementById('billing_info');
	let billing_info_submit = document.getElementById('billing_info_submit');
	let card_error_box = document.getElementById('card_error_box');
	let card_error = document.getElementById('card-error');
	let wechat_form = document.getElementById('wechat');
	let confirmation = document.getElementById('confirmation');
	let pay_method = $('#pmethod');

	let billing_name = $('#billing_name');
	let billing_address_line1 = $('#billing_address_line1');
	let billing_address_line2 = $('#billing_address_line2');
	let billing_address_city = $('#billing_address_city');
	let billing_address_state = $('#billing_address_state');
	let billing_address_postal_code = $('#billing_address_postal_code');
	let billing_address_country = $('#billing_address_country');
	let billing_email = $('#billing_email');

	function unhide(elem) {
		elem.classList.remove('hidden');
	}

	function hide(elem) {
		elem.classList.add('hidden');
	}

	function unhide_all(elems) {
		if (elems != null) {
			for (let elem of elems) {
				elem.classList.remove('hidden');
			}
		}
	}

	function hide_all(elems) {
		if (elems != null) {
			for (let elem of elems) {
				elem.classList.add('hidden');
			}
		}
	}

	fetch('/static/js/iso3166.json').then(function(result) {
		return result.json();
	}).then(function(result) {
		result.sort(function(lhs, rhs) {
			return lhs.name.localeCompare(rhs.name);
		});
		let countries = document.getElementById('billing_address_country');
		for (const pair of result) {
			let opt = document.createElement('option');
			opt.appendChild(document.createTextNode(pair.name));
			opt.value = pair.code;
			countries.appendChild(opt);
		}
		countries.value = 'US';
	});

	let req_fields = [
		billing_name,
		billing_address_line1,
		billing_address_city,
		billing_address_postal_code,
		billing_address_country
	];

	function validateEmail($email) {
		let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		return emailReg.test( $email );
	}

	function removeError() {
		let _this = $(this);
		_this.removeClass('contains-error');
		_this.off('change');
	}

	function updateErrors() {
		let nerrors = 0;
		for (const elem of req_fields) {
			elem.off('change');
			if (elem.val() == '') {
				elem.addClass('contains-error');
				elem.on('change', updateErrors);
				nerrors++;
			} else {
				elem.removeClass('contains-error');
			}
		}
		return nerrors;
	}

	function setStatusSuccess() {
		hide_all(confirmation.querySelectorAll('.status'));
		unhide(confirmation.querySelector('.success'));
		unhide(confirmation);
	}

	function setStatusError() {
		hide_all(confirmation.querySelectorAll('.status'));
		unhide(confirmation.querySelector('.error'));
		unhide(confirmation);
	}

	pay_method.on('change', function(event) {
		let meth = $(this).val();
		if (meth === 'Card') {
			billing_info_submit.value = 'Continue';
		} else if (meth === 'WeChat') {
			billing_info_submit.value = 'Generate QR code';
		}
		return true;
	});

	function show_card_form(payment_intent_client_secret) {
		var stripe = Stripe('pk_live_YxigZj219LLVr7vOV7lXzlKm');
		let elements = stripe.elements();
		let card_elem = elements.create('card', {
			'style': {
				'base': {
					'fontFamily': 'Verdana, sans-serif',
					'fontSize': '10px',
					'color': '#cccccc',
					'::placeholder': {
						'color': '#aaaaaa'
					}
				},
				'invalid': {
					'color': '#e5424d',
					':focus': {
						'color': '#cccccc'
					}
				}
			},
			'hidePostalCode': true
		});
		card_elem.mount('#card-element');
		card_elem.addEventListener('change', ({error}) => {
			if (error) {
				card_error.textContent = error.message;
				unhide(card_error_box);
			} else {
				card_error.textContent = '';
				hide(card_error_box);
			}
		});

		$('#billing_info').off('submit');
		$('#billing_info').on('submit', function (event) {
			event.preventDefault();

			billing_info_submit.disabled = true;
			
			let nerrors = 0;
			if (billing_email.val() == '' || !validateEmail(billing_email.val())) {
				billing_email.off('change');
				billing_email.on('change', removeError);
				nerrors++;
			}

			nerrors += updateErrors();
			if (nerrors != 0) {
				billing_info_submit.disabled = false;
				return false;
			}

			$('#billing_info').off('submit');

			hide(billing_info_form);
			unhide(loading);

			stripe.confirmCardPayment(payment_intent_client_secret, {
				receipt_email: billing_email.val(),
				payment_method: {
					card: card_elem,
					billing_details: {
						address: {
							city: billing_address_city.val(),
							country: billing_address_country.val(),
							line1: billing_address_line1.val(),
							line2: billing_address_line2.val(),
							postal_code: billing_address_postal_code.val(),
							state: billing_address_state.val()
						},
						email: billing_email.val(),
						name: billing_name.val()
					}
				}
			}).then(function(result) {
				hide(loading);
				if (result.error) {
					setStatusError();
				} else if (result.paymentIntent.status === 'succeeded') {
					setStatusSuccess();
				} else {
					setStatusError();
				}
			});
			return false;
		});

		hide(loading);
		unhide(billing_info_form);
	}

	function show_wechat_form(source_id, qr_code_url) {
		// Generate QR code
		const qrCode = new QRCode('wechat_qr', {
			text: qr_code_url,
			width: 128,
			height: 128,
			colorDark: '#000000',
			colorLight: '#ffffff',
			correctLevel: QRCode.CorrectLevel.H,
		});

		let wechat_status = confirmation.querySelector('.wechat');

		hide(loading);
		unhide(wechat_status);
		unhide(confirmation);

		const poll_payment_status = async(
			payment_id,
			timeout = 180000,
			interval = 5000,
			start = null
		) => {
			start = start ? start : Date.now();
			const success_states = ['success', 'succeeded'];
			const fail_states = ['canceled', 'failed'];
			const raw_response = await fetch('/forums/payment_query.php', {
				method: "POST",
				credentials: 'same-origin',
				headers: {
					"Content-Type": "application/json"
				},
				body: JSON.stringify({
					payment_id: payment_id,
					csrf_token: $('meta[name="csrf-token"]').attr('content')
				})
			});
			if (raw_response.ok) {
				const response = await raw_response.json();
				// succeeded, canceled, chargeable, consumed, failed, or pending
				if (success_states.includes(response.status)) {
					setStatusSuccess();
				} else if (fail_states.includes(response.status)) {
					setStatusError();
				} else if (Date.now() > (start + timeout)) {
					setStatusError();
				} else {
					setTimeout(
						poll_payment_status,
						interval,
						payment_id,
						timeout,
						interval,
						start
					);
				}
			} else {
				setStatusError();
			}
		};
		poll_payment_status(source_id);
	}

	payment_options_form.addEventListener('submit', function(event) {
		event.preventDefault();

		payment_options_submit.disabled = true;
		hide(payment_options_form);
		unhide(loading);

		fetch("/forums/newpayment.php", {
			method: "POST",
			credentials: 'same-origin',
			headers: {
				"Content-Type": "application/json"
			},
			body: JSON.stringify({
				username: $('#username').val(),
				email: billing_email.val(),
				game: $('#game').val(),
				plan: $('#plan').val(),
				payment_method: pay_method.val(),
				csrf_token: $('meta[name="csrf-token"]').attr('content')
			})
		}).then(function(result) {
			if (!result.ok) {
				throw new Error('Network error');
			}
			return result.json();
		}).then(function(result) {
			if (result.error) {
				throw new Error(result.error);
			}
			if (result.type === 'card') {
				show_card_form(result.token);
			} else if (result.type === 'wechat') {
				show_wechat_form(result.token, result.qr_code_url);
			} else {
				throw new Error('Unexpected type');
			}
		}).catch(function(error) {
			hide(loading);
			setStatusError();
		});
	});
});
