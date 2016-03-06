<?php
require_once('config.php');
require_once('functions.php');

session_start();

if (empty($_SESSION['id']))
{
    header('Location: login.php');
    exit;
}

$dbh = connectDatabase();

$sql = 'select * from users where id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_SESSION['id']);

$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

$name = $user['name'];
$password = $user['password'];

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = h($_POST['name']);
    $password = h($_POST['password']);

    $errors = array();

    // 未入力チェック
    if (empty($name))
    {
        $errors[] = 'ユーザネームが未入力です。';
    }

    // 未入力チェック
    if (empty($password))
    {
        $errors[] = 'パスワードが未入力です。';
    }

    // 変更チェック
    if ($user['name'] === $name &&
        $user['password'] === $password)
    {
        $errors[] = 'ユーザネームかパスワードを変更して下さい。';
    }

    // ユーザネームが変更された時だけ重複チェックをする
    if ($user['name'] != $name)
    {
        $dbh = connectDatabase();
        $sql = 'select * from users where name = :name';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $name);

        $stmt->execute();

        $checkUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($checkUser)
        {
            $errors[] = 'すでにそのユーザネームは登録されています。';
        }
    }

    if (empty($errors))
    {
        // ユーザネームとパスワードを更新
        $dbh = connectDatabase();
        $sql = 'update users set ';
        $sql.= 'name = :name, password = :password ';
        $sql.= 'where id = :id';

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $_SESSION['id']);

        $stmt->execute();

        header('Location: index.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザ情報編集</title>
    <style>
    .error {
        color: red;
        list-style: none;
    }
    </style>
</head>
<body>
    <h1>ユーザ情報編集</h1>
    <?php if (isset($errors)): ?>
        <div class="error">
        <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="" method="post">
        ユーザネーム: <input type="text" name="name" value="<?=h($name) ?>"><br>
        パスワード: <input type="text" name="password" value="<?=h($password) ?>"><br>
        <input type="submit" value="編集する">
    </form>
    <a href="signup.php">新規ユーザー登録はこちら</a>
</body>
</html>