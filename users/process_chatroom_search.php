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
			// echo "hello";
			// exit();

			if (!empty($search)){
						// this will return the list of users found
				$user_object = new ChatUser;
				$users_data = $user_object->search($search);

				?>
					<h3 class="p-3 fs-bolder">search users</h3>
				<?php

					// check that the result is not zero
				if (count($users_data) === 0){
					?>

					<p class="font-bold text-4 text-center">No match found!</p>
					<?php
					exit();
				}

				// foreach ($users as $user){

				foreach($users_data as $key => $user){
     				$data_username = isset($user['username']) ? $user['username'] : NULL;
     				$data_is_agent = isset($user['is_agent']) ? $user['is_agent'] : NULL;
     				$data_user_profile = isset($user['user_profile']) ? $user['user_profile'] : "img/profilePic.png";
     				$data_user_login_status = isset($user['user_login_status']) ? $user['user_login_status'] : NULL;
     				$data_user_msg = isset($user['msg']) ? $user['msg'] : NULL;
     				$data_user_type = isset($user['type']) ? $user['type'] : NULL;
     				$data_created_on = isset($user['created_on']) ? $user['created_on'] : "";
     				$icon = (isset($data_user_login_status) && $data_user_login_status == "online") ? '<span class="dot-label bg-success"></span>' : '<span class="dot-label bg-danger"></span>';

     				if (!empty($data_created_on)){
     					$elapsed_time = $user_object->time_elapsed_string($data_created_on);
     				} else {
     					$elapsed_time = "";
     				}

     				if ($data_user_type == "message"){
	     				if (!empty($data_user_msg)){

	     					$data_data_user_msg_to_display = strlen($data_user_msg) > 20 ? substr($data_user_msg, 0, 21). "..." : $data_user_msg;
	     				} else {
	     					$data_data_user_msg_to_display = "";
	     				}

	     			}

	     			if ($data_user_type == "image"){
	     				$data_data_user_msg_to_display = "['image']";
	     			}

	     			if (empty($data_user_type)){
	     				$data_data_user_msg_to_display = "";
	     			}

     				if ($data_username !== $logged_in_usename){
     					?>
     						<a class="media new" href="#">
								<div class="main-img-user online">
									<img alt="<?= $data_username; ?>" src="<?= $url_link_in.$data_user_profile; ?>">
								</div>
								<div class="media-body">
									<div class="media-contact-name">
										<span><?= $data_username; ?> </span> <span><?= $elapsed_time; ?></span>
									</div>
									<p><?= $data_data_user_msg_to_display; ?><?= $icon; ?></p>
								</div>
							</a>
     					<?php
     				}
     			}	

     				
			}
		}
	}

 ?>