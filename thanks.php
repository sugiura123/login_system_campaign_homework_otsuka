<?php

require_once 'functions.php';

session_start();

if (!isset($_SESSION['password'])) {
  header('Location: login.php');
  exit;
}

$password = $_SESSION['password'];
unset($_SESSION['password']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>応募フォーム</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body id="thanks">
  <br>
  <div class="container">
    <div class="head"><p>ドーナツがもらえるキャンペーン</div>
    <br>
    <br>
    <p style="text-align:center">ご応募ありがとうございます。<br>結果確認用パスワードをお控えください。</p>
    <p style="text-align:center;font-size: 80%;">結果確認用パスワード: </p>
    <p class="password"><?php echo h($password) ?></p>
  </div>

</body>
</html>
