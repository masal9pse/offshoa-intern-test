<?php
ini_set('display_errors', "On");
require_once dirname(__FILE__) . '/../Classes/UtilClass.php';
$util = new UtilClass;
$post = $util->sanitize($_POST);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>新規投稿</title>
</head>

<body>
 <form action="signup.php" method="post">
  名前<input type="text" name="name" value="<?php print($util->empty_check($post, 'name')); ?>"><br />
  パスワード<input type="password" name="password" value="<?php print($util->empty_check($post, 'password')); ?>"><br />
  パスワード確認<input type="password" name="password_conf" value="<?php print($util->empty_check($post, 'password_conf')); ?>"><br />
  <button type="submit">新規登録</button>
 </form>
 <button type="button" onclick="location.href='./login.php'">ログイン画面へ</button>
 <button type="button" onclick="location.href='../index.php'">トップページへ</button>
</body>

</html>
<html>