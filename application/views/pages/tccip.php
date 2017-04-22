<h1><?php echo $ch_title?></h1>
<input id="cht" type="input" name="cht" value="<?php echo $ch_title?>" style="display: none;">
<div class="w3-col m12">
    <div class="w3-col m6 s12">
        <p>年：</p>
        <select id="year" name="year" class="w3-select w3-border">
            <?php  for($i=1960;$i<2013;$i++): ?>
            <option value="<?php echo $i?>">
                <?php echo $i?>
            </option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="w3-col m6 s12">
        <p>月：</p>
        <select id="month" name="month" class="w3-select w3-border">
            <?php  for($i=1;$i<13;$i++): ?>
            <option value="<?php echo $i?>">
                <?php echo $i?>
            </option>
            <?php endfor; ?>
        </select>
    </div>
</div>
<div class="w3-col m6 s12 w3-center">
    <div class="w3-animate-zoom" id="map" style="height:700px"></div>
</div>
<div class="w3-col m6 s12 w3-lightgray w3-border w3-border-green" style="height:700px;padding-left: 5px">
    <div class="w3-panel w3-blue w3-center">
        <p>網格</p>
    </div>
    <p id="value1"></p>
    <div class="w3-panel w3-blue w3-center">
        <p>網格緯度</p>
    </div>
    <p id="value2"></p>
    <div class="w3-panel w3-blue w3-center">
        <p>網格經度</p>
    </div>
    <p id="value3"></p>
    <div class="w3-panel w3-blue w3-center">
        <p>網格氣象資訊</p>
    </div>
    <p id="value4"></p>
    <p id="value5"></p>
</div>
<div class="w3-col m12 s12 w3-center w3-panel w3-border">
    <input id="btnst1" class="w3-btn w3-green w3-large w3-center" type="button" value="克利金推估圖"></input>
</div>
<script type="text/javascript">
//地圖初始化
var myLatlng = new google.maps.LatLng(23.7, 120.9082103);
var myOptions = {
    zoom: 8,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.SATELLITE
};
map = new google.maps.Map(document.getElementById("map"), myOptions);


$('#month').on('change', function() {

    //$('#mytext1').val("時間:　"+$('#mytime').val()+":00:00");
    // $('#mytext').val($('#mytime').val());
    var date_ = $('#year').val();
    var time_ = $('#month').val();

    $.ajax({
        url: "index.php/Pages/" + "<?php echo $title?>",
        type: 'POST',
        dataType: 'text',
        data: {
            date_s: date_,
            time_s: time_
        },
        success: function(data) {

            var myLatlng = new google.maps.LatLng(22.6, 120.5);
            var myOptions = {
                zoom: 12,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.SATELLITE
            };
            map = new google.maps.Map(document.getElementById("map"), myOptions);

            var datas = data.split("@");
            var num = datas.length;
            var obj = new Array();

            for (var i = 0; i < num - 1; i++) {
                obj[i] = JSON.parse(datas[i]);

            }

            var myArray = [];
            var wellCircle;


            for (var i = 0; i < num - 1; i++) {

                var Color = 360 - Math.round((360 * obj[i].temperature / 30));
                wellCircle = new google.maps.Circle({
                    strokeColor: "hsl(" + Color + ", 100%, 50%)",
                    fillColor: "hsl(" + Color + ", 100%, 50%)",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillOpacity: 0.35,
                    map: map,
                    center: new google.maps.LatLng(obj[i].latitude, obj[i].longitude),
                    radius: 1000,
                    zIndex: 99999
                });
                var infoWindow = new google.maps.InfoWindow({
                    content: "<div>" + obj[i].longitude + "</br>" + obj[i].latitude + "</br>溫度:" + obj[i].temperature + "</br>雨量:" + obj[i].rainfall + "</div>",
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
                    // $("#value1").text(obj[a].locationName+":"+obj[a].temperature);

                    myArray[a].setPosition(ev.latLng);
                    myArray[a].open(map);
                });
            }








            btnst1.onclick = function() {

                var array_value = [];
                var array_value1 = [];
                var array_lat = [];
                var array_lon = [];
                var array_newcenter = [];
                var array_newlat = [];
                var array_newlon = [];

                for (var i = 0; i < 9; i++) {

                    array_lat.push(obj[i].latitude);
                    array_lon.push(obj[i].longitude);
                    array_value.push(obj[i].temperature);
                    array_value1.push(obj[i].rainfall);



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

                //---------------------------------------------
                var t1 = array_value1;
                var x1 = array_lat;
                var y1 = array_lon;
                var model1 = "exponential";
                var sigma21 = 0,
                    alpha1 = 100;
                var variogram1 = kriging.train(t1, x1, y1, model1, sigma21, alpha1);
                //---------------------------------------------
                var myArray0 = [];
                var SN = (22.65 - 22.55) * 55 / 0.3;
                var ES = (120.546 - 120.45) * 55 / 0.3;
                var dis = 0.3 / 110;
                var Map_Lat = 22.65;
                var Map_lng = 120.45;
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
                    rectangle.setMap(map);
                    rectangle.setBounds(latLngBounds);
                    if (count != Math.ceil(SN) + 1) {
                        Map_Lat = Map_Lat - (dis * 2);
                    } else {
                        count = 1;
                        Map_Lat = 22.65;
                        Map_lng = Map_lng + (dis * 2);
                    }


                    var infoWindow0 = new google.maps.InfoWindow({
                        content: "<div>" + (i + 1) + "</br>中心點:" + P_center + "</Br>溫度:" + kriging.predict(array_newlat[i], array_newlon[i], variogram) + "</Br>雨量:" + kriging.predict(array_newlat[i], array_newlon[i], variogram1) + "</div>",
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
                        $("#value1").text((a + 1));
                        $("#value2").text(array_newlat[a]);
                        $("#value3").text(array_newlon[a]);
                        $("#value4").text(kriging.predict(array_newlat[a], array_newlon[a], variogram));
                        $("#value5").text(kriging.predict(array_newlat[a], array_newlon[a], variogram1));

                        myArray0[a].setPosition(ev.latLng);
                        myArray0[a].open(map);
                    });
                }
            }




        },
        error: function() {
            alert("目前氣象站資料有誤!");
        }

    })
});
</script>
