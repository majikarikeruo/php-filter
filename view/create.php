<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
session_start();
loginCheck();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケートアプリ</title>
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include __DIR__ . '/../view/components/header.php'; ?>

    <div class="container">
        <form method="POST" action="../controller/survey_insert.php" enctype="multipart/form-data">
            <div class="jumbotron">
                <fieldset>
                    <legend>フリーアンケート</legend>
                    <div class="form-group">
                        <label for="content">満足度（1-5で入力）：</label>
                        <input type="number" name="rating" id="" min="1" max="5" class="form-field ">
                    </div>

                    <div class="form-group">
                        <label for="content">内容：</label>
                        <textarea id="content" name="content" rows="4" cols="40" class="form-field"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">画像:</label>
                        <input type="file" name="image" id="image">
                    </div>

                    <div class="form-group">
                        <input type="submit" value="送信" class="btn">
                    </div>
                </fieldset>
            </div>
        </form>
    </div>

    <?php include __DIR__ . '/../view/components/footer.php'; ?>

</body>

</html>