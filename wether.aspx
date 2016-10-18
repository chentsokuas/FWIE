<%@ Page Language="C#" AutoEventWireup="true" CodeFile="wether.aspx.cs" Inherits="wether_" %>

<!DOCTYPE html>
<!--
Created using JS Bin
http://jsbin.com

Copyright (c) 2016 by toasttsao (http://jsbin.com/sucuhe/9/edit)

Released under the MIT license: http://jsbin.mit-license.org
-->
<meta name="robots" content="noindex">
<html ng-app="myFirstAppDemo">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular.min.js"></script>
    <script src="http://code.angularjs.org/1.2.13/angular-animate.js"></script>
    <script src="js/myFirstAppDemo.js"></script>
    <link href="css/Style.css" rel="stylesheet" />
    <meta charset="utf-8">
    <title>angular_APP_DEMO</title>
<style id="jsbin-css">
/*淡入淡出效果*/
.fadein,
.fadeout {
  -webkit-transition:all cubic-bezier(0.250, 0.460, 0.450, 0.940) 1.5s;
  -moz-transition:all cubic-bezier(0.250, 0.460, 0.450, 0.940) 1.5s;
  -o-transition:all cubic-bezier(0.250, 0.460, 0.450, 0.940) 1.5s;
  transition:all cubic-bezier(0.250, 0.460, 0.450, 0.940) 1.5s;
}
/*
    透過$scope.rtval.active此變數
    為false時
    透過.ng-hide-add-active此事件做隱藏
    */
.fadeout.ng-hide-add-active {
  opacity: 0;
  display: block !important;
}

/*
    透過$scope.rtval.active此變數
    為true時
    透過.ng-hide-remove-active此事件做呈現
    */
.fadein.ng-hide-remove-active {
  opacity: 1;
  display: block !important;
}

.divfix {
    margin-top: 18px;
}

.pfix {
    margin-top: 0px; 
    margin-bottom: 0px;
}
</style>
</head>
<body ng-controller="MySelectCtrl" ng-init="action('TaipeiCityList')">
    <p class="divfix">縣市：
        <select ng-model="Selectcityarr" ng-options="m.city.name for m in cityarr" ng-change="action(Selectcityarr.city.id)">
            <option value="">-- 請選擇 --</option>
        </select>

    </p>
    <table >
        <!-- 放置內容區域-->
        <td width="280px;">
  
            <p >日期</p>
            <p >{{timeNow | date:"yyyy/MM/dd"}}</p>
        </td>
        <td width="280x;">
            <p>縣市</p>
            <p ng-show="rtval.active" class="fadein fadeout">{{rtval.name}}</p>
        </td>
        <td width="280px;">
            <p>溫度</p>
            <p ng-show="rtval.active" class="fadein fadeout">{{rtval.temp}}</p>
        </td>
        <td width="280px;">
            <p>降雨機率</p>
            <p ng-show="rtval.active" class="fadein fadeout" ng-bind="rtval.percent"></p>
        </td>
        <td width="280px;">
            <div class="spic divfix">
                <p class="pfix">示意圖</p>
                <img ng-src="http://www.cwb.gov.tw/{{rtval.imgurl}}" width="50" height="50" border="0" ng-show=" rtval.active" class="fadein fadeout">
            </div>

        </td>

    </table>
<script id="jsbin-javascript">

/*
在此定義module名稱,與引用的model
myFirstAppDemo-自定義名稱,對應到HTML中的ng-app
ngAnimate-引用<畫面特效>
*/
var app = angular.module('myFirstAppDemo', ['ngAnimate']);


/*
在此定義controller名稱,與引用的所會用到服務
MySelectCtrl-自定義名稱,對應到HTML中的ng-controller
$scope-控制在HTML與angular之間橋樑如在JS中$scope.rtval.percent ,
       在HTML上要輸出則{{ rtval.imgurl}} ,
       或是在HTML TAG上加上 ng-bind="rtval.percent"
$http-Ajax服務
*/
app.controller('MySelectCtrl', ['$scope', '$http',
  function Action($scope, $http) {
      //回傳值
      $scope.rtval = new Object();
      //顯示今日時間
      $scope.timeNow = Date.now();
      //縣市選單
      $scope.cityarr = [
        {
            "city": {
                "id": "KeelungList",
                "name": "基隆市"
            }
        },
                {
                    "city": {
                        "id": "TaipeiCityList",
                        "name": "臺北市"
                    }
                },
                {
                    "city": {
                        "id": "TaipeiList",
                        "name": "新北市"
                    }
                },
                {
                    "city": {
                        "id": "TaoyuanList",
                        "name": "桃園縣"
                    }
                },
                {
                    "city": {
                        "id": "HsinchuCityList",
                        "name": "新竹市"
                    }
                },
                {
                    "city": {
                        "id": "HsinchuList",
                        "name": "新竹縣"
                    }
                },
                {
                    "city": {
                        "id": "MiaoliList",
                        "name": "苗栗縣"
                    }
                },
                {
                    "city": {
                        "id": "TaichungList",
                        "name": "臺中市"
                    }
                },
                {
                    "city": {
                        "id": "ChanghuaList",
                        "name": "彰化縣"
                    }
                },
                {
                    "city": {
                        "id": "NantouList",
                        "name": "南投縣"
                    }
                },
                {
                    "city": {
                        "id": "YunlinList",
                        "name": "雲林縣"
                    }
                },
                {
                    "city": {
                        "id": "ChiayiCityList",
                        "name": "嘉義市"
                    }
                },
                {
                    "city": {
                        "id": "ChiayiList",
                        "name": "嘉義縣"
                    }
                },
                {
                    "city": {
                        "id": "YilanList",
                        "name": "宜蘭縣"
                    }
                },
                {
                    "city": {
                        "id": "HualienList",
                        "name": "花蓮縣"
                    }
                },
                {
                    "city": {
                        "id": "TaitungList",
                        "name": "臺東縣"
                    }
                },
                {
                    "city": {
                        "id": "TainanList",
                        "name": "臺南市"
                    }
                },
                {
                    "city": {
                        "id": "KaohsiungCityList",
                        "name": "高雄市"
                    }
                },
                {
                    "city": {
                        "id": "PingtungList",
                        "name": "屏東縣"
                    }
                },
                {
                    "city": {
                        "id": "MatsuList",
                        "name": "連江縣"
                    }
                },
                {
                    "city": {
                        "id": "KinmenList",
                        "name": "金門縣"
                    }
                },
                {
                    "city": {
                        "id": "PenghuList",
                        "name": "澎湖縣"
                    }
                }];

      //下拉式清單更動事件
      $scope.action = function ( cityId) {
          getWeather(cityId);
      };

      /*網頁Loaded如:jquery ready事件或者在TAG中添加ng-init
       angular.element(document).ready(function () {
                  getWeather('TaipeiCityList');
              });
      */

      //透過YQL取得中央氣象局天氣資訊(當日)
     
      function getWeather(citycode) {
          $scope.rtval.active = false;
          var BasicQueryUrl = 'http://query.yahooapis.com/v1/public/yql?';
          var query = 'q=' +
                      encodeURIComponent('select * from html where ' +
                      '  url = "http://www.cwb.gov.tw/V7/forecast/f_index.htm" and ' +
                      'xpath=' + "'" + '//tr[@id="' + citycode + '"]' + "'") + '&format=json';

          $http({
              method: 'GET',
              url: BasicQueryUrl + query
          })
          .success(function (data, status, headers, config) {
              $scope.rtval.active=true ;
              var lastImglength = data.query.results.tr.td.length - 1;
              angular.forEach(data.query.results.tr.td, function (value, key) {
                  if (value.a.content !== undefined) {
                      var name = '';
                      switch (key) {
                          case 0:
                              //"縣市";
                              $scope.rtval.name = value.a.content;
                              break;
                          case 1:
                              //"溫度";
                              $scope.rtval.temp = value.a.content;
                              break;
                          case 2:
                              //"降雨機率";
                              $scope.rtval.percent = value.a.content;

                              break;
                      }
                  }
              });
          
              var iurl = data.query.results.tr.td[lastImglength].div.a.img.src;
                         $scope.rtval.imgurl = iurl;
           
          })
          .error(function (data, status, headers, config) {
          });

      };
  }

]);


</script>
</body>
</html>
