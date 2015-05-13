<?php  
    $conn=new mysqli("localhost","root","thebestweare");  
    if($conn->connect_error) die("数据库连接失败".$conn->connect_error);  
    $conn->select_db("php");  
    $sql="select * from score";  
    $result=$conn->query($sql);  
    if(!$result) die("数据查询失败");  
      
    $row_num=$result->num_rows;  
    $col_num=$result->field_count;  
    echo"行数为:$row_num,列数为:$col_num";  
    echo "<br/>";  
      
    echo "<table border=1><tr>";//表格  
    while ($field=$result->fetch_field())  
    {  
        echo "<th>$field->name</th>";//$field的name属性..  
    }  
    echo "</tr>";  
      
    while($res=$result->fetch_row())  
    {  
        echo "<tr>";  
        foreach($res as $val)  
            echo "<th>$val</th>";  
        echo "</tr>";  
    }  
    echo "</table>";  
?>  