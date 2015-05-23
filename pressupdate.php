<?php
require_once 'login.php';
header("Content-type:text/html;charset=utf");
$con=mysql_connect("localhost","root",$db_password) or die('Could not connect: ' . mysql_error());
mysql_select_db("smart_home",$con);
$press = 0;
echo $press;
if(isset($_GET['press']))
{
	$press = $_GET['press'];
	echo $press;
	$querytemp = "UPDATE Press SET pressure = $press WHERE room = '406'";
	mysql_query($querytemp) or die('Could not connect: ' . mysql_error());
	mysql_query("DELETE FROM Press WHERE pressure='100'");
}
//$con=mysql_connect("localhost","root",$db_password) or die('Could not connect: ' . mysql_error());
//mysql_select_db("smart_home",$con);
//mysql_query("INSERT INTO Press (room) 
//VALUES ('406')") or die('insert failed');
 //$result = mysql_query("SELECT * FROM Pressure");
// while($row = mysql_fetch_array($result))
//   {
//   echo $row['pressure'];
//   echo "<br />";
//   echo $row['Room'];
//   echo "<br />";
//   }
 //mysql_query("DELETE FROM Pressure WHERE pressure=100") or die('Could not connect: ' . mysql_error());
// while($row = mysql_fetch_array($result))
 // {
 // echo $row['pressure'];
 // echo "<br />";
 //}
mysql_close($con);
?>
