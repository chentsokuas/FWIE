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
      <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16" href="javascript:void(0)" onclick="w3_show_nav('menuTut')">經緯度查詢</a></li>
      <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16" href="javascript:void(0)" onclick="w3_show_nav('menuRef')">氣象預測</a></li>
    </ul>
  </div>

  <!-- Sidenav -->
  <nav class="w3-sidenav w3-collapse w3-light-green w3-animate-left" style="z-index:3;width:270px;margin-top:1%; " id="mySidenav">
    <div class="w3-hide-large">
      <a href="javascript:void(0)" onclick="w3_show_nav('menuTut')" class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large" style="width:50%">經緯度查詢</a>
      <a href="javascript:void(0)" onclick="w3_show_nav('menuRef')" class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large" style="width:50%">氣象資料查詢</a>
    </div>
    <div class="w3-clear"></div>
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-right w3-xlarge w3-padding-large w3-hide-large" title="close menu">×</a>
    <div id="menuTut" class="myMenu">
      <div class="w3-container w3-padding-top">
        <form action="home.php" method="get">
          <h3>經緯度查詢</h3>
          <p>緯度:<input name="lat" id="lat" class="w3-input" value="23.7"></input></p>
          <p>經度:<input name="lng" id="lng" class="w3-input" value="120.9082103"></input></p>
          <input class="w3-btn w3-blue w3-right" type="submit" value="查詢氣象資料">
          <input   class="w3-btn w3-red w3-right" id="btn1" type="button" value="打點" onclick="Button1_onclick()"></input>
        </form>

        <script language="javascript" type="text/javascript">
          var geocoder = new google.maps.Geocoder();
          function Button1_onclick() {
            var lat = document.getElementById('lat').value;
            var lng = document.getElementById('lng').value;
            if (lat > -90 && lat < 90) {
              var location = new google.maps.LatLng(lat, lng);
                        // 加上marker和點擊時的訊息視窗
                        var marker = new google.maps.Marker({
                          position: location,
                          map: map
                        });
                        markersToRemove.push(marker);
                        google.maps.event.addListener(marker, 'click', function () {
                          if (infowindow) { infowindow.close(); }
                          infowindow.setContent(InfoContent(marker));
                          infowindow.open(map, marker);
                        });

                        map.setCenter(location);

                      } else { alert("請輸入正確之經緯度"); }
                    }



                function InfoContent(markerObj) {//設定資訊視窗內容要呈現什麼 
                  html = "<div>緯度：<font color='blue'>" + markerObj.getPosition().lat() + "</font></div>";
                  html += "<div>經度：<font color='blue'>" + markerObj.getPosition().lng() + "</font></div>";

                  var url = "information.aspx?lat=" + markerObj.getPosition().lat() + "&lng=" + markerObj.getPosition().lng();

                  html += "<div><a href='" + url + "'target='data'>查詢詳細氣象資料</a></div>";
                  return html;
                }

                function HideRectangle() {
                  for (var i = 0; i < TempRectangle.length; i++) {
                    TempRectangle[i].setMap(null);
                  }
                } 
              </script>
<?php

        $lat=$_GET[lat];
        $lng=$_GET[lng];

              $xml = simplexml_load_file(
                "http://api.wunderground.com/auto/wui/geo/WXCurrentObXML/index.xml?query=".$lat.",".$lng
                );
    if($lat!=null)
    {
   echo "<br><br>";      
    echo "溫度：",$xml->temp_c,"°C<br>";
    echo "相對濕度：",$xml->relative_humidity,"<br>";
    echo "風向：",$xml->wind_dir,"<br>";
    echo "風速：",$xml->wind_mph,"MPH<br>";
    echo "風速：每小時",round($xml->wind_mph*1.6093),"公里<br>";
    echo "風速：每秒",round($xml->wind_mph*0.447028),"公尺<br>";
    echo "海平面氣壓：",$xml->pressure_mb,"百帕<br>";
    echo "高溫指數：",$xml->heat_index_c,"°C<br>";
    echo "風寒指數：",$xml->windchill_c,"°C<br>";
    echo "水凝點：",$xml->dewpoint_c,"°C<br>";
    echo "能見度：",$xml->visibility_km,"公里<br>";}
?>
</div>

</div>
<div id="menuRef" class="myMenu w3-padding-top" style="display:none">
  <div class="w3-container">
    <h3>氣象預測</h3>
<a href="http://opendata.cwb.gov.tw/opendata/MFC/F-C0032-001.xml">政府開放資料</a>
  </div>
  
</div>
</nav>

<!-- Overlay effect when opening sidenav on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- Main content: shift it to the right by 270 pixels when the sidenav is visible -->
<div class="w3-main w3-container" style="margin-left:270px;margin-top:117px;">

  <div class="w3-container w3-section w3-padding-large w3-card-4 w3-light-grey">
   <div id="map" style="width:100%;height:450px"></div>
   <script>
    var map;
    var infowindow = new google.maps.InfoWindow();
    var markersToRemove = [];
    var TempRectangle = [];

    function initMap() {
      map = new google.maps.Map(document.getElementById("map"),{
        center: new google.maps.LatLng(23.7, 120.9082103),
        zoom: 7,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });

    }  
  </script>

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQ2OAc23JPD1J470b2zfddyy-PrDIrZag&callback=initMap"
async defer></script>

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

