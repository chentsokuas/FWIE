<h1><?php echo $ch_title?></h1>
<input id="cht" type="input" name="cht" value="<?php echo $ch_title?>" style="display: none;">
<img class="box" id="id01" src="./asset/img/loada.gif">
<div id="time">最後更新:</div>
<div class="w3-col m12">
    網格大小:
</div>
<div class="w3-center">
    <div class="w3-col m10 s6">
        <input name="long0" id="long0" class="w3-input" value="資料來源:中央氣象局 Opendata:O-A0001-001" disabled></input>
        <input name="long" id="long" class="w3-input" value="3"></input>
    </div>
    <div class="w3-col m2 s6">
        <input id="btnst" class="w3-btn w3-blue w3-large w3-right" type="button" value="1.顯示氣象資料"></input>
        <input id="btnst1" class="w3-btn w3-green w3-large w3-right" type="button" value="2.克利金推估圖"></input>
    </div>
</div>
<div class="w3-animate-zoom" id="map" style="width:100%;height:450px"></div>
<script type="text/javascript">
//地圖初始化
var myLatlng = new google.maps.LatLng(23.7, 120.9082103);
var myOptions = {
    zoom: 7,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.SATELLITE
};
map = new google.maps.Map(document.getElementById("map"), myOptions);
$(window).ajaxStart(function() {
    $("#id01").show();
});
$.ajax({
    url: "index.php/Pages/" + "<?php echo $title?>",
    type: "POST",
    dataType: "text",
    data: {
        Message: $("#Message").val(),

    },

    success: function(Message) {
        //資料初始化開始
        var datas = Message.split("@");
        var num = datas.length;
        var obj = new Array();
        for (var i = 0; i < num - 1; i++) {
            obj[i] = JSON.parse(datas[i]);
        }
        $("#time").html("最後更新:</br>" + obj[0].time.split("T")[0] + " " + (obj[0].time.split("T")[1]).split("+08:00")[0]);
        //資料初始化結束

        //圓圈開始
        btnst.onclick = function() {
                var myArray = [];
                var wellCircle;
                for (var i = 0; i < num - 1; i++) {
                    var Color = 180 - Math.round((360 * obj[i].value/30));
                    wellCircle = new google.maps.Circle({
                        strokeColor: "hsl(" + Color + ", 100%, 50%)",
                        fillColor: "hsl(" + Color + ", 100%, 50%)",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        map: map,
                        center: new google.maps.LatLng(obj[i].lat, obj[i].lon),
                        radius: 3000,
                        zIndex: 99999
                    });

                    var infoWindow = new google.maps.InfoWindow({
                        content: "<div>" + obj[i].locationName + "</Br>"+  document.getElementById('cht').value+":" + obj[i].value + "</div>",
                        maxWidth: 500
                    });
                    myArray.push(infoWindow);
                    fn1(i);
                };

                function fn1(a) {
                    google.maps.event.addListener(wellCircle, 'click', function(ev) {
                        for (var h = 0; h < myArray.length; h++) {
                            myArray[h].close();
                        }

                        myArray[a].setPosition(ev.latLng);
                        myArray[a].open(map);
                    });
                }
            }
            //圓圈結束
            //網格開始

        var array_value = [];
        var array_lat = [];
        var array_lon = [];
        var array_newcenter = [];
        var array_newlat = [];
        var array_newlon = [];

        for (var i = 0; i < num - 1; i++) {

            array_lat.push(parseFloat(obj[i].lat));
            array_lon.push(parseFloat(obj[i].lon));
            array_value.push(parseFloat(obj[i].value));
        }


        //---------------------------------------------
        var t = array_value;
        var x = array_lat;
        var y = array_lon;
        var model = "exponential";
        var sigma2 = 0,
            alpha = 100;
        var variogram = kriging.train(t, x, y, model, sigma2, alpha);
        //---------------------------------------------
        btnst1.onclick = function() {
            var myArray0 = [];
            var SN = (25.34 - 21.871) * 55 / document.getElementById('long').value;
            var ES = (122.03 - 120.03322005271912) * 55 / document.getElementById('long').value;
            var dis = document.getElementById('long').value / 110;
            var Map_Lat = 25.34;
            var Map_lng = 120.03322005271912;
            var count = 1;
            var rectangleOptions = {
                strokeOpacity: 0.1,
                fillColor: "hsl(126, 100%, 50%)"
            };
            for (var i = 0; i < Math.ceil(SN) * (Math.ceil(ES) + 1); i++) {
                count++;

                var P1 = new google.maps.LatLng(Map_Lat + dis, Map_lng - dis);
                var P2 = new google.maps.LatLng(Map_Lat - dis, Map_lng + dis);
                var P_center = new google.maps.LatLng(Map_Lat, Map_lng);
                var latLngBounds = new google.maps.LatLngBounds(P1, P2);
                array_newcenter.push(P_center);
                array_newlat.push(P_center.lat());
                array_newlon.push(P_center.lng());


                var rectangle = new google.maps.Rectangle(rectangleOptions);
                var Color =  180-Math.round(360 * (kriging.predict(array_newlat[i],array_newlon[i], variogram)/30));
                rectangle.setOptions({
                    fillColor: "hsl(" + Color + ", 100%, 50%)"
                });
                rectangle.setMap(map);
                rectangle.setBounds(latLngBounds);
                if (count != Math.ceil(SN) + 1) {
                    Map_Lat = Map_Lat - (dis * 2);
                } else {
                    count = 1;
                    Map_Lat = 25.34;
                    Map_lng = Map_lng + (dis * 2);
                }


                var infoWindow0 = new google.maps.InfoWindow({
                    content: "<div>" + (i + 1) + "</br>中心點:" + P_center + "</Br>"+  document.getElementById('cht').value+":" + kriging.predict(array_newlat[i], array_newlon[i], variogram) + "</div>",
                    maxWidth: 500
                });
                myArray0.push(infoWindow0);
                fn0(i);

            }

            function fn0(a) {

                google.maps.event.addListener(rectangle, 'click', function(ev) {
                    for (var hz = 0; hz < myArray0.length; hz++) {
                        myArray0[hz].close();
                    }

                    myArray0[a].setPosition(ev.latLng);
                    myArray0[a].open(map);
                });
            }

        }

        //網格結束
        //高度開始
        var elevator = new google.maps.ElevationService;
        var infowindow = new google.maps.InfoWindow({
            map: map
        });

        // Add a listener for the click event. Display the elevation for the LatLng of
        // the click inside the infowindow.
        map.addListener('click', function(event) {
            displayLocationElevation(event.latLng, elevator, infowindow);
        });

        function displayLocationElevation(location, elevator, infowindow) {
            // Initiate the location request
            elevator.getElevationForLocations({
                'locations': [location]
            }, function(results, status) {
                infowindow.setPosition(location);
                if (status === google.maps.ElevationStatus.OK) {
                    // Retrieve the first result
                    if (results[0]) {
                        // Open the infowindow indicating the elevation at the clicked position.
                        infowindow.setContent('高度為 <br> ' +
                            results[0].elevation + ' 米.');
                    } else {
                        infowindow.setContent('找不到資料');
                    }
                } else {
                    infowindow.setContent('高度偵測失敗 原因: ' + status);
                }
            });
        }

        //高度結束



        // alert("最後更新:" + obj[0].time.split("T")[0] + " " + (obj[0].time.split("T")[1]).split("+08:00")[0]);


    },
    error: function() {
        alert("目前氣象站資料有誤!");
    }

})

$(window).ajaxStop(function() {
    $("#id01").hide();
});
</script>
