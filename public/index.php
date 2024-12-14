<?php 
include '../includes/db.php';
session_start();
$coffees = $pdo->query("SELECT * FROM coffees")->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <style>
        #menu-icon {
            position: relative;
            font-size: 3.6rem;
            color: var(--text-color);
            cursor: pointer;
            display: none;
        }

        body {
            font-family: Arial, sans-serif;
            background: #000;  /* Latar belakang putih untuk halaman */
        }

        /* Hero Section */
        .hero {
            background: white;
            text-align: center;
            padding: 4rem 0;
            color: white;
        }

        .hero-text {
            color: #000;
        }

        .hero h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
        }

        /* Featured Coffee Section */
        .featured-section {
            padding: 2rem 0;
            text-align: center;
        }

        .featured-section h3 {
            margin-bottom: 2rem;
            font-size: 2rem;
            color: #333;
        }

        .coffee-list {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .coffee-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .coffee-item:hover {
            transform: scale(1.05);
        }

        .coffee-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .coffee-info {
            padding: 1rem;
        }

        .coffee-info h4 {
            margin: 0.5rem 0;
            font-size: 1.2rem;
        }

        .coffee-info p {
            margin: 0.5rem 0;
            color: #555;
        }

        .coffee-info .price {
            font-size: 1.1rem;
            color: #e58e26;
            font-weight: bold;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            text-align: center;
            color: #fff;
            cursor: pointer;
        }

        .btn-buy {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .btn-buy:hover {
            background: #e58e26;
        }

        /* Footer */
        footer {
            background: #000;
            color: #333;
            text-align: center;
            padding: 1rem 0;
        }
    </style>

<header class="header">
    <div class="container1">
        <h1>Coffee <span>Shop</span></h1>

        <div class='bx bx-menu' id="menu-icon"></div>


        <nav>
            <ul>
                <li><a href="index.php" class="btn1">Home</a></li>
                <li><a href="add.php" class="btn1">Add Coffee</a></li>
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
        <h2>Welcome to Coffee Shop</h2>
        <p>Discover the best coffee in town, brewed to perfection just for you.</p>
    </div>
</div>

<div class="container">
    <section class="featured-section">
        <h3>Featured Coffee</h3>
        <div class="coffee-list">
            <?php if (count($coffees) > 0): ?>
                <?php foreach ($coffees as $coffee): ?>
                    <div class="coffee-item">
                        <img src="../<?= htmlspecialchars($coffee['image']) ?>" alt="<?= htmlspecialchars($coffee['name']) ?>" class="coffee-img">
                        <div class="coffee-info">
                            <h4><?= htmlspecialchars($coffee['name']) ?></h4>
                            <p><?= htmlspecialchars($coffee['description']) ?></p>
                            <p class="price">Rp <?= number_format($coffee['price'], 2) ?></p>
                            <a href="./buy.php?id=<?= $coffee['id'] ?>" class="btn btn-buy">Buy Now</a>
                            <a href="./edit.php?id=<?= $coffee['id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="./delete.php?id=<?= $coffee['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this coffee?')">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No coffee available.</p>
            <?php endif; ?>
        </div>
    </section>
</div>
<footer>
    <div class="container">
        <p>&copy; 2024 Coffee Shop. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>


