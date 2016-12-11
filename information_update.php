<?php
include("connect.php");
$id = $_POST[id_s];
$temperature= $_POST[temperature];
$humidity= $_POST[humidity];
$temperature1= $_POST[temperature1];
$humidity1= $_POST[humidity1];

$query="UPDATE `crop_waring` SET
`temperature_t1` = '".$temperature."',
`temperature_t2` = '".$temperature1."',
`humidity_t1` = '".$humidity."',
`humidity_t2` = '".$humidity1."'
WHERE `id` = '".$id."';";

mysql_query($query) or die(mysql_error());
header("Location:information.php");




?>