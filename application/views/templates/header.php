<html>

<head>
    <title>以台灣氣象站為基礎之農地氣象資訊推估系統</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    .w3-theme {
        color: #fff !important;
        background-color: #4CAF50 !important
    }
    
    .w3-btn {
        background-color: #4CAF50;
        margin-bottom: 4px
    }
    
    .w3-code {
        border-left: 4px solid #4CAF50
    }
    
    .myMenu {
        margin-bottom: 150px
    }
    
    .box {
        z-index: 99;
        width: 200px;
        height: 200px;
        position: absolute;
        left: 50%;
        top: 50%;
        margin-top: -100px;
        margin-left: -100px;
    }
    
    html {
        height: 100%
    }
    
    body {
        height: 100%;
        margin: 0;
        padding: 0
    }
    </style>
    <script type="text/javascript" src="./asset/src/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyDQ2OAc23JPD1J470b2zfddyy-PrDIrZag&callback=initMap"></script>
    <script type="text/javascript" src="./asset/src/kriging.js"></script>
</head>

<body>
    <div class="w3-top">
        <div class="w3-row w3-white w3-padding">
            <div class="w3-half" style="margin:4px 0 6px 0"><img src='./asset/img/logoban.png'></div>
            <div class="w3-half w3-margin-top w3-wide w3-hide-medium w3-hide-small">
                <div class="w3-right">以台灣氣象站為基礎之農地氣象資訊推估系統</div>
            </div>
        </div>
        <ul class="w3-navbar w3-theme w3-large" style="z-index:4;">
            <li class="w3-opennav w3-left w3-hide-large">
                <a class="w3-hover-white w3-large w3-theme w3-padding-16" href="javascript:void(0)" onclick="w3_open()">☰</a>
            </li>
            <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="<?php echo base_url();?>home">首頁</a></li>
            <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="<?php echo base_url();?>temperature">溫度</a></li>
            <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="<?php echo base_url();?>rainfall">雨量</a></li>
            <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="<?php echo base_url();?>humidity">濕度</a></li>
            <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="<?php echo base_url();?>pressure">氣壓</a></li>

             <li class="w3-hide-small w3-dropdown-hover">
                <a href="javascript:void(0)" class="w3-hover-white w3-padding-16 w3-center" title="wether">綜合氣象資訊<i class="fa fa-caret-down"></i></a>
                <div class="w3-dropdown-content w3-white w3-card-4">
                    <a href="<?php echo base_url();?>complex">即時氣象資訊</a>
                    <a href="<?php echo base_url();?>complex_pass">歷史氣象資訊</a>
                </div>
            </li>
            <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16 w3-center" href="<?php echo base_url();?>som">SOM分群</a></li>
            <li class="w3-hide-small w3-dropdown-hover w3-right">
                <a href="javascript:void(0)" class="w3-hover-white w3-padding-16 w3-center" title="testF">　　測試功能　　<i class="fa fa-caret-down"></i></a>
                <div class="w3-dropdown-content w3-white w3-card-4">
                    <a href="<?php echo base_url();?>sql_complex">資料庫處理</a>
                      <a href="<?php echo base_url();?>taiwan_edge">台灣邊緣測試</a>
                </div>
            </li>
        </ul>
    </div>
    <!-- Sidenav -->
    <nav class="w3-sidenav w3-collapse w3-light-green w3-animate-left" style="z-index:3;width:270px;margin-top:1%; " id="mySidenav">
        <a href="javascript:void(0)" onclick="w3_close()" class="w3-right w3-xlarge w3-padding-large w3-hide-large" title="close menu">×</a>
        <div class="w3-hide-large">
            <select class="w3-select" onChange="location = this.options[this.selectedIndex].value;">
                <option value="" disabled selected>選擇頁面</option>
                <option value="<?php echo base_url();?>home">首頁</option>
                <option value="<?php echo base_url();?>temperature">溫度</option>
                <option value="<?php echo base_url();?>rainfall">雨量</option>
                <option value="<?php echo base_url();?>humidity">濕度</option>
                <option value="<?php echo base_url();?>pressure">氣壓</option>
                <option value="<?php echo base_url();?>complex">綜合</option>
                 <option value="<?php echo base_url();?>som">som</option>
             
            </select>
        </div>
        <div class="w3-clear"></div>
        <div class="myMenu">
            <div class="w3-container w3-padding-top" style="padding-left: 20px">
                <h3>經緯度定位</h3>
                <input name="lng" id="lng" type="text" class="w3-input w3-border w3-round-large" value="">
                <input name="lat" id="lat" type="text" class="w3-input w3-border w3-round-large" value="">
                <input class="w3-btn-block  w3-blue" id="Button1" type="button" value="查詢" onclick="Button1_onclick()" />
                <script language="javascript" type="text/javascript">
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

                        google.maps.event.addListener(marker, 'click', function() {
                            if (infowindow) {
                                infowindow.close();
                            }
                            infowindow.setContent(InfoContent(marker));
                            infowindow.open(map, marker);
                        });

                        map.setCenter(location);

                    } else {
                        alert("請輸入正確之經緯度");
                    }
                }
                </script>
            </div>
        </div>
    </nav>
    <!-- Overlay effect when opening sidenav on small screens -->
    <div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>
    <!-- Main content: shift it to the right by 270 pixels when the sidenav is visible -->
    <div class="w3-main w3-container" style="margin-left:270px;margin-top:117px;">
        <div class="w3-container w3-section w3-padding-large w3-card-4 w3-light-grey">
