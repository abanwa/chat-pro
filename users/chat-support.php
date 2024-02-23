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
$user_unique_chat_token = isset($_SESSION['xyxyxy.user_unique_chat_token']) ? $_SESSION['xyxyxy.user_unique_chat_token'] : NULL;
$xyxyxy_package_name = (isset($_SESSION['xyxyxy.package_name'])) ? $_SESSION['xyxyxy.package_name'] : NULL;
if (!$auth || empty($logged_in_usename) || empty($user_unique_chat_token)){
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

$is_agent = (isset($_SESSION['xyxyxy.is_agent'])) ? $_SESSION['xyxyxy.is_agent'] : NULL;


$username = $logged_in_usename;
require('chatClasses/ChatUser.php');
$user_object = new ChatUser;
$agent_data = $user_object->get_messages_with_count_notification($logged_in_usename);
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
		<title>Chat Support - TDC Mobilestore</title>

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

			.select_user.active {
			 	background-color: #c4c4c43d !important;
    			border: 1px solid lightgray;

			 }

			 #file-icon {
			 	cursor:pointer;
			 }

			 #appMainAgentListContainer {
			 	max-width: 100vh;
			 	height: 500px !important;
			 	border-radius: 10px;
			 	overflow-y: scroll;
			 	scrollbar-width: none; /* For Firefox */
			    -ms-overflow-style: none; /* For Internet Explorer and Edge */
			    -webkit-overflow-scrolling: touch;
			 }

			 #appMainAgentListContainer::-webkit-scrollbar {
			    display: none; /* Hide the scrollbar for WebKit-based browsers */
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

						<input type="hidden" name="is_agent" id="is_agent" value="<?= $is_agent; ?>">
						<input type="hidden" name="logged_in_user" id="logged_in_user" value="<?= $username; ?>">
						<input type="hidden" name="is_active_chat" id="is_active_chat" value="No" />
						<!-- End Page Header -->

						<!-- Row -->
						<div class="row row-sm">
							<div class="col-sm-12 col-md-12 col-lg-5 col-xl-4">
								<div class="card custom-card">
									<div class="main-content-app pt-0" id="appMainAgentListContainer">
										<div class="main-content-left main-content-left-chat">

											<div class="card-body">
												<div class="input-group">
													<input type="text" id="search" class="form-control" placeholder="Search Agents...">
													<span class="input-group-append">
														<button class="btn ripple btn-primary mt-0" type="button">Search</button>
													</span>
												</div>
											</div>
											<nav class="nav main-nav-line main-nav-line-chat card-body">
												<ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
								                <li class="nav-item m-1" role="presentation">
								                  <button class="nav-link active" id="AgentList-tab" data-bs-toggle="tab" data-bs-target="#bordered-AgentList" type="button" role="tab" aria-controls="AgentList" aria-selected="false" tabindex="-1">Agent List</button>
								                </li>
								              </ul>
											</nav>
											<div class="tab-content main-chat-list pt-2" id="borderedTabContent">
								                <div class="tab-pane show active" id="bordered-AgentList" role="tabpanel"aria-labelledby="AgentList-tab">
								                	<div class="main-chat-list tab-pane result hidden"></div>
									                <div class="main-chat-list tab-pane" id="onlineUsersList">

									                 	<?php 

									                 		if (count($agent_data) > 0){
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
									                 				
									                 				$icon = (isset($data_user_login_status) && $data_user_login_status == "online") ? "<span class='dot-label bg-success'></span>" : "<span class='dot-label bg-danger'></span>";
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
									                 						<a class="media new select_user" href="#"data-receiver="<?= $data_username; ?>" data-receiver_id="<?= $data_username_id; ?>">
																				<div class="main-img-user online" id="user_list_img_<?= $data_username_id; ?>">
																					<img alt="<?= $data_username; ?>" src="<?= $url_link_in.$data_user_profile; ?>">
																					<?= $notif_span; ?>
																				</div>
																				<div class="media-body">
																					<div class="media-contact-name">
																						<span><?= $data_username; ?> &nbsp; <?= $agent_card; ?> </span> <span id="elap_time_<?= $data_username_id; ?>"><?= $elapsed_time; ?></span>
																					</div>
																					<p class="mr-1" id="user_msg_list_<?= $data_username_id; ?>"><?= $data_data_user_msg_to_display; ?> &nbsp;<span id='icon_<?=$data_username_id; ?>'>
																						<?= $icon; ?>
																					</span></p>
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
										<div class="main-content-body main-content-body-chat" id="chat_main_holder">
											
											<!-- main-chat-header -->

											<div class="main-chat-body" id="ChatBody">
												
												<div class="content-inner" id="chatroom_message_body">
													
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

		<script src="<?= $url_link_in; ?>js/search_chat_agent.js"></script>
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
  				var conn = new WebSocket('ws://localhost:8080?user_unique_token=<?= $user_unique_chat_token; ?>');

  				$("#chat_main_holder").html("");
  				var logged_in_user = $('#logged_in_user').val();
  				var is_agent = $('#is_agent').val();
  				var src_link = "http://localhost/chat_project/users/";
  				// OUR FUNCTIONS 

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

				function createUserListElement(data){
					const msg = (data.msg.length > 21) ? data.msg.slice(0, 20) + "..." : data.msg;
					const agent_card =  data.is_agent == "Yes" ? "<i class='bi bi-award-fill'></i>" : "";
					const notif_span = `<span class='badge rounded-circle bg-danger' id='notif_${data.sender_id}'>1</span>`;
					let img_url = "http://localhost/chat_project/users/";
					var sender_html = '';
					sender_html += `<a class="media new select_user" href="#"data-receiver="${data.sender}" data-receiver_id="${data.sender_id}">
								<div class="main-img-user online" id="user_list_img_${data.sender_id}">
									<img alt="${data.sender}" src="${img_url}${data.profile_pic}">
									${notif_span}
								</div>
								<div class="media-body">
									<div class="media-contact-name">
										<span>${data.sender} &nbsp; ${agent_card} </span> <span id="elap_time_ ${data.sender_id}">${data.elapse_time}</span>
									</div>
									<p class="mr-1" id="user_msg_list_${data.sender_id}">${msg} &nbsp;<span id='icon_${data.sender_id}'>
										<span class='dot-label bg-success'></span>
									</span></p>
								</div>
							</a>`;

					// Append the element
					$("#bordered-AgentList").prepend(sender_html);
				}

				function instant_msg_display(data){
					let msg_to_show = '';

					if (data.type == "message"){

						if (data.msg.length > 21){
							const msg_to_show_holder = data.msg.slice(0, 20) + "...";
							msg_to_show = `${msg_to_show_holder} &nbsp;<span id='icon_${data.sender_id}'><span class='dot-label bg-success'></span>
							</span>`;
						} else {
							msg_to_show = `${data.msg} &nbsp;<span id='icon_${data.sender_id}'><span class='dot-label bg-success'></span>
							</span>`;
						}
					}

					if (data.type == "image"){
						msg_to_show = `[image] &nbsp;<span id='icon_${data.sender_id}'><span class='dot-label bg-success'></span>
							</span>`;
					}

					return msg_to_show;
				}

				function show_new_notification(data, msg){
					const spanElement = $('<span>').attr('id', `notif_${data.sender_id}`).text(`1`);
					$(`#user_list_img_${data.sender_id}`).append(spanElement);
					$(`#elap_time_${data.sender_id}`).text(data.elapse_time);
					$(`#user_msg_list_${data.sender_id}`).html(msg);
				}


				function increase_existing_notification(data, msg){
					let count_chat = parseInt($('#notif_'+data.sender_id).text(), 10);
					count_chat++;
					$(`#notif_${data.sender_id}`).text(count_chat);

					 $(`#elap_time_${data.sender_id}`).text(data.elapse_time);
					 $(`#user_msg_list_${data.sender_id}`).html(msg);
				}

				// OUR FUNCTION ENDS

  				var receiver_username= '';

  				conn.onopen = function(event){
					console.log('Connection Established');
				};

				conn.onmessage = function(e){
					var data = JSON.parse(e.data);
					// when users login, it will be online . user_id_status is registered table id
					if(data.status_type == 'online'){
						$("#icon_"+data.user_id_status).html(`<span class='dot-label bg-success'></span>`);
					} else if(data.status_type == 'offline'){
						$("#icon_"+data.user_id_status).html(`<span class='dot-label bg-danger'></span>`);
					} else {
						var html_data = '';
						var link = `http://localhost/chat_project/users/`;
						let chat = $('#chatroom_message_body');

						if (receiver_username == data.receiver || logged_in_user == data.receiver || receiver_username.length === 0 || data.from == 'Me'){
							console.log(`receiver_username is ${receiver_username} and data.receiver is ${data.receiver} and data.from is ${data.from}`);

							
							if($('#is_active_chat').val() == 'Yes'){
								console.log("open");

								// WHEN MESSAGE IS SENT BY ME
								if (receiver_username == data.receiver && data.from == 'Me'){
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

							    } 


							    if (receiver_username == data.sender && data.from == receiver_username){

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

							    if (receiver_username != data.receiver && data.from != receiver_username){

							    	// DOES SENDER EXIST IN MESSAGE LIST ELSE CREATE SENDER AND APPEND
									var senderSelector = `a.select_user[data-receiver_id=${data.sender_id}]`;
									var senderElement = $(senderSelector);

									// check if the elemet was selected
									if (senderElement.length){

									  	// Check if the span notification is empty
										if ($('#notif_'+data.sender_id).length){
											const msg_to_show = instant_msg_display(data);

											 increase_existing_notification(data, msg_to_show);


										} else {
											const msg_to_show = instant_msg_display(data);

											show_new_notification(data, msg_to_show);
										}

										// REMOVE THE USER AND PUT IT AT THE TOP
										senderElement.remove();
										$("#bordered-AgentList").prepend(senderElement);


									} else {

										// Create Element
										createUserListElement(data);
									}

							    }

							    // **** NEW ADDITION ******

							    // APPEND THE CHAT
							   	var areElementsOutOfView = checkElementsOutOfView();
								var isOutOfView = areElementsOutOfView === "YES" ? true : false;
							   	chat.append(html_data);

							   
							    $("#chat_message").val("");
							    is_from_receiver_or_me =  (data.from == 'Me' || data.from == receiver_username) ? true : false;

							    if (!isOutOfView && is_from_receiver_or_me){
									scrollToBottom();
								}


							 // chat area open ends
							} else {

								// DOES SENDER EXIST IN MESSAGE LIST ELSE CREATE SENDER AND APPEND
								var senderSelector = `a.select_user[data-receiver_id=${data.sender_id}]`;
								console.log(senderSelector);
								var senderElement = $(senderSelector);

								// check if the elemet was selected
								if (senderElement.length){
									console.log("elemet is true");
								  	// Check if the span notification is empty
									if ($('#notif_'+data.sender_id).length){
										const msg_to_show = instant_msg_display(data);

										 increase_existing_notification(data, msg_to_show);

									} else {;
										const msg_to_show = instant_msg_display(data);

										show_new_notification(data, msg_to_show);
									}

									// REMOVE THE USER AND PUT IT AT THE TOP
									senderElement.remove();
									console.log("elemt remove is present");
									$("#bordered-AgentList").prepend(senderElement);
									


								} else {

									// Create Element
									console.log("createElement");
									createUserListElement(data);
								}

							}

							
						}

						
					}
				};

				conn.onclose = function(event){
					console.log('connection close');
				};


  				// MAKE CHAT
  				function make_chat_area(logged_in_username, receiver_username, img_src){
					var html = `
					<div class="main-chat-header pt-3">
						<div class="main-img-user online"><img alt="avatar" src="${img_src}">
						</div>
							
						<div class="main-chat-msg-name">
							<h6>${receiver_username}</h6>
							<span class="dot-label bg-success"></span><small class="mr-3"></small>
						</div>
							<button type="button" class="close ms-auto fs-1" id="close_chat_area" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
					</div>

					<div class="main-chat-body" id="ChatBody">
						<div class="content-inner" id="chatroom_message_body">

						</div>
					</div>

					<form method="post" id="chat_form"  enctype="multipart/form-data">
						<div class="main-chat-footer">
							<nav class="nav" >
								<input class="hide" id="imageSend" type="file" name="images[]" style="width: 10px;" multiple><i class="fe fe-image fs-3" id="file-icon"></i></input>
							</nav>
							<textarea class="form-control" name="chat_message" id="chat_message" rows="2" data-sender="${logged_in_username}" placeholder="Type your message here..."></textarea>
							<a class="main-msg-send" href="#"><i class="bi bi-cursor-fill fs-3" id="send_icon"></i></a>
						</div>
					</form>
					`;

					$("#chat_main_holder").html(html);
				}


				$(document).on('click', '.select_user', function(e){
					e.preventDefault();
					let divSelector = $(this);
					receiver_username = $(this).data('receiver');
					var receiver_user_id = $(this).data('receiver_id');
					var imageLink = $(this).find('img').attr('src');

					var logged_in_username = $('#logged_in_user').val();

					$('.select_user.active').removeClass('active');

					$(this).addClass('active');

					make_chat_area(logged_in_username, receiver_username, imageLink);

					$('#is_active_chat').val('Yes');
					// $('#notif_'+receiver_user_id).remove();

					// get chat data sent to logged_in usser from reciever or from receiver to logged in user
					$.ajax({
						url: src_link+'process_fetch_private_chat.php',
						type: "POST",
				      	data: "sender_to="+logged_in_username+"&send_from="+receiver_username+"&fetch_private_chat="+true,
						success: function(response){
							const data = JSON.parse(response);
							if (data.code === 200){
								$('#notif_'+receiver_user_id).remove();

								$('#chatroom_message_body').html(data.html);

								$('#ChatBody').scrollTop($('#ChatBody')[0].scrollHeight + 1000);
							}
						}
					})

				});


				$(document).on('click', '#close_chat_area', function(){

					$('#chat_main_holder').html('');

					$('.select_user.active').removeClass('active');

					$('#is_active_chat').val('No');

					receiver_username = '';

				});

				$(document).on('click', '#send_icon', function(e){

					e.preventDefault();
	  				$('#chat_form').trigger('submit');

				});

				$(document).on('submit', '#chat_form', function(e){

					e.preventDefault();
					let message = $("#chat_message").val();

					// return if message is empty
				    if (message === '') return false;

					if (message.length > 1000) {
				      alertify.set('notifier', 'position', 'top-right');
				      alertify.warning("Max characters is 1000");
				      return false;
				    }


					// var logged_in_user = $('#logged_in_user').val();

					if (logged_in_user.length === 0 || receiver_username.length == 0){
						alertify.set('notifier', 'position', 'top-right');
					    alertify.warning("Something went wrong, contact support!");
					    return false;
					}

					var data = {
						sender : logged_in_user,
						receiver: receiver_username,
						msg : message,
						is_agent: is_agent,
						type: "message",
						command: "private"
					};

					console.log(data);
					// return;

					conn.send(JSON.stringify(data));

					
					scrollToBottom();
					// console.log(data);

				});


				$(document).on('click', '#file-icon', function(e) {
					e.preventDefault();
				    $('#imageSend').trigger('click');
				 });

				$(document).on('change', ':file', function(event){
					if (logged_in_user.length === 0 || receiver_username.length == 0){
						alertify.set('notifier', 'position', 'top-right');
					    alertify.warning("Something went wrong, contact support!");
					    return false;
					}


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

					// const sender = $('#chat_message').data('sender');

					// we will build a dynamic form to send the file
					const formData = new FormData();

					// formData.append('files', files);
					files.forEach(file => {
				      formData.append('images[]', file);
				    });
					formData.append('sender', logged_in_user);
					formData.append('receiver', receiver_username);
					$.ajax({
						url: src_link+'process_upload_private_chat_image.php',
						type: 'POST',
						data: formData,
						success: function(res_data){
							// alert(res_data);
							console.log(res_data, "yes, got it");
							// return;
							const data = JSON.parse(res_data);

							if (data.status === "error"){
								alertify.set('notifier', 'position', 'top-right');
							    alertify.warning(data.message);
							    $("#imageSend").val('');
				      			return false;
							} else {

								var data_to_send = {
									sender : logged_in_user,
									receiver: receiver_username,
									msg : data.message,
									is_agent: is_agent,
									type: "image",
									command: "private"
								};

								
								conn.send(JSON.stringify(data_to_send));
								scrollToBottom();
							}
						},
						cache: false,
						contentType: false,
						processData: false
					})
				});

				 

				// $('#chat_logout_link').click(function(e){
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