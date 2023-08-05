<?php

class addon_recaptcha extends flux_addon
{
	function register($manager)
	{
		global $pun_user;

		if (!$this->is_configured()) return;

		$this->get_language();

		if ($this->enabled_location('register'))
		{
			$manager->bind('register_after_validation', array($this, 'hook_after_validation'));
			$manager->bind('register_before_submit', array($this, 'hook_before_submit'));
		}

		if ($this->enabled_location('login'))
		{
			$manager->bind('login_after_validation', array($this, 'hook_after_validation'));
			$manager->bind('login_before_submit', array($this, 'hook_before_submit'));
		}

		if ($this->enabled_location('guestpost') && $pun_user['is_guest'])
		{
			$manager->bind('post_after_validation', array($this, 'hook_after_validation'));
			$manager->bind('post_before_submit', array($this, 'hook_before_submit'));
			$manager->bind('quickpost_before_submit', array($this, 'hook_before_submit'));
		}
	}

	function is_configured()
	{
		global $pun_config;

		return !empty($pun_config['recaptcha_enabled']) && !empty($pun_config['recaptcha_site_key']) && !empty($pun_config['recaptcha_secret_key']);
	}

	function enabled_location($page)
	{
		global $pun_config;

		return !empty($pun_config['recaptcha_location_'.$page]);
	}

	function get_language()
	{
		global $pun_user;

		if (file_exists(PUN_ROOT.'lang/'.$pun_user['language'].'/recaptcha_addon.php'))
			require PUN_ROOT.'lang/'.$pun_user['language'].'/recaptcha_addon.php';
		else
			require PUN_ROOT.'lang/English/recaptcha_addon.php';
	}

	function hook_after_validation()
	{
		global $errors, $lang_recaptcha;

		if (empty($errors) && !$this->verify_user_response())
		{
			$errors[] = $lang_recaptcha['Error'];
		}
	}

	function hook_before_submit()
	{
		global $pun_config, $lang_recaptcha;

		$site_key = $pun_config['recaptcha_site_key'];

		?>
        <div class="inform">
            <fieldset>
                <legend><?= $lang_recaptcha['Human']; ?></legend>
                <div class="infldset">
                    <p><?= $lang_recaptcha['Prove']; ?></p>
                    <script src="https://www.google.com/recaptcha/api.js"></script>
                    <div class="g-recaptcha" data-sitekey="<?php echo pun_htmlspecialchars($site_key) ?>"></div>
                </div>
            </fieldset>
        </div>
		<?php
	}

	function verify_user_response()
	{
		global $pun_config;

		if (empty($_POST['g-recaptcha-response'])) return false;

		$secret = $pun_config['recaptcha_secret_key'];
		$response = $_POST['g-recaptcha-response'];
		$ip = get_remote_address();

		$query = "secret=$secret&response=$response&remoteip=$ip";
		$url = "https://www.google.com/recaptcha/api/siteverify?$query";

		$response = $this->send_request($url);

		return strpos($response, '"success": true') !== false;
	}

	function send_request($url)
	{
		if (function_exists('curl_version'))
			return $this->send_curl_request($url);
		else
			return $this->get_remote_file($url);
	}

	function send_curl_request($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

	function get_remote_file($url)
	{
		global $lang_recaptcha;

		$response = file_get_contents($url);

		if ($response === false)
			throw new Exception($lang_recaptcha['API error']);

		return $response;
	}
}
