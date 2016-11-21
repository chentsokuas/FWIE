<?php
include("connect.php");
ignore_user_abort(); // run script in background 在背景跑.
set_time_limit(0); // run script forever 程式執行時間不做限制.
$interval=60*60;// do every 15 minutes... 15分鐘

do{
	$query="UPDATE `time` SET
`time_l` = '0' WHERE `id` = '1';";
  mysql_query($query);
// add the script that has to be ran every 15 minutes here 每15分鐘執行一次.
// ...
sleep($interval); // wait 15 minutes 等待15分鐘
}while(true);
?>


