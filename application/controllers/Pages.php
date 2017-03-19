<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

//跳頁
  public function view($page = 'home')                 
        {
           if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
          {
                show_404();
          }


          $data['title'] = ucfirst($page); 
          $en_title = array("home", "temperature", "rainfall", "humidity", "pressure", "complex", "air_psi", "air_co", "air_no2", "air_o3", "air_pm10", "air_pm25", "air_so2","sql_complex","complex_pass","som");
          $chi_title = array("全台氣象測站", "溫度", "雨量", "濕度", "氣壓", "即時氣象指數", "污染指標(PSI)", "一氧化碳(CO)", "二氧化氮(NO2)", "臭氧(O3)", "懸浮微粒(PM10)", "細懸浮微粒(PM2.5)", "二氧化硫(SO2)","資料庫處理","歷史氣象指數","som分群");
        for($i=0;$i<sizeof($en_title);$i++)
        {
            if($page == $en_title[$i])
            {
            $data['ch_title'] =$chi_title[$i];
            }  
        }

          $this->load->view('templates/header', $data);
          $this->load->view('pages/'.$page, $data);
          $this->load->view('templates/footer', $data);
        }


//首頁function
   function Home(){                                      
             $this->load->database();    
                 $query = $this->db->get('weather_station');
                 foreach ($query->result_array() as $row)
                    {
                     $arr['locationName'] = urlencode($row['StationName']);
                     $arr['lat'] = urlencode($row['GPS_Latitude']);
                     $arr['lon'] = urlencode($row['GPS_Longitude']);
                     $arr['value'] = urlencode($row['Address']);
                     echo urldecode(json_encode($arr))."@";
                   }

        
   }
//溫度function
   function Temperature(){                                      
                $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
                //$xml=simplexml_load_file("./asset/opendata/O-A0001-001.xml") or die("目前opendata資料出現問題");
         foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->weatherElement[3]->elementValue->value > -20)
              { 

                $arr['locationName'] = urlencode($books->locationName);
                $arr['lat'] = urlencode($books->lat);
                $arr['lon'] = urlencode($books->lon);
                $arr['time'] = urlencode($books->time->obsTime);
                $arr['value'] = urlencode($books->weatherElement[3]->elementValue->value);
                echo urldecode(json_encode($arr))."@";

              }
        }
   }
//雨量function
  function Rainfall(){                                      
                $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
               // $xml=simplexml_load_file("./asset/opendata/O-A0001-001.xml") or die("目前opendata資料出現問題");
         foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->weatherElement[7]->elementValue->value > 0)
              { 

                $arr['locationName'] = urlencode($books->locationName);
                $arr['lat'] = urlencode($books->lat);
                $arr['lon'] = urlencode($books->lon);
                $arr['time'] = urlencode($books->time->obsTime);
                $arr['value'] = urlencode($books->weatherElement[7]->elementValue->value);
                echo urldecode(json_encode($arr))."@";

              }
        }

  }
//濕度function
  function Humidity(){                                      
           $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
               // $xml=simplexml_load_file("./asset/opendata/O-A0001-001.xml") or die("目前opendata資料出現問題");
         foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->weatherElement[4]->elementValue->value > 0)
              { 

                $arr['locationName'] = urlencode($books->locationName);
                $arr['lat'] = urlencode($books->lat);
                $arr['lon'] = urlencode($books->lon);
                $arr['time'] = urlencode($books->time->obsTime);
                $arr['value'] = urlencode($books->weatherElement[4]->elementValue->value);
                echo urldecode(json_encode($arr))."@";

              }
        }
  }
//氣壓function
  function Pressure(){                                      
                $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
             //   $xml=simplexml_load_file("./asset/opendata/O-A0001-001.xml") or die("目前opendata資料出現問題"); 
         foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->weatherElement[5]->elementValue->value > -20)
              { 

                $arr['locationName'] = urlencode($books->locationName);
                $arr['lat'] = urlencode($books->lat);
                $arr['lon'] = urlencode($books->lon);
                $arr['time'] = urlencode($books->time->obsTime);
                $arr['value'] = urlencode($books->weatherElement[5]->elementValue->value);
                echo urldecode(json_encode($arr))."@";

              }
        }
  }
//空氣品質
//psi_function
  function Air_psi(){   
  $this->load->database();    
               $xml=simplexml_load_file("http://opendata.epa.gov.tw/webapi/api/rest/datastore/355000000I-000001/?format=xml&token=0yAptWQ8gUKNtr8q8mo+9g") or die("目前opendata資料出現問題");
            //$xml=simplexml_load_file("./asset/opendata/AQX.xml") or die("目前opendata資料出現問題");    
            
            foreach($xml->children() as $books) { 
             if($books->SiteName!="" && $books->PSI!="")
            {
            
                $arr['locationName'] = urlencode($books->SiteName);
                    $query = $this->db->get_where('airquality_station', array('st_name' => $books->SiteName));
                 foreach ($query->result_array() as $row)
                    {
                     $arr['lat'] = urlencode($row['GPS_Latitude']);
                     $arr['lon'] = urlencode($row['GPS_Longitude']);
                   }
                
                $arr['time'] = urlencode($books->PublishTime);
                $arr['value'] = urlencode($books->PSI);
                echo urldecode(json_encode($arr))."@";
           } 
        }

   
  }
//co_function
  function Air_co(){                                      
       $this->load->database();    

               $xml=simplexml_load_file("http://opendata.epa.gov.tw/webapi/api/rest/datastore/355000000I-000001/?format=xml&token=0yAptWQ8gUKNtr8q8mo+9g") or die("目前opendata資料出現問題");
            //$xml=simplexml_load_file("./asset/opendata/AQX.xml") or die("目前opendata資料出現問題");    
            
            foreach($xml->children() as $books) { 
             if($books->SiteName!="" && $books->CO!="")
            {
            
                $arr['locationName'] = urlencode($books->SiteName);
                    $query = $this->db->get_where('airquality_station', array('st_name' => $books->SiteName));
                 foreach ($query->result_array() as $row)
                    {
                     $arr['lat'] = urlencode($row['GPS_Latitude']);
                     $arr['lon'] = urlencode($row['GPS_Longitude']);
                   }
                
                $arr['time'] = urlencode($books->PublishTime);
                $arr['value'] = urlencode($books->CO);
                echo urldecode(json_encode($arr))."@";
           } 
        }
  }
//no2_function
  function Air_no2(){                                      
              $this->load->database();    

               $xml=simplexml_load_file("http://opendata.epa.gov.tw/webapi/api/rest/datastore/355000000I-000001/?format=xml&token=0yAptWQ8gUKNtr8q8mo+9g") or die("目前opendata資料出現問題");
            //$xml=simplexml_load_file("./asset/opendata/AQX.xml") or die("目前opendata資料出現問題");    
            
            foreach($xml->children() as $books) { 
             if($books->SiteName!="" && $books->NO2!="")
            {
            
                $arr['locationName'] = urlencode($books->SiteName);
                    $query = $this->db->get_where('airquality_station', array('st_name' => $books->SiteName));
                 foreach ($query->result_array() as $row)
                    {
                     $arr['lat'] = urlencode($row['GPS_Latitude']);
                     $arr['lon'] = urlencode($row['GPS_Longitude']);
                   }
                
                $arr['time'] = urlencode($books->PublishTime);
                $arr['value'] = urlencode($books->NO2);
                echo urldecode(json_encode($arr))."@";
           } 
        }
  }
//o3_function
  function Air_o3(){                                      
       $this->load->database();    

               $xml=simplexml_load_file("http://opendata.epa.gov.tw/webapi/api/rest/datastore/355000000I-000001/?format=xml&token=0yAptWQ8gUKNtr8q8mo+9g") or die("目前opendata資料出現問題");
            //$xml=simplexml_load_file("./asset/opendata/AQX.xml") or die("目前opendata資料出現問題");    
            
            foreach($xml->children() as $books) { 
             if($books->SiteName!="" && $books->O3!="")
            {
            
                $arr['locationName'] = urlencode($books->SiteName);
                    $query = $this->db->get_where('airquality_station', array('st_name' => $books->SiteName));
                 foreach ($query->result_array() as $row)
                    {
                     $arr['lat'] = urlencode($row['GPS_Latitude']);
                     $arr['lon'] = urlencode($row['GPS_Longitude']);
                   }
                
                $arr['time'] = urlencode($books->PublishTime);
                $arr['value'] = urlencode($books->O3);
                echo urldecode(json_encode($arr))."@";
           } 
        }
  }
//pm10_function
  function Air_pm10(){                                      
                $this->load->database();    

               $xml=simplexml_load_file("http://opendata.epa.gov.tw/webapi/api/rest/datastore/355000000I-000001/?format=xml&token=0yAptWQ8gUKNtr8q8mo+9g") or die("目前opendata資料出現問題");
            //$xml=simplexml_load_file("./asset/opendata/AQX.xml") or die("目前opendata資料出現問題");    
            
            foreach($xml->children() as $books) { 
             if($books->SiteName!="" && $books->PM10!="")
            {
            
                $arr['locationName'] = urlencode($books->SiteName);
                    $query = $this->db->get_where('airquality_station', array('st_name' => $books->SiteName));
                 foreach ($query->result_array() as $row)
                    {
                     $arr['lat'] = urlencode($row['GPS_Latitude']);
                     $arr['lon'] = urlencode($row['GPS_Longitude']);
                   }
                
                $arr['time'] = urlencode($books->PublishTime);
                $arr['value'] = urlencode($books->PM10);
                echo urldecode(json_encode($arr))."@";
           } 
        }

   
  }
//pm25_function
  function Air_pm25(){                                      
             $this->load->database();    
             $pm25 ='PM2.5';

               $xml=simplexml_load_file("http://opendata.epa.gov.tw/webapi/api/rest/datastore/355000000I-000001/?format=xml&token=0yAptWQ8gUKNtr8q8mo+9g") or die("目前opendata資料出現問題");
            //$xml=simplexml_load_file("./asset/opendata/AQX.xml") or die("目前opendata資料出現問題");    
            
            foreach($xml->children() as $books) { 
             if($books->SiteName!="" && $books->$pm25!="")
            {
            
                $arr['locationName'] = urlencode($books->SiteName);
                    $query = $this->db->get_where('airquality_station', array('st_name' => $books->SiteName));
                 foreach ($query->result_array() as $row)
                    {
                     $arr['lat'] = urlencode($row['GPS_Latitude']);
                     $arr['lon'] = urlencode($row['GPS_Longitude']);
                   }
                
                $arr['time'] = urlencode($books->PublishTime);
                $arr['value'] = urlencode($books->$pm25);
                echo urldecode(json_encode($arr))."@";
           } 
        }

   
  }
//so2_function
  function Air_so2(){                                      
                $this->load->database();    

               $xml=simplexml_load_file("http://opendata.epa.gov.tw/webapi/api/rest/datastore/355000000I-000001/?format=xml&token=0yAptWQ8gUKNtr8q8mo+9g") or die("目前opendata資料出現問題");
            //$xml=simplexml_load_file("./asset/opendata/AQX.xml") or die("目前opendata資料出現問題");    
            
            foreach($xml->children() as $books) { 
             if($books->SiteName!="" && $books->SO2!="")
            {
            
                $arr['locationName'] = urlencode($books->SiteName);
                    $query = $this->db->get_where('airquality_station', array('st_name' => $books->SiteName));
                 foreach ($query->result_array() as $row)
                    {
                     $arr['lat'] = urlencode($row['GPS_Latitude']);
                     $arr['lon'] = urlencode($row['GPS_Longitude']);
                   }
                
                $arr['time'] = urlencode($books->PublishTime);
                $arr['value'] = urlencode($books->SO2);
                echo urldecode(json_encode($arr))."@";
           } 
        }
   
  }


//綜合撈取氣象站資料
function Complex(){                                      
           $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
                //$xml=simplexml_load_file("./asset/opendata/O-A0001-001.xml") or die("目前opendata資料出現問題");
         foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->weatherElement[3]->elementValue->value > -20)
              { 

                $arr['locationName'] = urlencode($books->locationName);
                $arr['lat'] = urlencode($books->lat);
                $arr['lon'] = urlencode($books->lon);
                $arr['time'] = urlencode($books->time->obsTime);
                $arr['value'] = urlencode($books->weatherElement[3]->elementValue->value);
                echo urldecode(json_encode($arr))."@";

              }
        }
        echo "^";
         foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->weatherElement[7]->elementValue->value > 0)
              { 

                $arr['locationName'] = urlencode($books->locationName);
                $arr['lat'] = urlencode($books->lat);
                $arr['lon'] = urlencode($books->lon);
                $arr['time'] = urlencode($books->time->obsTime);
                $arr['value'] = urlencode($books->weatherElement[7]->elementValue->value);
                echo urldecode(json_encode($arr))."@";

              }
        }
       echo "^";
         foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->weatherElement[4]->elementValue->value > 0)
              { 

                $arr['locationName'] = urlencode($books->locationName);
                $arr['lat'] = urlencode($books->lat);
                $arr['lon'] = urlencode($books->lon);
                $arr['time'] = urlencode($books->time->obsTime);
                $arr['value'] = urlencode($books->weatherElement[4]->elementValue->value);
                echo urldecode(json_encode($arr))."@";

              }
        }
         echo "^";
         foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->weatherElement[5]->elementValue->value > -20)
              { 

                $arr['locationName'] = urlencode($books->locationName);
                $arr['lat'] = urlencode($books->lat);
                $arr['lon'] = urlencode($books->lon);
                $arr['time'] = urlencode($books->time->obsTime);
                $arr['value'] = urlencode($books->weatherElement[5]->elementValue->value);
                echo urldecode(json_encode($arr))."@";

              }
        }
        
   }
//存入資料庫
 function Complex1(){    
$this->load->database();  
    $NewString0 = split (',',  $_POST['timed']);
    $NewString0[0] = str_replace('[', "", $NewString0[0]);
    $NewString0[0] = str_replace('"', "", $NewString0[0]);
    $NewString0[1] = str_replace('"', "", $NewString0[1]);
    $NewString0[1] = str_replace(']', "", $NewString0[1]);

     $NewString = split (',',  $_POST['data']);
     $NewString[0] = str_replace("[", "", $NewString[0]);

      $NewString1 = split (',',  $_POST['data1']);
     $NewString1[0] = str_replace("[", "", $NewString1[0]);
      $NewString2 = split (',',  $_POST['data2']);
     $NewString2[0] = str_replace("[", "", $NewString2[0]);
      $NewString3 = split (',',  $_POST['data3']);
     $NewString3[0] = str_replace("[", "", $NewString3[0]);

     $query = $this->db->get_where('krg', array('date'=>$NewString0[0],'timed' => $NewString0[1]));
      if ($query->num_rows() > 0) {
            
         } 
            else {

                for($i=0;$i<sizeof($NewString);$i++)
              {
                     $datat = array(
                                   'grid' => $i+1,
                                   'temp' => round($NewString[$i],2),
                                   'rain' => round($NewString1[$i],2),
                                   'humi' => round($NewString2[$i],2),
                                   'pres' => round($NewString3[$i],2),
                                   'date' =>$NewString0[0],
                                   'timed' =>$NewString0[1]
                                   );
                     $this->db->insert('krg', $datat);
                }
   
                 }
      



     
  
  }
//歷史氣象資訊
  function Complex_pass(){    

    $date_s = $_REQUEST['date_s'];
    $time_s = $_REQUEST['time_s'];
   
    if($time_s<10){
      $time ='0'.$time_s.':00:00';
    }
      else{
         $time =$time_s.':00:00';
      }
   $this->load->database();  
     $query = $this->db->get_where('krg', array('date'=>$date_s,'timed' => $time));
     foreach ($query->result_array() as $row)
             {      
                $arr['grid'] = urlencode($row['grid']);
                $arr['temp'] = urlencode($row['temp']);
                $arr['rain'] = urlencode($row['rain']);
                $arr['humi'] = urlencode($row['humi']);
                $arr['pres'] = urlencode($row['pres']);
                echo urldecode(json_encode($arr))."@";
   
             }
      
                
               
                
  
  }

 






}
