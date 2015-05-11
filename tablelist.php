<?php
$con=mysql_connect("localhost","root","thebestweare") or die("401");
if (!$con)
 {
  die('Could not connect: ' . mysql_error());
 }
mysql_select_db("smart_home",$con);
$result = mysql_query("SHOW TABLES",$con);  
while($row = mysql_fetch_array($result))  
{  
echo $row[0]."";  
mysql_free_result($result);  
}
?>