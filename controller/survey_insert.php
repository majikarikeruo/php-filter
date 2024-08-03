<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

session_start();
loginCheck();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $content = $_POST['content'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $user_id = $_SESSION['user_id'] ?? null;


    if (empty($content) || empty($rating) || $user_id === null) {
        $_SESSION['error'] = "内容と評価は必須項目です。また、ログインが必要です。";
        header("Location: ../view/list.php");
        exit;
    }

    // 画像アップロード処理
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];

        // 拡張子の検証
        $ext = pathinfo($filename, PATHINFO_EXTENSION);




        // 画像を保存
        $upload_dir = "../uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $new_filename = uniqid() . "." . $ext;
        $upload_path = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $upload_path)) {
            $image_url = "uploads/" . $new_filename;
        } else {
            $_SESSION['error'] = "画像のアップロードに失敗しました。";
            header("Location: ../view/create.php");
            exit;
        }
    }

    try {
        $pdo = db_conn();

        $stmt = $pdo->prepare("INSERT INTO surveys (content, image_url, rating, user_id) VALUES (:content, :image_url, :rating, :user_id)");
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':image_url', $image_url, PDO::PARAM_STR);
        $stmt->bindValue(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = "アンケートの登録が完了しました！";
            header("Location: ../view/list.php");
            exit;
        } else {
            throw new Exception("アンケートの登録に失敗しました。");
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "データベースエラー: " . $e->getMessage();
        header("Location: ../view/create.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../view/create.php");
        exit;
    }
} else {
    header("Location: ../view/create.php");
    exit;
}
