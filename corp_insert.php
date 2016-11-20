<?php
include("connect.php");
$user_id= $_POST["user_id"];
$option = $_POST["option"];
$temperature =$_POST["temperature"];
$humidity =$_POST["humidity"];

$query="INSERT INTO `crop_waring`(`user_id`, `crop_id`, `temperature_warning`, `humidity_waring`) VALUES ('".$user_id."','".$option."','".$temperature."','".$humidity."')";
//echo $query;
mysql_query($query);
echo '新增成功......';
echo '<meta http-equiv=REFRESH CONTENT=0.5;url=information.php>';
?>