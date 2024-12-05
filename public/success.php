<?php
session_start();
include('../includes/db.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID transaksi dari URL
$transaction_id = $_GET['transaction_id'] ?? null;
if (!$transaction_id) {
    header("Location: index.php");
    exit();
}

// Ambil data transaksi dari database
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ?");
$stmt->execute([$transaction_id]);
$transaction = $stmt->fetch();

if (!$transaction || $transaction['user_id'] != $_SESSION['user_id'] || $transaction['payment_status'] != 'paid') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Sukses</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Pembayaran Sukses!</h1>
        <p>Terima kasih telah membeli kopi kami.</p>
        <p>Pesanan Anda akan segera diproses.</p>
    </div>
</body>
</html>
