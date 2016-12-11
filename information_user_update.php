<?php
include("connect.php");
$id = $_POST[id_tt];
$grid= $_POST[grid];


$query="UPDATE `user` SET
`grid` = '".$grid."'
WHERE `id` = '".$id."';";

mysql_query($query) or die(mysql_error());
header("Location:information.php");




?>