<?php 
	session_start();
	$url_link_in = "http://localhost/chat_project/users/";
	$logged_in_usename = isset($_SESSION['xyxyxy.username']) ? $_SESSION['xyxyxy.username'] : NULL;
	$username = $logged_in_usename;
	require('chatClasses/ChatUser.php');

	require('chatClasses/ChatRooms.php');

	if ($_SERVER['REQUEST_METHOD'] === "POST"){
		if (isset($_POST['search'])){
			$search = trim( stripcslashes(htmlentities($_POST['search'], ENT_QUOTES) ) );

			if (!empty($search)){
						// this will return the list of users found
				$user_object = new ChatUser;
				$agent_data = $user_object->search_agent_page($search);

				?>
					<h3 class="p-3 fs-bolder">search users</h3>
				<?php

					// check that the result is not zero
				if (count($agent_data) === 0){
					?>

					<p class="font-bold text-4 text-center">No match found!</p>
					<?php
					exit();
				}
				foreach($agent_data as $key => $agent){
					$data_username_id = isset($agent['id']) ? $agent['id'] : NULL;
     				$data_username = isset($agent['username']) ? $agent['username'] : NULL;
     				$data_user_profile = isset($agent['user_profile']) ? $agent['user_profile'] : "img/profilePic.png";
     				$data_user_login_status = isset($agent['user_login_status']) ? $agent['user_login_status'] : NULL;
     				$data_is_agent = isset($agent['is_agent']) ? $agent['is_agent'] : NULL;
     				$data_sent_from = isset($agent['sent_from']) ? $agent['sent_from'] : NULL;
     				$data_sent_to = isset($agent['sent_to']) ? $agent['sent_to'] : NULL;
     				$data_msg = isset($agent['msg']) ? $agent['msg'] : NULL;
     				$data_type = isset($agent['type']) ? $agent['type'] : NULL;
     				$data_created_on = isset($agent['created_on']) ? $agent['created_on'] : "";
     				$data_status = isset($agent['status']) ? $agent['status'] : NULL;
     				$data_notification_count = isset($agent['notification_count']) ? $agent['notification_count'] : 0;



     				// $data_is_agent = isset($agent['is_agent']) ? $agent['is_agent'] : NULL;
     				
     				$icon = (isset($data_user_login_status) && $data_user_login_status == "online") ? '<span class="dot-label bg-success"></span>' : '<span class="dot-label bg-danger"></span>';

     				if ($data_notification_count == 0 || empty($data_notification_count)){
     					$notif_span = "";
     				} else {
     					$notif_span = "<span class='badge rounded-circle bg-danger' id='notif_$data_username_id'>$data_notification_count</span>";
     				}

     				// check if it's agent
     				$agent_card =  $data_is_agent == "Yes" ? "<i class='bi bi-award-fill'></i>" : "";

     				if (!empty($data_created_on)){
     					$elapsed_time = $user_object->time_elapsed_string($data_created_on);
     				} else {
     					$elapsed_time = "";
     				}

     				if ($data_type == "message"){
         				if (!empty($data_msg)){

         					$data_data_user_msg_to_display = strlen($data_msg) > 20 ? substr($data_msg, 0, 21). "..." : $data_msg;
         				} else {
         					$data_data_user_msg_to_display="";
         				}

         			}

         			if ($data_type == "image"){
         				$data_data_user_msg_to_display = "[image]";
         			}

         			if (empty($data_type)){
	     				$data_data_user_msg_to_display = "";
	     			}



     				if ($data_username !== $logged_in_usename){
     					?>
     						<a class="media new select_user" href="#" data-receiver="<?= $data_username; ?>" data-receiver_id="<?= $data_username_id; ?>">
								<div class="main-img-user online" id="user_list_img_<?= $data_username_id; ?>">
									<img alt="<?= $data_username; ?>" src="<?= $url_link_in.$data_user_profile; ?>">
									<?= $notif_span; ?>
								</div>
								<div class="media-body">
									<div class="media-contact-name">
										<span><?= $data_username; ?> &nbsp; <?= $agent_card; ?> </span> <span id="elap_time_<?= $data_username_id; ?>"><?= $elapsed_time; ?></span>
									</div>
									<p class="mr-1"><?= $data_data_user_msg_to_display; ?> &nbsp;
										<span id='icon_<?=$data_username_id; ?>'><?= $icon; ?></span>
									</p>
								</div>
							</a>
     					<?php
     				}
     			}


			}
		}
	}

 ?>