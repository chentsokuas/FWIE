  <?php
  $xml=simplexml_load_file("http://opendata.epa.gov.tw/ws/Data/AQXDaily/?format=xml") or die("目前opendata資料出現問題");

//http://data.gov.tw/node/6350
 include("connect.php");

  foreach($xml->children() as $books) {
    if($books->SiteName!="")
    {
      $sql_basic = "SELECT * FROM `airquality_station` where  `st_name`='$books->SiteName'";
     $result_basic = mysql_query($sql_basic);
     $row_basic = mysql_fetch_array($result_basic);
     echo "地點:".$books->SiteName."</br>";
     echo "經度:".$row_basic['GPS_Longitude']."</br>";
     echo "緯度:".$row_basic['GPS_Latitude']."</br>";
     echo "監測日期:".$books->MonitorDate."</br>";
     echo "空氣污染指標PSI:".$books->PSI."</br>";
     echo "二氧化硫PSI副指標:".$books->SO2SubIndex."</br>";
     echo "一氧化碳PSI副指標:".$books->COSubIndex."</br>";
     echo "臭氧PSI副指標:".$books->O3SubIndex."</br>";
     echo "懸浮微粒PSI副指標:".$books->PM10SubIndex."</br>";
     echo "二氧化氮PSI副指標:".$books->NO2SubIndex."</br>";
   }
 }
 ?>