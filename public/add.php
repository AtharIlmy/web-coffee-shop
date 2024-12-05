<?php
session_start();
include('../includes/db.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $imagePath = null;

    // Proses upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $fileName = basename($_FILES['image']['name']);
        $targetPath = $uploadDir . time() . "_" . $fileName;

        // Validasi file gambar (opsional)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $error = "File harus berupa gambar (JPG, PNG, GIF)";
        } elseif (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $error = "Gagal mengunggah gambar!";
        } else {
            $imagePath = str_replace('../', '', $targetPath); // Simpan path relatif
        }
    }

    // Jika tidak ada error, simpan data ke database
    if (empty($error)) {
        $stmt = $pdo->prepare("INSERT INTO coffees (name, description, price, image) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $description, $price, $imagePath])) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Gagal menambahkan kopi!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kopi - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

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

        /* Gaya umum untuk form */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 400px;
            text-align: center;
        }

        .form-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group input[type="file"] {
            padding: 3px;
        }

        .form-group textarea {
            resize: none;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .error-msg {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

    </style>

    <div class="form-container">
        <h1>Tambah Menu Kopi</h1>
        <?php if (!empty($error)) echo "<p class='error-msg'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data" class="form-box">
            <div class="form-group">
                <label for="name">Nama Kopi</label>
                <input type="text" name="name" id="name" placeholder="Masukkan nama kopi..." required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" rows="4" placeholder="Masukkan deskripsi kopi..." required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Harga</label>
                <input type="number" step="0.01" name="price" id="price" placeholder="Masukkan harga kopi..." required>
            </div>

            <div class="form-group">
                <label for="image">Gambar</label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>

            <button type="submit" class="btn-submit">Tambah Kopi</button>
        </form>
    </div>
</body>
</html>
