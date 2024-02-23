<?php

//PrivateChat.php

class PrivateChat {
	protected $connect;
	public $array_count_for_days = [];
	public $date_holder = "";

	public function __construct()
	{
		require_once('Database_connection.php');

		$db = new Database_connection();

		$this->connect = $db->connect();
	}

	function change_chat_status($from, $to){
		$query = "
		UPDATE chat_message 
			SET status = 'seen' 
			WHERE sent_from = :sent_from 
			AND sent_to = :sent_to 
			AND status = 'not_seen'
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':sent_from', $from);

		$statement->bindParam(':sent_to', $to);

		$statement->execute();
	}

	// sent_to is the logged in user
	function get_last_six_months_chat_data($sent_from, $sent_to){
		$query = "
		SELECT chat_message.*, registered.is_agent, registered.user_profile, registered.user_login_status
		FROM chat_message
		INNER JOIN registered ON registered.username = chat_message.sent_from WHERE (chat_message.sent_from = :sent_from AND chat_message.sent_to = :sent_to OR chat_message.sent_from = :sent_to AND chat_message.sent_to = :sent_from) AND chat_message.created_on >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
		ORDER BY chat_message.id ASC";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':sent_from', $sent_from);

		$statement->bindParam(':sent_to', $sent_to);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	// get last/latest date between the login user and receiver
	function getLastMessageDate($login_user, $receiver){
		$query = "
		SELECT MAX(created_on) AS last_date
		FROM chat_message WHERE (sent_from=:login_user AND sent_to=:receiver) 
		OR (sent_from=:receiver AND sent_to=:login_user)
		";
		$statement = $this->connect->prepare($query);
		$statement->bindParam(":login_user", $login_user, PDO::PARAM_STR);
		$statement->bindParam(":receiver", $receiver, PDO::PARAM_STR);
		$statement->execute();
		$data = $statement->fetchColumn();
		if (isset($data) && !empty($data)){
			return $data;
		} else {
			return date("Y-m-d H:i:s");
		}
	}

	function date_label($today, $previous_date){
		$daysDifference = (strtotime($today) - strtotime($previous_date)) / (60 * 60 * 24);

		// if ($count == 0){
		if (!in_array($daysDifference, $this->array_count_for_days)){
			if ($daysDifference < 1) {
                $days =  "Today";
            } else if ($daysDifference === 1) {
                $days =  " Yesterday ";
            } else if ($daysDifference <= 7) {
                $days =  "{$daysDifference} days ago ";
            } else {
            	$dateTime = new DateTime($previous_date);
            	$days = $dateTime->format('l, F j, Y g:ia');
            }

            $this->date_holder = "<label class='main-chat-time'><span>{$days}</span></label>";
		}

		// if ($count > 0){
		if (in_array($daysDifference, $this->array_count_for_days)){
			$this->date_holder = "";
		}

		array_push($this->array_count_for_days, $daysDifference);
		return $this->date_holder;
	}

	function formatTime($date){
		$time_to_format = new DateTime($date);
        return $time_to_format->format('g:i A');
	}

	function save_private_message($login_user, $receiver, $msg, $type, $files_to_upload, $status){
		$created_on = date("Y-m-d H:i:s");
		$query = "
		INSERT INTO chat_message 
			(sent_from, sent_to, msg, type, image, status, created_on) 
			VALUES (:login_user, :receiver, :msg, :type, :image, :status, :created_on)
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':login_user', $login_user, PDO::PARAM_STR);
		$statement->bindParam(':receiver', $receiver, PDO::PARAM_STR);
		$statement->bindParam(':msg', $msg, PDO::PARAM_STR);
		$statement->bindParam(':type', $type, PDO::PARAM_STR);
		$statement->bindParam(':image', $files_to_upload, PDO::PARAM_STR);
		$statement->bindParam(':status', $status, PDO::PARAM_STR);
		$statement->bindParam(':created_on', $created_on, PDO::PARAM_STR);
		$statement->execute();
	}

	function update_chat_status_using_last_msg_id($status, $chat_id){
		$query = "
		UPDATE chat_message 
			SET status = :status 
			WHERE id = :chat_id
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':status', $status);

		$statement->bindParam(':chat_id', $chat_id);

		$statement->execute();
	}

}



?>