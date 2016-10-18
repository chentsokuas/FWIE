<%@ Page Language="C#" AutoEventWireup="true" CodeFile="FWIE.aspx.cs" Inherits="FWIE" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head runat="server">
    <title>以台灣氣象站為基礎之農地氣象資訊推估系統</title>
    <meta name="viewport" content="initial-scale=1.0" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css" />
    <style>
        .w3-theme {
            color: #fff !important;
            background-color: #4CAF50 !important;
        }

        .w3-btn {
            background-color: #4CAF50;
            margin-bottom: 4px;
        }

        .w3-code {
            border-left: 4px solid #4CAF50;
        }

        .myMenu {
            margin-bottom: 150px;
        }

        html {
            height: 100%;
        }

        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>

    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQ2OAc23JPD1J470b2zfddyy-PrDIrZag">
    </script>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <script language="javascript" type="text/javascript">

        var map;
        var infowindow = new google.maps.InfoWindow();
        var markersToRemove = [];
        var TempRectangle = [];
         
        function initialize() {
            var mapProp = {
                center: new google.maps.LatLng(23.7, 120.6082103),
                zoom: 8,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

        }
        google.maps.event.addDomListener(window, 'load', initialize);

        //////////////////////////////
        function DataRectanglesom(Data, dis ,weatername) {
            //var startTime = new Date().getTime();
            var rectangleOptions = {
                strokeOpacity: 0.1,

                fillColor: "hsl(126, 100%, 50%)"
            };

            if (TempRectangle.length != 0) {
                while (TempRectangle[0]) { TempRectangle.pop().setMap(null); }
            }

            for (var i = 0; i < Data.length; i++) {
                var sites = Data[i];

                var P1 = new google.maps.LatLng(sites[0] + dis, sites[1] - dis);
                var P2 = new google.maps.LatLng(sites[0] - dis, sites[1] + dis);
                var latLngBounds = new google.maps.LatLngBounds(P1, P2);

                var rectangle = new google.maps.Rectangle(rectangleOptions);
                //alert(sites[2]);
                var Color;
                if (sites[2] <"7" )
                {
                    rectangle.setOptions({ fillColor: "hsl(0, 0%, 100%)" });
                }
               else if (sites[2] >= "7" && sites[2] <= "9") {
                   
                    rectangle.setOptions({ fillColor: "hsl(0, 100%, 50%)" });
                }
                else if (sites[2] >= "10" && sites[2] <= "12") {
                    rectangle.setOptions({ fillColor: "hsl(240, 100%, 50%)" });
                }
                else if (sites[2] >= "13" && sites[2] <= "15") {
                    rectangle.setOptions({ fillColor: "hsl(300, 100%, 50%)" });
                }
               else if (sites[2] >= "16" && sites[2] <= "18") {
                    rectangle.setOptions({ fillColor: "hsl(120, 100%, 50%)" });
                }
                else // if (sites[2] >= "19" && sites[2] <= "21") 
                {
                    rectangle.setOptions({ fillColor: "hsl(60, 100%, 50%)" });
                }
   /* else{
                 Color = 360 - Math.round((360 * (sites[2] / 40)));}*/
      
                rectangle.setMap(map);
                rectangle.setBounds(latLngBounds);
                TempRectangle.push(rectangle);

                /*TempRectangle[i] = new google.maps.Rectangle(rectangleOptions);
                TempRectangle[i].setOptions({ fillColor: "hsl(" + sites[2] + ", 100%, 50%)" });
                TempRectangle[i].setMap(map);
                TempRectangle[i].setBounds(latLngBounds);
                //TempRectangle.push(rectangle);*/

                google.maps.event.addListener(rectangle, 'mouseover', function () { HighLight(this) });
                google.maps.event.addListener(rectangle, 'mouseout', function () { HighLight() });
                google.maps.event.addListener(rectangle, 'click', function () { ShowData(this, weatername) });
                infoWindow = new google.maps.InfoWindow();
            }
        }
        //////////////////////////////
               
        function DataRectangle(Data, dis,WeatherType) {
            //var startTime = new Date().getTime();
            var rectangleOptions = {
                strokeOpacity: 0.1,

                fillColor: "hsl(126, 100%, 50%)"
            };

            if (TempRectangle.length != 0) {
                while (TempRectangle[0]) { TempRectangle.pop().setMap(null); }
            }

            for (var i = 0; i < Data.length; i++) {
                var sites = Data[i];

                var P1 = new google.maps.LatLng(sites[0] + dis, sites[1] - dis);
                var P2 = new google.maps.LatLng(sites[0] - dis, sites[1] + dis);
                var latLngBounds = new google.maps.LatLngBounds(P1, P2);

                var rectangle = new google.maps.Rectangle(rectangleOptions);
                var Color = 360 - Math.round((360 * (sites[2] / 40)));
                rectangle.setOptions({ fillColor: "hsl(" + Color + ", 100%, 50%)" });
                rectangle.setMap(map);
                rectangle.setBounds(latLngBounds);
                TempRectangle.push(rectangle);

                /*TempRectangle[i] = new google.maps.Rectangle(rectangleOptions);
                TempRectangle[i].setOptions({ fillColor: "hsl(" + sites[2] + ", 100%, 50%)" });
                TempRectangle[i].setMap(map);
                TempRectangle[i].setBounds(latLngBounds);
                //TempRectangle.push(rectangle);*/

                google.maps.event.addListener(rectangle, 'mouseover', function () { HighLight(this) });
                google.maps.event.addListener(rectangle, 'mouseout', function () { HighLight() });
                google.maps.event.addListener(rectangle, 'click', function () { ShowData(this,WeatherType) });
                infoWindow = new google.maps.InfoWindow();
            }
        }
        function ShowData(Rect,WeatherName) {

            var bounds = Rect.getBounds();

            var Center = bounds.getCenter();
            var Center_lat = bounds.getCenter().lat().toFixed(5);
            var Center_lng = bounds.getCenter().lng().toFixed(5);

            for (var i = 0; i < sites.length; i++) 
            {
                var Data = sites[i]
                var Data_lat = Data[0].toFixed(5);
                var Data_lng = Data[1].toFixed(5);

                if (Center_lng == Data_lng && Center_lat == Data_lat) {

                    var contentString = '<b>點選區域相關資訊</b><br>' +
                    '中心點位置： ' + Center_lat + ', ' + Center_lng + '<br>' +
                    WeatherName + Data[2];

                    infoWindow.setContent(contentString);
                    infoWindow.setPosition(Center);

                    infoWindow.open(map);
                    
                 }
            }
        }

        function HighLight(Rec) {
            //alert(Rec.getBounds());
            for (var i = 0; i < TempRectangle.length; i++) {
                TempRectangle[i].setOptions({ strokeOpacity: 0.1 });
            }

            if (Rec) {
                Rec.setOptions({ strokeOpacity: 0.5 });
            }

        }

        
    </script>
</head>
<body>
    <!-- Top -->
    <div class="w3-top">
        <div class="w3-row w3-white w3-padding">
            <div class="w3-half" style="margin: 4px 0 6px 0">
                <img src='./img/logoban.png'></div>
            <div class="w3-half w3-margin-top w3-wide w3-hide-medium w3-hide-small">
                <div class="w3-right">以台灣氣象站為基礎之農地氣象資訊推估系統</div>
            </div>
        </div>

        <ul class="w3-navbar w3-theme w3-large" style="z-index: 4;">
            <li class="w3-opennav w3-left w3-hide-large">
                <a class="w3-hover-white w3-large w3-theme w3-padding-16" href="javascript:void(0)" onclick="w3_open()">☰</a>
            </li>
            <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16" href="FWIE.aspx">經緯度查詢</a></li>
            <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16" href="Prediction.aspx">氣象預測</a></li>
        </ul>
    </div>

    <!-- Sidenav -->
    <nav class="w3-sidenav w3-collapse w3-light-green w3-animate-left" style="z-index: 3; width: 270px; margin-top: 1%;" id="mySidenav">
    <div class="w3-hide-large">
      <a href="FWIE.aspx"  class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large" style="width:50%">經緯度查詢</a>
      <a href="Prediction.aspx" class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large" style="width:50%">氣象預測</a>
    </div>
    <div class="w3-clear"></div>
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-right w3-xlarge w3-padding-large w3-hide-large" title="close menu">×</a>
    <div id="menuTut" class="myMenu">
      <div class="w3-container w3-padding-top">
          <h3>經緯度查詢</h3>
          <p>緯度:<input name="lat" id="lat" class="w3-input" value="23.7"></input></p>
          <p>經度:<input name="lng" id="lng" class="w3-input" value="120.9082103"></input></p>
          <input class="w3-btn w3-red w3-right" id="btn1" type="button" value="查詢" onclick="Button1_onclick()"></input>
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
            <form id="form1" runat="server" style="margin-top:10px;" >
                <asp:ScriptManager ID="ScriptManager1" runat="server">
                </asp:ScriptManager>
                <asp:UpdatePanel ID="UpdatePanel1" runat="server">
                    <ContentTemplate>
                        <br /> <br />
                        氣象資料查詢<br /> 
                        網格寬度：<asp:TextBox ID="grid_weight"  class="w3-input" runat="server" Text = "10"></asp:TextBox>(單位：公里) 
                        網格數量：<asp:Label ID="grid_Num" runat="server"></asp:Label><br /> 
                        <p>一般</p>
                        <asp:Button ID="ButTemp" runat="server" class="w3-btn w3-red" onclick="ButTemp_Click" Text="溫度" />
                        <asp:Button ID="Button2" runat="server" class="w3-btn w3-red" onclick="ButTemp_Click" Text="露點" />
                        <asp:Button ID="Button3" runat="server" class="w3-btn w3-red" onclick="ButTemp_Click" Text="濕度" />    
                        <br />
                        <p>Som</p>
                        <asp:Button ID="ButSOM" runat="server" class="w3-btn w3-red" onclick="ButSOM_Click" Text="溫度" />
                        <asp:Button ID="Button4" runat="server" class="w3-btn w3-red" onclick="ButSOM_Click" Text="露點" />
                        <asp:Button ID="Button5" runat="server" class="w3-btn w3-red" onclick="ButSOM_Click" Text="濕度" />
                        <br />
                        <br />
                    </ContentTemplate>
                </asp:UpdatePanel>
            </form> 
</div>

</nav>

    <!-- Overlay effect when opening sidenav on small screens -->
    <div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor: pointer" title="close side menu" id="myOverlay"></div>

    <!-- Main content: shift it to the right by 270 pixels when the sidenav is visible -->
    <div class="w3-main w3-container" style="margin-left: 270px; margin-top: 117px;">

        <div class="w3-container w3-section w3-padding-large w3-card-4 w3-light-grey">
            <div id="map" style="width: 100%; height: 450px"></div>
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
