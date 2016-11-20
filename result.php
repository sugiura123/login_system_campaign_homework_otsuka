<?php

require_once('config.php');
require_once('functions.php');

session_start();

if (!isset($_SESSION['id']))
{
  header('Location: login.php');
  exit;
}

$dbh = connectDatabase();
$sql = "select * from entry_users where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $_SESSION['id']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
  unset($_SESSION['id']);
  header('Location: login.php');
  exit;
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>応募フォーム</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body id="result">
  <br>
  <div class="container">
    <div class="head"><p>ドーナツがもらえるキャンペーン</div>
    <br>
    <br>
    <p style="text-align:center">キャンペーン結果</p>
    <p class="result">
    <?php if ($row['result'] == 1): ?>
    　おめでとうございます！<br>　当たりです！
    <?php else: ?>
    　残念... ハズレです。
    <?php endif ?>
    </p>
  </div>

</body>
</html>
