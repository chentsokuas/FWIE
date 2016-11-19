<?php
include("connect.php");

$a1 = $_POST["a1"]; //array_newlat
$a2 = $_POST["a2"]; //array_newlon



$NewString = split ('[,]', $a1);
$NewString1 = split ('[,]', $a2);
$hh = "網格";

for($i=0;$i<sizeof($NewString);$i++)
{
$query="INSERT INTO `gps_grid`(`GPS_Longitude`,`GPS_Latitude`, `remark`) VALUES ('".$NewString1[$i]."','".$NewString[$i]."','".$hh.($i+1)."')";

echo $query;
mysql_query($query);
}
?>