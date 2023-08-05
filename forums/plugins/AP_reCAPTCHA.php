<?php

/**
 * New reCAPTCHA plugin for FluxBB
 *
 * Created by Franz Liedke
 */

// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
    exit;

// Tell admin_loader.php that this is indeed a plugin and that it is loaded
define('PUN_PLUGIN_LOADED', 1);

// Load language file
if (file_exists(PUN_ROOT.'lang/'.$pun_user['language'].'/recaptcha_addon.php'))
    require PUN_ROOT.'lang/'.$pun_user['language'].'/recaptcha_addon.php';
else
    require PUN_ROOT.'lang/English/recaptcha_addon.php';

// Store the config
if (isset($_POST['process_form']))
{
    $enabled = isset($_POST['recaptcha_enabled']) ? 1 : 0;
    $site_key = isset($_POST['recaptcha_site_key']) ? pun_trim($_POST['recaptcha_site_key']) : '';
    $secret_key = isset($_POST['recaptcha_secret_key']) ? pun_trim($_POST['recaptcha_secret_key']) : '';
    $location_register = isset($_POST['recaptcha_location_register']) ? 1 : 0;
    $location_login = isset($_POST['recaptcha_location_login']) ? 1 : 0;
    $location_guestpost = isset($_POST['recaptcha_location_guestpost']) ? 1 : 0;

    foreach (compact('enabled', 'site_key', 'secret_key', 'location_register', 'location_login', 'location_guestpost') as $key => $value)
    {
        $key = 'recaptcha_'.$key;

        if (isset($pun_config[$key]))
            $db->query('UPDATE '.$db->prefix.'config SET conf_value = \''.$db->escape($value).'\' WHERE conf_name = \''.$db->escape($key).'\'') or error('Unable to update config value for '.$key, __FILE__, __LINE__, $db->error());
        else
            $db->query('INSERT INTO '.$db->prefix.'config (conf_name, conf_value) VALUES (\''.$db->escape($key).'\', \''.$db->escape($value).'\')') or error('Unable to store config value for '.$key, __FILE__, __LINE__, $db->error());
    }

    // Regenerate the config cache
    if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
        require PUN_ROOT.'include/cache.php';

    generate_config_cache();

    redirect('admin_loader.php?plugin=AP_reCAPTCHA.php', $lang_recaptcha['Settings saved']);
}


// Display the admin navigation menu
generate_admin_menu($plugin);

?>

<div class="blockform">
    <h2><span><?= $lang_recaptcha['reCAPTCHA'] ?></span></h2>
    <div class="box">
        <form id="recaptcha" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
            <div class="inform">
                <fieldset>
                    <legend><?= $lang_recaptcha['General']; ?></legend>
                    <div class="infldset">
                        <p>
                            <?= $lang_recaptcha['General description']; ?>
                        </p>
                        <table class="aligntop" cellspacing="0">
                            <tr>
                                <th scope="row"><?= $lang_recaptcha['Enable']; ?></th>
                                <td>
                                    <input type="checkbox" name="recaptcha_enabled" <?= empty($pun_config['recaptcha_enabled']) ? '' : 'checked' ?> />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?= $lang_recaptcha['Site key']; ?></th>
                                <td>
                                    <input type="text" name="recaptcha_site_key" size="40" value="<?php if (!empty($pun_config['recaptcha_site_key'])) echo pun_htmlspecialchars($pun_config['recaptcha_site_key']); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?= $lang_recaptcha['Secret key']; ?></th>
                                <td>
                                    <input type="text" name="recaptcha_secret_key" size="40" value="<?php if (!empty($pun_config['recaptcha_secret_key'])) echo pun_htmlspecialchars($pun_config['recaptcha_secret_key']); ?>" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </fieldset>

                <fieldset>
                    <legend><?= $lang_recaptcha['Locations']; ?></legend>
                    <div class="infldset">
                        <p>
                            <?= $lang_recaptcha['Locations description']; ?>
                        </p>
                        <table class="aligntop" cellspacing="0">
                            <tr>
                                <th scope="row"><?= $lang_recaptcha['Register']; ?></th>
                                <td>
                                    <input type="checkbox" name="recaptcha_location_register" <?= empty($pun_config['recaptcha_location_register']) ? '' : 'checked' ?> />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?= $lang_recaptcha['Login']; ?></th>
                                <td>
                                    <input type="checkbox" name="recaptcha_location_login" <?= empty($pun_config['recaptcha_location_login']) ? '' : 'checked' ?> />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?= $lang_recaptcha['Guest post']; ?></th>
                                <td>
                                    <input type="checkbox" name="recaptcha_location_guestpost" <?= empty($pun_config['recaptcha_location_guestpost']) ? '' : 'checked' ?> />
                                </td>
                            </tr>
                        </table>
                    </div>
                </fieldset>
            </div>
            <p class="submitend"><input type="submit" name="process_form" value="<?= $lang_recaptcha['Save'] ?>" /></p>
        </form>
    </div>
</div>
