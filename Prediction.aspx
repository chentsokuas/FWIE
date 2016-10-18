<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Prediction.aspx.cs" Inherits="Prediction" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>以台灣氣象站為基礎之農地氣象資訊推估系統</title>
    <meta name="viewport" content="initial-scale=1.0" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css" />
    <style>
        .w3-theme {
            color: #fff !important;
            background-color: #4CAF50 !important;
        }

        .w3-btn {
            background-color: #4CAF50;
            margin-bottom: 4px;
        }

        .w3-code {
            border-left: 4px solid #4CAF50;
        }

        .myMenu {
            margin-bottom: 150px;
        }

        html {
            height: 100%;
        }

        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <script>
        window.onload = function () {
            var ddbtn1 = document.getElementById("ddbtn1");
            var ddbtn2 = document.getElementById("ddbtn2");
            var selWeather = document.getElementById("selWeather");
            var ir = document.getElementById("ir");
            var sc = document.getElementById("sc");
            ddbtn1.onclick=function()
            {
                selWeather.style.display = "none";
                ir.style.display = "none";
                sc.style.display = "block";
              
            }
            ddbtn2.onclick = function () {
                ir.style.display ="block" ;
                sc.style.display ="none";
                selWeather.style.display = "block";
               
            }
          
        }
        
    </script>
</head>
<!-- Top -->
<body>
    <form id="form1" runat="server">
        <div class="w3-top">
            <div class="w3-row w3-white w3-padding">
                <div class="w3-half" style="margin: 4px 0 6px 0">
                    <img src='./img/logoban.png'>
                </div>
                <div class="w3-half w3-margin-top w3-wide w3-hide-medium w3-hide-small">
                    <div class="w3-right">以台灣氣象站為基礎之農地氣象資訊推估系統</div>
                </div>
            </div>
            <ul class="w3-navbar w3-theme w3-large" style="z-index: 4;">
                <li class="w3-opennav w3-left w3-hide-large">
                    <a class="w3-hover-white w3-large w3-theme w3-padding-16" href="javascript:void(0)" onclick="w3_open()">☰</a>
                </li>
                <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16" href="FWIE.aspx">經緯度查詢</a></li>
                <li class="w3-hide-medium w3-hide-small"><a class="w3-hover-white w3-padding-16" href="Prediction.aspx">氣象預測</a></li>
            </ul>
        </div>

        <!-- Sidenav -->
        <nav class="w3-sidenav w3-collapse w3-light-green w3-animate-left" style="z-index: 3; width: 270px; margin-top: 1%;" id="mySidenav">
            <div class="w3-hide-large">
                <a href="FWIE.aspx" class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large" style="width: 50%">經緯度查詢</a>
                <a href="Prediction.aspx" class="w3-left w3-theme w3-hover-white w3-padding-16 w3-large" style="width: 50%">氣象預測</a>
            </div>
            <div class="w3-clear"></div>
            <a href="javascript:void(0)" onclick="w3_close()" class="w3-right w3-xlarge w3-padding-large w3-hide-large" title="close menu">×</a>
            <div id="menuTut" class="myMenu">
                <div class="w3-container w3-padding-top">
                    <h3>氣象預測查詢</h3>
                    <input id="ddbtn1" value="單日查詢" class="w3-btn w3-red" type="button" />
                    <input id="ddbtn2" value="連結氣象局查詢" class="w3-btn w3-red" type="button" />
                    <select class="w3-select" id="selWeather" runat="server" onchange="getWeather()" style="display:none">
                        <option value="Taipei_City.htm" selected="selected">臺北市</option>
                        <option value="New_Taipei_City.htm">新北市</option>
                        <option value="Taoyuan_City.htm">桃園市</option>
                        <option value="Taichung_City.htm">臺中市</option>
                        <option value="Tainan_City.htm">臺南市</option>
                        <option value="Kaohsiung_City.htm">高雄市</option>
                        <option value="Keelung_City.htm">基隆市</option>
                        <option value="Hsinchu_City.htm">新竹市</option>
                        <option value="Hsinchu_County.htm">新竹縣</option>
                        <option value="Miaoli_County.htm">苗栗縣</option>
                        <option value="Changhua_County.htm">彰化縣</option>
                        <option value="Nantou_County.htm">南投縣</option>
                        <option value="Yunlin_County.htm">雲林縣</option>
                        <option value="Chiayi_City.htm">嘉義市</option>
                        <option value="Chiayi_County.htm">嘉義縣</option>
                        <option value="Pingtung_County.htm">屏東縣</option>
                        <option value="Yilan_County.htm">宜蘭縣</option>
                        <option value="Hualien_County.htm">花蓮縣</option>
                        <option value="Taitung_County.htm">臺東縣</option>
                        <option value="Penghu_County.htm">澎湖縣</option>
                        <option value="Kinmen_County.htm">金門縣</option>
                        <option value="Lienchiang_County.htm">連江縣</option>
                        </select>
        <p>
            <script>
                function getWeather() {
                    var url = "http://www.cwb.gov.tw/V7/forecast/taiwan/" + document.getElementById("selWeather").value;
                    document.getElementById("ir").src = url;
                }
            </script>

            <asp:ScriptManager ID="ScriptManager1" runat="server">
            </asp:ScriptManager>
            <asp:UpdatePanel ID="UpdatePanel1" runat="server">
                <ContentTemplate>
                </ContentTemplate>
            </asp:UpdatePanel>
                </div>
        </nav>

        <!-- Overlay effect when opening sidenav on small screens -->
        <div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor: pointer" title="close side menu" id="myOverlay"></div>

        <!-- Main content: shift it to the right by 270 pixels when the sidenav is visible -->
        <div class="w3-main w3-container" style="margin-left: 270px; margin-top: 117px;">

            <div class="w3-container w3-section w3-padding-large w3-card-4 w3-light-grey">
                <div style="width: 100%"></div>
                <iframe id="ir" style="text-align: center" width="100%" height="1000px" frameborder="0" scrolling="0" style="display:none"></iframe>
                 <iframe id="sc" src="wether.aspx" id="ir" align:"center" width="100%" height="1000px" frameborder="0" scrolling="0" style="display:none"></iframe>
            </div>
            <footer class="w3-container w3-section w3-padding-32 w3-card-4 w3-light-grey w3-center w3-opacity">
                <p>
                    <nav>
                        An Intelligent Agriculture Platform for Estimating Agrometeorological and Mining Plant Diseases and Pests Features: Design and Implementation
                    </nav>
                </p>
            </footer>

            <!-- END MAIN -->
        </div>

        <script>
            // Script to open and close the sidenav
            function w3_open() {
                document.getElementById("mySidenav").style.display = "block";
                document.getElementById("myOverlay").style.display = "block";
            }

            function w3_close() {
                document.getElementById("mySidenav").style.display = "none";
                document.getElementById("myOverlay").style.display = "none";
            }
            function w3_show_nav(name) {
                document.getElementById("menuTut").style.display = "none";
                document.getElementById("menuRef").style.display = "none";
                document.getElementById(name).style.display = "block";
                w3 - open();
            }
        </script>
        <script src="http://www.w3schools.com/lib/w3codecolors.js"></script>
    </form>
</body>
</html>
