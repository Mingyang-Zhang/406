<?php
header("Content-type:text/html;charset=utf");
$con=mysql_connect("localhost","root","thebestweare") or die("401");
if (!$con)
 {
  die('Could not connect: ' . mysql_error());
 }
mysql_select_db("smart_home",$con);
mysql_query("INSERT INTO Pressure (pressure) 
VALUES (100);
mysql_close($con);
?>
