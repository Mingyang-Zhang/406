<?php
//根据表名，打出表头信息
require_once 'login.php';
function show_table_info($table_name){
require_once 'login.php';
$conn=mysql_connect("localhost",$db_username,$db_password);
if(!$conn){
die("数据库连接失败".mysql_error());
}
mysql_select_db("smart_home");
$sql="select * from $table_name";
$res=mysql_query($sql,$conn);
if(!$res){
die("查询失败".mysql_error());
}
//返回行记录
$rows=mysql_affected_rows($conn);
echo "行数".$rows;
echo "<br/>";
//返回列数
$cols=mysql_num_fields($res);
echo "列数".$cols;
echo "<table border='1'>";
//返回结果集当中字段的信息，每次取一个，循环的去取，一次取一列
while($field_infor=mysql_fetch_field($res)){
//name是指的列名
//table该列所在的表名
//max_length 该列最大长度
echo "<th>".$field_infor->name."</th>";
}
while ($row=mysql_fetch_row($res)){
echo "<tr>";
foreach ($row as $key=>$val){
echo "<td>$val</td>";
}
echo "</tr>";
}
echo "<table>";
mysql_free_result($res);
}
show_table_info("Pressure");
?>
