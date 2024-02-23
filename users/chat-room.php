<?php 
session_start();
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log');
error_reporting(E_ALL);
// error_reporting(0);
$url_link_in = "http://localhost/chat_project/users/";
$not_authorized_link = "http://localhost/chat_project/";
$auth = (isset($_SESSION['xyxyxy.auth'])) ? $_SESSION['xyxyxy.auth'] : false;
$logged_in_usename = isset($_SESSION['xyxyxy.username']) ? $_SESSION['xyxyxy.username'] : NULL;
$xyxyxy_package_name = (isset($_SESSION['xyxyxy.package_name'])) ? $_SESSION['xyxyxy.package_name'] : NULL;
if (!$auth || empty($logged_in_usename)){
    ?>
        <script>
            window.location.replace("<?= $not_authorized_link; ?>");
        </script>
    <?php
    exit();
}

if ($xyxyxy_package_name == "TDC COMMUNITY AFFILIATE AGENT"){
	?>
    <script>
        window.location.replace("<?= $url_link_in; ?>dashboard_affiliate.php");
    </script>
<?php
exit();
}



$username = $logged_in_usename;
require('chatClasses/ChatUser.php');

require('chatClasses/ChatRooms.php');



$chatroom_object = new ChatRooms;
$chat_data = $chatroom_object->get_last_six_months_all_chat_data();

$user_object = new ChatUser;
$user_data = $user_object->get_all_online_users();
 ?>

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<head>

		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
		<meta name="description" content="TDC Mobilestore -  SME Data">
		<meta name="author" content="TDC Mobile store">
		<meta name="keywords" content="TDC Mobile Store - Nigeria and International Airtime, Insurance, Data Topup, Electricity, TV Instant Payment">

        <!-- Favicon -->
		<link rel="icon" href="<?= $url_link_in; ?>img/favi.jpg" type="image/x-icon"/>

		<!-- Title -->
		<title>Chat Room - TDC Mobilestore</title>

		<?php 

			include("includes/top_css.php");
		 ?>

		 <!-- alertify -->
		<link rel="stylesheet" href="<?= $url_link_in; ?>css/alertify.min.css" async>
		<link rel="stylesheet" href="<?= $url_link_in; ?>css/alertify-default-theme.min.css" async>

		<link rel="stylesheet" href="<?= $url_link_in; ?>css/form.css" async>
		<style>
			.hide-spinner {
			  display: none !important;
			}

			.nav-tabs .nav-link {
				background-color: none !important;
			}
			.nav-tabs .nav-link:hover {
			    background-color: transparent;
				color: #6259ca !important;
			}

			li.nav-item > button.nav-link.active {
				border-bottom: 2px solid #6259ca !important;
			}
			.nav-tabs .nav-link:focus {
				    background-color: transparent !important;
    				color: #6259ca !important;
			}

			#appMainAgentListContainer {
			 	max-width: 100vh;
			 	height: 500px !important;
			 	overflow-y: scroll;
			 	scrollbar-width: none; /* For Firefox */
			    -ms-overflow-style: none; /* For Internet Explorer and Edge */
			    -webkit-overflow-scrolling: touch;
			 }

			 #appMainAgentListContainer::-webkit-scrollbar {
			    display: none; /* Hide the scrollbar for WebKit-based browsers */
			  }

			#ChatBody {
				max-height: 100vh !important;
				height: 500px;
			    overflow-y: scroll;
			    scrollbar-width: none; /* For Firefox */
			    -ms-overflow-style: none; /* For Internet Explorer and Edge */
			    -webkit-overflow-scrolling: touch;
			}

			#ChatBody::-webkit-scrollbar {
			    display: none; /* Hide the scrollbar for WebKit-based browsers */
			  }

			#chat_message {
				height: inherit !important;
				resize: none;
			}

			.hide {
				opacity: 0;
			}

			.emojionearea.emojionearea-inline {
				height: 60px !important;
			}

			.main-chat-footer {
				height: auto !important;
				padding: 4px;
    			padding-bottom: 10px;
			}

			.main-msg-wrapper {
				padding: 2px 10px 2px 10px;
			}

			#file-icon {
			 	cursor:pointer;
			 }
		</style>

		<!-- Switcher css-->
		<link href="<?= $url_link_in; ?>new_assets/switcher/switcher.css" rel="stylesheet">
		<link href="<?= $url_link_in; ?>new_assets/switcher/demo.css" rel="stylesheet">
		<!--  ********** JS ********** -->
		<script src="<?= $url_link_in; ?>new_assets/js/jquery3.6.3.min.js"></script>
		<script src="<?= $url_link_in; ?>new_assets/js/sweetalert.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.js"></script>
    	<link href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.css" rel="stylesheet"/>
		<script>
			$(document).ready(function(){
				$(document).on("click", "#toggleButton", function(){
					// alert("aloe!");
					let eleToToggle =  $("#navbarSupportedContent-4");
					if (eleToToggle.hasClass("show")) {
						$(this).removeClass("collapsed");
						$(this).removeAttr("aria-expanded");
						$(this).attr("aria-expanded", "true");
					 	eleToToggle.removeClass("show");
					} else {
					  eleToToggle.addClass("show");
					  $(this).addClass("collapsed");
					  $(this).removeAttr("aria-expanded");
					  $(this).attr("aria-expanded", "false");
					}
				});


				$(document).on("click", ".nav .main-nav-line #borderedTab", function(e){
					console.log(e.target.id);
					const eleItems2 = this.querySelectorAll("li .nav-link");

					eleItems2.forEach(ele => ele.classList.remove("active"));
					e.target.classList.add("active");
				});

			});

	// ********* FOR OTHER END *****************
		</script>
	</head>

	<body class="main-body leftmenu">

		<!-- Page -->
		<div class="page">

        	<!-- Sidemenu -->
			<?php 
				include("includes/sidebar.php");
			 ?>
			<!-- End Sidemenu -->        
			<!-- Main Header-->
			<?php 
				include("includes/main-header-chat.php");
			 ?>
			<!-- End Main Header-->		
			<!-- Mobile-header -->
			<?php 
				include("includes/mobile-header-chat.php");
			 ?>
			<!-- Mobile-header closed -->
			<!-- Main Content-->
			<div class="main-content side-content pt-0">
				<div class="container-fluid">
					<div class="inner-body">

		
						<!-- Page Header -->
						<div class="page-header">
							<div>
								<h2 class="main-content-title tx-24 mg-b-5">Chat</h2>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#">Apps</a></li>
									<li class="breadcrumb-item active" aria-current="page">Chat</li>
								</ol>
							</div>
						</div>
						<!-- End Page Header -->

						<!-- Row -->
						<div class="row row-sm">
							<div class="col-sm-12 col-md-12 col-lg-5 col-xl-4">
								<div class="card custom-card">
									<div class="main-content-app pt-0" id="appMainAgentListContainer">
										<div class="main-content-left main-content-left-chat">

											<div class="card-body">
												<div class="input-group">
													<input type="text" id="search" class="form-control" placeholder="Search ...">
													<span class="input-group-append">
														<button class="btn ripple btn-primary mt-0" type="button">Search</button>
													</span>
												</div>
											</div>
											<nav class="nav main-nav-line main-nav-line-chat card-body">
												<ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
								                <li class="nav-item m-1" role="presentation">
								                  <button class="nav-link active" id="ChatList-tab" data-bs-toggle="tab" data-bs-target="#bordered-ChatList" type="button" role="tab" aria-controls="ChatList" aria-selected="false" tabindex="-1">Chat List</button>
								                </li>
								              </ul>

												
											</nav>
											<div class="tab-content main-chat-list pt-2" id="borderedTabContent">
								                <div class="tab-pane show active" id="bordered-ChatList" role="tabpanel"aria-labelledby="ChatList-tab">
								                	<div class="main-chat-list tab-pane result hidden"></div>
									                <div class="main-chat-list tab-pane" id="onlineUsersList">

									                 	<?php 

									                 		if (count($user_data) > 0){
									                 			foreach($user_data as $key => $user){
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
										                 					$data_data_user_msg_to_display="";
										                 				}

										                 			}

										                 			if ($data_user_type == "image"){
										                 				$data_data_user_msg_to_display = "[image]";
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
																					<p class="mr-1"><?= $data_data_user_msg_to_display; ?>&nbsp;<?= $icon; ?></p>
																				</div>
																			</a>
									                 					<?php
									                 				}
									                 			}
									                 		}

									                 	 ?>
														
													</div>
								                </div>
								            </div>
								        
											<!-- main-chat-list -->
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-12 col-lg-7 col-xl-8">
								<div class="card custom-card">
									<div class="main-content-app pt-0">
										<div class="main-content-body main-content-body-chat">
											<div class="main-chat-header pt-3">
												<div class="main-chat-msg-name">
													<h6>Chat Room</h6>
													<span class="dot-label bg-success"></span><small class="mr-3"><?php

													 if ($user_object->count_online_users() > 1){
													 	echo "+". $user_object->count_online_users() - 1 . " users online";
													 } else if ($user_object->count_online_users() == 0){
													 	echo "";
													 } else {
													 	echo $user_object->count_online_users(). " user online";
													 }

													?> </small>
												</div>
											</div>
											<!-- main-chat-header -->

											<div class="main-chat-body" id="ChatBody">
												<div class="content-inner" id="chatroom_message_body">
													<?php 

														$current_date = date("Y-m-d");
														// $previousDate = null;
														// $daysDifference = null;
														// $date_label = "";
														$today = date("Y-m-d");
														// $array_count_for_days = [];
														foreach ($chat_data as $chat){
															$chat_message = $chat['msg'];
															$chat_sent_date = $chat['created_on'];
															$chat_username = $chat['username'];
															$chat_type = $chat['type'];
															$chat_image = $chat['image'];
															$chat_user_profile_pic = isset($chat['user_profile']) ? $chat['user_profile'] : "img/profilePic.png";

															$time_to_format = new DateTime($chat_sent_date);
															$display_time = $time_to_format->format('g:i A');
															// ************************************
															$previous_date = date("Y-m-d", strtotime($chat_sent_date));


															$date_label = $chatroom_object->date_label($today, $previous_date);
															
															// FOR THE LOGGED IN USER
															if ($logged_in_usename == $chat_username){

																if ($chat_type == "message"){
																	?>
																	<?= $date_label; ?>
																	<div class="media">
																		<div class="media-body">
																			<div class="main-msg-wrapper">

																				<?= $chat_message; ?>
																			</div>
																			<div>
																				<span><?= $display_time; ?></span> <a href="#"><i class="icon ion-android-more-horizontal"></i></a>
																			</div>
																		</div>
																	</div>
																	<?php
																}

																if ($chat_type == "image"){
																	if (!empty($chat_image)){
																		echo $date_label;
																			?>
																		<div class="pd-0">
																			<?php 
																				$image_arr = explode(",", $chat_image);
																				foreach($image_arr as $img){
																					?>
																					<a href="<?= $url_link_in.$img; ?>" target="_blank">
																						<img alt="avatar" class="wd-150 mb-1" src="<?= $url_link_in.$img; ?>">
																					</a>
																					<a href="<?= $url_link_in.$img; ?>" title="download" download><i class="bi bi-download"></i></a>
																					<?php
																				}

																			?>
																		</div>
																		<?php
																	}
																}

																// FOR THE USERS IN THE OTHER END
															} else {
																if ($chat_type == "message"){
																	?>
																		<?= $date_label; ?>
																		<div class="media flex-row-reverse">
																			<div class="main-img-user online"><img alt="avatar" src="<?= $url_link_in.$chat_user_profile_pic; ?>"></div>
																			<div class="media-body">
																				<div class="main-msg-wrapper bg-success text-white" >
																				<h6 class="m-0 p-0" style="color: #273169;"><?= $chat_username; ?></h6>
																					<?= $chat_message; ?>
																				</div>
																				<div>
																					<span><?= $display_time; ?></span> <a href="#"><i class="icon ion-android-more-horizontal"></i></a>
																				</div>
																			</div>
																		</div>
																	<?php
																}

																if ($chat_type == "image"){
																	if (!empty($chat_image)){
																		echo $date_label;
																			?>
																		<div class="pd-0">
																			<?php 
																				$image_arr = explode(",", $chat_image);
																				foreach($image_arr as $img){
																					?>
																					<a href="<?= $url_link_in.$img; ?>" target="_blank">
																						<img alt="avatar" class="wd-150 mb-1" src="<?= $url_link_in.$img; ?>">
																					</a>
																					<a href="<?= $url_link_in.$img; ?>" title="download" download><i class="bi bi-download"></i></a>
																					<?php
																				}

																			?>
																		</div>
																		<?php
																	}
																}
															}

														}


													 ?>

												</div>
											</div>
											<form method="post" id="chat_form"  enctype="multipart/form-data">
												<div class="main-chat-footer">
													<nav class="nav" >
														<input class="hide" id="imageSend" type="file" name="images[]" style="width: 10px;" multiple><i class="fe fe-image fs-3" id="file-icon"></i></input>
													</nav>
													<textarea class="form-control" name="chat_message" id="chat_message" rows="2" data-sender="<?= $logged_in_usename; ?>" placeholder="Type your message here..."></textarea>
													<a class="main-msg-send" href="#"><i class="bi bi-cursor-fill fs-3" id="send_icon"></i></a>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->


					</div>
				</div>
			</div>
			<!-- End Main Content-->
		</div>
        <!-- End Page -->

		<script src="<?= $url_link_in; ?>js/search_chatroom_user.js"></script>
		<?php 

			include("includes/scripts.php");
		 ?>

		<!-- Internal Chat js-->
		<script src="<?= $url_link_in; ?>assets/js/chat.js"></script>
		
		<!-- alertify js -->
  		<script defer src="<?= $url_link_in; ?>assets/js/alertify.js"></script>
  		<!-- Switcher js -->
		<script src="<?= $url_link_in; ?>new_assets/js/switcher.js"></script>

  		<script>
  			$(document).ready(function(){

  				var conn = new WebSocket('wss://localhost:8080');


  				// ****** scrolldown function ******
  				function scrollToBottom() {
				  $('#ChatBody').scrollTop($('#ChatBody')[0].scrollHeight + 1000);
				}

				// **** CHECK IF ANY ELEMENT IS OUT OF VIEW AT THE BOTTOM ****
				function checkElementsOutOfView() {
				  var chatBody = document.getElementById('ChatBody');
				  var isOutOfView = chatBody.scrollHeight > ((chatBody.scrollTop + chatBody.clientHeight) + 200);
				  return isOutOfView === true ? "YES" : "NO";
				}

				conn.onopen = function(e) {
				    console.log("Connection established!");
				};

				conn.onmessage = function(e) {
				    console.log(e.data);
				    console.log("Offline localhost server");
				    
				    var data = JSON.parse(e.data);
					var html_data = '';
					var link = `http://localhost/chat_project/users/`;
					let chat = $('#chatroom_message_body');

				    if (data.from == 'Me'){
				    	if (data.type == "message"){
				    		html_data += `${data.date_label}
										<div class="media">
											<div class="media-body">
												<div class="main-msg-wrapper">

													${data.msg}
												</div>
												<div>
													<span>${data.time_label}</span> <a href="#"><i class="icon ion-android-more-horizontal"></i></a>
												</div>
											</div>
										</div>`;
				    	}

				    	if (data.type == "image"){
							let stringArray = data.msg.split(",");
							html_data = `${data.date_label} <div class="pd-0">`;

							for (var i = 0; i < stringArray.length; i++) {
							  html_data += `<a href="${link}${stringArray[i]}" target="_blank">
												<img alt="avatar_${i + 1}" class="wd-150 mb-1" src="${link}${stringArray[i]}">
											</a>
											<a href="${link}${stringArray[i]}" title="download" download>
												<i class="bi bi-download"></i>
											</a>`;
							}

							html_data += `</div>`;
				    	}

				    } else {
				    	if (data.type == "message"){
				    		html_data += `${data.date_label}
				    					<div class="media flex-row-reverse">
											<div class="main-img-user online"><img alt="avatar" src="${link}${data.profile_pic}"></div>
											<div class="media-body">
												<div class="main-msg-wrapper bg-success text-white">
												<h6 class="m-0 p-0" style="color: #273169;">${data.sender}</h6>
													${data.msg}
												</div>
												<div>
													<span>${data.time_label}</span> <a href="#"><i class="icon ion-android-more-horizontal"></i></a>
												</div>
											</div>
										</div>`;
				    	}

				    	if (data.type == "image"){
				    		let stringArray = data.msg.split(",");
							html_data = `${data.date_label} <div class="pd-0">`;

							for (var i = 0; i < stringArray.length; i++) {
							  html_data += `<a href="${link}${stringArray[i]}" target="_blank">
												<img alt="avatar_${i + 1}" class="wd-150 mb-1" src="${link}${stringArray[i]}">
											</a>
											<a href="${link}${stringArray[i]}" title="download" download>
												<i class="bi bi-download"></i>
											</a>`;
							}

							html_data += `</div>`;
				    	}

				    }

				    

				   	// APPEND THE CHAT
				   	var areElementsOutOfView = checkElementsOutOfView();
					var isOutOfView = areElementsOutOfView === "YES" ? true : false;
				   	chat.append(html_data);

				    $("#chat_message").val("");

				    if (!isOutOfView){
						scrollToBottom();
					}
				    
				};
  				
  			

  			$('#file-icon').click(function(e){
  				e.preventDefault();
			    $('#imageSend').trigger('click');
			 });

			// CHECK IF ANY ELEMENT IS OUT OF VIEW AT THE BOTTOM
			function checkElementsOutOfView() {
			  var chatBody = document.getElementById('ChatBody');
			  var isOutOfView = chatBody.scrollHeight > (chatBody.scrollTop + chatBody.clientHeight);
			  return isOutOfView === true ? "YES" : "NO";
			}

			
  			$('#send_icon').click(function(e) {
  				e.preventDefault();
			    $('#chat_form').trigger('submit');
			 });

  			$('#chat_form').on('submit', function(event){

				event.preventDefault();
				let message = $("#chat_message").val();

				if (message.length > 1000) {
			      alertify.set('notifier', 'position', 'top-right');
			      alertify.warning("Max characters is 1000");
			      return false;
			    }

				
				const sender = $('#chat_message').data('sender');

				var data = {
					sender : sender,
					msg : message,
					type: "message",
					command: "public"
				};

				conn.send(JSON.stringify(data));

				$('#ChatBody').scrollTop($('#ChatBody')[0].scrollHeight + 1000);
				console.log(data);
			});


			$(':file').on('change', function(event){
				const URLL = "http://localhost/chat_project/users/";
				// const files = this.files[0];
				const files = Array.from(this.files);
				const maxFileSize = 10 * 1024 * 1024; // 10MB
				const number_of_files = files.length;

				// Check if any file size exceeds 10MB
		    	const oversizedFiles = files.filter(file => file.size > maxFileSize);

		    	if (oversizedFiles.length > 0) {
			      alertify.set('notifier', 'position', 'top-right');
			      alertify.warning("Max image size is 10MB");
			      $("#imageSend").val('');
			      return false;
			    }

			    if (files.length > 4) {
			      alertify.set('notifier', 'position', 'top-right');
			      alertify.warning("Only 4 images are accepted");
			      $("#imageSend").val('');
			      return false;
			    }
				const sender = $('#chat_message').data('sender');

						// we will build a dynamic form to send the file
				const formData = new FormData();

				// formData.append('files', files);
				files.forEach(file => {
			      formData.append('images[]', file);
			    });
				formData.append('sender', sender);
				$.ajax({
					url: URLL+'process_upload_chat_image.php',
					type: 'POST',
					data: formData,
					success: function(res_data){
						const data = JSON.parse(res_data);

						if (data.status === "error"){
							alertify.set('notifier', 'position', 'top-right');
						    alertify.warning(data.message);
						    $("#imageSend").val('');
			      			return false;
						} else {

							var data_to_send = {
								sender : sender,
								msg : data.message,
								type: "image",
								command: "public"
							};
							// this will send the message to the other user via the websocket

							conn.send(JSON.stringify(data_to_send));

						}
					},
					cache: false,
					contentType: false,
					processData: false
				})
			});

			$(document).on('click', '#chat_logout_link', function(e){
				e.preventDefault();
				$.ajax({
					url:src_link+"logout_chat.php",
					type:"POST",
					data:"action=leave",
					success:function(data){
						var response = JSON.parse(data);
						if(response.code == 200){
							conn.close();
							location.replace("<?= $not_authorized_link; ?>");
						}
					}
				})

			});

  		});


		$(document).on("change", "#imageSend", function(){
		    let file = this.files[0];
		    let fileType = file.type;

		    // let match = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
		    const match = ['image/jpeg', 'image/png', 'image/jpg'];
		    if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]))){
		      // alert('Sorry, only PDF, DOC, JPG, JPEG & PNG are allowed to be updated');
		      // alert('Sorry, only JPG, JPEG & PNG are allowed to be uploaded');
		      alertify.set('notifier','position', 'top-right');
		      alertify.warning("Sorry, only JPG, JPEG & PNG are allowed to be uploaded");
		      $("#imageSend").val('');
		      return false;
		    }

		    if (this.files.length > 4){
		        alertify.set('notifier','position', 'top-right');
		          alertify.warning("Only 4 images is accepted");
		          $("#imageSend").val('');
		          return false;
		    }
		  });
  		</script>

	</body>
</html>