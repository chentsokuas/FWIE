<?php
// ?query=[觀測點名稱]，例如: Taoyuan Air Base , Taipei
$xml = simplexml_load_file(
    "http://api.wunderground.com/auto/wui/geo/WXCurrentObXML/index.xml?query=RCSS"
);

// 氣象圖示

/* 
圖示造型種類:
    Default
    Smiley
    Helen
    Generic
    Old School
    Cartoon
    Mobile
    Simple
    Contemporary
    Dunkin' Donuts    
*/

echo "氣象狀況：",$xml->weather,"<br>";

echo "溫度：",$xml->temp_c,"°C<br>";

echo "相對濕度：",$xml->relative_humidity,"<br>";

echo "風向：",$xml->wind_dir,"<br>";

echo "風速：",$xml->wind_mph,"MPH<br>";
echo "風速：每小時",round($xml->wind_mph*1.6093),"公里<br>";
echo "風速：每秒",round($xml->wind_mph*0.447028),"公尺<br>";

echo "海平面氣壓：",$xml->pressure_mb,"百帕<br>";

echo "高溫指數：",$xml->heat_index_c,"°C<br>";

echo "風寒指數：",$xml->windchill_c,"°C<br>";

echo "水凝點：",$xml->dewpoint_c,"°C<br>";

echo "能見度：",$xml->visibility_km,"公里<br>";

echo "觀測時間：",date(
    'Y-m-d',
    strtotime($xml->observation_time_rfc822)
),"<br>";

/* 
thunderstorm rain = 雷雨
showers rain = 驟雨
light showers rain = 小驟雨

Cloudy = 多雲
Flurries = 小雪
Fog = 霧
Haze = 陰霾
Mostly Cloudy = 多雲時陰
Mostly Sunny = 晴時多雲
Partly Cloudy = 局部多雲
Partly Sunny = 多雲時晴
Freezing Rain = 凍雨
Rain = 雨
Sleet = 冰雹
Snow = 雪
Sunny = 晴朗
Unknown = 未知
Overcast = 陰天
Scattered Clouds = 疏雲 
*/
?>