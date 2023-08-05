<?php

if (file_exists(FORUM_CACHE_DIR.'cache_colorize_groups2.php'))
	include FORUM_CACHE_DIR.'cache_colorize_groups2.php';

if (!defined('PUN_COLORIZE_GROUPS2_LOADED'))
{
	generate_colorize_groups2_cache();
	if (file_exists(FORUM_CACHE_DIR.'cache_colorize_groups2.php'))
		require FORUM_CACHE_DIR.'cache_colorize_groups2.php';
	else // mod is not installed
		$pun_colorize_groups2 = array('groups' => array());
}

if (file_exists(PUN_ROOT.'lang/'.$pun_user['language'].'/colorize_groups2.php'))
	require PUN_ROOT.'lang/'.$pun_user['language'].'/colorize_groups2.php';
else
	require PUN_ROOT.'lang/English/colorize_groups2.php';

/*
	Main function for colorize username
	If $user_id is set, generate link to user profile
*/
function colorize_group2($name, $gid, $user_id = 0, $htmlencode = true)
{
	global $pun_user;
	
	if ($htmlencode)
		$name = pun_htmlspecialchars($name);
	
	$name = '<span class="gid'.$gid.'">'.$name.'</span>';
	
	if ($pun_user['g_view_users'] == 1 && $user_id > 1)
		$name = '<a href="profile.php?id='.$user_id.'">'.$name.'</a>';

	return $name;
}


function generate_colorize_groups2_cache()
{
	global $db, $pun_user;
	
	// If mod is not installed
	if (!isset($pun_user['g_color2']))
		return;
	
	// Get the group list from the DB
	$result = $db->query('SELECT g_id, g_title, g_color2 FROM '.$db->prefix.'groups') or error('Unable to fetch group list', __FILE__, __LINE__, $db->error());

	$output = array('style' => '', 'groups' => array());
	
	while ($cur_group = $db->fetch_assoc($result))
	{
		$group_style = array();
		$output['groups'][$cur_group['g_id']] = $cur_group['g_title'];
		
		if (!empty($cur_group['g_color2']))
			$group_style[] = 'color: '.pun_htmlspecialchars($cur_group['g_color2']);
			
		if (!in_array($cur_group['g_id'], array(PUN_GUEST, PUN_MEMBER)))
			$group_style[] = 'font-weight: bold';

		if (!empty($group_style))
			$output['style'] .= '.gid'.$cur_group['g_id'].' {'.implode('; ', $group_style).'} ';
	}

	// Output group list as PHP code
	$fh = @fopen(FORUM_CACHE_DIR.'cache_colorize_groups2.php', 'wb');
	if (!$fh)
		error('Unable to write colorize groups cache file to cache directory. Please make sure PHP has write access to the directory \'cache\'', __FILE__, __LINE__);

	fwrite($fh, '<?php'."\n\n".'define(\'PUN_COLORIZE_GROUPS2_LOADED\', 1);'."\n\n".'$pun_colorize_groups = '.var_export($output, true).';'."\n\n".'?>');

	fclose($fh);
}
