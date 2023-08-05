<head>
<title>Payment / GameSense</title>
<link rel="stylesheet" type="text/css" href="style/Cobalt1.min.css?v=31" />
<link rel="stylesheet" type="text/css" href="/static/css/font-awesome.min.css" />
<link href="https://fonts.googleapis.com/css?family=Raleway:900,400" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="/static/js/notifications.js?v=4" type="text/javascript" charset="UTF-8"></script>
<script type="text/javascript" src="/static/js/qrcode.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<script type="text/javascript" src="/static/js/payment3.js?v=5"></script>
<style type="text/css">.payment-field { box-sizing: border-box; width: 20em; padding: 5px; } #stripe-elements-card { box-sizing: border-box; width: 20em; padding: 5px; background-color: #212122; border: 1px solid #3e3e3e; }</style>
</head>
<?php
define('PUN_ROOT', dirname(__FILE__).'/');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'header.php';
if ($pun_user['g_read_board'] == '0')
	message($lang_common['No view']);
?>

<?php 

$myid1 = $pun_user['username'];
$myid2 = $pun_user['email'];

 ?>
<div id="brdmain">
<div class="blockform">
	<?php 
	if($pun_user['g_id'] == 4){
		echo "<h2><span>Buy GameSense</span></h2>";
	}
	else
	{	
		echo "<h2><span>Extend GameSense</span></h2>";
	}
	?>
	<div class="box">
		<div id="confirmation" class="fakeform hidden">
			<div class="status success hidden">
				<div class="flex-row">
					<div class="flex-col flex-30">
						<img class="statusimg" src="/static/img/checkmark.svg" />
					</div>
					<div class="flex-col flex-70">
						<h1>Success!</h1>
						<p class="note">We just sent a receipt to your email address, and your subscription will be extended soon.</p>
					</div>
				</div>
			</div>
			<br />
			<div class="status error hidden">
				<div class="flex-row">
					<div class="flex-col flex-30">
						<img class="statusimg" src="/static/img/warning.svg" />
					</div>
					<div class="flex-col flex-70">
						<h1>Payment failed</h1>
						<p>It looks like your payment could not be completed at this time. Avoid using a VPN and be sure to enter your details carefully.</p>
						<p class="error-message"></p>
					</div>
				</div>
			</div>
			<div class="status wechat hidden">
				<div class="flex-row">
					<div id="wechat_qr" class="flex-col flex-30">
					</div>
					<div class="flex-col flex-70">
						<h1>WeChat Pay</h1>
						<p class="note">This page will be automatically updated upon completing your payment.</p>
					</div>
				</div>
			</div>
		</div>

		<div id="payment-container">
			<div class="centered">
				<div id="loading" class="spinner hidden centered">
					<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>
				</div>
			</div>
			<form id="payment_options" method="post">
				<div class="inform">
					<fieldset>
						<div class="fakeform">
							<table class="aligntop">
								<tbody>
									<tr>
										<th scope="row">Username</th>
										<td><input class="payment-field" id="username" type="text" name="username" value="<?php echo $pun_user['username'] ?>" maxlength="80" /></td>
									</tr>
									<tr>
										<th scope="row">Email</th>
										<td><input class="payment-field" id="billing_email" type="text" name="email" value="<?php echo $pun_user['email'] ?>" maxlength="80" /></td>
									</tr>
									<tr>
										<th scope="row">Game</th>
										<td>
											<select id="game" class="payment-field" name="game">
												<option value="csgo" selected>Counter-Strike: Global Offensive</option>
											</select>
										</td>
									</tr>
									<tr>
										<th scope="row">Plan</th>
										<td>
											<select id="plan" class="payment-field" name="plan">
												<option value="30">30 days - $69.99</option>
												<option value="90">90 days - $69.99</option>
												<option value="365">365 days - $69.99</option>
												<option value="1825">5 years - $69.99</option>
											</select>
										</td>
									</tr>
									<tr>
										<th scope="row">Method</th>
										<td>
											<select id="pmethod" class="payment-field" name="pmethod">
												<option value="card">Card</option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</fieldset>
					<div class="centered">
						<input class="button" id="payment_options_submit" type="submit" value="Continue" />
					</div>
				</div>
			</form>

			<form id="billing_info" method="post" class="hidden">
				<div id="billinginfocc">
					<div class="inform">
						<fieldset>
							<div class="fakeform">
								<p>All sensitive cardholder data is transmitted directly through our payment processor using TLS. GameSense is fully compliant with the <a href="https://www.pcisecuritystandards.org/pci_security/" target="_blank">PCI Data Security Standards</a>.</p>
								<table class="aligntop">
									<tbody>
										<tr>
											<th scope="row">Full name</th>
											<td><input class="payment-field fullname required" id="billing_name" type="text" maxlength="80" autocomplete="off" /></td>
										</tr>
										<tr>
											<th scope="row">Billing address (line 1)</th>
											<td><input class="payment-field address_line1 required" id="billing_address_line1" type="text" maxlength="80" autocomplete="off" /></td>
										</tr>
										<tr>
											<th scope="row">Billing address (line 2)</th>
											<td><input class="payment-field" id="billing_address_line2" type="text" maxlength="80" autocomplete="off" /></td>
										</tr>
										<tr>
											<th scope="row">City</th>
											<td><input class="payment-field" id="billing_address_city" type="text" maxlength="80" autocomplete="off" /></td>
										</tr>
										<tr>
											<th scope="row">State / Province / Region</th>
											<td><input class="payment-field" id="billing_address_state" type="text" maxlength="80" autocomplete="off" /></td>
										</tr>
										<tr>
											<th scope="row">Postal code</th>
											<td><input class="payment-field" id="billing_address_postal_code" type="text" maxlength="80" autocomplete="off" /></td>
										</tr>
										<tr>
											<th scope="row">Country</th>
											<td>
												<select class="payment-field" id="billing_address_country"></select>
											</td>
										</tr>
										<tr>
											<th scope="row">Card details</th>
											<td>
												<div id="stripe-elements-card">
													<div id="card-element"></div>
												</div>
												<div id="card_error_box" class="inform hidden">
													<ul id="card-errors">
														<li id="card-error"></li>
													</ul>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</fieldset>
						<div class="centered">
							<input class="button" id="billing_info_submit" type="submit" value="Submit" />
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
require PUN_ROOT.'footer.php';