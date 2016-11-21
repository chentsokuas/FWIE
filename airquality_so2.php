<!DOCTYPE html>
<html>
<head>
  <title>以台灣氣象站為基礎之農地氣象資訊推估系統</title>
  <meta name="viewport" content="initial-scale=1.0">
  <meta charset="utf-8">
  <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
  <style>
    .w3-theme {color:#fff !important;background-color:#4CAF50 !important}
    .w3-btn {background-color:#4CAF50;margin-bottom:4px}
    .w3-code{border-left:4px solid #4CAF50}
    .myMenu {margin-bottom:150px}
    html { height: 100% }
    body { height: 100%; margin: 0; padding: 0 }
  </style>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyDQ2OAc23JPD1J470b2zfddyy-PrDIrZag&callback=initMap"></script>
  <script src="./src/kriging.js" type="text/javascript"></script>
</head>

<body>
  <!-- Top -->
  <div class="w3-top">
    <div class="w3-row w3-white w3-padding">
      <div class="w3-half" style="margin:4px 0 6px 0"><img src='./img/logoban.png'></div>
      <div class="w3-half w3-margin-top w3-wide w3-hide-medium w3-hide-small"><div class="w3-right">以台灣氣象站為基礎之農地氣象資訊推估系統</div></div>
    </div>

    <ul class="w3-navbar w3-theme w3-large" style="z-index:4;">
      <li class="w3-opennav w3-left w3-hide-large">
        <a class="w3-hover-white w3-large w3-theme w3-padding-16" href="javascript:void(0)" onclick="w3_open()">☰</a>
      </li>
      <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="temperature.php">溫度</a></li>
      <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="rainfall.php">雨量</a></li>
      <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="humidity.php">濕度</a></li>
      <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="Barometric_pressure.php">氣壓</a></li>
       <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="wind.php">風速風向</a></li>
        <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="airquality.php">空氣品質</a></li>
              <li class="w3-hide-medium w3-hide-small w3-right w3-pale-green"><a class="w3-hover-white w3-padding-16 w3-center" href="information.php">農地資訊</a></li>
    </ul>
  </div>

  <!-- Sidenav -->
  <nav class="w3-sidenav w3-collapse w3-light-green w3-animate-left" style="z-index:3;width:270px;margin-top:1%; " id="mySidenav">
    <div class="w3-hide-large">
      <a href="temperature.php" class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large w3-center" style="width:50%">溫度</a>
      <a href="rainfall.php" class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large w3-center" style="width:50%">雨量</a>
      <a href="humidity.php" class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large w3-center" style="width:50%">濕度</a>
      <a href="Barometric_pressure.php" class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large w3-center" style="width:50%">氣壓</a>
       <a href="wind.php" class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large w3-center" style="width:50%">風速風向</a>
         <a href="airquality.php" class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large w3-center" style="width:50%">空氣品質</a>
           <a href="information.php" class="w3-left w3-hover-white w3-padding-16 w3-large w3-center w3-pale-green" style="width:100%">農地資訊</a>
    </div>
    <div class="w3-clear"></div>
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-right w3-xlarge w3-padding-large w3-hide-large" title="close menu">×</a>
    <div id="menuTut" class="myMenu">
      <div class="w3-container w3-padding-top">
        <form action="index.php">
           <?php
         include("connect.php");
         //http://data.gov.tw/node/6350
         $xml=simplexml_load_file("http://opendata2.epa.gov.tw/AQX.xml") or die("目前opendata資料出現問題");
          //$xml=simplexml_load_file("./opendata/AQX.xml") or die("目前opendata資料出現問題");
         $so2 = 'SO2';
         foreach($xml->children() as $books) { 
          if($books->SiteName!="" && $books->$so2!="")
          {
            $sql_basic = "SELECT st_name,GPS_Longitude,GPS_Latitude FROM `airquality_station` where  `st_name`='$books->SiteName'";
            $result_basic = mysql_query($sql_basic);
            $row_basic = mysql_fetch_array($result_basic);
            ?>
            <locationName id="<?php echo "locationName".$i;?>" style="display: none;"><?php echo $books->SiteName;?></locationName>
            <lat style="display: none;"><?php echo $row_basic['GPS_Latitude'];?></lat>
            <lon style="display: none;"><?php echo $row_basic['GPS_Longitude'];?></lon>
            <time style="display: none;"><?php echo $books->PublishTime;?></time>
            <temp style="display: none;"><?php echo $books->$so2;?></temp>
            <?php
          }
        } 
        ?>
      <div id="time">time</div>
        <script type="text/javascript">
          function　Time(){
            var nntime = document.getElementsByTagName("time");
            var NewString = nntime[0].innerHTML;
            document.getElementById("time").innerHTML="最後更新:</br>"+nntime[0].innerHTML;
          }
          Time();
        </script>

       <h3>經緯度查詢</h3>
      <p>緯度:<input name="lat" id="lat" class="w3-input" value=""></input></p>
      <p>經度:<input name="lng" id="lng" class="w3-input" value=""></input></p>
      <p>網格大小:<input name="long" id="long" class="w3-input" value="5"></input></p>
      <input id="btnst" class="w3-blue w3-large w3-center" type ="button"  value="推估"></input>

    </form>
  </div>

</div>
</nav>

<!-- Overlay effect when opening sidenav on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- Main content: shift it to the right by 270 pixels when the sidenav is visible -->
<div class="w3-main w3-container" style="margin-left:270px;margin-top:117px;">

  <div class="w3-container w3-section w3-padding-large w3-card-4 w3-light-grey">
    <ul class="w3-navbar w3-container w3-section w3-topbar w3-bottombar w3-border-red w3-pale-red w3-center " style="z-index:4;">
      <li><a class="w3-hover-white w3-padding-16 w3-center" href="airquality.php">污染指標(PSI)</a></li>
      <li><a class="w3-hover-white w3-padding-16 w3-center" href="airquality_co.php">一氧化碳(CO)</a></li>
      <li><a class="w3-hover-white w3-padding-16 w3-center" href="airquality_no2.php">二氧化氮(NO2)</a></li>
      <li><a class="w3-hover-white w3-padding-16 w3-center" href="airquality_o3.php">臭氧(O3)</a></li>
      <li><a class="w3-hover-white w3-padding-16 w3-center" href="airquality_pm10.php">懸浮微粒(PM10)</a></li>
      <li><a class="w3-hover-white w3-padding-16 w3-center" href="airquality_pm25.php">細懸浮微粒(PM2.5)</a></li>
      <li class="w3-red"><a class="w3-hover-white w3-padding-16 w3-center" href="airquality_so2.php">二氧化硫(SO2)</a></li>
    </ul>
   <div id="map" style="width:100%;height:450px"></div>
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
        var btnst = document.getElementById('btnst');
        var array_locationName =[];
        var array_temp =[];
        var array_lat =[];
        var array_lon =[];
        var array_newcenter =[];
        var array_newlat =[];
        var array_newlon =[];
     



        for (var i=0;i<nnlocationName.length;i++)
        {
         array_locationName.push(nnlocationName[i].innerHTML);
         array_lat.push(parseFloat(nnlat[i].innerHTML));
         array_lon.push(parseFloat(nnlon[i].innerHTML));
         array_temp.push(parseFloat(nntemp[i].innerHTML));
        
       }


      //---------------------------------------------
      var t = array_temp;
      var x = array_lat;
      var y = array_lon;
      var model = "exponential";
      var sigma2 = 0, alpha = 100;
      var variogram = kriging.train(t, x, y, model, sigma2, alpha);


     //---------------------------------------------





//以下網格
/*
      north: 25.34,
      south: 21.871,
      east: 122.03,
      west: 120.03322005271912
      */
      btnst.onclick =function(){
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

      //  closest(array_newlat[i],array_newlon[i]);
      var rectangle = new google.maps.Rectangle(rectangleOptions);
      var Color =  240-Math.round(360*(kriging.predict(array_newlat[i],array_newlon[i], variogram))/10);
      rectangle.setOptions({  fillColor: "hsl(" + Color + ", 100%, 50%)" });
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


     var infoWindow0 = new google.maps.InfoWindow({
      content: "<div>"+"中心點:"+P_center+"</Br>二氧化硫(SO2):"+kriging.predict(array_newlat[i],array_newlon[i], variogram)+"</div>",
      maxWidth: 500
    });
     myArray0.push(infoWindow0);
     fn0(i);

   }

function fn0(a){

 google.maps.event.addListener(rectangle, 'click', function(ev) {
  for(var hz=0;hz<myArray0.length;hz++)
  {
    myArray0[hz].close();
  }

  myArray0[a].setPosition(ev.latLng);
  myArray0[a].open(map);
});
}



}
//以下圓圈
var myArray = [];
var wellCircle;
for (var s=0; s <nnlocationName.length; s++) {
 var Color = 240 - Math.round((360 * array_temp[s])/10);
 wellCircle = new google.maps.Circle({ 
  strokeColor: "hsl(" + Color + ", 100%, 50%)", 
  fillColor: "hsl(" + Color + ", 100%, 50%)",
  strokeOpacity: 0.8,
  strokeWeight: 2,
  fillOpacity: 0.35,
  map: map,
  center: new google.maps.LatLng(array_lat[s],array_lon[s]),
  radius: 3000,
  zIndex:99999
});

 var infoWindow = new google.maps.InfoWindow({
  content: "<div>"+nnlocationName[s].innerHTML+"</br>二氧化硫(SO2):"+nntemp[s].innerHTML+"</div>",
  maxWidth: 500
});
 myArray.push(infoWindow);
 fn1(s);
};
function fn1(a){
 google.maps.event.addListener(wellCircle, 'click', function(ev) {
  for(var h=0;h<myArray.length;h++)
  {
    myArray[h].close();
  }

  myArray[a].setPosition(ev.latLng);
  myArray[a].open(map);
});

}


</script>

</div>
<footer class="w3-container w3-section w3-padding-32 w3-card-4 w3-light-grey w3-center w3-opacity">
  <p><nav>
   An Intelligent Agriculture Platform for Estimating Agrometeorological and Mining Plant Diseases and Pests Features: Design and Implementation
 </nav></p>
</footer>

<!-- END MAIN -->
</div>

<script>
// Script to open and close the sidenav
function w3_open() {
  document.getElementById("mySidenav").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}

function w3_close() {
  document.getElementById("mySidenav").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}
function w3_show_nav(name) {
  document.getElementById("menuTut").style.display = "none";
  document.getElementById("menuRef").style.display = "none";
  document.getElementById(name).style.display = "block";
  w3-open();
}
</script>
<script src="http://www.w3schools.com/lib/w3codecolors.js"></script>

</body>
</html>

