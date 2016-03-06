<?php

session_start();

unset($_SESSION['id']);

//$_SESSIONの中身が全て消される
session_destroy();

header('Location: login.php');

exit;