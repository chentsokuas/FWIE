<h1><?php echo $ch_title?></h1>
<input id="cht" type="input" name="cht" value="<?php echo $ch_title?>" style="display: none;">
<img class="box" id="id01" src="./asset/img/loada.gif">
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
      
        //資料初始化結束

        //圓圈開始
 
                var myArray = [];
                var wellCircle;
                for (var i = 0; i < num - 1; i++) {
                    var Color = 360;
                    wellCircle = new google.maps.Circle({
                        strokeColor: "hsl(" + Color + ", 100%, 50%)",
                        fillColor: "hsl(" + Color + ", 100%, 50%)",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        map: map,
                        center: new google.maps.LatLng(obj[i].lat, obj[i].lon),
                        radius: 10000,
                        zIndex: 99999
                    });

                    var infoWindow = new google.maps.InfoWindow({
                        content: "<div>" + obj[i].locationName + "</Br>地址:"+ obj[i].value,
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
            
            //圓圈結束

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
