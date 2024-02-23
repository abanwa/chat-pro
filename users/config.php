<?php 

define('TIMEZONE', 'Africa/Lagos');
date_default_timezone_set(TIMEZONE);
		
		// Lets get the offset depending on our timezone, in this case, africa/lagos
$now = new DateTime();
$mins = $now->getOffset() / 60;

$sgn = ($mins < 0 ? -1 : 1);
$mins = abs($mins);
$hrs = floor($mins / 60);
$mins -= $hrs * 60;

$offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
// SET time_zone='offset';


// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','tdcmobile');
// Establish database conn9ection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
$dbh->exec("SET time_zone='$offset';");
// $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,[PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'", SET time_zone => "Africa/Lagos"]);
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}

?>