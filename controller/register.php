<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../functions.php';
session_start();




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_id = $_POST['login_id'] ?? '';
    $password = $_POST['password'] ?? '';
    $user_name = $_POST['user_name'] ?? '';


    if (empty($login_id) || empty($password) || empty($user_name)) {
        $_SESSION['error'] = "全ての項目を入力してください。";
        header("Location: ../view/register.php");
        exit;
    }

    try {
        $pdo = db_conn();

        // ログインIDの重複チェック
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login_id = :login_id");
        $stmt->bindValue(':login_id', $login_id, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetch()) {
            $_SESSION['error'] = "このログインIDは既に使用されています。";
            header("Location: ../view/register.php");
            exit;
        }

        // 新規ユーザーの登録
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (login_id, password, user_name) VALUES (:login_id, :password, :user_name)");
        $stmt->bindValue(':login_id', $login_id, PDO::PARAM_STR);
        $stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindValue(':user_name', $user_name, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['success'] = "ユーザー登録が完了しました！";
            header("Location: ../index.php");
            exit;
        } else {
            throw new Exception("登録に失敗しました。");
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "データベースエラー: " . $e->getMessage();
        header("Location: ../view/register.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../view/register.php");
        exit;
    }
} else {
    header("Location: ../view/register.php");
    exit;
}
