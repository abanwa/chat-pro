<?php 


namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require dirname(__DIR__) . "/chatClasses/ChatUser.php";
require dirname(__DIR__) . "/chatClasses/ChatRooms.php";
require dirname(__DIR__) . "/chatClasses/PrivateChat.php";

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
         echo "Server is running Offline localhost \n";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        $querystring = $conn->httpRequest->getUri()->getQuery();

        parse_str($querystring, $queryarray);

        if(isset($queryarray['user_unique_token'])){

            $user_object = new \ChatUser;

            $user_unique_token = trim($queryarray['user_unique_token']);

            // update the logged in user connection id using the token
            $user_object->update_connection_id_base_on_token($conn->resourceId, $user_unique_token);

            $user_data = $user_object->get_user_id_using_token($user_unique_token);
            // var_dump($user_data);
            $user_id = $user_data['id'];

            $data['status_type'] = 'online';

            $data['user_id_status'] = $user_id;

            // first, you are sending to all existing users message of 'new'
            foreach ($this->clients as $client)
            {
                $client->send(json_encode($data)); //here we are sending a status-message
            }
        }

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        // when we recieve the message sent, decode the JSON msg recieved to array
        $data = json_decode($msg, true);

        // check if the message is sent for a private chat
        if ($data['command'] == "private"){
            //private chat
            $private_chat_object = new \PrivateChat;

            $receiver = trim($data['receiver']);
            $logged_in_user = trim($data['sender']);

            $chat_msg = trim($data['msg']);
            $msg_type = $data['type'];

            $user_name = $logged_in_user;
            $today_date_label = date("Y-m-d");
            $today = date("Y-m-d H:i:s");

            // get the last date where login user sent message to receiver or receiver sent msg to login user
            $previous_chat_date = $private_chat_object->getLastMessageDate($logged_in_user, $receiver);
            $previous_date = date("Y-m-d", strtotime($previous_chat_date));

            // DAYS DIFF
            $timestamp1 = strtotime($today_date_label);
            $timestamp2 = strtotime($previous_date);

            $dateDiff = abs($timestamp2 - $timestamp1);
            $daysDiff = floor($dateDiff / (60 * 60 * 24));

            if ($daysDiff <= 0){
                $date_label = "";
            } else {

                $date_label = $private_chat_object->date_label($today_date_label, $previous_date);
            }

            $display_time = $private_chat_object->formatTime($today);

            $data['date_label'] = $date_label;
            $data['time_label'] = $display_time;

            if ($msg_type == "message"){

                $chat_id = $private_chat_object->save_private_message($logged_in_user, $receiver, $chat_msg, "message", NULL, "not_seen");
            }

            if ($msg_type == "image"){
                $files_to_upload = $chat_msg;

                $chat_id = $private_chat_object->save_private_message($logged_in_user, $receiver, NULL, "image", $files_to_upload, "not_seen");
            }

            $user_object = new \ChatUser;

            $elapse_time = $user_object->time_elapsed_string($today);
            $data['elapse_time'] = $elapse_time;

            // get the receiver's connect_id and user_id from registered table using username
            $receiver_user_data = $user_object->get_connection_id_using_username($receiver);
            $data['reciever_id'] = $receiver_user_data['id'];
            $receiver_user_connection_id = $receiver_user_data['user_connection_id'];

            // get the sender user id
            $sender_user_data = $user_object->get_connection_id_using_username($logged_in_user);
            $data['sender_id'] = $sender_user_data['id'];


            foreach ($this->clients as $client) {

                // if sender is the receiver, it should show as me else it should show the sender name
                if($from == $client){
                    // if it's me that sent it, it will show as me and no profile pic in my own page
                    $data['from'] = 'Me';
                    $data['profile_pic'] = "";
                } else {
                    // else it will show as "my name" for the other user in his/her page
                    $data['from'] = $logged_in_user;
                    $sender_profile = $user_object->getProfile($logged_in_user);

                    if (!empty($sender_profile)){
                        $data['profile_pic'] = $sender_profile;
                    } else {
                        $data['profile_pic'] = "img/profilePic.png";
                    }
                }


                // that means the receiver is online
                if($client->resourceId == $receiver_user_connection_id || $from == $client){   
                    $client->send(json_encode($data));
                } else {
                    // other wise, the message will not be sent but it has already been inserted into database
                    // we will just update the status to "not_seen" using the last chat_id

                    $private_chat_object->update_chat_status_using_last_msg_id("not_seen", $chat_id);
                }
            }



        } else {

            // the message is sent from group chat (chat-room.php)

            // initiate the ChatRooms class
            $chat_object = new \ChatRooms;

            $user_object = new \ChatUser;

            // check message type
            $msg_sender = $data['sender'];
            $msg_sent = $data['msg'];
            $msg_type = $data['type'];

            $user_name = $msg_sender;
            $today_date_label = date("Y-m-d");
            $today = date("Y-m-d H:i:s");

            $previous_chat_date = $chat_object->getLastMessageDate();
            $previous_date = date("Y-m-d", strtotime($previous_chat_date));

            // DAYS DIFF
            $timestamp1 = strtotime($today_date_label);
            $timestamp2 = strtotime($previous_date);

            $dateDiff = abs($timestamp2 - $timestamp1);
            $daysDiff = floor($dateDiff / (60 * 60 * 24));

            if ($daysDiff <= 0){
                $date_label = "";
            } else {

                $date_label = $chat_object->date_label($today_date_label, $previous_date);
            }


            // $time_to_format = new DateTime($today);
            // $display_time = $time_to_format->format('g:i A');
            $display_time = $chat_object->formatTime($today);

            $data['date_label'] = $date_label;
            $data['time_label'] = $display_time;


            if ($msg_type == "message"){

                $chat_object->savechatroom_message($msg_sender, $msg_sent, "message", NULL);
            }

            if ($msg_type == "image"){
                $files_to_upload = $msg_sent;
                $chat_object->savechatroom_message($msg_sender, NULL, "image", $files_to_upload);
            }

            foreach ($this->clients as $client) {
                /*if ($from !== $client) {
                    // The sender is not the receiver, send to each client connected
                    $client->send($msg);
                }*/

                // if sender is the receiver, it should show as me else it should show the sender name
                if($from == $client){
                    $data['from'] = 'Me';
                    $data['profile_pic'] = "";
                } else {
                    $data['from'] = $msg_sender;
                    $sender_profile = $user_object->getProfile($msg_sender);

                    if (!empty($sender_profile)){
                        $data['profile_pic'] = $sender_profile;
                    } else {
                        $data['profile_pic'] = "img/profilePic.png";
                    }
                }

                // the message will be send as a JSON to chat-room.php
                $client->send(json_encode($data));
            }


        }

    }

    public function onClose(ConnectionInterface $conn) {

        $querystring = $conn->httpRequest->getUri()->getQuery();

        parse_str($querystring, $queryarray);

        if(isset($queryarray['user_unique_token'])){

            $user_object = new \ChatUser;

            $user_unique_token = trim($queryarray['user_unique_token']);


            $user_data = $user_object->get_user_id_using_token($user_unique_token);
            // var_dump($user_data);
            $user_id = $user_data['id'];

            $data['status_type'] = 'offline';

            $data['user_id_status'] = $user_id;

            // first, you are sending to all existing users message of 'new'
            foreach ($this->clients as $client)
            {
                $client->send(json_encode($data)); //here we are sending a status-message
            }
        }


        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}




 ?>