<?php
session_start();
include('../includes/db.php');

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Hapus pengguna
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$_GET['delete_id']])) {
        $_SESSION['message'] = "Pengguna berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus pengguna.";
    }
    header("Location: manage_users.php");
    exit();
}

// Ambil semua pengguna
$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .header {
            background: #000; /* Warna latar belakang header */
            color: white; /* Teks putih untuk header */
            padding: 20px 25px; /* Padding untuk header */
        }

        .header .container1 {
            display: flex;
            justify-content: space-between; /* Spasi antara elemen di dalam container */
            align-items: center; /* Menjaga elemen tetap sejajar secara vertikal */
        }

        .header h1 {
            color: white; 
        }

        .header nav ul {
            list-style: none;
            display: flex;
            gap: 15px; /* Jarak antar item navigasi */
        }

        .header nav ul li a {
            text-decoration: none;
            color: white; /* Teks putih untuk navigasi */
            font-weight: 400;
        }

        .header nav ul li a:hover {
            text-decoration: underline; /* Efek hover untuk item navigasi */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .alert {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background-color: #5d4037; /* Warna coklat untuk header */
            color: #fff;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            font-size: 16px;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        tbody tr:hover {
            background-color: #f9dcb1; /* Hover effect */
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #4CAF50; /* Hijau */
        }

        .btn-edit:hover {
            background-color: #45a049;
        }

        .btn-delete {
            background-color: #e74c3c; /* Merah */
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }


                /* Responsif untuk layar tablet dan ponsel */
        @media (max-width: 768px) {
            .container {
                max-width: 100%; /* Lebar penuh untuk layar kecil */
                margin: 20px; /* Margin lebih kecil */
                padding: 15px; /* Padding yang disesuaikan */
            }

            h1 {
                font-size: 20px; /* Ukuran font lebih kecil */
            }

            table {
                font-size: 14px; /* Ukuran font tabel lebih kecil */
                overflow-x: auto; /* Scroll horizontal jika tabel terlalu lebar */
                display: block; /* Membuat tabel dapat digeser jika layar terlalu sempit */
            }

            th, td {
                padding: 8px 10px; /* Padding lebih kecil */
                font-size: 14px; /* Ukuran font disesuaikan */
            }

            .action-buttons {
                flex-direction: column; /* Tombol diatur vertikal */
                gap: 5px; /* Jarak antar tombol lebih kecil */
            }

            .btn {
                font-size: 12px; /* Ukuran font tombol lebih kecil */
                padding: 8px; /* Padding disesuaikan */
                text-align: center; /* Teks di tengah */
            }
        }

        /* Responsif untuk layar ponsel kecil */
        @media (max-width: 480px) {
            h1 {
                font-size: 18px; /* Font lebih kecil lagi untuk ponsel */
            }

            .container {
                padding: 10px; /* Padding lebih kecil */
            }

            table {
                font-size: 12px; /* Ukuran font lebih kecil lagi */
            }

            th, td {
                padding: 6px 8px; /* Padding lebih kecil */
            }

            .btn {
                font-size: 10px; /* Ukuran font tombol untuk ponsel */
                padding: 6px 8px; /* Padding lebih kecil */
            }
        }

    </style>
</head>
<body>

    <header class="header">
        <div class="container1">
            <h1>Coffee <span>Shop</span></h1>
            <nav>
                <ul>
                    <li><a href="index.php" class="btn1">Home</a></li>
                    <li><a href="add.php" class="btn1">Add Coffee</a></li>
                    <li><a href="admin_dashboard.php" class="btn1">Back</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="logout.php" class="btn1">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn1">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1>Kelola Pengguna</h1>

        <!-- Pesan Notifikasi -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-edit">Edit</a>
                                <?php if ($user['role'] !== 'admin'): ?>
                                    <a href="manage_users.php?delete_id=<?= $user['id'] ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

