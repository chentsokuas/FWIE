
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

<script type="text/javascript">
//地圖初始化
var myLatlng = new google.maps.LatLng(23.7, 120.9082103);
var myOptions = {
    zoom: 7,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.SATELLITE
};
map = new google.maps.Map(document.getElementById("map"), myOptions);


//SOM

const maxClusters = 6;
const vecLen = 4;
const decayRate = 0.96;              //About 100 iterations. 
const minAlpha = 0.01;
const radiusReductionPoint = 0.023;  //Last 20% of iterations.

var alpha = 0.6;
var d = new Array(maxClusters);      //Network nodes.

//Weight matrix with randomly chosen values between 0.0 and 1.0
var w = new Array([0.2, 0.6, 0.5, 0.9],
                  [0.9, 0.3, 0.6, 0.4,],
                  [0.8, 0.5, 0.7, 0.2],
                  [0.6, 0.4, 0.2, 0.3],
                  [0.8, 0.9, 0.7, 0.9],
                  [0.4, 0.1, 0.5, 0.7]);

//Training patterns.
const inputPatterns = 5;
var pattern = new Array([16.18, 0.64, 0.94, 1015.99],
                        [16.3, 0.64, 0.94, 1016.54],
                        [10.04, 0.66, 0.67, 713.57],
                        [26.53, 1.05, 0.66, 957.92],
                        [20.29, 0.82, 0.92, 978.78]
                        );

//Testing patterns to try after training is complete.
const inputTests = 6;
var tests = new Array([16.18, 0.64, 0.94, 1015.99],
                        [16.3, 0.64, 0.94, 1016.54],
                        [10.04, 0.66, 0.67, 713.57],
                        [26.53, 1.05, 0.66, 957.92],
                        [20.29, 0.82, 0.92, 978.78]
                        );




window.onload = function()
{
   
    training();
    printResults();
}

function training()
{
    var iterations = 0;
    var reductionFlag = false;
    var reductionPoint = 0;
    var dMin = 0;

    do
    {
        iterations += 1;

        for(vecNum = 0; vecNum <= (inputPatterns - 1); vecNum++)
        {
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
        if(alpha < radiusReductionPoint){
            if(reductionFlag == false){
                reductionFlag = true;
                reductionPoint = iterations;
            }
        }

    } while(alpha > minAlpha);


     $( "#div" ).append('<p>Iterations: '+iterations+'</p>');
    
     $( "#div" ).append('<p>Neighborhood radius reduced after '+
                             reductionPoint+' iterations.</p>');


}

function computeInput(vectorArray, vectorNumber)
{
    clearArray(d);

    for(i = 0; i <= (maxClusters - 1); i++){
        for(j = 0; j <= (vecLen - 1); j++){
            d[i] += Math.pow((w[i][j] - vectorArray[vectorNumber][j]), 2);
        } // j
    } // i
}

function updateWeights(vectorNumber, dMin){

    for(i = 0; i <= (vecLen - 1); i++)
    {
        //Update the winner.
        w[dMin][i] = w[dMin][i] + (alpha * (pattern[vectorNumber][i] - 
                                                         w[dMin][i]));

        //Only include neighbors before radius reduction point is reached.
        if(alpha > radiusReductionPoint){
            if((dMin > 0) && (dMin < (maxClusters - 1))){
                //Update neighbor to the left...
                w[dMin - 1][i] = w[dMin - 1][i] + 
                    (alpha * (pattern[vectorNumber][i] - w[dMin - 1][i]));
                //and update neighbor to the right.
                w[dMin + 1][i] = w[dMin + 1][i] + 
                    (alpha * (pattern[vectorNumber][i] - w[dMin + 1][i]));
            } else {
                if(dMin == 0){
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

function clearArray(NodeArray)
{
    for(i = 0; i <= (maxClusters - 1); i++)
    {
        NodeArray[i] = 0.0;
    } // i
}

function minimum(nodeArray)
{
    var winner = 0;
    var foundNewWinner = false;
    var done = false;

    do
    {
        foundNewWinner = false;
        for(i = 0; i <= (maxClusters - 1); i++)
        {
            if(i != winner){             //Avoid self-comparison.
                if(nodeArray[i] < nodeArray[winner]){
                    winner = i;
                    foundNewWinner = true;
                }
            }
        } // i

        if(foundNewWinner == false){
            done = true;
        }

    } while(done != true);

    return winner;
}

function printResults()
{
    var dMin = 0;
$( "#div" ).append('<p>Clusters for training input:</p>');
//Print clusters created.
    for(vecNum = 0; vecNum <= (inputPatterns - 1); vecNum++)
    {
        //Compute input.
        computeInput(pattern, vecNum);

        //See which is smaller.
        dMin = minimum(d);


        for(i = 0; i <= (vecLen - 1); i++)
        {
            $( "#div" ).append(pattern[vecNum][i]+', ');
        } // i
         $( "#div" ).append(' fits into category '+dMin+'</p>');

    } // VecNum

//Print weight matrix.
   $( "#div" ).append('<p><hr></p>');
    for(i = 0; i <= (maxClusters - 1); i++)
    {
        $( "#div" ).append('<p>Weights for Node '+i+' connections:</p>');
    
        for(j = 0; j <= (vecLen - 1); j++)
        {
            $( "#div" ).append((Math.round(w[i][j] * 1000) / 1000)+', ');
        } // j
        $( "#div" ).append('</pre></p>');
    } // i

//Print post-training tests.
  $( "#div" ).append('<p><hr></p>');
  $( "#div" ).append('<p>Categorized test input:</p>');
    for(vecNum = 0; vecNum <= (inputTests - 1); vecNum++)
    {
        //Compute input for all nodes.
        computeInput(tests, vecNum);

        //See which is smaller.
        dMin = minimum(d);

        for(i = 0; i <= (vecLen - 1); i++)
        {
           $( "#div" ).append(tests[vecNum][i]+', ');
        } // i
     $( "#div" ).append(' fits into category '+dMin+'</p>');

    } // VecNum
}
</script>