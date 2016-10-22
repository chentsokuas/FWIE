  <?php
  $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-C9371CA5-B4C3-4C97-8F34-2B4AC9357E61") or die("Error: Cannot create object");

  foreach($xml->children() as $books) { 
    if($books->locationName !="")
    {
     echo "-----------------------------</br>" ;
     echo "地點:".$books->locationName  ."</br>";
     echo "經度:".$books->lon ."</br>" ;
     echo "緯度:".$books->lat ."</br>";
     echo "時間:".$books->time->obsTime ."</br>" ;
     echo "溫度:".$books->weatherElement[3]->elementValue->value."</br>" ;

   }
 } 
 ?>