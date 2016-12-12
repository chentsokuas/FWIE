<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Progress Bar Liquid Bubble</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
  
      <link rel="stylesheet" href="css/style.css">

  
</head>

<body>
  <!--
    Inspired by Code Pen User
    Jamie Dixon, with this pen
    http://codepen.io/JamieDixon/pen/Pqrjvv

    I cleaned up a lot of errors and redundant stuff
    then made it more awesome and customizeable
-->
<?php
$db_server = "127.0.0.1";
$db_name = "fwie";
$db_user = "fwie";
$db_password = "fwie";
$connServer = mysql_connect($db_server, $db_user, $db_password) or trigger_error(mysql_error(),E_USER_ERROR); 
//if(!@mysql_connect($db_server, $db_user, $db_password))
  //      die("無法對資料庫連線");
mysql_query('SET NAMES utf8',$connServer);
mysql_query('SET CHARACTER_SET_CLIENT=utf8',$connServer);
mysql_query('SET CHARACTER_SET_RESULTS=utf8',$connServer);

if(!@mysql_select_db($db_name))
  die("無法使用資料庫");


$grid=$_GET[grid];
   $sql_iot = "SELECT * FROM `iot`";
   $result_iot = mysql_query($sql_iot);
   $row_iot = mysql_fetch_array($result_iot);

   $sql_crop_waring1 = "SELECT * FROM `kriging` WHERE `gps_id` ='".$grid."' ORDER BY `gps_id`,`time` DESC";
   $result_crop_waring1 = mysql_query($sql_crop_waring1);
   $row_crop_waring1 = mysql_fetch_array($result_crop_waring1);

   $temp = $row_crop_waring1[3];
   $hum = $row_crop_waring1[4];
?>

  <div class="w3-center">
  <div class="w3-col s3 m3">
  <div class="green">
    <div class="progress">
      <div class="inner">
        <div class="percent"><span><?php echo $temp;?></span></div>
        <div class="water"></div>
        <div class="glare">氣象溫度</div>
      </div>
    </div>
  </div>
  </div>
   <div class="w3-col s3 m3">
  <div class="green">
    <div class="progress">
      <div class="inner">
        <div class="percent"><span><?php echo $hum;?></span>%</div>
        <div class="water"></div>
        <div class="glare">氣象濕度</div>
      </div>
    </div>
  </div>
  </div>
   <div class="w3-col s3 m3">
  <div class="green">
    <div class="progress">
      <div class="inner">
        <div class="percent"><span><?php echo $row_iot[temp];?></span></div>
        <div class="water"></div>
        <div class="glare">感測器溫度</div>
      </div>
    </div>
  </div>
  </div>
   <div class="w3-col s3 m3">
  <div class="green">
    <div class="progress">
      <div class="inner">
        <div class="percent"><span><?php echo $row_iot[hum];?>%</span></div>
        <div class="water"></div>
        <div class="glare">感測器濕度</div>
      </div>
    </div>
  </div>
  </div>
  
  </div>
  

  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>

</body>
</html>
