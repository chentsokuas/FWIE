<?php
include("connect.php");
$id = $_POST[id_s];
$temperature= $_POST[temperature];
$humidity= $_POST[humidity];

$query="UPDATE `crop_waring` SET
`temperature_warning` = '".$temperature."',
`humidity_waring` = '".$humidity."'
WHERE `id` = '".$id."';";

mysql_query($query) or die(mysql_error());
header("Location:information.php");




?>