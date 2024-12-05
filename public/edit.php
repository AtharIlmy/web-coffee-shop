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

// Ambil data kopi dari database
$stmt = $pdo->prepare("SELECT * FROM coffees WHERE id = ?");
$stmt->execute([$id]);
$coffee = $stmt->fetch();

if (!$coffee) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'] ?? null;

    // Proses upload gambar jika ada
    if ($image) {
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

        $stmt = $pdo->prepare("UPDATE coffees SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
        $success = $stmt->execute([$name, $description, $price, $image, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE coffees SET name = ?, description = ?, price = ? WHERE id = ?");
        $success = $stmt->execute([$name, $description, $price, $id]);
    }

    if ($success) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal mengedit kopi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Kopi - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Edit Menu Kopi</h1>
        <?php if (!empty($error)) echo "<p class='error-msg'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data" class="form-box">
            <div class="form-group">
                <label for="name">Nama Kopi</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($coffee['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" required><?= htmlspecialchars($coffee['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Harga</label>
                <input type="number" step="0.01" name="price" id="price" value="<?= $coffee['price'] ?>" required>
            </div>

            <div class="form-group">
                <label for="image">Gambar</label>
                <input type="file" name="image" id="image">
                <?php if (!empty($coffee['image'])): ?>
                    <p>Gambar saat ini:</p>
                    <img src="../uploads/<?= htmlspecialchars($coffee['image']) ?>" alt="Gambar Kopi" width="150">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
