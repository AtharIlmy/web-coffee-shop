<?php
session_start();
include('../includes/db.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID kopi dari URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit();
}

try {
    // Mulai transaksi untuk memastikan kedua query berjalan dengan benar
    $pdo->beginTransaction();

    // Hapus data terkait di tabel orders
    $stmt = $pdo->prepare("DELETE FROM orders WHERE coffee_id = ?");
    $stmt->execute([$id]);

    // Hapus data kopi dari tabel coffees
    $stmt = $pdo->prepare("DELETE FROM coffees WHERE id = ?");
    $stmt->execute([$id]);

    // Commit transaksi jika kedua query berhasil
    $pdo->commit();

    header("Location: index.php");
    exit();
} catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    $pdo->rollBack();
    echo "Gagal menghapus kopi: " . $e->getMessage();
}
?>
