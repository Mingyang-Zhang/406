<?php
require_once 'login.php';
header("Content-type:text/html;charset=utf");
$con=mysql_connect("localhost","root",$db_password) or die('Could not connect: ' . mysql_error());
mysql_select_db("smart_home",$con);
//mysql_query("INSERT INTO Press (room) 
//VALUES ('406')") or die('insert failed');
$querytemp = "UPDATE Press SET id=0 WHERE room = '406'";
mysql_query($querytemp) or die('Could not connect: ' . mysql_error());
mysql_close($con);
?>
