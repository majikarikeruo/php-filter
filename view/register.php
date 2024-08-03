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
        <h1>新規ユーザー登録</h1>
        <form name="form1" action="../controller/register.php" method="post" class="form">
            <div class="form-group">
                <label for="user_name">ユーザー名</label>
                <input type="text" id="user_name" name="user_name" class="form-field" required placeholder="ユーザー名">
            </div>
            <div class="form-group">
                <label for="login_id">ID</label>
                <input type="text" id="login_id" name="login_id" class="form-field" required placeholder="ユーザーID">
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" class="form-field" required placeholder="パスワード">
            </div>
            <input type="submit" value="新規登録" class="btn">
        </form>
    </div>
</body>

</html>