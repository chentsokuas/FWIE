<?php
include("connect.php");
$id_d= $_POST["id_d"];

$query="DELETE FROM `crop_waring`  WHERE `id` = '$id_d'";

//echo $query;
mysql_query($query);
echo '刪除成功......';
echo '<meta http-equiv=REFRESH CONTENT=0.5;url=information.php>';
?>