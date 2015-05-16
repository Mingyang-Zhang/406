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
<<<<<<< HEAD
pressure int，
=======
pressure int,
>>>>>>> 4043d2b985c6c3779490687e29a0bf6a9d6796d7
room int
)";
mysql_query($sql,$con);
mysql_close($con);
?>
