<?php
session_start();
include('../includes/db.php');

// Hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password, $role])) {
        $success = "Pengguna berhasil didaftarkan.";
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
    <title>Register User/Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px 30px;
        }

        h1 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #555555;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        select:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .btn-submit {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: #45a049;
        }

        .success-msg {
            color: #28a745;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .error-msg {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>

    <div class="form-container">
        <h1>Register User/Admin</h1>
        <?php if (!empty($success)) echo "<p class='success-msg'>$success</p>"; ?>
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

            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Daftar</button>
        </form>
    </div>
</body>
</html>
