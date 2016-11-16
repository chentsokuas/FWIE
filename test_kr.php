<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>

  <script src="heatmap.js"></script>
  <script src="gmaps-heatmap.js"></script>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyDQ2OAc23JPD1J470b2zfddyy-PrDIrZag&callback=initMap"></script>
</head>
<body>
  <?php
  $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
  $i=0;
  foreach($xml->children() as $books) { 
    if($books->locationName !="" && $books->weatherElement[3]->elementValue->value > -20)
      { ?>
    <div>
      <locationName id="<?php echo "locationName".$i;?>" style="display: none;"><?php echo $books->locationName;?></locationName>
      <lat id="<?php echo "lat".$i;?>" style="display: none;"> <?php echo $books->lat;?></lat>
      <lon id="<?php echo "lon".$i;?>" style="display: none;"><?php echo $books->lon;?></lon>
      <elev id="<?php echo "elev".$i;?>" style="display: none;"><?php echo $books->weatherElement[0]->elementValue->value;?></elev>
      <temp id="<?php echo "temp".$i;?>" style="display: none;"><?php echo $books->weatherElement[3]->elementValue->value;?></temp>
    </div>
    <?php
    $i++;
  }
} 
?>
<p>網格大小:<input name="long" id="long" class="w3-input" value="10"></input></p>
<input id="btnst" class="w3-blue w3-large w3-center" type ="button"  value="推估"></input>


<div id="map" style="width:100%;height:450px"></div>
<script type="text/javascript" src="./src/gmaps-heatmap.js"></script>
<script type="text/javascript">



  // Create the map.
  var myLatlng = new google.maps.LatLng(23.7, 120.9082103);
        // map options,
        var myOptions = {
          zoom: 7,
          center: myLatlng
        };
        // standard map
        map = new google.maps.Map(document.getElementById("map"), myOptions);




        var nnlocationName = document.getElementsByTagName("locationName");
        var nnlat = document.getElementsByTagName("lat");
        var nnlon = document.getElementsByTagName("lon");
        var nntemp = document.getElementsByTagName("temp");
        var btnst = document.getElementById('btnst');
        var btnst1 = document.getElementById('btnst1');
        var array_locationName =[];
        var array_temp =[];
        var array_lat =[];
        var array_lon =[];
        var array_newcenter =[];
        var array_newlat =[];
        var array_newlon =[];
        var cities = [];
        var new_cities = [];
        var new_test=[];
        var iii=0;
        var coloralp="50%";

        for (var i=0;i<nnlocationName.length;i++)
        {
         array_locationName.push(nnlocationName[i].innerHTML);
         array_lat.push(parseFloat(nnlat[i].innerHTML));
         array_lon.push(parseFloat(nnlon[i].innerHTML));
         array_temp.push(parseFloat(nntemp[i].innerHTML));
         cities.push([array_locationName[i],array_lat[i],array_lon[i],array_temp[i]]);
         new_test[i]="{lat:"+ array_lat[i]+", lng:"+array_lon[i]+", count:"+array_temp[i]+"}";
       }
       function Deg2Rad(deg) {
        return deg * Math.PI / 180;
      }

      function PythagorasEquirectangular(lat1, lon1, lat2, lon2) {
        lat1 = Deg2Rad(lat1);
        lat2 = Deg2Rad(lat2);
        lon1 = Deg2Rad(lon1);
        lon2 = Deg2Rad(lon2);
  var R = 6371; // km
  var x = (lon2 - lon1) * Math.cos((lat1 + lat2) / 2);
  var y = (lat2 - lat1);
  var d = Math.sqrt(x * x + y * y) * R;
  return d;
}


function closest(latitude, longitude) {
  var mindif = 99999;
  var closest;

  for (index = 0; index < cities.length; ++index) {
    var dif = PythagorasEquirectangular(latitude, longitude, cities[index][1], cities[index][2]);
    if (dif < mindif) {
      closest = index;
      mindif = dif;
    }
  }

  // echo the nearest city
  //alert(cities[closest]);
  new_cities[iii]=cities[closest];
  iii++;
}



//以下網格
/*
      north: 25.34,
      south: 21.871,
      east: 122.03,
      west: 120.03322005271912
      */
      
        //document.write(new_test);
        heatmap = new HeatmapOverlay(map, 
        {
            // radius should be small ONLY if scaleRadius is true (or small radius is intended)
            "radius": 0.1,
            "maxOpacity": 0.3, 
            // scales the radius based on map zoom
            "scaleRadius": true, 
            // if set to false the heatmap uses the global maximum for colorization
            // if activated: uses the data maximum within the current map boundaries 
            //   (there will always be a red spot with useLocalExtremas true)
            "useLocalExtrema": true,
            // which field name in your data represents the latitude - default "lat"
            latField: 'lat',
            // which field name in your data represents the longitude - default "lng"
            lngField: 'lng',
            // which field name in your data represents the data value - default "value"
            valueField: 'count'
          }
          );

        var testData = {
          max: 3000,

          data: [{lat:25.1149, lng:121.7926, count:21.8},{lat:25.0533, lng:121.4385, count:23.7},{lat:24.9958, lng:121.6546, count:20.8},{lat:25.0044, lng:121.7346, count:20.1},{lat:25.0041, lng:121.5675, count:22.9},{lat:24.9242, lng:121.5381, count:23.7},{lat:24.9767, lng:121.3938, count:23.5},{lat:24.8501, lng:121.5896, count:21.3},{lat:25.0494, lng:121.2178, count:22.6},{lat:24.9943, lng:121.315, count:23.2},{lat:24.9305, lng:121.275, count:22.9},{lat:24.9141, lng:121.1348, count:22.7},{lat:24.9066, lng:121.0354, count:22.6},{lat:24.8001, lng:121.1657, count:22},{lat:24.6924, lng:121.0091, count:22.3},{lat:24.6198, lng:120.9406, count:22.9},{lat:24.4189, lng:120.8664, count:21.9},{lat:24.6081, lng:120.7756, count:23.8},{lat:24.5853, lng:120.8772, count:24.2},{lat:24.4736, lng:120.6964, count:22.3},{lat:24.8518, lng:121.1426, count:20.6},{lat:24.7108, lng:120.8808, count:23.6},{lat:24.6032, lng:120.9921, count:22.3},{lat:24.6801, lng:121.2004, count:19.5},{lat:24.4531, lng:120.9219, count:18.7},{lat:24.4128, lng:120.7578, count:20.5},{lat:24.3499, lng:120.6324, count:21.7},{lat:24.2482, lng:120.8249, count:21.8},{lat:24.2778, lng:120.7695, count:22},{lat:24.2903, lng:120.8964, count:19.7},{lat:24.245, lng:121.2377, count:11.6},{lat:24.1448, lng:121.2641, count:4.8},{lat:23.2225, lng:120.3911, count:22.3},{lat:23.1206, lng:120.3528, count:23.6},{lat:23.0816, lng:120.4869, count:22.6},{lat:23.3264, lng:120.5736, count:22},{lat:23.2619, lng:120.6561, count:18},{lat:23.1144, lng:120.2894, count:24.3},{lat:23.1278, lng:120.4525, count:22.6},{lat:22.7444, lng:120.7356, count:18.9},{lat:22.6847, lng:120.6789, count:20.7},{lat:22.8059, lng:120.3474, count:23.7},{lat:22.6749, lng:120.2849, count:25.5},{lat:22.9769, lng:120.4583, count:21.3},{lat:22.7403, lng:120.4386, count:23.1},{lat:22.6486, lng:120.3481, count:25.6},{lat:22.6619, lng:120.4836, count:24.5},{lat:22.7413, lng:120.5227, count:25.3},{lat:22.5469, lng:120.3845, count:25.4},{lat:23.0231, lng:120.3398, count:22.7},{lat:22.9935, lng:120.2854, count:23.9},{lat:22.9613, lng:120.3612, count:22},{lat:23.0783, lng:120.1367, count:24.1},{lat:23.0636, lng:120.2904, count:23.6},{lat:22.9728, lng:120.5318, count:23.5},{lat:23.2242, lng:120.7978, count:20.8},{lat:22.5941, lng:120.6063, count:25.4},{lat:22.895, lng:120.3939, count:22.5},{lat:22.9006, lng:120.5111, count:24.3},{lat:22.8349, lng:120.6758, count:18.2},{lat:22.7117, lng:120.6319, count:25},{lat:23.0817, lng:120.5825, count:21},{lat:22.5361, lng:120.532, count:25.7},{lat:23.9722, lng:120.9442, count:21.5},{lat:23.8863, lng:120.7585, count:21.2},{lat:24.1749, lng:120.7141, count:23.9},{lat:24.0172, lng:120.6131, count:21.7},{lat:23.9754, lng:120.6722, count:23.8},{lat:23.5361, lng:120.8347, count:16},{lat:24.2153, lng:120.6244, count:23},{lat:23.9115, lng:120.6873, count:23.7},{lat:23.9518, lng:120.4711, count:22.8},{lat:24.1547, lng:120.564, count:22.3},{lat:23.7647, lng:120.6778, count:22.7},{lat:23.5973, lng:120.6853, count:18.2},{lat:23.7297, lng:120.7792, count:18},{lat:24.0769, lng:120.4222, count:23.3},{lat:23.9483, lng:120.5775, count:23.5},{lat:23.6914, lng:120.8428, count:19.2},{lat:24.1231, lng:121.2654, count:5.4},{lat:24.035, lng:121.1733, count:16.9},{lat:23.7575, lng:120.3108, count:22.6},{lat:23.2695, lng:120.1174, count:23.9},{lat:23.2269, lng:120.2481, count:23.1},{lat:23.149, lng:120.0782, count:24.1},{lat:23.3716, lng:120.2399, count:22.9},{lat:23.2986, lng:120.3772, count:22.9},{lat:23.4958, lng:120.6911, count:16},{lat:23.7214, lng:120.4325, count:23.1},{lat:23.6311, lng:120.2172, count:23.3},{lat:23.5374, lng:120.161, count:23.5},{lat:23.175, lng:120.1367, count:23.3},{lat:25.2656, lng:121.5573, count:21.1},{lat:25.1308, lng:121.9153, count:23.1},{lat:25.1677, lng:121.6248, count:19.6},{lat:25.1339, lng:121.6003, count:17.7},{lat:25.0192, lng:121.9342, count:22.6},{lat:25.0379, lng:121.8561, count:21.7},{lat:25.2349, lng:121.587, count:21.1},{lat:25.2253, lng:121.6356, count:22.6},{lat:24.6342, lng:121.7853, count:22.5},{lat:24.7456, lng:121.7814, count:23.6},{lat:24.7547, lng:121.6331, count:18.9},{lat:25.0094, lng:121.9939, count:22.6},{lat:24.8193, lng:121.7574, count:22.7},{lat:24.6772, lng:121.5789, count:19},{lat:24.5072, lng:121.5175, count:14.3},{lat:24.3989, lng:121.3486, count:9.4},{lat:23.8971, lng:121.5417, count:23.2},{lat:23.8137, lng:121.4332, count:22.3},{lat:24.1814, lng:121.4875, count:20.1},{lat:24.0397, lng:121.6013, count:23.8},{lat:23.9374, lng:121.5008, count:22.8},{lat:23.6625, lng:121.4168, count:22.4},{lat:22.9736, lng:121.2983, count:25.9},{lat:23.2106, lng:121.2542, count:22.8},{lat:22.6103, lng:120.9858, count:20.6},{lat:22.9186, lng:121.1147, count:22.3},{lat:23.4569, lng:121.4867, count:23.3},{lat:22.6539, lng:121.4753, count:24.9},{lat:23.1214, lng:121.2017, count:22.6},{lat:23.25, lng:120.9781, count:9.9},{lat:23.3469, lng:121.205, count:18},{lat:23.0714, lng:121.12, count:13.4},{lat:22.48, lng:120.9353, count:21.7},{lat:23.4697, lng:121.3658, count:22},{lat:22.5291, lng:120.6171, count:23.2},{lat:22.3722, lng:120.6203, count:23.3},{lat:22.334, lng:120.3542, count:25.9},{lat:21.9478, lng:120.7942, count:25.4},{lat:21.9942, lng:120.8367, count:24.4},{lat:25.1114, lng:121.4614, count:24.3},{lat:25.0797, lng:121.5347, count:23.7},{lat:25.1176, lng:121.5059, count:21.7},{lat:25.1193, lng:121.5289, count:23.2},{lat:25.013, lng:121.4999, count:23.2},{lat:25.092, lng:121.4948, count:24},{lat:25.0812, lng:121.5672, count:22.9},{lat:25.0575, lng:121.5947, count:23.6},{lat:25.0396, lng:121.5564, count:22.9},{lat:25.0585, lng:121.4813, count:23.2},{lat:22.1696, lng:120.8325, count:20.4},{lat:24.7783, lng:121.4946, count:20.7},{lat:24.94, lng:121.7015, count:20.6},{lat:24.8944, lng:121.7375, count:20.8},{lat:24.9731, lng:121.8153, count:19.6},{lat:24.9408, lng:121.3615, count:23.7},{lat:24.1879, lng:121.3078, count:6.6},{lat:23.8258, lng:121.5403, count:20.3},{lat:23.9936, lng:121.5292, count:20.1},{lat:24.1056, lng:120.7433, count:20.9},{lat:24.8414, lng:121.9531, count:19.9},{lat:22.2919, lng:120.8819, count:26.3},{lat:22.54, lng:120.9589, count:22.2},{lat:23.7478, lng:121.4214, count:20.3},{lat:23.8806, lng:121.5819, count:19.8},{lat:23.6842, lng:121.5189, count:18.1},{lat:23.5803, lng:121.5058, count:22},{lat:23.7989, lng:121.5342, count:22.2},{lat:24.1497, lng:121.6219, count:23.1},{lat:24.2681, lng:121.7333, count:23.4},{lat:24.5237, lng:121.8249, count:23.3},{lat:24.4512, lng:121.802, count:24.2},{lat:25.1773, lng:121.5142, count:15.4},{lat:24.2742, lng:120.6503, count:22.4},{lat:24.8004, lng:120.9787, count:23.3},{lat:24.8706, lng:120.9772, count:21.4},{lat:25.0289, lng:121.1451, count:22.1},{lat:24.822, lng:121.3441, count:19.6},{lat:24.7117, lng:121.1172, count:21.8},{lat:24.8718, lng:121.2132, count:21.4},{lat:24.5287, lng:121.1079, count:14.5},{lat:24.7693, lng:121.0499, count:21.9},{lat:25.0861, lng:121.2575, count:22.3},{lat:24.9695, lng:121.1771, count:22.2},{lat:24.7487, lng:120.8973, count:21.7},{lat:24.8994, lng:121.2064, count:21.4},{lat:24.8847, lng:121.2569, count:21.7},{lat:25.0331, lng:121.3664, count:21.8},{lat:24.7367, lng:121.0168, count:21.9},{lat:24.69, lng:120.9042, count:24},{lat:22.4667, lng:120.4333, count:27.2},{lat:25.2596, lng:121.4934, count:22.6},{lat:25.1523, lng:121.3953, count:24.2},{lat:25.0031, lng:121.6095, count:22.6},{lat:25.0885, lng:121.4641, count:24.2},{lat:24.9749, lng:121.4369, count:23},{lat:24.953, lng:121.3373, count:23.1},{lat:22.6291, lng:120.2906, count:25.9},{lat:22.5904, lng:120.2779, count:25.5},{lat:24.6887, lng:121.7902, count:23.3},{lat:24.541, lng:120.912, count:22.5},{lat:24.3148, lng:120.8162, count:22.7},{lat:24.4415, lng:120.6449, count:23.5},{lat:24.6388, lng:120.8566, count:24.5},{lat:24.5667, lng:120.8164, count:24.2},{lat:24.4915, lng:120.7826, count:22.9},{lat:24.5644, lng:120.7403, count:21.9},{lat:24.0359, lng:120.4957, count:24},{lat:24.0178, lng:120.5441, count:23.2},{lat:24.0022, lng:120.4236, count:23.6},{lat:23.9034, lng:120.5014, count:22.9},{lat:23.8678, lng:120.4447, count:23.2},{lat:23.8587, lng:120.5804, count:23.9},{lat:23.8994, lng:120.5781, count:23.8},{lat:23.8128, lng:120.609, count:23.8},{lat:23.924, lng:120.3119, count:24.1},{lat:24.1505, lng:120.4762, count:23.7},{lat:24.0944, lng:120.6922, count:24.2},{lat:24.1088, lng:120.6159, count:23.9},{lat:24.182, lng:120.6329, count:23.6},{lat:24.1389, lng:120.6301, count:24.8},{lat:24.3474, lng:120.58, count:23.5},{lat:24.3121, lng:120.7215, count:21.7},{lat:24.2563, lng:120.7123, count:23.6},{lat:24.2148, lng:120.6958, count:23.7},{lat:24.314, lng:120.5541, count:23.1},{lat:24.3496, lng:120.6975, count:21.4},{lat:24.1863, lng:120.5208, count:23.5},{lat:24.2016, lng:120.8075, count:19.8},{lat:23.5898, lng:121.3704, count:21.3},{lat:22.8849, lng:120.3195, count:24.1},{lat:22.8904, lng:120.4755, count:24.2},{lat:22.8566, lng:120.2514, count:25},{lat:22.7596, lng:120.2977, count:25.7},{lat:22.7319, lng:120.3386, count:25.5},{lat:22.6074, lng:120.3877, count:26.4},{lat:22.6526, lng:120.5191, count:25.8},{lat:22.4877, lng:120.4947, count:26.8},{lat:21.9026, lng:120.8472, count:25.5},{lat:23.1851, lng:120.2405, count:23.5},{lat:23.195, lng:120.3075, count:24.3},{lat:23.1274, lng:120.1951, count:23.3},{lat:23.1044, lng:120.22, count:22.7},{lat:23.0774, lng:120.3552, count:23.4},{lat:22.9701, lng:120.2498, count:24.6},{lat:22.9649, lng:120.3197, count:24.5},{lat:22.963, lng:120.1804, count:25},{lat:23.0122, lng:120.1863, count:24.3},{lat:22.995, lng:120.1441, count:24.8},{lat:22.9084, lng:120.1746, count:24.1},{lat:22.8889, lng:120.2364, count:25.3},{lat:22.8249, lng:120.2288, count:25},{lat:22.7988, lng:120.2869, count:25.7},{lat:22.7856, lng:120.2383, count:25.5},{lat:22.7624, lng:120.2593, count:25.6},{lat:22.7029, lng:120.3397, count:26},{lat:22.7215, lng:120.2779, count:25.1},{lat:22.6251, lng:120.2675, count:25.9},{lat:22.6469, lng:120.3033, count:26.6},{lat:22.6245, lng:120.3226, count:26.3},{lat:22.5096, lng:120.3866, count:27.7},{lat:22.7423, lng:120.4825, count:25.3},{lat:22.8282, lng:120.5927, count:25.6},{lat:22.6948, lng:120.5346, count:25.4},{lat:22.5896, lng:120.4824, count:26.3},{lat:22.5866, lng:120.5359, count:25.2},{lat:22.5452, lng:120.4535, count:26.2},{lat:22.5166, lng:120.499, count:26.3},{lat:22.4698, lng:120.5748, count:24.4},{lat:22.4327, lng:120.5021, count:26.9},{lat:22.4212, lng:120.5442, count:25.9},{lat:22.59, lng:120.6569, count:20.4},{lat:22.9998, lng:120.6255, count:23.5},{lat:23.8975, lng:120.9332, count:19.8},{lat:24.0396, lng:120.8469, count:21.8},{lat:24.0239, lng:121.1241, count:16.9},{lat:23.8143, lng:120.8417, count:21.8},{lat:23.83, lng:120.7933, count:22.1},{lat:23.8388, lng:120.6933, count:23},{lat:23.9051, lng:120.3679, count:22.9},{lat:23.3492, lng:120.4063, count:23.1},{lat:23.4664, lng:120.546, count:21.6},{lat:23.3124, lng:120.3086, count:23.1},{lat:23.2167, lng:120.1277, count:23.5},{lat:23.6927, lng:120.2955, count:23},{lat:23.6059, lng:120.3954, count:22.7},{lat:23.7746, lng:120.4008, count:22.7},{lat:23.5279, lng:120.5474, count:22.4},{lat:23.4271, lng:120.5315, count:22.7},{lat:23.2743, lng:120.2395, count:23},{lat:23.2321, lng:120.1741, count:23.4},{lat:23.3681, lng:120.3542, count:22.8},{lat:23.8022, lng:120.4593, count:22.8},{lat:23.6808, lng:120.3874, count:22.8},{lat:23.6024, lng:120.4503, count:23.1},{lat:23.4607, lng:120.1457, count:23.4},{lat:23.4215, lng:120.3808, count:22.9},{lat:23.4594, lng:120.4524, count:23.9},{lat:23.259, lng:120.3655, count:22.4},{lat:23.3331, lng:120.4995, count:21.6},{lat:23.8533, lng:120.4909, count:22.9},{lat:23.6805, lng:120.4702, count:22.9},{lat:23.7223, lng:120.533, count:22.8},{lat:23.4364, lng:120.2309, count:23.1},{lat:23.5758, lng:120.2837, count:23},{lat:23.4568, lng:120.3233, count:23.1},{lat:23.7504, lng:120.6015, count:21.3},{lat:23.8542, lng:120.3128, count:23.2},{lat:23.6561, lng:120.5516, count:22.6},{lat:23.6767, lng:120.2447, count:23.4},{lat:23.5535, lng:120.4203, count:23.2},{lat:23.6478, lng:120.4234, count:22.6},{lat:23.7629, lng:120.4942, count:22.4},{lat:23.8484, lng:120.3743, count:22.9},{lat:23.7033, lng:120.1894, count:23.6},{lat:23.5747, lng:120.2378, count:23.1},{lat:23.6515, lng:120.3069, count:22.9},{lat:23.3826, lng:120.1597, count:23.4},{lat:23.4131, lng:120.3002, count:23},{lat:23.4946, lng:120.2825, count:23.2},{lat:23.555, lng:120.3376, count:23.3},{lat:23.5871, lng:120.5475, count:22.4},{lat:23.5161, lng:120.2216, count:23.1},{lat:23.3008, lng:120.6567, count:21.6},{lat:23.3949, lng:120.7066, count:16.5},{lat:23.4558, lng:120.7414, count:18.1},{lat:23.3856, lng:120.6601, count:21.4},{lat:23.3878, lng:120.4692, count:22.1},{lat:23.6882, lng:120.5953, count:21.6},{lat:24.0871, lng:121.1654, count:11.6},{lat:23.93, lng:121.0833, count:16.7},{lat:23.0413, lng:121.1669, count:23.4},{lat:25.073, lng:121.7726, count:17.7},{lat:25.9668, lng:119.9723, count:20},{lat:23.5643, lng:119.4603, count:22.7},{lat:23.4016, lng:119.3147, count:23.1},{lat:24.4345, lng:121.2956, count:3.9},{lat:24.3906, lng:121.2633, count:5.1},{lat:24.3904, lng:121.2282, count:0.5},{lat:23.4723, lng:120.9478, count:4.5},{lat:25.1309, lng:121.5689, count:19.8},{lat:24.4899, lng:118.4006, count:21.6},{lat:24.4593, lng:118.3208, count:21.1},{lat:24.9955, lng:119.4466, count:20.1}]


        };

        heatmap.setData(testData);
      
      btnst.onclick =function(){
       var myArray0 = [];
       var SN = (25.34-21.871)*55/document.getElementById('long').value;
       var ES = (122.03-120.03322005271912)*55/document.getElementById('long').value;
       var dis = document.getElementById('long').value/110;
       var Map_Lat=25.34;
       var Map_lng = 120.03322005271912;
       var count =1;
       var rectangleOptions = {
        strokeOpacity: 0.1,
        fillColor: "hsl(126, 100%, 50%)"
      };
      for (var i = 0; i < Math.ceil(SN)*(Math.ceil(ES)+1); i++) {
        count++;

        var P1 = new google.maps.LatLng(Map_Lat + dis, Map_lng - dis);
        var P2 = new google.maps.LatLng(Map_Lat - dis, Map_lng + dis);
        var P_center = new google.maps.LatLng(Map_Lat, Map_lng);
        var latLngBounds = new google.maps.LatLngBounds(P1, P2);
        array_newcenter.push(P_center);
        array_newlat.push(P_center.lat());
        array_newlon.push(P_center.lng());

        closest(array_newlat[i],array_newlon[i]);
        var rectangle = new google.maps.Rectangle(rectangleOptions);
        var Color = 360 - Math.round((360 * new_cities[i][3]/30));
        rectangle.setOptions({ fillColor: "hsl(" + Color + ", 100%, 50%)" });
        rectangle.setMap(map);
        rectangle.setBounds(latLngBounds);
        if(count!=Math.ceil(SN)+1)
        {
          Map_Lat = Map_Lat - (dis*2);
        }
        else
        {
         count =1;
         Map_Lat=25.34;
         Map_lng = Map_lng  + (dis*2);
       }


       var infoWindow0 = new google.maps.InfoWindow({
        content: "<div>"+"中心點:"+P_center+"</Br>溫度:"+new_cities[i][3]+"</div>",
        maxWidth: 500
      });
       myArray0.push(infoWindow0);
       fn0(i);

     }
//alert(array_newcenter[0]);
function fn0(a){

 google.maps.event.addListener(rectangle, 'click', function(ev) {
  for(var hz=0;hz<myArray0.length;hz++)
  {
    myArray0[hz].close();
  }

  myArray0[a].setPosition(ev.latLng);
  myArray0[a].open(map);
});
}


//alert(new_cities[i][3]);
}





</script>

</body>
</html>



