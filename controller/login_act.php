<?php
require_once '../functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_id = $_POST['login_id'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($login_id) || empty($password)) {
        $error = "Both login ID and password are required.";
    } else {
        $pdo = db_conn();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE login_id = ?");
        $stmt->execute([$login_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['chk_ssid'] = session_id();
            header("Location: ../index.php");
            exit;
        } else {
            $error = "Invalid login credentials.";
        }
    }
}
