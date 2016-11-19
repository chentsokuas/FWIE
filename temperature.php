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
  <script src="./src/heatmap.js"></script>
  <script src="./src/gmaps-heatmap.js"></script>
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
      <h3>經緯度查詢</h3>
      <p>緯度:<input name="lat" id="lat" class="w3-input" value=""></input></p>
      <p>經度:<input name="lng" id="lng" class="w3-input" value=""></input></p>
      <input class="w3-red w3-large w3-center" type ="button" onclick="javascript:location.href='temperature_10min.php'" value="10分鐘版本"></input>
      <p>網格大小:<input name="long" id="long" class="w3-input" value="5"></input></p>
      <input id="btnst" class="w3-blue w3-large w3-center" type ="button"  value="推估"></input>
      <input id="btnst1" class="w3-blue w3-large w3-center" type ="button"  value="熱圖資訊"></input>

    </form>
  </div>

</div>
</nav>

<!-- Overlay effect when opening sidenav on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- Main content: shift it to the right by 270 pixels when the sidenav is visible -->
<div class="w3-main w3-container" style="margin-left:270px;margin-top:117px;">

  <div class="w3-container w3-section w3-padding-large w3-card-4 w3-light-grey">
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
        var btnst1 = document.getElementById('btnst1');
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
      btnst1.onclick =function(){
        //document.write(new_test);
        heatmap = new HeatmapOverlay(map, 
        {
            // radius should be small ONLY if scaleRadius is true (or small radius is intended)
            "radius": 0.1,
            "maxOpacity": 0.7, 
            // scales the radius based on map zoom
            "scaleRadius": true, 
            // if set to false the heatmap uses the global maximum for colorization
            // if activated: uses the data maximum within the current map boundaries 
            //   (there will always be a red spot with useLocalExtremas true)
            "useLocalExtrema": true,
            // which field name in your data represents the latitude - default "lat"
            latField: 'lat',
            // which field name in your data represents the longitude - default "lng"
            lngField: 'lng',
            // which field name in your data represents the data value - default "value"
            valueField: 'count'
          }
          );

        var testData = {
          max: 3000,
          

          data: [
          <?php
          $T_length=1000;
          for($i=0;$i<$T_length;$i++)
          {
            echo "{lat:variogram.x[$i], lng:variogram.y[$i], count:variogram.t[$i]},";

          }
          echo "{lat:variogram.x[$T_length], lng:variogram.y[$T_length], count:variogram.t[$T_length]}";
          ?>

          ]


        };

        heatmap.setData(testData);
      }
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


       var infoWindow0 = new google.maps.InfoWindow({
        content: "<div>"+"中心點:"+P_center+"</Br>溫度:"+kriging.predict(array_newlat[i],array_newlon[i], variogram)+"</div>",
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


//alert(new_cities[i][3]);
}
//以下圓圈
var myArray = [];
var wellCircle;
for (var s=0; s <nnlocationName.length; s++) {
 var Color = 360 - Math.round((360 * array_temp[s]/30));
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
  content: "<div>"+nnlocationName[s].innerHTML+"</br>溫度:"+nntemp[s].innerHTML+"</div>",
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

