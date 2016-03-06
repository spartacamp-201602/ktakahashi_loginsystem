<?php

require_once('config.php');
require_once('functions.php');

session_start();

if (empty($_SESSION['id']))
{
    header('Location: login.php');
    exit;
}

// ユーザネームを表示したい
// そのためには、$_SESSION['id']の情報をもとに、
// usersテーブルから SELECT 文を実行し、ユーザネームを取得する
$dbh = connectDatabase();

$sql = 'select * from users where id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_SESSION['id']);

$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員限定画面</title>
</head>
<body>
    <h1>登録したユーザーのみ閲覧可能です！</h1>
    <h2><?php echo h($user['name']); ?>さん ようこそ！</h2>
    <p><a href="logout.php">ログアウト</a></p>
</body>
</html>