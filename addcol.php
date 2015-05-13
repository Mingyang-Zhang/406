<?php
$con=mysql_connect("localhost","root","thebestweare") or die("401");
if (!$con)
 {
  die('Could not connect: ' . mysql_error());
 }
mysql_select_db("smart_home",$con);
$sql = "ALTER TABLE "Pressure" ADD "Room" VARCHAR(10)";
mysql_query($sql) or die('402');
mysql_close($con);
?>
