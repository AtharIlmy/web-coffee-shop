<?php
session_start();
include('../includes/db.php');

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil semua pesanan
$orders = $pdo->query("
    SELECT orders.*, users.username, coffees.name AS coffee_name 
    FROM orders 
    JOIN users ON orders.user_id = users.id 
    JOIN coffees ON orders.coffee_id = coffees.id
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pesanan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <style>
        /* Header */
        .admin-header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }

        /* Orders Container */
        .orders-container {
            margin: 20px auto;
            max-width: 1000px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Orders Table */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            text-align: center;
        }

        .orders-table th,
        .orders-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .orders-table th {
            background-color: #333;
            color: white;
            font-weight: bold;
        }

        .orders-table td {
            background-color: #fff;
        }

        .orders-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .orders-table tr:hover {
            background-color: #eaeaea;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .orders-table th,
            .orders-table td {
                font-size: 14px;
                padding: 8px;
            }
        }

    </style>

    <header class="admin-header">
        <h1>Daftar Pesanan</h1>
    </header>
    
    <main class="orders-container">
        <div class="table-container">
            <h2>Semua Pesanan</h2>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Kopi</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['username']) ?></td>
                            <td><?= htmlspecialchars($order['coffee_name']) ?></td>
                            <td><?= $order['quantity'] ?></td>
                            <td>Rp <?= number_format($order['total_price'], 2) ?></td>
                            <td><?= $order['created_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>

