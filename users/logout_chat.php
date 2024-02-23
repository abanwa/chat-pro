<?php 

session_start();
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log');
error_reporting(E_ALL);
   // error_reporting(0);
include("config.php");
// include("dbcon.php");

if (isset($_POST['action']) && $_POST['action'] == 'leave'){


	$username = (isset($_SESSION['xyxyxy.username'])) ? $_SESSION['xyxyxy.username'] : false;

    $user_login_status = "offline";
    $logout_query = $dbh->prepare("UPDATE registered SET user_login_status=:user_login_status WHERE username=:username");
    $logout_query->bindParam(":user_login_status", $user_login_status, PDO::PARAM_STR);
    $logout_query->bindParam(":username", $username, PDO::PARAM_STR);
    $logout_query->execute();

    // session_destroy();

    $res = json_encode([
    	"code" => 200
    ]);

    echo $res;
    exit();
}


 ?>