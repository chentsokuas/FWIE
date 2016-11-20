<?php session_start(); ?>
<?php
include("connect.php");
$usrname = $_POST[usrname];
$psw = $_POST[psw];
$sql_user ="SELECT * FROM `user` where `login` = '".$usrname."';";
$result_user = mysql_query($sql_user);
$row_user = mysql_fetch_array($result_user);

if($row_user[login] == $usrname &&  $row_user[password]  == $psw)
{

 $_SESSION['user_id'] = $row_user[id];
  $_SESSION['username'] =  $row_user[login];
  $_SESSION['passowrd'] =  $row_user[password];
  echo '登入成功!';
  echo '<meta http-equiv=REFRESH CONTENT=0.5;url=information.php>';
}
else
{

  echo '登入失敗!';
  echo '<meta http-equiv=REFRESH CONTENT=0.5;url=index.php>';
}


?>