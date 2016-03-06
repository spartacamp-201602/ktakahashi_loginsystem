<?php

function connectDatabase()
{
    try
    {
        return new PDO(DSN, USER, PASSWORD);
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
        exit;
    }
}

// エスケープ処理
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}