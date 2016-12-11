<?php
include("connect.php");
$user_id= $_POST["user_id"];
$option = $_POST["option"];
$temperature =$_POST["temperature"];
$humidity =$_POST["humidity"];
$temperature1 =$_POST["temperature1"];
$humidity1 =$_POST["humidity1"];

$query="INSERT INTO `crop_waring`(`user_id`, `crop_id`, `temperature_t1`, `temperature_t2`, `humidity_t1`, `humidity_t2`) VALUES ('".$user_id."','".$option."','".$temperature."','".$temperature1."','".$humidity."','".$humidity1."')";
//echo $query;
mysql_query($query);
echo '新增成功......';
echo '<meta http-equiv=REFRESH CONTENT=0.5;url=information.php>';
?>