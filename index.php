<?php
require_once 'login.php';
header("Content-type:text/html;charset=utf");
mysql_connect("localhost","root",$db_password) or die("401");
mysql_select_db("smart_home");

$temperature = 0;
$token = "toekn";
$token = $_GET['token'];
$query = "SELECT * FROM Token";
$result = mysql_query($query);
$array = mysql_fetch_array($result);
if($array['token'] == md5($token) && isset($_GET['temperature'])) {
	$temperature = $_GET['temperature'];
	$queryTime = "INSERT INTO Temperature (temp,time) VALUES ('$temperature',now())";
	mysql_query($queryTime) or die("402");
    	die("200");
}
mysql_close($conn);

?>
