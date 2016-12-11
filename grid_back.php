<!DOCTYPE html>
<html>
<head>
  <title>以台灣氣象站為基礎之農地氣象資訊推估系統</title>
  <meta name="viewport" content="initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
  <meta charset="utf-8">
<meta http-equiv="refresh" content="3600" />
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyDQ2OAc23JPD1J470b2zfddyy-PrDIrZag&callback=initMap"></script>
  <script src="./src/kriging.js" type="text/javascript"></script>

</head>

<body>


  <!-- Sidenav -->
  <nav class="w3-sidenav w3-collapse w3-light-green w3-animate-left" style="z-index:3;width:270px;margin-top:1%; " id="mySidenav">
        <?php
         $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
         //$xml=simplexml_load_file("./opendata/O-A0001-001.xml") or die("目前opendata資料出現問題");
         $i=0;
         foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->weatherElement[3]->elementValue->value > -20)
            { ?>
          <locationName id="<?php echo "locationName".$i;?>" style="display: none;"><?php echo $books->locationName;?></locationName>
          <lat id="<?php echo "lat".$i;?>" style="display: none;"><?php echo $books->lat;?></lat>
          <lon id="<?php echo "lon".$i;?>" style="display: none;"><?php echo $books->lon;?></lon>
          <time id="<?php echo "time".$i;?>" style="display: none;"><?php echo $books->time->obsTime;?></time>
          <elev id="<?php echo "elev".$i;?>" style="display: none;"><?php echo $books->weatherElement[0]->elementValue->value;?></elev>
          <temp id="<?php echo "temp".$i;?>" style="display: none;"><?php echo $books->weatherElement[3]->elementValue->value;?></temp>
          <?php
          $i++;
        }
      } 
        foreach($xml->children() as $books1) { 
          if($books1->locationName !="" &&  $books1->weatherElement[4]->elementValue->value > 0)
            { ?>
          <locationName1 id="<?php echo "locationName1".$i;?>" style="display: none;"><?php echo $books->locationName;?></locationName1>
          <lat1 id="<?php echo "lat1".$i;?>" style="display: none;"><?php echo $books1->lat;?></lat1>
          <lon1 id="<?php echo "lon1".$i;?>" style="display: none;"><?php echo $books1->lon;?></lon1>
          <time1 id="<?php echo "time1".$i;?>" style="display: none;"><?php echo $books1->time->obsTime;?></time1>
          <temp1 id="<?php echo "temp1".$i;?>" style="display: none;"><?php echo $books1->weatherElement[4]->elementValue->value;?></temp1>
          <?php
        
        }
      } 
      ?>
      <div id="time">time</div>
      <script type="text/javascript">
        function　Time(){
          var nntime = document.getElementsByTagName("time");
          var NewString = nntime[0].innerHTML;
          document.getElementById("time").innerHTML="最後更新:</br>"+NewString.split("T")[0]+" "+(NewString.split("T")[1]).split("+08:00")[0];
        }
        Time();
      </script>
      <input name="long" id="long" class="w3-input" value="5" style="display: none">


   <div id="map" style="width:100%;height:450px"></div>
   <form name="myform3" action="" method="post">
<input type="hidden" name="temperature" value="">
<input type="hidden" name="temperature1" value="">
</form>


   <script type="text/javascript">
    var myLatlng = new google.maps.LatLng(23.7, 120.9082103);
        // map options,
        var myOptions = {
          zoom: 7,
          center: myLatlng
        };
        // standard map
        map = new google.maps.Map(document.getElementById("map"), myOptions);




        var nnlocationName = document.getElementsByTagName("locationName");
        var nnlat = document.getElementsByTagName("lat");
        var nnlon = document.getElementsByTagName("lon");
        var nntemp = document.getElementsByTagName("temp");

        var nnlocationName1 = document.getElementsByTagName("locationName1");
        var nnlat1 = document.getElementsByTagName("lat1");
        var nnlon1 = document.getElementsByTagName("lon1");
         var nntemp1 = document.getElementsByTagName("temp1");

        var array_locationName =[];
        var array_temp =[];
        var array_lat =[];
        var array_lon =[];
        var array_newcenter =[];
        var array_newlat =[];
        var array_newlon =[];


        var array_locationName1 =[];
        var array_temp1 =[];
        var array_lat1 =[];
        var array_lon1 =[];
        var array_newcenter1 =[];
        var array_newlat1 =[];
        var array_newlon1 =[];



        for (var i=0;i<nnlocationName.length;i++)
        {
         array_locationName.push(nnlocationName[i].innerHTML);
         array_lat.push(parseFloat(nnlat[i].innerHTML));
         array_lon.push(parseFloat(nnlon[i].innerHTML));
         array_temp.push(parseFloat(nntemp[i].innerHTML));
       
       }

              for (var i=0;i<nnlocationName1.length;i++)
        {
         array_locationName1.push(nnlocationName1[i].innerHTML);
         array_lat1.push(parseFloat(nnlat1[i].innerHTML));
         array_lon1.push(parseFloat(nnlon1[i].innerHTML));
         array_temp1.push(parseFloat(nntemp1[i].innerHTML));
       
       }



      //---------------------------------------------
      var t = array_temp;
      var x = array_lat;
      var y = array_lon;
      var model = "exponential";
      var sigma2 = 0, alpha = 100;
      var variogram = kriging.train(t, x, y, model, sigma2, alpha);

       var t1 = array_temp1;
      var x1 = array_lat1;
      var y1 = array_lon1;
       var variogram1 = kriging.train(t1, x1, y1, model, sigma2, alpha);


     //---------------------------------------------





//以下網格
/*
      north: 25.34,
      south: 21.871,
      east: 122.03,
      west: 120.03322005271912
      */

       var myArray0 = [];
       var SN = (25.34-21.871)*55/document.getElementById('long').value;
       var ES = (122.03-120.03322005271912)*55/document.getElementById('long').value;
       var dis = document.getElementById('long').value/110;
       var Map_Lat=25.34;
       var Map_lng = 120.03322005271912;
       var count =1;
       var rectangleOptions = {
        strokeOpacity: 0.1,
        fillColor: "hsl(126, 100%, 50%)"
      };
      for (var i = 0; i < Math.ceil(SN)*(Math.ceil(ES)+1); i++) {
        count++;

        var P1 = new google.maps.LatLng(Map_Lat + dis, Map_lng - dis);
        var P2 = new google.maps.LatLng(Map_Lat - dis, Map_lng + dis);
        var P_center = new google.maps.LatLng(Map_Lat, Map_lng);
        var latLngBounds = new google.maps.LatLngBounds(P1, P2);
        array_newcenter.push(P_center);
        array_newlat.push(P_center.lat());
        array_newlon.push(P_center.lng());
        array_newcenter1.push(P_center);
        array_newlat1.push(P_center.lat());
        array_newlon1.push(P_center.lng());

   
      var rectangle = new google.maps.Rectangle(rectangleOptions);
      var Color = 360 - Math.round((360 * kriging.predict(array_newlat[i],array_newlon[i], variogram)/30));
      rectangle.setOptions({ fillColor: "hsl(" + Color + ", 100%, 50%)" });
      rectangle.setMap(map);
      rectangle.setBounds(latLngBounds);
      if(count!=Math.ceil(SN)+1)
      {
        Map_Lat = Map_Lat - (dis*2);
      }
      else
      {
       count =1;
       Map_Lat=25.34;
       Map_lng = Map_lng  + (dis*2);
     }


document.myform3.temperature.value += kriging.predict(array_newlat[i],array_newlon[i], variogram)+",";
document.myform3.temperature1.value += kriging.predict(array_newlat1[i],array_newlon1[i], variogram1)+",";

   }



</script>
  <?php session_start(); 
  if( $_SESSION['flag'] == 1)
  {
    sleep(3);
    unset($_SESSION['flag']);
  }
  else
  {
    $_SESSION['flag']=1;
    echo '<script>';
    echo 'document.myform3.submit();';
    echo '</script>';
  }
   
  
 ?>
  
<?php

if(isset($_POST["temperature"])){
  $temperature = $_POST["temperature"];
   $temperature1 = $_POST["temperature1"];
 
  include("connect.php");
$NewString = split ('[,]', $temperature);
$NewString1 = split ('[,]', $temperature1);
date_default_timezone_set("Asia/Taipei");
$datetime =  date("Y-m-d H:i:s") ; 
for($y=0;$y<sizeof($NewString)-1;$y++){
  $NewString1[$y]=$NewString1[$y]*100;
  if($NewString1[$y]>100)
      $NewString1[$y]=100;
      if($NewString1[$y]<0)
      $NewString1[$y]=0;
  $zz=$y+1;
  $query="INSERT INTO `kriging`(`gps_id`, `time`, `temperature`,`humidity`) VALUES ('".$zz."','".$datetime."','".$NewString[$y]."','".$NewString1[$y]."')";
  mysql_query($query);
}


}
?>



</body>
</html>

