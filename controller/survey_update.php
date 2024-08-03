<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

session_start();
loginCheck();


// データベース接続
$pdo = db_conn();

// フォームデータの取得
$id = $_POST['id'] ?? null;
$content = $_POST['content'] ?? '';
$rating = $_POST['rating'] ?? '';

// バリデーション
if (!$id || empty($content) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
    $_SESSION['error'] = "入力内容に誤りがあります。";
    header("Location: ../view/detail.php?id=" . $id);
    exit;
}

try {
    // 現在のアンケートデータを取得
    $stmt = $pdo->prepare("SELECT * FROM surveys WHERE id = :id AND user_id = :user_id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    $stmt->execute();
    $survey = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$survey) {
        throw new Exception("アンケートが見つからないか、編集権限がありません。");
    }

    // 画像の処理
    $image_url = $survey['image_url']; // デフォルトは現在の画像URL

    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["new_image"]["name"];
        $filetype = $_FILES["new_image"]["type"];
        $filesize = $_FILES["new_image"]["size"];

        // 拡張子の確認
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        // 新しいファイル名を生成
        $new_filename = uniqid() . "." . $ext;
        $upload_path = __DIR__ . "/../uploads/" . $new_filename;

        // ファイルを移動
        if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $upload_path)) {
            // 古い画像を削除（存在する場合）
            if ($image_url && file_exists(__DIR__ . "/../" . $image_url)) {
                unlink(__DIR__ . "/../" . $image_url);
            }
            $image_url = "uploads/" . $new_filename;
        } else {
            throw new Exception("ファイルのアップロードに失敗しました。");
        }
    }

    // データベースの更新
    $stmt = $pdo->prepare("UPDATE surveys SET content = :content, rating = :rating, image_url = :image_url WHERE id = :id AND user_id = :user_id");
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindValue(':image_url', $image_url, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    $result = $stmt->execute();

    if ($result) {
        header("Location: ../view/list.php");
    } else {
        throw new Exception("アンケートの更新に失敗しました。");
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: ../view/detail.php?id=" . $id);
}

exit;
