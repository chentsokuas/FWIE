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
var myLatlng = new google.maps.LatLng(22.830909090909085, 120.57867459817362);
var myOptions = {
    zoom: 10,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.roadmap
};
map = new google.maps.Map(document.getElementById("map"), myOptions);
map1 = new google.maps.Map(document.getElementById("map1"), myOptions);

//map
var lat=[22.7810,22.7413,22.9728,22.9006,22.8349,22.7117,22.8904,22.7423,22.6948,22.9077,22.9998,22.8282];
var lng=[120.4865,120.5227,120.5318,120.5111,120.6758,120.6319,120.4755,120.4825,120.5346,120.6800,120.6255,120.5927];
var value=[22.8,22.7,22.0,22.6,18.4,22.7,21.4,23.1,23.7,21.4,23.2,22.4];
var location_name = ['里港', '新圍', '月眉', '美濃', '尾寮山', '三地門', '旗山', '九如', '長治', '萬山', '六龜', '高樹'];
                var myArray = [];
                var wellCircle;
                for (var i = 0; i < lat.length; i++) {
                    if(i==11){var Color = 360;}
                    else{var Color = 240;}
                    wellCircle = new google.maps.Circle({
                        strokeColor: "hsl(" + Color + ", 100%, 50%)",
                        fillColor: "hsl(" + Color + ", 100%, 50%)",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        map: map,
                        center: new google.maps.LatLng(lat[i], lng[i]),
                        radius: 3000,
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
var lat1=[22.7810,22.7413,22.9728,22.9006,22.8349,22.7117,22.8904,22.7423,22.6948,22.9077,22.9998];
var lng1=[120.4865,120.5227,120.5318,120.5111,120.6758,120.6319,120.4755,120.4825,120.5346,120.6800,120.6255];
var value1=[22.8,22.7,22.0,22.6,18.4,22.7,21.4,23.1,23.7,21.4,23.2];
var location_name1 = ['里港', '新圍', '月眉', '美濃', '尾寮山', '三地門', '旗山', '九如', '長治', '萬山', '六龜'];
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
                        radius: 3000,
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

        for (var i = 0; i < 8; i++) {

            array_lat.push(parseFloat(lat[i]));
            array_lon.push(parseFloat(lng[i]));
            array_value.push(parseFloat(value[i]));
      
        }
     
        //---------------------------------------------
        var t = array_value;
        var x = array_lat;
        var y = array_lon;
        var model = "exponential";
        var sigma2 = 0.7,
            alpha = 100;
        var variogram = kriging.train(t, x, y, model, sigma2, alpha);
        //---------------------------------------------




        btnst1.onclick = function() {
            var myArray0 = [];
            var SN = (22.99454545454545 -22.66727272727272) * 55 / 3;
            var ES = (120.68776550726443 -120.4695836890827) * 55 / 3;
            var dis = 3 / 110;
            var Map_Lat = 22.99454545454545;
            var Map_lng = 120.4695836890827;
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
                var Color = 360 - Math.round((360 * kriging.predict(array_newlat[i], array_newlon[i], variogram) / 30));
                rectangle.setOptions({
                    fillColor: "hsl(" + Color + ", 100%, 50%)"
                });
                rectangle.setMap(map1);
                rectangle.setBounds(latLngBounds);
                if (count != Math.ceil(SN) + 1) {
                    Map_Lat = Map_Lat - (dis * 2);
                } else {
                    count = 1;
                    Map_Lat = 22.99454545454545;
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
