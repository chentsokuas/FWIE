<?php
include("connect.php");
$usrname=$_POST["usrname"]; 
$login=$_POST["login"]; 
$psw=$_POST["psw"]; 
$email=$_POST["email"]; 
$lon=$_POST["lon"]; 
$lat=$_POST["lat"]; 

$query="INSERT INTO `user`(`login`,`password`, `GPS_Longitude`, `GPS_Latitude`, `user_name`, `user_email`) VALUES ('".$login."','".$psw."','".$lon."','".$lat."','".$usrname."','".$email."')";
//echo $query;
mysql_query($query);
echo '申請成功......';
echo '<meta http-equiv=REFRESH CONTENT=0.5;url=login.php>';
?>