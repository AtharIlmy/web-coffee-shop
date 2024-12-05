<?php
$host = 'localhost';
$db = 'coffee_shop2';
$users = 'root'; // Ganti jika berbeda
$pass = ''; // Ganti jika berbeda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $users, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
