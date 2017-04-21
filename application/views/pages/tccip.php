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
                    content: "<div>" + obj[i].temperature+ "</div>",
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


        },
        error: function() {
            alert("目前氣象站資料有誤!");
        }

    })
});

</script>
