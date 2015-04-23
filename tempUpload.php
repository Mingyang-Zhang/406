<?php
header("Content-type:text/html;charset=utf-8");
$conn = mysql_connect("localhost".":"."3306","root","thebestweare");
mysql_select_db("smart_home",$conn);

$temperature = 0;
$token = "smart_home";
$query = "INSERT INTO TOKEN(token) VALUES(".md5($token).")";
$result = mysql_query($query,$conn);
echo "200";

?>
