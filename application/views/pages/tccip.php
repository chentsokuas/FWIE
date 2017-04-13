<h1><?php echo $ch_title?></h1>
<input id="cht" type="input" name="cht" value="<?php echo $ch_title?>" style="display: none;">
<div class="w3-col m12">
    <div class="w3-col m12 s12">
        <input class="w3-input" type="date" id="mydate" value="">
    </div>
    <div class="w3-col m10 s10">
        <input class="w3-input" type="range" id="mytime" min="0" max="23" value="0">
    </div>
    <div class="w3-col m2 s2">
        <input class="w3-input" type="text" id="mytext" value="0" style="display: none;">
        <input class="w3-input" type="text" id="mytext1" value="0">
    </div>
</div>
<div class="w3-col m6 s12 w3-center">
<div class="w3-animate-zoom" id="map" style="height:700px"></div>
</div>
<div class="w3-col m6 s12 w3-lightgray w3-border w3-border-green"  style="height:700px;padding-left: 5px">
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
<p id="value6"></p>
<p id="value7"></p>
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


$('#mytime').on('change', function() {

 $('#mytext1').val("時間:　"+$('#mytime').val()+":00:00");
    $('#mytext').val($('#mytime').val());
    var date_ = $('#mydate').val();
    var time_ = $('#mytext').val();


    $.ajax({
        url: "index.php/Pages/" + "<?php echo $title?>",
        type: 'POST',
        dataType: 'text',
        data: {
            date_s: date_,
            time_s: time_
        },
        success: function(data) {
            var myLatlng = new google.maps.LatLng(23.7, 120.9082103);
var myOptions = {
    zoom: 8,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.SATELLITE
};
map = new google.maps.Map(document.getElementById("map"), myOptions);
            var array_newcenter = [];
            var array_newlat = [];
            var array_newlon = [];
            var datas = data.split("@");
            var num = datas.length;
            var obj = new Array();
            for (var i = 0; i < num - 1; i++) {
                obj[i] = JSON.parse(datas[i]);
            }
            var myArray0 = [];
            var SN = (25.34 - 21.871) * 55 / 3;
            var ES = (122.03 - 120.03322005271912) * 55 / 3;
            var dis = 3 / 110;
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
                var Color = 360 - Math.round((360 * obj[i].temp / 30));
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
                    content: "<div>" + (i + 1) + "</br>中心點:" + P_center + "</Br>溫度:" + obj[i].temp + "</Br>雨量:" + +obj[i].rain + "</Br>濕度:" + obj[i].humi + "</Br>氣壓:" + obj[i].pres + "</div>",
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
                     $("#value1").text((a+1));
                     $("#value2").text(array_newlat[a]);
                     $("#value3").text(array_newlon[a]);
                     $("#value4").text("溫度:"+obj[a].temp);
                     $("#value5").text("雨量:"+obj[a].rain);
                     $("#value6").text("濕度:"+obj[a].humi);
                     $("#value7").text("氣壓:"+obj[a].pres);

                    myArray0[a].setPosition(ev.latLng);
                    myArray0[a].open(map);
                });
            }
        },
         error: function() {
        alert("目前氣象站資料有誤!");
    }

    })
});
$(window).ajaxStop(function() {
    $("#id01").hide();
});
</script>
