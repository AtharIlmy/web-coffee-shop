<?php
session_start();
include('../includes/db.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Periksa apakah pengguna adalah admin
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['is_admin'] != 1) {
    header("Location: index.php");
    exit();
}
?>
