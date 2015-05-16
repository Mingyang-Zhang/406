<?php
require_once 'login.php';
header("Content-type:text/html;charset=utf-8");
$conn = mysql_connect("localhost".":"."3306","root",$db_password);
mysql_select_db("smart_home",$conn);
/**Jim edite
$temperature = 0;
$token = "smart_home";
$query = "INSERT INTO TOKEN(token) VALUES(".md5($token).")";
$result = mysql_query($query,$conn);
echo "200";
***/
$result = mysql_query("select * from comd");
$row = mysql_fetch_array($result);
if($row["cmd"] == "1")
{
  echo "Y";
  exit;
}
else 
{
   echo "N";
  exit;
}
?>
