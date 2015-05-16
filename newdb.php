<?php
require_once 'login.php';
header("Content-type:text/html;charset=utf");
$con=mysql_connect("localhost","root",$db_password) or die("401");
if (!$con)
 {
  die('Could not connect: ' . mysql_error());
 }
mysql_select_db("smart_home",$con);
$sql = "CREATE TABLE Press
(
pressure int，
room int
)";
mysql_query($sql,$con);
mysql_close($con);
?>
