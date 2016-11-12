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

<script type="text/javascript">

window.onload = function() {

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
  //var cities=["",,,""];




// Convert Degress to Radians
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

var lat = 120; // user's latitude
var lon = 23; // user's longitude

//var cities = [""][][][""];
 var cities = [
 
];
  for (var i=0;i<nnlocationName.length;i++)
  {
   array_locationName.push(nnlocationName[i].innerHTML);
   array_lat.push(parseFloat(nnlat[i].innerHTML));
   array_lon.push(parseFloat(nnlon[i].innerHTML));
   array_temp.push(parseFloat(nntemp[i].innerHTML));
   cities.push([array_locationName[i],array_lat[i],array_lon[i],nntemp[i].innerHTML]);
 }




 closest(lat,lon);

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
    alert(cities[closest]);
}
}
</script>