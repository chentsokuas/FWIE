<?php
include("connect.php");
include("PHPMailerAutoload.php"); //匯入PHPMailer類別.

ignore_user_abort(true); // run script in background 在背景跑.
set_time_limit(0); // run script forever 程式執行時間不做限制.
$interval=10; // do every 15 minutes... 15分鐘
do{
  $sql_crop_waring = "SELECT * FROM `crop_waring`";
  $result_crop_waring = mysql_query($sql_crop_waring);
  while($row_crop_waring = mysql_fetch_array($result_crop_waring))
  {
  
  $sql_user = "SELECT * FROM `user` WHERE `id` = '".$row_crop_waring[user_id]."'";
  $result_user = mysql_query($sql_user);
  $row_user = mysql_fetch_array($result_user);

 $sql_crop = "SELECT * FROM `crop` WHERE `id` = '".$row_crop_waring[crop_id]."'";
  $result_crop = mysql_query($sql_crop);
  $row_crop = mysql_fetch_array($result_crop);

  $temperature_t1 =  $row_crop_waring[3];
  $temperature_t2 =  $row_crop_waring[4];
  $humidity_t1=  $row_crop_waring[5];
  $humidity_t2 =  $row_crop_waring[6];
if($row_user[grid]!=0)
{
  $sql_crop_waring1 = "SELECT * FROM `kriging` WHERE `gps_id` ='".$row_user[grid]."' ORDER BY `gps_id`,`time` DESC";
  $result_crop_waring1 = mysql_query($sql_crop_waring1);
  $row_crop_waring1 = mysql_fetch_array($result_crop_waring1);
}
  $temp = $row_crop_waring1[3];
   $hum = $row_crop_waring1[4];
   //||($hum<$humidity_t1||$hum>$humidity_t2)

  if(($temp<$temperature_t1||$temp>$temperature_t2)||($hum<$humidity_t1||$hum>$humidity_t2)) {
    // 要處理的工作
$mail= new PHPMailer(); //建立新物件        
$mail->IsSMTP(); //設定使用SMTP方式寄信        
$mail->SMTPAuth = true; //設定SMTP需要驗證        
$mail->SMTPSecure = "ssl"; // Gmail的SMTP主機需要使用SSL連線   
$mail->Host = "smtp.gmail.com"; //Gamil的SMTP主機        
$mail->Port = 465;  //Gamil的SMTP主機的SMTP埠位為465埠。        
$mail->CharSet = "utf8"; //設定郵件編碼        

$mail->Username = "jescallab@gmail.com"; //設定驗證帳號        
$mail->Password = "fish037631438"; //設定驗證密碼        

$mail->From = "jescal0001@gmail.com"; //設定寄件者信箱        
$mail->FromName = "農地氣象推估"; //設定寄件者姓名        

$mail->Subject = "農地異常"; //設定郵件標題        
$mail->Body = $row_user[user_name]."　　你好!<br \>".
"你設定的作物:".$row_crop[crop_name]."<br \>"
."你設定的溫度是".$temperature_t1."~".$temperature_t2."目前溫度為".$temp."<br \>"
."你設定的濕度是".$humidity_t1."~".$humidity_t2."目前濕度為".$hum."<br \>"
."請前往農地查看!"; //設定郵件內容       
$mail->IsHTML(true); //設定郵件內容為HTML        
$mail->AddAddress($row_user[user_email], $row_user[login]); //設定收件者郵件及名稱        

if(!$mail->Send()) {        
  echo "Mailer Error: " . $mail->ErrorInfo;        
} else {        
  echo "Message sent!";        
}    
}
// add the script that has to be ran every 15 minutes here 每15分鐘執行一次.
// ...
sleep($interval); // wait 15 minutes 等待15分鐘
}}while(true);




?>