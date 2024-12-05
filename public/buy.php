<?php
session_start();
include('../includes/db.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Periksa apakah ID kopi ada di URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit();
}

// Ambil data kopi berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM coffees WHERE id = ?");
$stmt->execute([$id]);
$coffee = $stmt->fetch();

if (!$coffee) {
    header("Location: index.php");
    exit();
}

// Proses jika form pembayaran disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil ID pengguna dari session
    $user_id = $_SESSION['user_id'];
    $quantity = $_POST['quantity'];
    $total_price = $coffee['price'] * $quantity;

    // Menambahkan order ke database
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, coffee_id, quantity, total_price) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $coffee['id'], $quantity, $total_price])) {
        header("Location: order_success.php");  // Redirect ke halaman sukses setelah pembayaran
        exit();
    } else {
        $error = "Gagal memproses pembayaran.";  // Jika gagal, tampilkan error
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <style>

        .header {
            background-color: #423434; /* Warna latar belakang header */
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


        .coffee-details {
            text-align: center;
            margin-bottom: 20px;
        }

        .coffee-image {
            max-width: 300px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        /* Gaya untuk halaman Checkout */
        .coffee-details {
            text-align: center;
            margin-bottom: 20px;
        }

        .coffee-image {
            max-width: 300px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .container h2 {
            font-size: 2rem;
        }

        form {
            margin-top: 20px;
        }

        form label {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        div input[type="text"] {
            padding: 10px;
            width: 50%;
            margin: 10px auto;
            display: block;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        form input[type="number"] {
            padding: 10px;
            width: 50%;
            margin: 10px auto;
            display: block;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        form .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            width: 50%;
        }

        form .btn-submit:hover {
            background-color: #45a049;
        }


    </style>

    <header class="header">
        <div class="container1">
            <h1>Coffee Shop</h1>
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
        <h2>Checkout</h2>
        <div class="coffee-details">
            <!-- Menampilkan gambar kopi -->
            <img src="../<?= htmlspecialchars($coffee['image']) ?>" alt="<?= htmlspecialchars($coffee['name']) ?>" class="coffee-image">
            <!-- Menampilkan informasi kopi -->
            <div class="form-group">
                <label for="coffee_name">Nama Kopi</label>
                <input type="text" id="coffee_name" value="<?= htmlspecialchars($coffee['name']) ?>" disabled>
            </div>
            <div class="form-group">
                <label for="price">Harga (per kopi)</label>
                <input type="text" id="price" value="<?= number_format($coffee['price'], 2) ?>" disabled>
            </div>

        </div>
        
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST">
            <center><label for="quantity">Jumlah:</label></center>
            <center><input type="number" name="quantity" id="quantity" min="1" value="1" required></center>

            <center><button type="submit" class="btn-submit">Bayar</button></center>
        </form>
    </div>

</body>
</html>
