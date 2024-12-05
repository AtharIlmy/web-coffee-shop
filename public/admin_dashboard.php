<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* Admin Dashboard Header */
        .admin-header {
            background-color: #333;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .admin-header .container h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #333;
        }

        .admin-header .container p {
            font-size: 18px;
            margin: 0;
            color: #333;
        }

        /* Dashboard Main Content */
        .admin-dashboard {
            margin-top: 20px;
            padding: 20px 0;
        }

        .dashboard-menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .dashboard-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
            width: 300px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .dashboard-card h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        .dashboard-card p {
            font-size: 16px;
            color: #555;
        }

        .dashboard-card.logout {
            background-color: #f44336;
            color: white;
        }

        .dashboard-card.logout:hover {
            background-color: #d32f2f;
        }

    </style>

    <header class="admin-header">
        <div class="container">
            <h1>Admin Dashboard</h1>
            <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong></p>
        </div>
    </header>
    
    <main class="admin-dashboard">
        <div class="container">
            <div class="dashboard-menu">
                <a href="manage_coffees.php" class="dashboard-card">
                    <h2>Kelola Menu Kopi</h2>
                    <p>Tambah, edit, atau hapus menu kopi yang tersedia.</p>
                </a>
                <a href="manage_users.php" class="dashboard-card">
                    <h2>Kelola Pengguna</h2>
                    <p>Lihat dan kelola daftar pengguna.</p>
                </a>
                <a href="logout.php" class="dashboard-card logout">
                    <h2>Logout</h2>
                    <p>Keluar dari akun admin.</p>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
