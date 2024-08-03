<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

session_start();
loginCheck();

$id = $_POST['id'];

$pdo = db_conn();

$stmt = $pdo->prepare('DELETE FROM surveys WHERE id = :id;');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status === false) {
    sql_error($stmt);
} else {
    redirect('../view/list.php');
}
