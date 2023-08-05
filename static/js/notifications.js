$(function () {
	function notification_list_link_mousedown(e) {
		var id = $(this).attr('data-notif-id');
		if (id != null) {
			var items = notifications_storage_load();
			for (var i = 0; i < items.length; i++) {
				var item = items[i];
				if (item.id == id) {
					if (item.new != 0) {
						items[i].new = 0;
						notifications_storage_save(items);
					}
					break;
				}
			}
			notifications_list_mark_seen(id);
		}
	}

	function notifications_list_add(msg) {
		var li = document.createElement('li');
		var p = document.createElement('p');
		var d = document.createElement('span');
		var br = document.createElement('br');
		var a = document.createElement('a');

		// Format date
		var local_date = new Date(0);
		local_date.setUTCSeconds(msg.date);
		d.textContent = local_date.toLocaleDateString() + ' ' + local_date.toLocaleTimeString();
		d.className = 'dateTime';

		// Format message
		if (msg.type == 0) {
			if ('id' in msg) {
				a.setAttribute('data-notif-id', msg.id);
			}
			a.setAttribute('href', 'viewtopic.php?pid=' + parseInt(msg.extra) + '#p' + parseInt(msg.extra));
			a.appendChild(document.createTextNode('You were tagged in a post'));
			a.className = 'notification-link';
			a.addEventListener('mousedown', notification_list_link_mousedown);
		}
		a.appendChild(br);
		a.appendChild(d);
		p.appendChild(a);

		// Insert notification
		li.appendChild(p);
		if (msg.new != 0) {
			li.className = 'new-notification';
		} else {
			li.className = 'old-notification';
		}
		var notifications_list = document.getElementById('notifications-list');
		notifications_list.insertBefore(li, notifications_list.firstChild);
	}

	function notifications_list_mark_seen(id) {
		try {
			var li = $('#notifications-list').find('a[data-notif-id=' + id.toString() + ']').parent().parent();
			if (li.hasClass('new-notification')) {
				li.removeClass('new-notification');
				li.addClass('old-notification');
				notifications_set_count(notifications_get_count() - 1);
			}
		} catch (e) {
		}
	}

	function notifications_list_clear() {
		var notifications_list = document.getElementById('notifications-list');
		while (notifications_list.firstChild) {
			notifications_list.removeChild(notifications_list.firstChild);
		}
	}

	function notifications_storage_save(i) {
		try {
			window.localStorage.setItem('notifications', JSON.stringify(i));
		} catch (err) {
		}
	}

	function notifications_storage_clear() {
		try {
			window.localStorage.removeItem('notifications');
		} catch (e) {
		}
	}

	function notifications_clear() {
		notifications_storage_clear();
		notifications_list_clear();
	}

	function notifications_storage_load() {
		var arr = [];
		try {
			var s = window.localStorage.getItem('notifications');
			if (s != null) {
				try {
					var i = JSON.parse(s);
					if (i != null) {
						for (const m of i) {
							if ('new' in m && 'id' in m) {
								arr.push(m);
							}
						}
					}
				} catch (e) {
					notifications_storage_clear();
				}
			}
		} catch (err) {
		}
		return arr;
	}

	function find_first_dead_index(arr) {
		var idx = -1;
		for (var i = 0; i < arr.length; i++) {
			var entry = arr[i];
			if (entry.new != 0) {
				continue;
			}
			idx = i;
			break;
		}
		return idx;
	}

	function notifications_download() {
		$.ajax({
			method: "POST",
			url: "/forums/notifications.php",
			data: { csrf_token: $('meta[name="csrf-token"]').attr('content') },
			success: function (result) {
				if (result != null && result.length > 0) {
					var items = notifications_storage_load();
					for (const msg of result) {
						items.push(msg);
						notifications_list_add(msg);
					}
					while (items.length > 5) {
						var idx = find_first_dead_index(items);
						if (idx == -1) {
							break;
						}
						items.splice(idx, 1);
					}
					notifications_storage_save(items);
				}
			}
		});
	}

	function notifications_get_count() {
		return parseInt($('#notifications-badge').attr('data-after-content'));
	}

	function notifications_set_count(count) {
		count = Math.max(count, 0);
		var badge = $('#notifications-badge');
		if (count > 0) {
			badge.attr('data-after-content', count.toString());
			badge.addClass('badge-unread');
		} else {
			badge.attr('data-after-content', '0');
			badge.removeClass('badge-unread');
		}
	}

	$(document).click(function() {
		$('#notifications-container').hide();
	});

	var num_new = notifications_get_count();
	var should_download = num_new > 0;

	$('#notifications').click(function () {
		var container = $('#notifications-container');
		if (container.toggle().is(':visible')) {
			var badge = $('#notifications-badge');
			if (should_download) {
				should_download = false;
				notifications_download();
			} else {
				var items = notifications_storage_load();
				if (items.length == 0) {
					container.hide();
				}
			}
		}
		return false;
	});

	$('#notifications-clear').click(function() {
		notifications_clear();
		$('#notifications-container').hide();
		notifications_set_count(0);
	});

	var items = notifications_storage_load();
	for (const msg of items) {
		notifications_list_add(msg);
		if (msg.new != 0) {
			num_new++;
		}
	}
	notifications_set_count(num_new);
});
