<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Tambahkan pengguna baru dengan role default 'user'
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
    if ($stmt->execute([$username, $password])) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Pendaftaran gagal, coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Register</h1>
        <?php if (!empty($error)) echo "<p class='error-msg'>$error</p>"; ?>
        <form method="POST" class="form-box">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Masukkan username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn-submit">Daftar</button>
        </form>
    </div>
</body>
</html>
