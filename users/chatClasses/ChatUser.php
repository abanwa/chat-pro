<?php

//ChatUser.php

class ChatUser {
	public $connect, $username, $error;

	public function __construct(){
		require_once('Database_connection.php');

		$database_object = new Database_connection;

		$this->connect = $database_object->connect();
	}

	///// 
	function isLoggedIn(){
		return (isset($_SESSION['xyxyxy.auth'])) ? $_SESSION['xyxyxy.auth'] : false;
	}

	function getLoggedInUsername(){
		return $this->isLoggedIn() ? $_SESSION['xyxyxy.username'] : NULL;
	}

	function getLoggedInUserID(){
		return $this->isLoggedIn() ? $_SESSION['xyxyxy.id'] : NULL;
	}

	function error(){
		return $this->error;
	}

	///

	function get_user_all_data_online(){
		$query = "
		SELECT registered.username, registered.is_agent, registered.user_profile,registered.user_login_status, chatrooms.msg, chatrooms.created_on
		FROM registered
		INNER JOIN chatrooms ON chatrooms.username = registered.username
		WHERE registered.user_login_status = 'online'
		AND chatrooms.id = (
		    SELECT MAX(id)
		    FROM chatrooms
		    WHERE chatrooms.username = registered.username
		)
		LIMIT 50
		";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		$data = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}


	function get_all_online_users(){
		$query = "
		SELECT
		    registered.username,
		    registered.user_profile, registered.user_login_status,
		    chatrooms.msg, chatrooms.type,
		    chatrooms.created_on
		FROM
		    registered
		LEFT JOIN (
		    SELECT
		        username,
		        msg,
		        type,
		        created_on
		    FROM
		        chatrooms
		    WHERE
		        (username, type, created_on) IN (
		            SELECT
		                username,
		                type,
		                MAX(created_on)
		            FROM
		                chatrooms
		            GROUP BY
		                username
		        )
		    ORDER BY
		        created_on DESC
		    LIMIT 50
		) AS chatrooms ON registered.username = chatrooms.username
		WHERE
		    registered.user_login_status = 'online' AND registered.is_agent = 'No' LIMIT 50
		";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		$data = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}

	function get_messages_with_count_notification($user){

		$query = "
		SELECT registered.id, registered.username, registered.user_profile, registered.user_login_status, registered.is_agent,
        chat_message.sent_from, chat_message.sent_to, chat_message.msg, chat_message.type, chat_message.created_on, chat_message.status, 
       
       IF(chat_message.status = 'seen', 0, latest_msg.count_not_seen) AS notification_count
       
		FROM registered 
		LEFT JOIN (SELECT sent_from, MAX(created_on) AS latest_created_on,
		    SUM(CASE WHEN status = 'not_seen' THEN 1 ELSE 0 END) AS count_not_seen
		    FROM chat_message WHERE sent_to = :user
		    GROUP BY sent_from
		) AS latest_msg ON registered.username = latest_msg.sent_from 
		LEFT JOIN chat_message ON chat_message.sent_from = latest_msg.sent_from AND chat_message.created_on = latest_msg.latest_created_on

		WHERE registered.is_agent = 'Yes' OR registered.username = latest_msg.sent_from ORDER BY chat_message.created_on DESC LIMIT 50 
		";

		$statement = $this->connect->prepare($query);
		$statement->bindParam(':user', $user, PDO::PARAM_STR);
		$statement->execute();
		$data = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $data;

	}

	function count_online_users(){
		$query = "
		SELECT COUNT(id) as num_of_online_users FROM registered WHERE user_login_status = 'online';
		";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchColumn();
	}


	function search($search_term){
		$online_status = "online";
		$logged_in_user = $this->getLoggedInUsername();
		$query = "
		SELECT
		    registered.username,
		    registered.user_profile, registered.user_login_status,
		    chatrooms.msg, chatrooms.type,
		    chatrooms.created_on
		FROM
		    registered
		LEFT JOIN (
		    SELECT
		        username,
		        msg,
		        type,
		        created_on
		    FROM
		        chatrooms
		    WHERE
		        (username, type, created_on) IN (
		            SELECT
		                username,
		                type,
		                MAX(created_on)
		            FROM
		                chatrooms
		            GROUP BY
		                username
		        )
		    ORDER BY
		        created_on DESC
		    LIMIT 20
		) AS chatrooms ON registered.username = chatrooms.username
		WHERE
		    registered.user_login_status LIKE :online_status AND registered.username LIKE :search_term AND registered.username !=:logged_in_user AND registered.is_agent = 'No'
		";



		$statement = $this->connect->prepare($query);

		$statement->bindParam(":online_status", $online_status, PDO::PARAM_STR);
		$statement->bindValue(":search_term", '%' . $search_term. '%');
		$statement->bindParam(":logged_in_user", $logged_in_user, PDO::PARAM_STR);
		$statement->execute();

		return $statement->fetchALl(PDO::FETCH_ASSOC);
	}


	function search_agent_page($search_term){
		$logged_in_user = $this->getLoggedInUsername();
		$query = "
		SELECT registered.id, registered.username, registered.user_profile, registered.user_login_status, registered.is_agent,
        chat_message.sent_from, chat_message.sent_to, chat_message.msg, chat_message.type, chat_message.created_on, chat_message.status, 
       
       IF(chat_message.status = 'seen', 0, latest_msg.count_not_seen) AS notification_count
       
		FROM registered 
		LEFT JOIN (SELECT sent_from, MAX(created_on) AS latest_created_on,
		    SUM(CASE WHEN status = 'not_seen' THEN 1 ELSE 0 END) AS count_not_seen
		    FROM chat_message WHERE sent_from = :search_term AND sent_to = :logged_in_user
		    GROUP BY sent_from
		) AS latest_msg ON registered.username = latest_msg.sent_from 
		LEFT JOIN chat_message ON chat_message.sent_from = latest_msg.sent_from AND chat_message.created_on = latest_msg.latest_created_on

		WHERE registered.is_agent = 'Yes' OR registered.username = latest_msg.sent_from ORDER BY chat_message.created_on DESC LIMIT 10
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(":search_term", $search_term, PDO::PARAM_STR);
		$statement->bindParam(":logged_in_user", $logged_in_user, PDO::PARAM_STR);
		$statement->execute();

		return $statement->fetchALl(PDO::FETCH_ASSOC);
	}


	function upload_chat_image($files){
		$number_of_images =  count($files["name"]);
		$images = $files['name'];

		$upload_dir = "chatUploads/";
		$images_str_to_databse = "";
		// get the extensions of the different images
		for ($i = 0; $i < $number_of_images; $i++){
			$ext = strtolower(pathinfo($images[$i], PATHINFO_EXTENSION));
			$filename =  time() . mt_rand(000, 999). date("YmdHis") . ".".$ext; // 63542535336.jpg
			$source_path = $files['tmp_name'][$i];
			$destination_path = $upload_dir . $filename;
			$databaseFile = "chatUploads/{$filename}";

			$images_str_to_databse .= ($i == ($number_of_images - 1)) ? "chatUploads/{$filename}" : "chatUploads/{$filename},";

            if (!move_uploaded_file($source_path, $destination_path)){
				$this->error = 'Sorry, there was an error uploading your file';
				return false;
            }
		}

		// AFTER MOVING THE IMAGES
		return $images_str_to_databse;
	}


	function upload_private_chat_image($files){
		$number_of_images =  count($files["name"]);
		$images = $files['name'];

		/*
		$image_names = implode(",", $images); // this implode will bring all the images together by separating them with , and making them to be strings

		// convert the image_names to array
		$image_arr = explode(",", $image_names);
		*/

		$upload_dir = "privatechatUploads/";
		$images_str_to_databse = "";
		// get the extensions of the different images
		for ($i = 0; $i < $number_of_images; $i++){
			$ext = strtolower(pathinfo($images[$i], PATHINFO_EXTENSION));
			$filename =  time() . mt_rand(000, 999). date("YmdHis") . ".".$ext; // 63542535336.jpg
			$source_path = $files['tmp_name'][$i];
			$destination_path = $upload_dir . $filename;
			$databaseFile = "privatechatUploads/{$filename}";

			$images_str_to_databse .= ($i == ($number_of_images - 1)) ? "privatechatUploads/{$filename}" : "privatechatUploads/{$filename},";

            if (!move_uploaded_file($source_path, $destination_path)){
				$this->error = 'Sorry, there was an error uploading your file';
				return false;
            }
		}

		// AFTER MOVING THE IMAGES
		return $images_str_to_databse;
	}


	function time_elapsed_string($datetime, $full = false) {
      $now = new DateTime;
      $ago = new DateTime($datetime);
      $diff = $now->diff($ago);

      $diff->w = floor($diff->d / 7);
      $diff->d -= $diff->w * 7;

      $string = array(
          'y' => 'year',
          'm' => 'month',
          'w' => 'week',
          'd' => 'day',
          'h' => 'hour',
          'i' => 'minute',
          's' => 'second',
      );
      foreach ($string as $k => &$v) {
          if ($diff->$k) {
              $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
          } else {
              unset($string[$k]);
          }
      }

      if (!$full) $string = array_slice($string, 0, 1);
      return $string ? implode(', ', $string) . ' ago' : 'just now';
  	}

	function getProfile($sender){
		$query = "
		SELECT user_profile FROM registered 
		WHERE username = :sender LIMIT 1
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':sender', $sender, PDO::PARAM_STR);

		$statement->execute();

		$profilePic = $statement->fetchColumn();

		return $profilePic;
	}

	function getUsername($user){
		$query = "
		SELECT username FROM registered 
		WHERE username = :user LIMIT 1
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user', $user, PDO::PARAM_STR);

		$statement->execute();

		$username = $statement->fetchColumn();

		return $username;
	}

	function update_connection_id_base_on_token($connection_id, $user_unique_token){
		$query = "
		UPDATE registered 
		SET user_connection_id = :connection_id 
		WHERE user_token = :user_unique_token
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':connection_id', $connection_id, PDO::PARAM_INT);

		$statement->bindParam(':user_unique_token', $user_unique_token, PDO::PARAM_STR);

		$statement->execute();
	}


	function get_user_id_using_token($user_unique_token){
		$query = "
		SELECT id FROM registered 
		WHERE user_token = :user_unique_token LIMIT 1
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_unique_token', $user_unique_token);

		$statement->execute();

		$user_id = $statement->fetch(PDO::FETCH_ASSOC);

		return $user_id;
	}

	function get_connection_id_using_username($username){
		$query = "
		SELECT id, user_connection_id FROM registered 
		WHERE username = :username LIMIT 1";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':username', $username, PDO::PARAM_STR);

		try{
			if ($statement->execute()){
				$user_data = $statement->fetch(PDO::FETCH_ASSOC);
			} else {
				$user_data = array();
			}
		} catch (Exception $error){
			echo $error->getMessage();
		}
		return $user_data;
	}

}



?>