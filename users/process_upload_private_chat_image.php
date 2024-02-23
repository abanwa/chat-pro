<?php 
	session_start();
	$logged_in_usename = isset($_SESSION['xyxyxy.username']) ? $_SESSION['xyxyxy.username'] : NULL;
	$username = $logged_in_usename;
	require('chatClasses/ChatUser.php');

	require('chatClasses/ChatRooms.php');

	if ($_SERVER['REQUEST_METHOD'] === "POST"){

		if (isset($_FILES["images"]["name"]) && $_FILES["images"]["name"] !== "" && !in_array("", $_FILES['images']['name'])){

			$sender = trim($_POST['sender']);
			$receiver = trim($_POST['receiver']);

			// echo "sender is $sender and receiver is $receiver";
			// exit();

			$number_of_images =  count($_FILES["images"]["name"]);
			$images = $_FILES["images"]["name"];
			if ($number_of_images > 4){
				$res = [
					'code' => "error",
					'message'=> 'max images is 4'
				];
				echo json_encode($res);
				exit();
			}

			if (empty($sender) || empty($receiver)){
				$res = [
					'code' => "error",
					'message'=> 'unknown user!'
				];
				echo json_encode($res);
				exit();
			}

			$allowType = array('jpg', 'png', 'jpeg');

			for ($i = 0; $i < $number_of_images; $i++){
				$ext = strtolower(pathinfo($images[$i], PATHINFO_EXTENSION));
				if (!in_array($ext, $allowType)){
					$res = [
						'code' => "error",
						'message'=> 'Sorry, only '.implode(',', $allowType) . ' files are allowed to upload'
					];
					echo json_encode($res);
					exit();
	            }
			}

			$user_object = new ChatUser;
			$files_to_upload = $user_object->upload_private_chat_image($_FILES['images']);
			// echo $files_to_upload;
			// exit();
			if ($files_to_upload){

				$res = [
					"status" => "success",
					"message" => $files_to_upload
				];
				echo json_encode($res);
				exit();

			} else {
				$res = [
					"status" => "error",
					"message" => $user_object->error()
				];

				echo json_encode($res);
				exit();
			}


		} else {
			$res = [
				"status" => "error",
				"message" => "File missing"
			];

			echo json_encode($res);
			exit();
		}

	}


 ?>