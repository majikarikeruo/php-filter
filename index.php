<?php
require_once 'config.php';
require_once __DIR__ . '/functions.php';
session_start();
loginCheck();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケートアプリ</title>
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include __DIR__ . '/view/components/header.php'; ?>
    <ul>
        <li><a href="view/create.php">アンケート登録</a></li>
        <li><a href="view/list.php">アンケート結果一覧</a></li>
    </ul>

    <?php include __DIR__ . '/view/components/footer.php'; ?>
</body>

</html>