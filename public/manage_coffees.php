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
        $image_name = basename($_FILES['image']['name']);
        $image_path = '../uploads/' . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image_path_db = 'uploads/' . $image_name; // Path untuk database
        } else {
            $error = "Gagal mengunggah gambar.";
        }
    } else {
        $image_path_db = 'uploads/default.jpg'; // Path default jika gambar tidak diunggah
    }

    if (empty($error)) {
        $stmt = $pdo->prepare("INSERT INTO coffees (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $image_path_db]);
    }
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
        /* Tambahkan CSS yang relevan */
        .coffee-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<header class="header">
    <div class="container1">
        <h1>Coffee <span>Shop</span></h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="add.php">Add Coffee</a></li>
                <li><a href="admin_dashboard.php">Back</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <!-- Form Tambah Kopi -->
    <div class="form-container">
        <h2>Tambah Menu Kopi</h2>
        <?php if (!empty($error)) echo "<p class='error-msg'>$error</p>"; ?>
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
                <img src="../<?= htmlspecialchars($coffee['image']) ?>" alt="<?= htmlspecialchars($coffee['name']) ?>">
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
