<?php
header("Content-type:text/html;charset=utf");
$con=mysql_connect("localhost","root","thebestweare") or die("401");
if (!$con)
 {
  die('Could not connect: ' . mysql_error());
 }
mysql_select_db("smart_home",$con);
mysql_query("INSERT INTO Pressure (pressure) 
VALUES (100)");
$result = mysql_query("SELECT * FROM Persons");

while($row = mysql_fetch_array($con,$result))
  {
  echo $row['pressure'];
  echo "<br />";
  }
mysql_close($con);
?>
