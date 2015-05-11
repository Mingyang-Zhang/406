<?php
$con=mysql_connect("localhost","root","thebestweare") or die("401");
if (!$con)
 {
  die('Could not connect: ' . mysql_error());
 }
$result = mysql_list_tables("smart_home");
while($row = mysql_fetch_row($result))  
{  
echo $row[0]."";  
mysql_free_result($result);  
}
?>
