<?php
$con=mysql_connect("localhost","root","thebestweare") or die("401");
if (!$con)
 {
  die('Could not connect: ' . mysql_error());
 }
$result = mysql_list_tables("smart_home");
<<<<<<< HEAD
while($row = mysql_fetch_array($result))  
=======
if(!$result)
{
 die('Cannot connect'); 
}
while($row = mysql_fetch_row($result))  
>>>>>>> 83c231f6a0d1bd889647b0a155d7a6f06cfae1f9
{  
echo $row[0]."";  
mysql_free_result($result);  
}
?>
