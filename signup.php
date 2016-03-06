<?php

require_once('config.php');
require_once('functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST['name'];
    $password = $_POST['password'];

    $errors = array();

    if (empty($name))
    {
        $errors[] = 'ユーザネームが未入力です。';
    }

    if (empty($password))
    {
        $errors[] = 'パスワードが未入力です。';
    }

    $dbh = connectDatabase();
    $sql = 'select * from users where name = :name';
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user)
    {
        $errors[] = 'すでにそのユーザネームは登録されています。';
    }

    if (empty($errors))
    {
        $dbh = connectDatabase();
        $sql = 'insert into users (name, password, created_at) ';
        $sql.= 'values (:name, :password, now())';

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        header('Location: login.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録画面</title>
    <style>
    .error{
        color: red;
        list-style: none;
    }
    </style>
</head>
<body>
    <h1>新規ユーザー登録</h1>
    <?php if (isset($errors)): ?>
        <div class="error">
        <?php foreach ($errors as $error) : ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="" method="post">
        ユーザネーム: <input type="text" name="name"><br>
        パスワード: <input type="text" name="password"><br>
        <input type="submit" value="新規登録">
    </form>
    <a href="login.php">ログインはこちら</a>
</body>
</html>