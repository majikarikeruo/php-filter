<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

// セッション開始
session_start();
loginCheck();

// データベース接続
$pdo = db_conn();

// GETパラメータからIDを取得
$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['error'] = "IDが指定されていません。";
    header("Location: list.php");
    exit;
}

// アンケートデータの取得
$stmt = $pdo->prepare("
    SELECT 
        surveys.id AS survey_id,
        surveys.content AS survey_content,
        surveys.rating AS survey_rating,
        surveys.image_url AS survey_image,
        surveys.created_at AS survey_created_at,
        users.user_name AS creator_name,
        surveys.user_id AS survey_user_id
    FROM 
        surveys AS surveys
    JOIN 
        users AS users ON surveys.user_id = users.id 
    WHERE 
        surveys.id = :id
");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$survey = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) {
    $_SESSION['error'] = "指定されたアンケートは存在しません。";
    header("Location: list.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケート詳細 - アンケートアプリ</title>
    <link href="../assets/css/style.css" rel="stylesheet" />
    <script src="../assets/js/script.js"></script>
</head>

<body>
    <?php include __DIR__ . '/../view/components/header.php'; ?>

    <div class="container">
        <h1>アンケート詳細</h1>

        <form method="POST" action="../controller/survey_update.php" enctype="multipart/form-data">
            <div class="jumbotron">
                <fieldset>
                    <div class="form-group">
                        <label for="rating">満足度（1-5で入力）：</label>
                        <input type="number" name="rating" id="rating" min="1" max="5" class="form-field" value="<?= h($survey['survey_rating']) ?>" <?= ($_SESSION['user_id'] != $survey['survey_user_id']) ? 'readonly' : '' ?>>
                    </div>
                    <div class="form-group">
                        <label for="content">内容：</label>
                        <textarea id="content" name="content" rows="4" cols="40" class="form-field" <?= ($_SESSION['user_id'] != $survey['survey_user_id']) ? 'readonly' : '' ?>><?= h($survey['survey_content']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>現在の画像：</label>
                        <div class="form-img">
                            <?php if (!empty($survey['survey_image'])) : ?>
                                <img src="<?= '../' . h($survey['survey_image']) ?>" alt="アンケート画像" class="image-class">
                            <?php else : ?>
                                <p>画像はありません</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="new_image">新しい画像（任意）：</label>
                        <input type="file" name="new_image" id="new_image" class="form-field" onchange="previewImage(event)">
                    </div>
                    <div class="form-group">
                        <label>新しい画像のプレビュー：</label>
                        <img id="imagePreview" src="#" alt="画像プレビュー" style="display:none; max-width:200px;">
                    </div>

                    <div class="form-group">
                        <p>作成者: <?= h($survey['creator_name']) ?></p>
                        <p>作成日時: <?= h(date('Y年m月d日 H:i', strtotime($survey['survey_created_at']))) ?></p>
                    </div>

                    <?php if ($_SESSION['user_id'] == $survey['survey_user_id']) : ?>
                        <div class="form-group">
                            <input type="submit" value="更新" class="btn">
                            <input type="hidden" name="id" value="<?= h($survey['survey_id']) ?>">
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <a href="./list.php" class="btn-default">一覧へ戻る</a>
                    </div>
                </fieldset>
            </div>
        </form>
    </div>

    <?php include __DIR__ . '/../view/components/footer.php'; ?>

</body>

</html>