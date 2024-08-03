<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケートアプリ</title>
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>

    <div class="container">
        <h1>ログイン</h1>
        <form name="form1" action="<?= $_ENV['APP_URL']; ?>controller/login_act.php" method="post" class="form">
            <div class="form-group">
                <label for="login_id">ID</label>
                <input type="text" id="login_id" name="login_id" class="form-field" required placeholder="ユーザーID">
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" class="form-field" required placeholder="パスワード">
            </div>
            <input type="submit" value="ログイン" class="btn">
        </form>
    </div>
</body>

</html>