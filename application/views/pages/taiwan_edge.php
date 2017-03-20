<h1><?php echo $ch_title?></h1>
<input id="cht" type="input" name="cht" value="<?php echo $ch_title?>" style="display: none;">
<div class="w3-center">
    <div class="w3-col m10 s6">
        <input name="long" id="long" class="w3-input" value="3" style="display: none"></input>
    </div>
    <div class="w3-col m2 s6">
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
    url: "index.php/Pages/" + "<?php echo $title?>",
    type: "POST",
    dataType: "text",
    data: {
        Message: $("#Message").val(),

    },

    success: function(Message) {


        var datas = Message.split("@");
        var num = datas.length;
        var obj = new Array();
        var twgrid = [];
        for (var i = 0; i < num - 1; i++) {
            obj[i] = JSON.parse(datas[i]);

        }
        for (var i = 0; i < num - 1; i++) {

            twgrid.push(parseInt(obj[i].grid));

        }



        //網格開始
        var array_lat = [];
        var array_lon = [];
        var array_newcenter = [];
        var array_newlat = [];
        var array_newlon = [];
        var array_rectangle = [];
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


           
            if (twgrid.includes(i + 1) == true) {
                 var rectangle = new google.maps.Rectangle(rectangleOptions);
                var Color = 360;
                var Light = "40%";
                  rectangle.setOptions({
                fillColor: "hsl(" + Color + ", 100%, " + Light + ")"
            });
            array_rectangle.push(rectangle);
            rectangle.setMap(map);
            rectangle.setBounds(latLngBounds);

            } else {
              
                
            }




          
            if (count != Math.ceil(SN) + 1) {
                Map_Lat = Map_Lat - (dis * 2);
            } else {
                count = 1;
                Map_Lat = 25.34;
                Map_lng = Map_lng + (dis * 2);
            }



        }


    },
    error: function() {
        alert("目前氣象站資料有誤!");
    }

})



//網格結束
</script>
