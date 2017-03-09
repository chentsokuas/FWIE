<h1><?php echo $ch_title?></h1>
<input id="cht" type="input" name="cht" value="<?php echo $ch_title?>" style="display: none;">
<div id="time">最後更新:</div>
<div class="w3-col m12">
    網格大小:
</div>
<div class="w3-center">
    <div class="w3-col m12 s12">
        <input name="long0" id="long0" class="w3-input" value="資料來源:中央氣象局 Opendata:O-A0001-001" disabled></input>
        <input name="long" id="long" class="w3-input" value="3"></input>
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

$.ajax({
    url: "index.php/Pages/Complex",
    type: "POST",
    dataType: "text",
    data: {
        Message: $("#Message").val(),

    },

    success: function(Message) {
        //資料初始化開始
        var datas = Message.split("^");
        var temp = datas[0].split("@");
        var rain = datas[1].split("@");
        var humi = datas[2].split("@");
        var pres = datas[3].split("@");
        var num = temp.length;
        var num1 = rain.length;
        var num2 = humi.length;
        var num3 = pres.length;
        var obj = new Array();
        var obj1 = new Array();
        var obj2 = new Array();
        var obj3 = new Array();

        for (var i = 0; i < num - 1; i++) {
            obj[i] = JSON.parse(temp[i]);
        }
        for (var i = 0; i < num1 - 1; i++) {
            obj1[i] = JSON.parse(rain[i]);
        }
        for (var i = 0; i < num2 - 1; i++) {
            obj2[i] = JSON.parse(humi[i]);
        }
        for (var i = 0; i < num3 - 1; i++) {
            obj3[i] = JSON.parse(pres[i]);
        }
        var timed = [];
        timed.push(obj[0].time.split("T")[0]);
        timed.push((obj[0].time.split("T")[1]).split("+08:00")[0]);
        $("#time").html("最後更新:</br>" + timed[0] + " " + timed[1]);
        //資料初始化結束


        //網格開始

        var array_temp = [];
        var array_rain = [];
        var array_humi = [];
        var array_pres = [];

        var array_lat = [];
        var array_lon = [];
        var array_lat1 = [];
        var array_lon1 = [];
        var array_lat2 = [];
        var array_lon2 = [];
        var array_lat3 = [];
        var array_lon3 = [];
        var array_newcenter = [];
        var array_newlat = [];
        var array_newlon = [];
        var array_krsvalue = [];
        var array_krsvalue1 = [];
        var array_krsvalue2 = [];
        var array_krsvalue3 = [];

        for (var i = 0; i < num - 1; i++) {

            array_lat.push(parseFloat(obj[i].lat));
            array_lon.push(parseFloat(obj[i].lon));
            array_temp.push(parseFloat(obj[i].value));
        }

        for (var i = 0; i < num1 - 1; i++) {

            array_lat1.push(parseFloat(obj1[i].lat));
            array_lon1.push(parseFloat(obj1[i].lon));
            array_rain.push(parseFloat(obj1[i].value));
        }

        for (var i = 0; i < num2 - 1; i++) {

            array_lat2.push(parseFloat(obj2[i].lat));
            array_lon2.push(parseFloat(obj2[i].lon));
            array_humi.push(parseFloat(obj2[i].value));
        }

        for (var i = 0; i < num3 - 1; i++) {

            array_lat3.push(parseFloat(obj3[i].lat));
            array_lon3.push(parseFloat(obj3[i].lon));
            array_pres.push(parseFloat(obj3[i].value));
        }


        //---------------------------------------------
        var t = array_temp;
        var x = array_lat;
        var y = array_lon;
        var model = "exponential";
        var sigma2 = 0,
            alpha = 100;
        var variogram = kriging.train(t, x, y, model, sigma2, alpha);
        //---------------------------------------------
        //---------------------------------------------
        var t1 = array_rain;
        var x1 = array_lat1;
        var y1 = array_lon1;
        var model1 = "exponential";
        var sigma21 = 0,
            alpha1 = 100;
        var variogram1 = kriging.train(t1, x1, y1, model1, sigma21, alpha1);
        //---------------------------------------------
        //---------------------------------------------
        var t2 = array_humi;
        var x2 = array_lat2;
        var y2 = array_lon2;
        var model2 = "exponential";
        var sigma22 = 0,
            alpha2 = 100;
        var variogram2 = kriging.train(t2, x2, y2, model2, sigma22, alpha2);
        //---------------------------------------------
        //---------------------------------------------
        var t3 = array_pres;
        var x3 = array_lat3;
        var y3 = array_lon3;
        var model3 = "exponential";
        var sigma23 = 0,
            alpha3 = 100;
        var variogram3 = kriging.train(t3, x3, y3, model3, sigma23, alpha3);
        //---------------------------------------------

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
            if (kriging.predict(array_newlat[i], array_newlon[i], variogram) < 0) {
                array_krsvalue.push(0);
            } else {
                array_krsvalue.push(kriging.predict(array_newlat[i], array_newlon[i], variogram));
            }
            if (kriging.predict(array_newlat[i], array_newlon[i], variogram1) < 0) {
                array_krsvalue1.push(0);
            } else {
                array_krsvalue1.push(kriging.predict(array_newlat[i], array_newlon[i], variogram1));
            }
            if (kriging.predict(array_newlat[i], array_newlon[i], variogram2) < 0) {
                array_krsvalue2.push(0);
            } else {
                array_krsvalue2.push(kriging.predict(array_newlat[i], array_newlon[i], variogram2));
            }
              if (kriging.predict(array_newlat[i], array_newlon[i], variogram2) > 1) {
                array_krsvalue2.push(1);
            } else {
                array_krsvalue2.push(kriging.predict(array_newlat[i], array_newlon[i], variogram2));
            }
            if (kriging.predict(array_newlat[i], array_newlon[i], variogram3) < 0) {
                array_krsvalue3.push(0);
            } else {
                array_krsvalue3.push(kriging.predict(array_newlat[i], array_newlon[i], variogram3));
            }
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
                Map_Lat = 25.34;
                Map_lng = Map_lng + (dis * 2);
            }


            var infoWindow0 = new google.maps.InfoWindow({
                content: "<div>" + (i + 1) + "</br>中心點:" + P_center + "</Br>溫度:" +array_krsvalue[i]+"</Br>雨量:"+ +array_krsvalue1[i]+"</Br>濕度:"+array_krsvalue2[i]+"</Br>氣壓:"+array_krsvalue3[i]  + "</div>",
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



    },
    error: function() {
        alert("目前氣象站資料有誤!");
    }

})
</script>
