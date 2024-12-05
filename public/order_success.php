<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhasil - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container1">
            <h1>Coffee <span>Shop</span></h1>
            <nav>
                <ul>
                    <li><a href="index.php" class="btn1">Home</a></li>
                    <li><a href="add.php" class="btn1">Add Coffee</a></li>
                    <li><a href="logout.php" class="btn1">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Pembayaran Berhasil</h2>
        <center><p>Terima kasih telah memesan di Coffee Shop kami! Pesanan Anda telah berhasil diproses.</p></center>
        <br>
        <center><a href="index.php" class="btn-back">Kembali ke Menu</a></center>
    </div>
</body>
</html>
