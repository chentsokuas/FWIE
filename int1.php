<?php
 //選擇檔案位置
    $file ="F-C0032-001.xml";
    //建立XML操作物件
    $data = new XMLReader();  
    //使用open方法打開 $file 檔案
    $data -> open($file);  
    //建立儲存資料的陣列
    $books = array();
    $j=0;
 //讀取xml資料
      while($data->read()){ 
        //如果xml資料深度是2 節點類型是元素類型就繼續讀取
        if($data -> depth ==2 && $data->nodeType ==1){ 
          //檢查節點名稱
          switch($data->name){
           //節點名稱是 bookid 則讀取資料並取出節點內的值
           case "bookid":
             //每一次讀取資料都需要呼叫read函數
             $data->read();
             $books[$j]['bookid'] = $data -> value;
             break;
           case "booksname":
             $data->read();
             $books[$j]['booksname'] = $data -> value;
             //當這個元素的最後一個節點被讀取之後，代表這一個元素內的同一筆資料被讀取完了，接著要讀取下一個，所以計數的$j必須在這裡+1
             $j++;
             break;
         }
       }  
    } 
   print_r($books);
?>