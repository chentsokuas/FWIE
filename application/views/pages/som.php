<h1><?php echo $ch_title?></h1>
<input id="cht" type="input" name="cht" value="<?php echo $ch_title?>" style="display: none;">
<div class="w3-col m12">
    <div class="w3-col m12 s12">
        <input class="w3-input" type="date" id="mydate" value="">
    </div>
    <div class="w3-col m10 s10">
        <input class="w3-input" type="range" id="mytime" min="0" max="23" value="0">
    </div>
    <div class="w3-col m2 s2">
        <input class="w3-input" type="text" id="mytext" value="0" style="display: none;">
        <input class="w3-input" type="text" id="mytext1" value="0">
    </div>
</div>
<div class="w3-animate-zoom" id="map" style="width:100%;height:450px"></div>
<div class="w3-col m12 s12" id="div"></div>
<div class="w3-col m5 s5" id="div1"></div>
<div class="w3-col m4 s4" id="div2"></div>
<div class="w3-col m3 s3" id="div3"></div>
<script type="text/javascript">
//地圖初始化
var myLatlng = new google.maps.LatLng(23.7, 120.9082103);
var myOptions = {
    zoom: 7,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.SATELLITE
};
map = new google.maps.Map(document.getElementById("map"), myOptions);


$('#mytime').on('change', function() {

    $('#mytext1').val("時間:　" + $('#mytime').val() + ":00:00");
    $('#mytext').val($('#mytime').val());
    var date_ = $('#mydate').val();
    var time_ = $('#mytext').val();


    $.ajax({
        url: "index.php/Pages/" + "<?php echo $title?>",
        type: 'POST',
        dataType: 'text',
        data: {
            date_s: date_,
            time_s: time_
        },
        success: function(data) {
            var myLatlng = new google.maps.LatLng(23.7, 120.9082103);
            var myOptions = {
                zoom: 7,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.SATELLITE
            };
            map = new google.maps.Map(document.getElementById("map"), myOptions);
            var array_newcenter = [];
            var array_newlat = [];
            var array_newlon = [];
            var index = data.indexOf("^"); // Gets the first index where a space occours
            var datas0 = data.substr(0, index); // Gets the first part
            var datas1 = data.substr(index + 1); // Gets the text part
            var datas = datas0.split("@");
            var datas_pass = datas1.split("@");
            var num = datas.length;
            var obj = new Array();
            var obj_pass = new Array();
            for (var i = 0; i < num - 1; i++) {
                obj[i] = JSON.parse(datas[i]);
                obj_pass[i]= JSON.parse(datas_pass[i]);
            }
            var myArray0 = [];
            var SN = (25.34 - 21.871) * 55 / 3;
            var ES = (122.03 - 120.03322005271912) * 55 / 3;
            var dis = 3 / 110;
            var Map_Lat = 25.34;
            var Map_lng = 120.03322005271912;
            var count = 1;
            var rectangleOptions = {
                strokeOpacity: 0.1,
                fillColor: "hsl(126, 100%, 50%)"
            };









            const maxClusters = 6;
            const vecLen = 4;
            const decayRate = 0.96; //大約100次迭代 
            const minAlpha = 0.01;
            const radiusReductionPoint = 0.023; //Last 20% of iterations.

            var alpha = 0.6;
            var d = new Array(maxClusters); //Network nodes.

            //權重陣列，隨機選擇的值在0.0和1.0之間
            var w = [];
              for(var i=0;i<maxClusters;i++)
            {
                w.push([Math.random(),Math.random(),Math.random(),Math.random()]);
            }
          

           var pattern=[];
           var tests=[];
           var som_clusters=[];
             const inputPatterns = 2432;
             const inputTests = 2432;

            for(var i=0;i<2432;i++)
            {
                pattern.push([obj_pass[i].temp, obj_pass[i].rain, obj_pass[i].humi, obj_pass[i].pres]);
                tests.push([obj[i].temp, obj[i].rain, obj[i].humi, obj[i].pres]);

            }





            function training() {
                var iterations = 0;
                var reductionFlag = false;
                var reductionPoint = 0;
                var dMin = 0;

                do {
                    iterations += 1;

                    for (vecNum = 0; vecNum <= (inputPatterns - 1); vecNum++) {
                        //Compute input for all nodes.
                        computeInput(pattern, vecNum);

                        //See which is smaller?
                        dMin = minimum(d);

                        //Update the weights on the winning unit.
                        updateWeights(vecNum, dMin);

                    } // VecNum

                    //Reduce the learning rate.
                    alpha = decayRate * alpha;

                    //Reduce radius at specified point.
                    if (alpha < radiusReductionPoint) {
                        if (reductionFlag == false) {
                            reductionFlag = true;
                            reductionPoint = iterations;
                        }
                    }

                } while (alpha > minAlpha);


                $("#div").append('<p>迭代次數: ' + iterations + '次</p>');

                $("#div").append('<p>在 ' +
                    reductionPoint + ' 次迭代後 鄰域半徑減小。.</p>');


            }

            function computeInput(vectorArray, vectorNumber) {
                clearArray(d);

                for (i = 0; i <= (maxClusters - 1); i++) {
                    for (j = 0; j <= (vecLen - 1); j++) {
                        d[i] += Math.pow((w[i][j] - vectorArray[vectorNumber][j]), 2);
                    } // j
                } // i
            }

            function updateWeights(vectorNumber, dMin) {

                for (i = 0; i <= (vecLen - 1); i++) {
                    //Update the winner.
                    w[dMin][i] = w[dMin][i] + (alpha * (pattern[vectorNumber][i] -
                        w[dMin][i]));

                    //Only include neighbors before radius reduction point is reached.
                    if (alpha > radiusReductionPoint) {
                        if ((dMin > 0) && (dMin < (maxClusters - 1))) {
                            //Update neighbor to the left...
                            w[dMin - 1][i] = w[dMin - 1][i] +
                                (alpha * (pattern[vectorNumber][i] - w[dMin - 1][i]));
                            //and update neighbor to the right.
                            w[dMin + 1][i] = w[dMin + 1][i] +
                                (alpha * (pattern[vectorNumber][i] - w[dMin + 1][i]));
                        } else {
                            if (dMin == 0) {
                                //Update neighbor to the right.
                                w[dMin + 1][i] = w[dMin + 1][i] +
                                    (alpha * (pattern[vectorNumber][i] - w[dMin + 1][i]));
                            } else {
                                //Update neighbor to the left.
                                w[dMin - 1][i] = w[dMin - 1][i] +
                                    (alpha * (pattern[vectorNumber][i] - w[dMin - 1][i]));
                            }
                        }
                    }
                } // i
            }

            function clearArray(NodeArray) {
                for (i = 0; i <= (maxClusters - 1); i++) {
                    NodeArray[i] = 0.0;
                } // i
            }

            function minimum(nodeArray) {
                var winner = 0;
                var foundNewWinner = false;
                var done = false;

                do {
                    foundNewWinner = false;
                    for (i = 0; i <= (maxClusters - 1); i++) {
                        if (i != winner) { //Avoid self-comparison.
                            if (nodeArray[i] < nodeArray[winner]) {
                                winner = i;
                                foundNewWinner = true;
                            }
                        }
                    } // i

                    if (foundNewWinner == false) {
                        done = true;
                    }

                } while (done != true);

                return winner;
            }

            function printResults() {
                var dMin = 0;
                $("#div1").append('<p>Clusters for training input:</p>');
                //Print clusters created.
                for (vecNum = 0; vecNum <= (inputPatterns - 1); vecNum++) {
                    //Compute input.
                    computeInput(pattern, vecNum);

                    //See which is smaller.
                    dMin = minimum(d);


                    for (i = 0; i <= (vecLen - 1); i++) {
                        $("#div1").append(pattern[vecNum][i] + ', ');
                    } // i
                    $("#div1").append(':第' + dMin + '群</p>');

                } // VecNum

                 for (i = 0; i <= (maxClusters - 1); i++) {
                    $("#div2").append('<p>節點 ' + i + ' 權重:</p>');

                    for (j = 0; j <= (vecLen - 1); j++) {
                        $("#div2").append((Math.round(w[i][j] * 1000) / 1000) + ', ');
                    } // j
                    $("#div2").append('</pre></p>');
                } // i


                  $("#div3").append('<p>Categorized test input:</p>');
                for (vecNum = 0; vecNum <= (inputTests - 1); vecNum++) {
                    //Compute input for all nodes.
                    computeInput(tests, vecNum);

                    //See which is smaller.
                    dMin = minimum(d);

                    for (i = 0; i <= (vecLen - 1); i++) {
                        $("#div3").append(tests[vecNum][i] + ', ');
                    } // i
                    $("#div3").append(':第' + dMin + '群</p>');
                    som_clusters.push(dMin);

                } // VecNum


             
            }



            training();
            printResults();



            for (var i = 0; i < Math.ceil(SN) * (Math.ceil(ES) + 1); i++) {
                count++;

                var P1 = new google.maps.LatLng(Map_Lat + dis, Map_lng - dis);
                var P2 = new google.maps.LatLng(Map_Lat - dis, Map_lng + dis);
                var P_center = new google.maps.LatLng(Map_Lat, Map_lng);
                var latLngBounds = new google.maps.LatLngBounds(P1, P2);
                array_newcenter.push(P_center);
                array_newlat.push(P_center.lat());
                array_newlon.push(P_center.lng());


                var rectangle = new google.maps.Rectangle(rectangleOptions);
                if(som_clusters[i]==0)
                    var Color =0;
              if(som_clusters[i]==1)
                    var Color =51;
                   if(som_clusters[i]==2)
                    var Color =102;
                   if(som_clusters[i]==3)
                    var Color =153;
                   if(som_clusters[i]==4)
                    var Color =204;
                   if(som_clusters[i]==5)
                    var Color =255;
                rectangle.setOptions({
                    fillColor: "hsl(" + Color + ", 100%, 50%)"
                });
                rectangle.setMap(map);
                rectangle.setBounds(latLngBounds);
                if (count != Math.ceil(SN) + 1) {
                    Map_Lat = Map_Lat - (dis * 2);
                } else {
                    count = 1;
                    Map_Lat = 25.34;
                    Map_lng = Map_lng + (dis * 2);
                }



                var infoWindow0 = new google.maps.InfoWindow({
                    content: "<div>" + (i + 1) + "</br>中心點:" + P_center +"</Br>第"+som_clusters[i]+"群"+"</Br>溫度:" + obj[i].temp + "</Br>雨量:" + +obj[i].rain + "</Br>濕度:" + obj[i].humi + "</Br>氣壓:" + obj[i].pres + "</div>",
                    maxWidth: 500
                });
                myArray0.push(infoWindow0);
                fn0(i);

            }

            function fn0(a) {

                google.maps.event.addListener(rectangle, 'click', function(ev) {
                    for (var hz = 0; hz < myArray0.length; hz++) {
                        myArray0[hz].close();
                    }

                    myArray0[a].setPosition(ev.latLng);
                    myArray0[a].open(map);
                });
            }






































        },
        error: function() {
            alert("目前氣象站資料有誤!");
        }

    })
});
$(window).ajaxStop(function() {
    $("#id01").hide();
});
</script>
