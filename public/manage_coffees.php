<?php
session_start();
include('../includes/db.php');

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Tambah menu kopi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_coffee'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Upload gambar
    if (!empty($_FILES['image']['name'])) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../' . $image_path);
    } else {
        $image_path = 'default.jpg';
    }

    $stmt = $pdo->prepare("INSERT INTO coffees (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $image_path]);
}

// Hapus menu kopi
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM coffees WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
}

// Ambil semua menu kopi
$coffees = $pdo->query("SELECT * FROM coffees")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu Kopi</title>
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
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        /* Header */
        .hero {
            background: white;
            text-align: center;
            padding: 4rem 0;
            color: white;
        }

        .hero-text {
            color: #000;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        /* Main Container */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
        }

        /* Form Section */
        .form-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-container h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-submit {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        /* Coffee List */
        .coffee-list {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .coffee-list h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .coffee-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            background: #f9f9f9;
        }

        .coffee-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }

        .coffee-card-content {
            flex-grow: 1;
        }

        .coffee-card-content h3 {
            margin: 0;
            font-size: 1.2rem;
            color: #333;
        }

        .coffee-card-content p {
            margin: 5px 0;
            color: #555;
        }

        .coffee-card-content .price {
            font-weight: bold;
            color: #28a745;
        }

        .coffee-card-actions {
            display: flex;
            gap: 10px;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

                /* Responsif untuk tombol delete */
        @media (max-width: 768px) {
            .btn-delete {
                padding: 10px 15px; /* Membesarkan padding untuk area klik lebih besar */
                font-size: 14px; /* Ukuran font lebih besar agar mudah terbaca */
                width: 100%; /* Tombol menjadi penuh pada layar kecil */
                text-align: center; /* Teks tombol rata tengah */
            }
        }

        @media (max-width: 480px) {
            .btn-delete {
                padding: 12px 20px; /* Padding lebih besar untuk ponsel */
                font-size: 12px; /* Ukuran font yang pas untuk layar kecil */
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


<div class="hero">
    <div class="hero-text">
        <h1>Kelola Kopi</h1>
    </div>
</div>

<div class="container">
    <!-- Form Tambah Kopi -->
    <div class="form-container">
        <h2>Tambah Menu Kopi</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nama Kopi</label>
                <input type="text" name="name" id="name" placeholder="Nama Kopi" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi Kopi</label>
                <textarea name="description" id="description" placeholder="Deskripsi Kopi" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Harga Kopi</label>
                <input type="number" name="price" id="price" placeholder="Harga Kopi" required>
            </div>
            <div class="form-group">
                <label for="image">Gambar Kopi</label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>
            <button type="submit" name="add_coffee" class="btn-submit">Tambah Product</button>
        </form>
    </div>

    <!-- List Kopi -->
    <div class="coffee-list">
        <h2>Daftar Menu Kopi</h2>
        <?php foreach ($coffees as $coffee): ?>
            <div class="coffee-card">
                <img src="../<?= $coffee['image'] ?>" alt="<?= htmlspecialchars($coffee['name']) ?>">
                <div class="coffee-card-content">
                    <h3><?= htmlspecialchars($coffee['name']) ?></h3>
                    <p><?= htmlspecialchars($coffee['description']) ?></p>
                    <p class="price">Rp <?= number_format($coffee['price'], 2) ?></p>
                </div>
                <div class="coffee-card-actions">
                    <a href="manage_coffees.php?delete_id=<?= $coffee['id'] ?>" class="btn-delete">Hapus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
