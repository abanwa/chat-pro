<?php 

session_start();
$url_link_in = "http://localhost/chat_project/users/";
if(isset($_POST["sender_to"]) && isset($_POST["send_from"]) && $_POST["fetch_private_chat"]){
	$logged_in_user = trim(strip_tags($_POST['sender_to']));
	$user_in_database = trim(strip_tags($_POST['send_from']));
	require 'chatClasses/PrivateChat.php';

	$private_chat_object = new PrivateChat;

	$private_chat_object->change_chat_status($user_in_database, $logged_in_user);

	$chat_data = $private_chat_object->get_last_six_months_chat_data($user_in_database, $logged_in_user);

	// MAKE THE HTML

	if (count($chat_data) > 0){
		$current_date = date("Y-m-d");
		// $previousDate = null;
		// $daysDifference = null;
		// $date_label = "";
		$today = date("Y-m-d");
		// $array_count_for_days = [];
		$html = "";
		foreach ($chat_data as $chat){
			$chat_sent_from = $chat['sent_from'];
			$chat_sent_to = $chat['sent_to'];
			$chat_message = $chat['msg'];
			$chat_type = $chat['type'];
			$chat_image = $chat['image'];
			$chat_status = $chat['status'];
			$chat_sent_date = $chat['created_on'];
			$chat_is_agent = $chat['is_agent'];
			$chat_user_profile_pic = isset($chat['user_profile']) ? $chat['user_profile'] : "img/profilePic.png";
			// $chat_username = $chat['username'];
			$chat_user_login_status = $chat['user_login_status'];

			$time_to_format = new DateTime($chat_sent_date);
			$display_time = $time_to_format->format('g:i A');
			// ************************************
			$previous_date = date("Y-m-d", strtotime($chat_sent_date));


			$date_label = $private_chat_object->date_label($today, $previous_date);
			
			// FOR THE LOGGED IN USER
			if ($logged_in_user == $chat_sent_from){

				if ($chat_type == "message"){
					$html .= $date_label;
					$html .= "<div class='media'>
								<div class='media-body'>
									<div class='main-msg-wrapper'>

										$chat_message
									</div>
									<div>
										<span> $display_time</span> <a href='#'><i class='icon ion-android-more-horizontal'></i></a>
									</div>
								</div>
							</div>";
				}

				if ($chat_type == "image"){
					if (!empty($chat_image)){
						$html .= $date_label;
						$html .= "<div class='pd-0'>";
									 
								$image_arr = explode(",", $chat_image);
								$ix = 1;
								foreach($image_arr as $img){
									$html .= "<a href='$url_link_in$img' target='_blank'>
										<img alt='avatar_$ix' class='wd-150 mb-1' src='$url_link_in$img'>
									</a>
									<a href='$url_link_in$img' title='download' download><i class='bi bi-download'></i></a>";
									$ix++;
								}

						$html .= "</div>";
					}
				}

				// FOR THE USERS IN THE OTHER END
			} else {
				if ($chat_type == "message"){
					$html .= $date_label;
					$html .= "<div class='media flex-row-reverse'>
							<div class='main-img-user online'><img alt='avatar' src='$url_link_in$chat_user_profile_pic'></div>
							<div class='media-body'>
								<div class='main-msg-wrapper bg-success text-white' >
								<h6 class='m-0 p-0' style='color: #273169;'>$chat_sent_from</h6>
									$chat_message
								</div>
								<div>
									<span>$display_time</span> <a href='#'><i class='icon ion-android-more-horizontal'></i></a>
								</div>
							</div>
						</div>";
				}

				if ($chat_type == "image"){
					if (!empty($chat_image)){
						$html .= $date_label;
						$html .= "<div class='pd-0'>";
									 
								$image_arr = explode(",", $chat_image);
								$ix = 1;
								foreach($image_arr as $img){
									$html .= "<a href='$url_link_in$img' target='_blank'>
										<img alt='avatar_$ix' class='wd-150 mb-1' src='$url_link_in$img'>
									</a>
									<a href='$url_link_in$img' title='download' download><i class='bi bi-download'></i></a>";
									$ix++;
								}

						$html .= "</div>";
					}
				}
			}

		}


		$res = json_encode([
			"code" => 200,
			"html" => $html
		]);
		 echo $res;
		 exit();
	} else {
		$res = json_encode([
			"code" => 404,
			"html" => ""
		]);
		 echo $res;
		 exit();
	}


}



 ?>