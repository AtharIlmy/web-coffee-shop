<?php
session_start();
include('../includes/db.php');

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil data pengguna berdasarkan ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID pengguna tidak ditemukan.");
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("Pengguna tidak ditemukan.");
}

// Update data pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];

    $updateStmt = $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
    if ($updateStmt->execute([$username, $role, $id])) {
        $_SESSION['message'] = "Data pengguna berhasil diperbarui.";
        header("Location: manage_users.php");
        exit();
    } else {
        $error = "Gagal memperbarui data pengguna.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            background-color: #f9f9f9;
            transition: all 0.3s;
        }

        input[type="text"]:focus, select:focus {
            border-color: #5d4037;
            background-color: #ffffff;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .error-msg, .success-msg {
            text-align: center;
            font-size: 14px;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .error-msg {
            background-color: #e74c3c;
            color: white;
        }

        .success-msg {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Pengguna</h1>

        <?php if (isset($error)): ?>
            <p class="error-msg"><?= htmlspecialchars($error) ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success-msg"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
