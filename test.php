  <?php
  $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");

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