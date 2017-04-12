<h1><?php echo $ch_title?></h1>
<div class="w3-center">
    <div class="w3-col m10 s6">
        <input name="long" id="long" class="w3-input" value="3" style="display: none"></input>
    </div>
    <div class="w3-col m2 s6">
    </div>
</div>
<div class="w3-col m6 s12 w3-center w3-panel w3-border">
<div class="w3-animate-zoom" id="map" style="height:450px"></div>
</div>
<div class="w3-col m6 s12 w3-center w3-panel w3-border">
<div class="w3-animate-zoom" id="map1" style="height:450px"></div>
</div>
<div class="w3-col m12 s12 w3-center w3-panel w3-border">
  <input id="btnst1" class="w3-btn w3-green w3-large w3-center" type="button" value="克利金推估圖"></input>
  </div>

</div>
<script type="text/javascript">
//地圖初始化
var myLatlng = new google.maps.LatLng(22.6656, 120.5191);
var myOptions = {
    zoom: 11,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.roadmap
};
map = new google.maps.Map(document.getElementById("map"), myOptions);
map1 = new google.maps.Map(document.getElementById("map1"), myOptions);

//map
var lat=[22.6619,22.7413,22.5941,22.7117,22.6074,22.6526,22.7423,22.6948,22.5896,22.5866];
var lng=[120.4836,120.5227,120.6063,120.6319,120.3877,120.5191,120.4825,120.5346,120.4824,120.5359];
var value=[23.0,22.8,23.7,22.7,23.1,23.5,22.8,23.7,23.1,24.2];
var location_name = ['屏東', '新圍', '赤山', '三地門', '大寮', '麟洛', '九如', '長治', '萬丹', '竹田'];
                var myArray = [];
                var wellCircle;
                for (var i = 0; i < lat.length; i++) {
                    if(i==5){var Color = 360;}
                    else{var Color = 240;}
                    wellCircle = new google.maps.Circle({
                        strokeColor: "hsl(" + Color + ", 100%, 50%)",
                        fillColor: "hsl(" + Color + ", 100%, 50%)",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        map: map,
                        center: new google.maps.LatLng(lat[i], lng[i]),
                        radius: 2000,
                        zIndex: 99999
                    });
                    var infoWindow = new google.maps.InfoWindow({
                        content: "<div>" + location_name[i] + "</Br>"+"溫度" + value[i] + "</div>",
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
//map1
var lat1=[22.6619,22.7413,22.5941,22.7117,22.6074,22.7423,22.6948,22.5896,22.5866];
var lng1=[120.4836,120.5227,120.6063,120.6319,120.3877,120.4825,120.5346,120.4824,120.5359];
var value1=[23.0,22.8,23.7,22.7,23.1,22.8,23.7,23.1,24.2];
var location_name1 = ['屏東', '新圍', '赤山', '三地門', '大寮', '九如', '長治', '萬丹', '竹田'];
                var myArray1 = [];
                var wellCircle1;
                for (var i = 0; i < lat1.length; i++) {

                    var Color = 240;
                    wellCircle1 = new google.maps.Circle({
                        strokeColor: "hsl(" + Color + ", 100%, 50%)",
                        fillColor: "hsl(" + Color + ", 100%, 50%)",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        map: map1,
                        center: new google.maps.LatLng(lat1[i], lng1[i]),
                        radius: 2000,
                        zIndex: 99999
                    });
                    var infoWindow1 = new google.maps.InfoWindow({
                        content: "<div>" + location_name1[i] + "</Br>"+"溫度" + value1[i] + "</div>",
                        maxWidth: 500
                    });
                    myArray1.push(infoWindow1);
                    fn11(i);
                };

                function fn11(a) {
                    google.maps.event.addListener(wellCircle1, 'click', function(ev) {
                        for (var h = 0; h < myArray1.length; h++) {
                            myArray1[h].close();
                        }
                       

                        myArray1[a].setPosition(ev.latLng);
                        myArray1[a].open(map1);
                    });
                }
//map2

    //網格開始

        var array_value = [];
        var array_lat = [];
        var array_lon = [];
        var array_newcenter = [];
        var array_newlat = [];
        var array_newlon = [];

        for (var i = 0; i < 9; i++) {

            array_lat.push(parseFloat(lat1[i]));
            array_lon.push(parseFloat(lng1[i]));
            array_value.push(parseFloat(value1[i]));
      
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
            var SN = (22.77636363636363 -22.55818181818181) * 55 / 3;
            var ES = (120.63322005271903 -120.45) * 55 / 3;
            var dis = 3 / 110;
            var Map_Lat = 22.77636363636363;
            var Map_lng = 120.41503823453718;
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
                if(i==12)
                {
                     var Color = 360;
                }
                else
                {
                var Color = 360 - Math.round((360 * kriging.predict(array_newlat[i], array_newlon[i], variogram) / 30));
                }
                rectangle.setOptions({
                    fillColor: "hsl(" + Color + ", 100%, 50%)"
                });
                rectangle.setMap(map1);
                rectangle.setBounds(latLngBounds);
                if (count != Math.ceil(SN) + 1) {
                    Map_Lat = Map_Lat - (dis * 2);
                } else {
                    count = 1;
                    Map_Lat = 22.77636363636363;
                    Map_lng = Map_lng + (dis * 2);
                }


                var infoWindow0 = new google.maps.InfoWindow({
                    content: "<div>" + (i + 1) + "</br>中心點:" + P_center + "</Br>溫度:" + kriging.predict(array_newlat[i], array_newlon[i], variogram) + "</div>",
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
                    myArray0[a].open(map1);
                });
            }















  


















        }
        
    

        //網格結束

            

</script>
