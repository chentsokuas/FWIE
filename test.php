  <?php
  $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0004-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");



  foreach($xml as $books) {

   for($y=0;$y<12;$y++)
   {
    for($i=0;$i<=strlen($books->weatherElement[$y]);$i++)
    {
      if($books->weatherElement[$y]->location[$i]->locationName!="")
      {
       echo "地點:".$books->weatherElement[$y]->elementName."</br>";
       echo "地點:".$books->weatherElement[$y]->location[$i]->locationName ."</br>";
       echo "平均值:".$books->weatherElement[$y]->location[$i]->parameter[0]->parameterValue ."</br>";
       echo "最高值:".$books->weatherElement[$y]->location[$i]->parameter[1]->parameterValue ."</br>";
       echo "最低值:".$books->weatherElement[$y]->location[$i]->parameter[2]->parameterValue ."</br>";
     }
   }
 }
}
?>