<?php

require_once('config.php');
require_once('functions.php');

session_start();

if (!empty($_SESSION['id']))
{
  header('Location: login.php');
  exit;
}

$dbh = connectDatabase();

$name       = '';
$email      = '';
$postcode_1 = '';
$postcode_2 = '';
$address    = '';
$kiyaku     = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $name       = $_POST['name'];
  $email      = $_POST['email'];
  $postcode_1 = $_POST['postcode_1'];
  $postcode_2 = $_POST['postcode_2'];
  $address    = $_POST['address'];
  $kiyaku     = $_POST['kiyaku'];

  $errors = array();

  if ($name == '') {
    $errors['name'] = 'お名前を入力してください';
  }

  if ($email == '') {
    $errors['email'] = 'メールアドレスを入力してください';
  }

  if ($postcode_1 == '' || $postcode_2 == '') {
    $errors['postcode'] = '郵便番号を入力してください';
  }
  elseif (!preg_match("/^[0-9]+$/", $postcode_1) || !preg_match("/^[0-9]+$/", $postcode_2)) {
    $errors['postcode'] = '半角数字で入力して下さい';
  }

  if ($address == '') {
    $errors['address'] = '住所を入力してください';
  }

  if ($kiyaku == '') {
    $errors['kiyaku'] = '応募規約に同意する必要があります';
  }


  if ($email) {
    $sql = "select * from entry_users where email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $errors['email']  = "このメールアドレスは既に登録されています";
    }
  }


  if (empty($errors))
  {
    $sql = "insert into entry_users (name, email, postcode, address, created_at, password, result) values (:name, :email, :postcode, :address, now(), :password, :result);";
    $stmt = $dbh->prepare($sql);

    $password = mt_rand(1000000, 9999999);
    $postcode = $postcode_1 . "-" . $postcode_2;
    $result   = mt_rand(0, 1);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":postcode", $postcode);
    $stmt->bindParam(":address", $address);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":result", $result);
    $result = $stmt->execute();

    if ($result) {
      $_SESSION['password'] = $password;
      header('Location: thanks.php');
      exit;
    }

  }
}

?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>応募フォーム</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body id="form">
  <br>
  <form action="" method="post">
  <div class="container">
    <div class="head"><p>ドーナツがもらえるキャンペーン</div>
    <br>
    <table>
      <tbody>
        <tr>
          <td class="cell01">お名前</td>
          <td class="cell02">
              <input class="input" name="name" type="text" value="<?php echo h($name) ?>" size=50>
              <?php if (isset($errors['name'])) : ?>
              <div class="c_r"><?php echo h($errors['name']) ?></div>
              <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td class="cell01">メールアドレス </td>
          <td class="cell02">
              <input class="input" name="email" type="text" value="<?php echo h($email) ?>" size=50>
              <?php if (isset($errors['email'])) : ?>
              <div class="c_r"><?php echo h($errors['email']) ?></div>
              <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td class="cell01">郵便番号</td>
          <td class="cell02">
              <input type="text" maxlength="4" name="postcode_1" size="4" value="<?php echo h($postcode_1) ?>"> -
              <input type="text" maxlength="5" name="postcode_2" size="6" value="<?php echo h($postcode_2) ?>">
              <?php if (isset($errors['postcode'])) : ?>
              <div class="c_r"><?php echo h($errors['postcode']) ?></div>
              <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td class="cell01">ご住所</td>
          <td class="cell02">
              <input type="text" name="address" value="<?php echo h($address) ?>" size=50>
              <?php if (isset($errors['address'])) : ?>
              <div class="c_r"><?php echo h($errors['address']) ?></div>
              <?php endif; ?>
          </td>
        </tr>
      </tbody>
    </table>
    <br>
    <div style="text-align:center">
      <div style="width: 560px; background-color: #eee; padding:20px;">
      <span style="font-size:80%">応募規約: <br>ドーナツは抽選で当たった方にプレゼントします。</span><br>
      </div>
      <div style="margin-top: 10px; margin-left: -40px;"><input type="checkbox" name="kiyaku" value="1" <?php if ($kiyaku == "1"): ?>checked<?php endif ?>> 応募規約に同意する</div>
      <?php if (isset($errors['kiyaku'])) : ?>
      <div class="c_r"><?php echo h($errors['kiyaku']) ?></div>
      <?php endif; ?>
    </div>
    <br>
    <button class="submit-button">上記内容でキャンペーンに応募する</button>
    <br>
  </div>
</form>
</body>
</html>
