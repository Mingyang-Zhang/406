<?php
require_once 'login.php';
$con=mysql_connect("localhost","root",$db_password) or die("Can not connect");
if (!$con)
 {
  die('Could not connect: ' . mysql_error());
 }
mysql_select_db("smart_home",$con);
$sql = "ALTER TABLE 'Pressure' ADD COLUMN 'Room' VARCHAR(10)";
mysql_query($sql) or die("add failed");
mysql_close($con);
?>
