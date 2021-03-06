<?php

session_start();
ini_set('display_errors', "On");

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\PostTitleController;
use App\Controllers\TagController;

$tagInstance = new TagController('tags', 'asc');

// 認証チェック
$tagInstance->auth_check('../auth/login.php');

// 全権取得
$tags = $tagInstance->getAllData();

// XSS対策
$post = $tagInstance->sanitize($_POST);
?>
<!DOCTYPE html>

<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>投稿フォーム</title>
</head>

<body>
 <?php
 $title = new PostTitleController;
 //var_dump($_SESSION);
 ?>
 <h1>投稿フォーム</h1>
 <form action="../auth/logout.php" method="post">
  <button type="submit" name="logout" class="btn btn-danger">ログアウト</button>
 </form>
 <a href="./index.php">一覧表示リンク</a>
 <a href="./search_form.php">検索リンク</a>
 <form action="../Execute/insert.php" method="post" enctype="multipart/form-data">
  <table border="1">
   <tr>
    <td>タイトル</td>
    <!-- 空文字判定して変数にまとめる -->
    <td><input type="text" name="title" value="<?php $tagInstance->empty_check($post, 'title') ?>"></td>
    <td>本文</td>
    <!--XSS対策は後ほど-->
    <td><input type="text" name="detail" value="<?php $tagInstance->empty_check($post, 'detail') ?>"></td>
    <td>画像</td>
    <!-- valueを指定したい -->
    <td><input type="file" name="image"></td>
    <td><input type="hidden" name="user_id" value="<?php print(htmlspecialchars($_SESSION['auth_id'])); ?>"></td>
    <td>
     <input type="hidden" name="tags" value="">
     <?php foreach ($tags as $tag) : ?>
      <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>">
      <label for="<?php echo $tag['tag']; ?>"><?php echo $tag['tag']; ?></label>
     <?php endforeach; ?>
    </td>
    <td colspan="2" align="center">
     <input type="submit" value="送信">
   </tr>
  </table>
 </form>
</body>

</html>
<html>