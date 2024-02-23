<?php 
	
class ChatRooms
{
	protected $connect;
	public $array_count_for_days = [];
	public $date_holder = "";

	public function __construct(){
		require_once("Database_connection.php");

		$database_object = new Database_connection;

		$this->connect = $database_object->connect();
	}

	function get_all_chat_data(){
		// $lastSixMonthsStartDate = date('Y-m-d', strtotime('-6 months'));
		// $lastSixMonthsEndDate = date('Y-m-d');
		$query = "
		SELECT * FROM chatrooms 
			INNER JOIN registered. 
			ON registered.username = chatrooms.username 
			ORDER BY chatrooms.id ASC
		";

		$statement = $this->connect->prepare($query);
		$statement->execute();
		// $statement->bindValue(':created_on', $this->created_on);
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	function savechatroom_message($sender, $msg, $type, $files_to_upload){
		$created_on = date("Y-m-d H:i:s");
		$query = "
		INSERT INTO chatrooms 
			(username, msg, type, image, created_on) 
			VALUES (:sender, :msg, :type, :image, :created_on)
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':sender', $sender, PDO::PARAM_STR);
		$statement->bindParam(':msg', $msg, PDO::PARAM_STR);
		$statement->bindParam(':type', $type, PDO::PARAM_STR);
		$statement->bindParam(':image', $files_to_upload, PDO::PARAM_STR);
		$statement->bindParam(':created_on', $created_on, PDO::PARAM_STR);
		$statement->execute();
	}

	function get_last_six_months_all_chat_data(){
		$query = "
		SELECT chatrooms.*, registered.is_agent, registered.user_profile, registered.user_login_status
		FROM chatrooms
		INNER JOIN registered ON registered.username = chatrooms.username WHERE chatrooms.created_on >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
		ORDER BY chatrooms.id ASC;
		";

		$statement = $this->connect->prepare($query);
		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
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


	function getLastMessageDate(){
		$query = "
		SELECT MAX(created_on) AS last_date
		FROM chatrooms
		";
		$statement = $this->connect->prepare($query);
		$statement->execute();
		$data = $statement->fetchColumn();
		if (isset($data) && !empty($data)){
			return $data;
		} else {
			return date("Y-m-d H:i:s");
		}
	}

	function formatTime($date){
		$time_to_format = new DateTime($date);
        return $time_to_format->format('g:i A');
	}
}
	
?>