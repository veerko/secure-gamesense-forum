<?php

// Copyright (c) 2023 st1koo <https://github.com/st1koo>
 //
 // This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 // This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 // You should have received a copy of the GNU General Public License along with this program. If not, see <https://www.gnu.org/licenses/>. 

define('PUN_ROOT', dirname(__FILE__).'/');
require PUN_ROOT.'include/common.php';


if ($pun_user['g_read_board'] == '0')
	message($lang_common['No view'], false, '403 Forbidden');


// Load the index.php language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/index.php';

// Get list of forums and topics with new posts since last visit
if (!$pun_user['is_guest'])
{
	$result = $db->query('SELECT f.id, f.last_post FROM '.$db->prefix.'forums AS f LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id='.$pun_user['g_id'].') WHERE (fp.read_forum IS NULL OR fp.read_forum=1) AND f.last_post>'.$pun_user['last_visit']) or error('Unable to fetch forum list', __FILE__, __LINE__, $db->error());

	if ($db->num_rows($result))
	{
		$forums = $new_topics = array();
		$tracked_topics = get_tracked_topics();

		while ($cur_forum = $db->fetch_assoc($result))
		{
			if (!isset($tracked_topics['forums'][$cur_forum['id']]) || $tracked_topics['forums'][$cur_forum['id']] < $cur_forum['last_post'])
				$forums[$cur_forum['id']] = $cur_forum['last_post'];
		}

		if (!empty($forums))
		{
			if (empty($tracked_topics['topics']))
				$new_topics = $forums;
			else
			{
				$result = $db->query('SELECT forum_id, id, last_post FROM '.$db->prefix.'topics WHERE forum_id IN('.implode(',', array_keys($forums)).') AND last_post>'.$pun_user['last_visit'].' AND moved_to IS NULL') or error('Unable to fetch new topics', __FILE__, __LINE__, $db->error());

				while ($cur_topic = $db->fetch_assoc($result))
				{
					if (!isset($new_topics[$cur_topic['forum_id']]) && (!isset($tracked_topics['forums'][$cur_topic['forum_id']]) || $tracked_topics['forums'][$cur_topic['forum_id']] < $forums[$cur_topic['forum_id']]) && (!isset($tracked_topics['topics'][$cur_topic['id']]) || $tracked_topics['topics'][$cur_topic['id']] < $cur_topic['last_post']))
						$new_topics[$cur_topic['forum_id']] = $forums[$cur_topic['forum_id']];
				}
			}
		}
	}
}

if ($pun_config['o_feed_type'] == '1')
	$page_head = array('feed' => '<link rel="alternate" type="application/rss+xml" href="extern.php?action=feed&amp;type=rss" title="'.$lang_common['RSS active topics feed'].'" />');
else if ($pun_config['o_feed_type'] == '2')
	$page_head = array('feed' => '<link rel="alternate" type="application/atom+xml" href="extern.php?action=feed&amp;type=atom" title="'.$lang_common['Atom active topics feed'].'" />');

$forum_actions = array();

// Display a "mark all as read" link
if (!$pun_user['is_guest'])
	$forum_actions[] = '<a href="misc.php?action=markread&amp;csrf_token='.pun_csrf_token().'">'.$lang_common['Mark all as read'].'</a>';

$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']));
define('PUN_ALLOW_INDEX', 1);
define('PUN_ACTIVE_PAGE', 'index');
require PUN_ROOT.'header.php';

// Print the categories and forums
$result = $db->query('SELECT c.id AS cid, c.cat_name, f.id AS fid, f.forum_name, f.forum_desc, f.redirect_url, f.moderators, f.num_topics, f.num_posts, f.last_post, f.last_post_id, f.last_poster, f.last_topic FROM '.$db->prefix.'categories AS c INNER JOIN '.$db->prefix.'forums AS f ON c.id=f.cat_id LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id='.$pun_user['g_id'].') WHERE fp.read_forum IS NULL OR fp.read_forum=1 ORDER BY c.disp_position, c.id, f.disp_position', true) or error('Unable to fetch category/forum list', __FILE__, __LINE__, $db->error());

$cur_category = 0;
$cat_count = 0;
$forum_count = 0;
while ($cur_forum = $db->fetch_assoc($result))
{
	$moderators = '';

	if ($cur_forum['cid'] != $cur_category) // A new category since last iteration?
	{
		if ($cur_category != 0)
			echo "\t\t\t".'</tbody>'."\n\t\t\t".'</table>'."\n\t\t".'</div>'."\n\t".'</div>'."\n".'</div>'."\n\n";

		++$cat_count;
		$forum_count = 0;

?>
<div id="idx<?php echo $cat_count ?>" class="blocktable">
	<h2><span><?php echo pun_htmlspecialchars($cur_forum['cat_name']) ?></span></h2>
	<div class="box">
		<div class="inbox">
			<table>
			<thead>
				<tr>
					<th class="tcl" scope="col"><?php echo $lang_common['Forum'] ?></th>
					<th class="tcr" scope="col"><?php echo $lang_common['Last post'] ?></th>
				</tr>
			</thead>
			<tbody>
<?php

		$cur_category = $cur_forum['cid'];
	}

	++$forum_count;
	$item_status = ($forum_count % 2 == 0) ? 'roweven' : 'rowodd';
	$forum_field_new = '';
	$icon_type = 'icon';

	// Are there new posts since our last visit?
	if (isset($new_topics[$cur_forum['fid']]))
	{
		$item_status .= ' inew';
		$forum_field_new = '<span class="newtext">[ <a href="search.php?action=show_new&amp;fid='.$cur_forum['fid'].'">'.$lang_common['New posts'].'</a> ]</span>';
		$icon_type = 'icon icon-new';
	}

	// Is this a redirect forum?
	if ($cur_forum['redirect_url'] != '')
	{
		$forum_field = '<h3><span class="redirtext">'.$lang_index['Link to'].'</span> <a href="'.pun_htmlspecialchars($cur_forum['redirect_url']).'" title="'.$lang_index['Link to'].' '.pun_htmlspecialchars($cur_forum['redirect_url']).'">'.pun_htmlspecialchars($cur_forum['forum_name']).'</a></h3>';
		$num_topics = $num_posts = '-';
		$item_status .= ' iredirect';
		$icon_type = 'icon';
	}
	else
	{
		$forum_field = '<h3><a href="viewforum.php?id='.$cur_forum['fid'].'">'.pun_htmlspecialchars($cur_forum['forum_name']).'</a>'.(!empty($forum_field_new) ? ' '.$forum_field_new : '').'</h3>';
		$num_topics = $cur_forum['num_topics'];
		$num_posts = $cur_forum['num_posts'];
	}

	if ($cur_forum['forum_desc'] != '')
		$forum_field .= "\n\t\t\t\t\t\t\t\t".'<div class="forumdesc">'.$cur_forum['forum_desc'].'</div>';

	// If there is a last_post/last_poster
	if ($cur_forum['last_post'] != '')
		{
		if (pun_strlen($cur_forum['last_topic']) > 30)
			$cur_forum['last_topic'] = utf8_substr($cur_forum['last_topic'], 0, 30).'...';

		$last_post = '<a href="viewtopic.php?pid='.$cur_forum['last_post_id'].'#p'.$cur_forum['last_post_id'].'">'.pun_htmlspecialchars($cur_forum['last_topic']).'</a><br />'.format_time($cur_forum['last_post']).'<span class="byuser">'.$lang_common['by'].' '.pun_htmlspecialchars($cur_forum['last_poster']).'</span>';
	}
	else if ($cur_forum['redirect_url'] != '')
		$last_post = '- - -';
	else
		$last_post = $lang_common['Never'];

	if ($cur_forum['moderators'] != '')
	{
		$mods_array = unserialize($cur_forum['moderators']);
		$moderators = array();

		foreach ($mods_array as $mod_username => $mod_id)
		{
			if ($pun_user['g_view_users'] == '1')
				$moderators[] = '<a href="profile.php?id='.$mod_id.'">'.pun_htmlspecialchars($mod_username).'</a>';
			else
				$moderators[] = pun_htmlspecialchars($mod_username);
		}

		$moderators = "\t\t\t\t\t\t\t\t".'<p class="modlist">(<em>'.$lang_common['Moderated by'].'</em> '.implode(', ', $moderators).')</p>'."\n";
	}

?>
				<tr class="<?php echo $item_status ?>">
			<td class="tcl">
				<ul>
					<li class="rowlist">
						<div class="<?php echo $icon_type ?>"><div class="nosize"><?php echo forum_number_format($forum_count) ?></div></div>
					</li>
					<li class="rowlist">
						<div class="tclcon">
							<div>
								<?php echo $forum_field."\n".$moderators ?>
							</div>
						</div>
					</li>
				</ul>
			</td>
				<td class="tcr"><?php echo $last_post ?></td>
		</tr>
<?php

}

// Did we output any categories and forums?
if ($cur_category > 0)
	echo "\t\t\t".'</tbody>'."\n\t\t\t".'</table>'."\n\t\t".'</div>'."\n\t".'</div>'."\n".'</div>'."\n\n";
else
	echo '<div id="idx0" class="block"><div class="box"><div class="inbox"><p>'.$lang_index['Empty board'].'</p></div></div></div>';

// Collect some statistics from the database
if (file_exists(FORUM_CACHE_DIR.'cache_users_info.php'))
	include FORUM_CACHE_DIR.'cache_users_info.php';

if (!defined('PUN_USERS_INFO_LOADED'))
{
	if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
		require PUN_ROOT.'include/cache.php';

	generate_users_info_cache();
	require FORUM_CACHE_DIR.'cache_users_info.php';
}

$result = $db->query('SELECT SUM(num_topics), SUM(num_posts) FROM '.$db->prefix.'forums') or error('Unable to fetch topic/post count', __FILE__, __LINE__, $db->error());
list($stats['total_topics'], $stats['total_posts']) = array_map('intval', $db->fetch_row($result));

if ($pun_user['g_view_users'] == '1')
	$stats['newest_user'] = '<a href="profile.php?id='.$stats['last_user']['id'].'">'.pun_htmlspecialchars($stats['last_user']['username']).'</a>';
else
	$stats['newest_user'] = pun_htmlspecialchars($stats['last_user']['username']);

if (!empty($forum_actions))
{

?>
<div class="linksb">
	<div class="inbox crumbsplus">
		<p class="subscribelink clearb"><?php echo implode(' - ', $forum_actions); ?></p>
	</div>
</div>
<?php

}

?>
<div id="brdstats" class="block">
	<h2><span><?php echo $lang_index['Board info'] ?></span></h2>
	<div class="box">
		<div class="inbox">
			<dl class="conr">
				<dt><strong><?php echo $lang_index['Board stats'] ?></strong></dt>
			</dl>
			<dl class="conl">
				<dt><strong><?php echo $lang_index['User info'] ?></strong></dt>
<?php

if ($pun_config['o_users_online'] == '1')
{
	// Fetch users online info and generate strings for output
	$num_guests = 0;
	$users = array();
	$result = $db->query('SELECT user_id, ident, u.group_id FROM '.$db->prefix.'online LEFT JOIN '.$db->prefix.'users AS u ON (ident=u.username) WHERE idle=0 ORDER BY ident', true) or error('Unable to fetch online list', __FILE__, __LINE__, $db->error());

	while ($pun_user_online = $db->fetch_assoc($result))
	{
		if ($pun_user_online['user_id'] > 1)
		{
			if ($pun_user['g_view_users'] == '1')
				$users[] = "\n\t\t\t\t".'<dd><a href="profile.php?id='.$pun_user_online['user_id'].'">'.pun_htmlspecialchars($pun_user_online['ident']).'</a>';
			else
				$users[] = "\n\t\t\t\t".'<dd><a onclick="return false;">'.pun_htmlspecialchars($pun_user_online['ident']).'</a>';
			if($pun_user['g_id'] == 4){
				$users[count($users) - 1] = str_replace('">'.pun_htmlspecialchars($pun_user_online['ident']).'</a>', '">'.colorize_group2($pun_user_online['ident'], $pun_user_online['group_id']).'</a>', $users[count($users) - 1]);
			}
			if($pun_user['g_id'] != 4){
				$users[count($users) - 1] = str_replace('">'.pun_htmlspecialchars($pun_user_online['ident']).'</a>', '">'.colorize_group($pun_user_online['ident'], $pun_user_online['group_id']).'</a>', $users[count($users) - 1]);
			}
		}
		else
			++$num_guests;
	}

	$num_users = count($users);
	echo "\t\t\t\t".'<dd><span>'.sprintf($lang_index['Users online'], '<strong>'.forum_number_format($num_users).'</strong>').'</span></dd>'."\n\t\t\t".'</dl>'."\n";


	if ($num_users > 0)
		echo "\t\t\t".'<dl id="onlinelist" class="clearb">'."\n\t\t\t\t".'<dt><strong>'.$lang_index['Online'].' </strong></dt>'."\t\t\t\t".implode(',</dd> ', $users).'</dd>'."\n\t\t\t".'</dl>'."\n";
	else
		echo "\t\t\t".'<div class="clearer"></div>'."\n";
	if($pun_user['g_id'] == 4){
	$groups = array();
	foreach ($pun_colorize_groups2['groups'] as $g_id => $g_title)
	{
		if (!in_array($g_id, array(PUN_GUEST, PUN_MEMBER)))
		{
			$cur_group = colorize_group2($g_title, $g_id);
			if ($pun_user['g_view_users'] == 1)
				$cur_group = '<a href="userlist.php?show_group='.$g_id.'">'.$cur_group.'</a>';

			$groups[] = "\n\t\t\t\t".'<dd>'.$cur_group.'</dd>';
		}
	}
	}
	if($pun_user['g_id'] != 4){
    $groups = array();
	foreach ($pun_colorize_groups['groups'] as $g_id => $g_title)
	{
		if (!in_array($g_id, array(PUN_GUEST, PUN_MEMBER)))
		{
			$cur_group = colorize_group($g_title, $g_id);
			if ($pun_user['g_view_users'] == 1)
				$cur_group = '<a href="userlist.php?show_group='.$g_id.'">'.$cur_group.'</a>';

			$groups[] = "\n\t\t\t\t".'<dd>'.$cur_group.'</dd>';
		}
	}
	}
}
else
	echo "\t\t\t".'</dl>'."\n\t\t\t".'<div class="clearer"></div>'."\n";


?>
		</div>
	</div>
</div>
<?php

$footer_style = 'index';
require PUN_ROOT.'footer.php';
