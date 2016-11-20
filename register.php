<!DOCTYPE html>
<html>
<title>以台灣氣象站為基礎之農地氣象資訊推估系統</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<script type="text/javascript">
  window.onload = function(){
    document.getElementById('id01').style.display='block';
  }
</script>
<body>

<div class="w3-container">




  <div id="id01" class="w3-modal">
    <div class="w3-modal-content w3-card-8 w3-animate-zoom" style="max-width:600px">
  
      <div class="w3-center"><br>
        <span onclick="document.getElementById('id01').style.display='none'" class="w3-closebtn w3-hover-red w3-container w3-padding-8 w3-display-topright" title="Close Modal">×</span>
        <img src="./img/farmer.jpg" alt="Avatar" style="width:30%" class="w3-circle w3-margin-top">
      </div>

      <form class="w3-container" action="registerinto.php" method="post">
        <div class="w3-section">

         <label><b>申請姓名</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="請 輸 入 姓 名" name="usrname" required>

          <label><b>申請帳號</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="請 輸 入 帳 號" name="login" required>
          
          <label><b>申請密碼</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="請 輸 入 密 碼" name="psw" required>

            <label><b>Email電子郵件</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="請 輸 入 電 子 郵 件" name="email" required>

          <label><b>農地經度</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="請 輸 入 農 地 經 度" name="lon" required>
          <label><b>農地緯度</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="請 輸 入 農 地 緯 度" name="lat" required>

          <button class="w3-btn-block w3-red w3-section w3-padding" type="submit">確定</button>
        </div>
      </form>

   

    </div>
  </div>
</div>
            
</body>
</html>

