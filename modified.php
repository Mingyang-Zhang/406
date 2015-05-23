<?php
require_once 'login.php';
header("Content-type:text/html;charset=utf");
$con=mysql_connect("localhost","root",$db_password) or die('Could not connect: ' . mysql_error());
mysql_select_db("smart_home",$con);
//mysql_query("UPDATE Press SET flag = 1 WHERE room = 406");
//$querytemp = "UPDATE Press SET id=0 WHERE room = '406'";
//mysql_query($querytemp) or die('Could not connect: ' . mysql_error());
//$con=mysql_connect("localhost","root",$db_password) or die('Could not connect: ' . mysql_error());
//mysql_select_db("smart_home",$con);
mysql_query("INSERT INTO location (id) 
VALUES ('0')") or die('insert failed');
mysql_close($con);
?>
