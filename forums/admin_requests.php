<?php

/**
 * Copyright (C) 2008-2011 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

// Tell header.php to use the admin template
define('PUN_ADMIN_CONSOLE', 1);

define('PUN_ROOT', dirname(__FILE__).'/');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'include/common_admin.php';


if (!$pun_user['is_admmod'])
	message($lang_common['No permission']);

// Load the admin_index.php language file
require PUN_ROOT.'lang/'.$admin_language.'/admin_index.php';




$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), $lang_admin_common['Admin'], "Requests");
define('PUN_ACTIVE_PAGE', 'requests');
require PUN_ROOT.'header.php';


if(isset($_GET['discord']) && isset($_GET['id'])){
									// Add an extra layer of security
									confirm_referrer('admin_requests.php');
									
									// CSRF check
									check_csrf($_GET['csrf_token']);

									$resetid = intval($_GET['id']); // We do not need to escape this one as it will be casted to integer, which will eliminate the possibility of a sql injection.
									
									if($_GET['discord'] == "approve" && isset($_GET['new'])){
										$resetidnew = $db->escape($_GET['new']);
										$db->query("UPDATE `gs_users` SET `discord` = '$resetidnew', `discord_new` = NULL, `discord_reason` = NULL WHERE `id` = " . $resetid) or error('Unable to update user', __FILE__, __LINE__, $db->error());

										//
										redirect('admin_requests.php', "Request was approved");
										die();
									} elseif($_GET['discord'] == "decline"){
										$db->query("UPDATE `gs_users` SET `discord_new` = NULL, `discord_reason` = NULL WHERE `id` = " . $resetid) or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());

											//
										redirect('admin_requests.php', "Request was declined");
										die();
									} else {
									
									redirect('admin_requests.php', "Unknown parameters");
								die();
									}
								}
								
								
if(isset($_GET['hwid']) && isset($_GET['id'])){

									// Add an extra layer of security
									confirm_referrer('admin_requests.php');
									
									// CSRF check
									check_csrf($_GET['csrf_token']);

									$resetid = intval($_GET['id']);
									
									if($_GET['hwid'] == "approve" && isset($_GET['new']) && isset($_GET['ip'])){
										$resetidnew = intval($_GET['new']);
										$resetipnew = $db->escape($_GET['ip']);
										$db->query("UPDATE `gs_users` SET `hwid` = '$resetidnew', `hwid_new` = NULL, `hwid_reason` = NULL, `hwid_ip` = '$resetipnew', `hwid_ip_new`, `parts` = `newparts`= NULL, `newparts` = NULL WHERE `id` = '$resetid'") or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());

										//
										redirect('admin_requests.php', "Request was approved");
										die();
									} elseif($_GET['hwid'] == "decline"){
										$db->query("UPDATE `gs_users` SET `hwid_new` = NULL, `hwid_reason` = NULL, `hwid_ip_new` = NULL WHERE `id` = '$resetid'") or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());

											//
										redirect('admin_requests.php', "Request was declined");
										die();
									} else {
									
									redirect('admin_requests.php', "Unknown parameters");
								die();
									}
								}								
								
								
								
								generate_admin_menu('requests');
							
?>


	<div class="block">
		<h2><span>Discord</span></h2>
		<div id="adintro" class="box">
			<div class="inbox">
			
					
				<table><thead>
						<tr>
							<th class="tc3" scope="col">User</th>
							<th class="tc3" scope="col">Discord ID</th>
								<th class="tc3" scope="col">IP</th>
							<th class="tc3" scope="col">Requested ID</th>
							<th class="tc3" scope="col">Requested IP</th>
							<th class="tc3" scope="col">Reason</th>
							<th class="tc3" scope="col">Actions</th>
					
						</tr>
						<tbody>
						<?php 
						
						$result44 = $db->query("SELECT * FROM `gs_users` WHERE `discord_reason` IS NOT NULL") or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());

						
						foreach($result44 as $item): ?>
						
						<tr>
						
						
						<td class="tc3"><a href="profile.php?id=<?php echo $item['id'] ?>"><?php echo colorize_group($item['username'],$item['group_id']) ?></a></td>
							<td class="tc3"><?php echo $item['discord'] ?></td>
							<td class="tc3"><?php echo $item['discord_ip'] ?></td>
							<td class="tc3"><?php echo $item['discord_new'] ?></td>
							<td class="tc3"><?php echo $item['discord_ip_new'] ?></td>
							<td class="tc3"><?php echo pun_htmlspecialchars($item['discord_reason']) ?></td>
							<td class="tc3"><a href="admin_requests.php?csrf_token=<?php echo pun_csrf_token() ?>&discord=approve&id=<?php echo $item['id']?>&new=<?php echo $item['discord_new']?>">Approve</a> / <a href="admin_requests.php?csrf_token=<?php echo pun_csrf_token() ?>&discord=decline&id=<?php echo $item['id']?>">Decline</a></td>
							
							
							
							
							
</tr>


<?php endforeach; ?> 
						<?php if(empty($result44) || !$db->num_rows($result44)): ?> 
						<tr>
							<td colspan="8" style="text-align: center;">Discord ID requests are empty.</td>
						<tr>
						<?php endif; ?> 


								</tbody>
								
					</thead>
					</table>
					
					</div>
		</div>

		<h2 class="block2"><span>Hardware</span></h2>
		<div id="adintro" class="box">
			<div class="inbox">
		
	
					<table><thead>
						<tr>
							<th class="tc3" scope="col">User</th>
							<th class="tc3" scope="col">Parts</th>
							<th class="tc3" scope="col">IP</th>
							<th class="tc3" scope="col">New Parts</th>
							<th class="tc3" scope="col">Requested IP</th>
							<th class="tc3" scope="col">Reason</th>
							<th class="tc3" scope="col">Actions</th>
					
						</tr>
						<tbody>
						<?php 
						
						$result447 = $db->query("SELECT * FROM `gs_users` WHERE `hwid_reason` IS NOT NULL") or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());

						
						foreach($result447 as $item): ?>
						
						<tr>
						
						
						<td class="tc3"><a href="profile.php?id=<?php echo $item['id'] ?>"><?php echo colorize_group($item['username'],$item['group_id']) ?></a></td>
							
							<td class="tc3"><?php echo pun_htmlspecialchars($item['parts']) ?></td>
							<td class="tc3"><?php echo $item['registration_ip'] ?></td>
							<td class="tc3"><?php echo pun_htmlspecialchars($item['newparts']) ?></td>
							<td class="tc3"><?php echo $item['hwid_ip_new'] ?></td>
							<td class="tc3"><?php echo pun_htmlspecialchars($item['hwid_reason']) ?></td>
							<td class="tc3"><a href="admin_requests.php?csrf_token=<?php echo pun_csrf_token() ?>&hwid=approve&id=<?php echo $item['id']?>&new=<?php echo $item['hwid_new']?>&ip=<?php echo $item['ip_new']?>">Approve</a> / <a href="admin_requests.php?csrf_token=<?php echo pun_csrf_token() ?>&hwid=decline&id=<?php echo $item['id']?>">Decline</a></td>
							
							
							
							
							
</tr>


<?php endforeach; ?> 
						<?php if(empty($result447) || !$db->num_rows($result447)): ?> 
						<tr>
							<td colspan="8" style="text-align: center;">Hardware ID requests are empty.</td>
						<tr>
						<?php endif; ?> 


								</tbody>
								
					</thead>
					</table>
				
			
		</div>
	</div>
	<div class="clearer"></div>
</div>





<?php

require PUN_ROOT.'footer.php';

							
							
		
