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
          $en_title = array("home", "temperature", "rainfall", "humidity", "pressure", "complex","sql_complex","complex_pass","som","taiwan_edge","test_krg","comfirm_krg1","tccip","unnormal");
          $chi_title = array("全台氣象測站", "溫度", "雨量", "濕度", "氣壓", "即時推估氣象指數","資料庫處理","歷史推估氣象指數","som分群","台灣邊緣測試","克利金推估測試","克利金推估驗證(麟洛)","TCCIP歷史氣象資訊(1960~2012)","異常環境與影像");
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
                //$xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
                $xml=simplexml_load_file("./asset/opendata/O-A0001-001.xml") or die("目前opendata資料出現問題");


         foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->locationName !="綠島" && $books->locationName !="琉球嶼" && $books->locationName !="花嶼" && $books->locationName !="西嶼" && $books->locationName !="東莒" && $books->locationName !="金沙"  && $books->locationName !="金寧" && $books->locationName !="烏坵"  && $books->locationName !="東引" && $books->locationName !="龜山島"  && $books->weatherElement[3]->elementValue->value > -20 )
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
                //$xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
                $xml=simplexml_load_file("./asset/opendata/O-A0001-001.xml") or die("目前opendata資料出現問題");
       foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->locationName !="綠島" && $books->locationName !="琉球嶼" && $books->locationName !="花嶼" && $books->locationName !="西嶼" && $books->locationName !="東莒" && $books->locationName !="金沙"  && $books->locationName !="金寧" && $books->locationName !="烏坵"  && $books->locationName !="東引" && $books->locationName !="龜山島" && $books->weatherElement[7]->elementValue->value >= 0 )
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
           //$xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
                $xml=simplexml_load_file("./asset/opendata/O-A0001-001.xml") or die("目前opendata資料出現問題");
           foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->locationName !="綠島" && $books->locationName !="琉球嶼" && $books->locationName !="花嶼" && $books->locationName !="西嶼" && $books->locationName !="東莒" && $books->locationName !="金沙"  && $books->locationName !="金寧" && $books->locationName !="烏坵"  && $books->locationName !="東引" && $books->locationName !="龜山島" && $books->weatherElement[4]->elementValue->value >= 0 )
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
                //$xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
                $xml=simplexml_load_file("./asset/opendata/O-A0001-001.xml") or die("目前opendata資料出現問題"); 
          foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->locationName !="綠島" && $books->locationName !="琉球嶼" && $books->locationName !="花嶼" && $books->locationName !="西嶼" && $books->locationName !="東莒" && $books->locationName !="金沙"  && $books->locationName !="金寧" && $books->locationName !="烏坵" && $books->locationName !="東引" && $books->locationName !="龜山島" && $books->weatherElement[5]->elementValue->value > -20 )
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



//綜合撈取氣象站資料
function Complex(){                                      
           $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
                //$xml=simplexml_load_file("./asset/opendata/O-A0001-001.xml") or die("目前opendata資料出現問題");
         foreach($xml->children() as $books) { 
          if($books->locationName !="" && $books->locationName !="綠島" && $books->locationName !="琉球嶼" && $books->locationName !="花嶼" && $books->locationName !="西嶼" && $books->locationName !="東莒" && $books->locationName !="金沙"  && $books->locationName !="金寧" && $books->locationName !="烏坵" && $books->locationName !="東引" && $books->locationName !="龜山島" && $books->weatherElement[3]->elementValue->value > -20 )
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
          if($books->locationName !="" && $books->locationName !="綠島" && $books->locationName !="琉球嶼" && $books->locationName !="花嶼" && $books->locationName !="西嶼" && $books->locationName !="東莒" && $books->locationName !="金沙"  && $books->locationName !="金寧" && $books->locationName !="烏坵" && $books->locationName !="東引" && $books->locationName !="龜山島" && $books->weatherElement[7]->elementValue->value >= 0 )
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
          if($books->locationName !="" && $books->locationName !="綠島" && $books->locationName !="琉球嶼" && $books->locationName !="花嶼" && $books->locationName !="西嶼" && $books->locationName !="東莒" && $books->locationName !="金沙"  && $books->locationName !="金寧" && $books->locationName !="烏坵" && $books->locationName !="東引" && $books->locationName !="龜山島" && $books->weatherElement[4]->elementValue->value >= 0 )
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
          if($books->locationName !="" && $books->locationName !="綠島" && $books->locationName !="琉球嶼" && $books->locationName !="花嶼" && $books->locationName !="西嶼" && $books->locationName !="東莒" && $books->locationName !="金沙"  && $books->locationName !="金寧" && $books->locationName !="烏坵" && $books->locationName !="東引" && $books->locationName !="龜山島" && $books->weatherElement[5]->elementValue->value > -20 )
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

//異常狀況
    function Unnormal(){    
   $this->load->database();  
     $query = $this->db->get_where('sensor', array('id'=>1));
     foreach ($query->result_array() as $row)
             {      
                $arr['temp_s'] = urlencode($row['temp']);
                $arr['humi_s'] = urlencode($row['humi']);
                $arr['time_t'] = urlencode($row['time_t']);
                echo urldecode(json_encode($arr))."@";
   
             }
               echo "^";
               //ORDER BY id DESC
               $query1 = $this->db->query("SELECT * FROM krg where grid=627 order by  id DESC LIMIT 1;");






     foreach ($query1->result_array() as $row)
             {      
                $arr['grid'] = urlencode($row['grid']);
                $arr['temp'] = urlencode($row['temp']);
                $arr['rain'] = urlencode($row['rain']);
                $arr['humi'] = urlencode($row['humi']);
                $arr['pres'] = urlencode($row['pres']);
                echo urldecode(json_encode($arr))."@";
   
             } 


  }


//SOM分群
  function Som(){    

    $lot_s = $_REQUEST['lot_s'];
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

           $query1 = $this->db->get_where('krg', array('timed' => $time),$lot_s);
           echo "^";
     foreach ($query1->result_array() as $row)
             {      
                $arr['grid'] = urlencode($row['grid']);
                $arr['temp'] = urlencode($row['temp']);
                $arr['rain'] = urlencode($row['rain']);
                $arr['humi'] = urlencode($row['humi']);
                $arr['pres'] = urlencode($row['pres']);
                echo urldecode(json_encode($arr))."@";
   
             } 
             
             $query2 = $this->db->get('taiwan_grid');
               echo "^";
                 foreach ($query2->result_array() as $row)
                  {
                    $arr['grid'] = urlencode($row['grid']);
                    echo urldecode(json_encode($arr))."@";
                   }

  }

   function Taiwan_edge(){   
    $this->load->database(); 
    $query = $this->db->get('taiwan_grid');
                 foreach ($query->result_array() as $row)
                  {
                    $arr['grid'] = urlencode($row['grid']);
                    echo urldecode(json_encode($arr))."@";
                   }
   } 



   //歷史氣象資訊
  function Tccip(){    

    $date_s = $_REQUEST['date_s'];
    $time_s = $_REQUEST['time_s'];
   

   $this->load->database();  
     $query = $this->db->get_where('tccip', array('year'=>$date_s,'month' => $time_s));
     foreach ($query->result_array() as $row)
             {      
                $arr['longitude'] = urlencode($row['longitude']);
                $arr['latitude'] = urlencode($row['latitude']);
                $arr['temperature'] = urlencode($row['temperature']);
                $arr['rainfall'] = urlencode($row['rainfall']);
                echo urldecode(json_encode($arr))."@";
   
             }

  }



   //測試function
   function Test_krg(){                                      
                $xml=simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=O-A0001-001&authorizationkey=CWB-D577C943-B81B-4378-A6F9-538D294948BA") or die("目前opendata資料出現問題");
                //$xml=simplexml_load_file("./asset/opendata/O-A0001-001.xml") or die("目前opendata資料出現問題");


         foreach($xml->children() as $books) { 
          if($books->locationName !="" && ($books->locationName =="屏東"||$books->locationName =="長治"||$books->locationName =="三地門"||$books->locationName =="赤山"||$books->locationName =="竹田"||$books->locationName =="萬丹"||$books->locationName =="大寮"||$books->locationName =="九如"||$books->locationName =="新圍"||$books->locationName =="麟洛") && $books->weatherElement[3]->elementValue->value > -20 )
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



   

 



}
