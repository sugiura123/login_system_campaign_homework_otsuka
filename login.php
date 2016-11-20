<?php

require_once('config.php');
require_once('functions.php');

session_start();

if (!empty($_SESSION['id']))
{
  header('Location: result.php');
  exit;
}

$dbh = connectDatabase();

$email    = "";
$password = "";
$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $email    = $_POST['email'];
  $password = $_POST['password'];

  if ($email == '') {
    $errors['email'] = 'メールアドレスを入力してください';
  }

  if ($password == '') {
    $errors['password'] = 'パスワードを入力してください';
  }

  if (empty($errors)) {
    $sql = "select * from entry_users where email = :email and password = :password";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $_SESSION['id'] = $row['id'];
      header('Location: result.php');
      exit;
    }
    else {
      $errors['login'] = 'メールアドレスかパスワードが正しくありません';
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>応募フォーム</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body id="login">
<form action="" method="post">
  <br>
  <div class="container">
    <div class="head"><p>ドーナツがもらえるキャンペーン</div>
    <br>
    <?php if ($errors): ?>
    <div style="color:red;text-align:center">ログインに失敗しました。</div>
    <?php endif ?>
    <table>
      <tbody>
        <tr>
          <td class="cell01">メールアドレス </td>
          <td class="cell02">
              <input class="input" name="email" type="text" value="<?php echo h($email) ?>" size=50>
          </td>
        </tr>
        <tr>
          <td class="cell01">結果確認用パスワード</td>
          <td class="cell02">
              <input class="input" name="password" type="text" size=50>
          </td>
        </tr>
      </tbody>
    </table>
    <br>
    <button class="button">結果確認</button>
  </div>
</form>
</body>
</html>
