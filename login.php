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

      <form class="w3-container" action="logininto.php" method="post">
        <div class="w3-section">
          <label><b>帳號</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="請 輸 入 帳 號" name="usrname" required>
          <label><b>密碼</b></label>
          <input class="w3-input w3-border" type="text" placeholder="請 輸 入 密 碼" name="psw" required>
          <button class="w3-btn-block w3-green w3-section w3-padding" type="submit">登入</button>
        </div>
      </form>

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
        <span class="w3-right w3-padding"><a href="register.php">申請帳號</a></span>
      </div>

    </div>
  </div>
</div>
            
</body>
</html>

