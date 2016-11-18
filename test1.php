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
<input id="btnst1" class="w3-blue w3-large w3-center" type ="button"  value="test"></input>

<div id="map" style="width:100%;height:450px"></div>
<script type="text/javascript" src="./src/gmaps-heatmap.js"></script>
<script src="./src/kriging.js" type="text/javascript"></script>
<script type="text/javascript">
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


      //---------------------------------------------
    var t = array_temp;
    var x = array_lat;
    var y = array_lon;
    var model = "exponential";
    var sigma2 = 0, alpha = 100;
    var variogram = kriging.train(t, x, y, model, sigma2, alpha);


     //---------------------------------------------





//以下網格
/*
      north: 25.34,
      south: 21.871,
      east: 122.03,
      west: 120.03322005271912
      */
      btnst1.onclick =function(){
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
          

          data: [
          <?php
          $T_length=1000;
           for($i=0;$i<$T_length;$i++)
          {
            echo "{lat:variogram.x[$i], lng:variogram.y[$i], count:variogram.t[$i]},";
      
          }
          echo "{lat:variogram.x[$T_length], lng:variogram.y[$T_length], count:variogram.t[$T_length]}";
          ?>
        
          ]


        };

        heatmap.setData(testData);
      }
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

      //  closest(array_newlat[i],array_newlon[i]);
        var rectangle = new google.maps.Rectangle(rectangleOptions);
        var Color = 360 - Math.round((360 * kriging.predict(array_newlat[i],array_newlon[i], variogram)/30));
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
        content: "<div>"+"中心點:"+P_center+"</Br>溫度:"+kriging.predict(array_newlat[i],array_newlon[i], variogram)+"</div>",
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
//以下圓圈
var myArray = [];
var wellCircle;
for (var s=0; s <nnlocationName.length; s++) {
 var Color = 360 - Math.round((360 * array_temp[s]/30));
 wellCircle = new google.maps.Circle({ 
  strokeColor: "hsl(" + Color + ", 100%, 50%)", 
  fillColor: "hsl(" + Color + ", 100%, 50%)",
  strokeOpacity: 0.8,
  strokeWeight: 2,
  fillOpacity: 0.35,
  map: map,
  center: new google.maps.LatLng(array_lat[s],array_lon[s]),
  radius: 3000,
  zIndex:99999
});

 var infoWindow = new google.maps.InfoWindow({
  content: "<div>"+nnlocationName[s].innerHTML+"</br>溫度:"+nntemp[s].innerHTML+"</div>",
  maxWidth: 500
});
 myArray.push(infoWindow);
 fn1(s);
};
function fn1(a){
 google.maps.event.addListener(wellCircle, 'click', function(ev) {
  for(var h=0;h<myArray.length;h++)
  {
    myArray[h].close();
  }

  myArray[a].setPosition(ev.latLng);
  myArray[a].open(map);
});

}


</script>

</body>
</html>



