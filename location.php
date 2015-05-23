<?php
require_once 'login.php';
header("Content-type:text/html;charset=utf");
$con=mysql_connect("localhost","root",$db_password) or die("Can not connect");
if (!$con)
{
  die('Could not connect: ' . mysql_error());
}
mysql_select_db("smart_home",$con);
$result = mysql_query("SELECT * FROM location
		WHERE id=0");
while($row = mysql_fetch_array($result))
{
  		if($row['distance']>30) echo "<a";
  		else echo "<b";
}
mysql_close($con);
?>