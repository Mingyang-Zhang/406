<?php
require_once 'login.php';
header("Content-type:text/html;charset=utf");
$con=mysql_connect("localhost","root",$db_password) or die("401");
if (!$con)
 {
  die('Could not connect: ' . mysql_error());
 }
mysql_select_db("smart_home",$con);
$sql = "CREATE TABLE location
(
	latitude float(15),
	longitude float(15),
	id int(1)
)";
mysql_query($sql,$con) or die("failed");
mysql_close($con);
?>
