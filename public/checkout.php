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
        #coffee_image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

        .coffee-image {
            max-width: 200px; /* Atur ukuran gambar sesuai keinginan */
            display: block;
            margin: 0 auto; /* Centering gambar */
        }
    </style>

    <header class="header">
        <div class="container">
            <h1>Coffee <span>Shop</span></h1>
            <nav>
                <ul>
                    <li><a href="index.php" class="btn1">Home</a></li>
                    <li><a href="add.php" class="btn1">Add Coffee</a></li>
                    <li><a href="logout.php" class="btn1">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container checkout-container">
        <h2>Checkout</h2>

        <!-- Display the coffee details in the form -->
        <form method="POST" class="checkout-form">
            <div class="form-group">
                <label for="coffee_name">Nama Kopi</label>
                <input type="text" id="coffee_name" value="<?= htmlspecialchars($coffee['name']) ?>" disabled>
            </div>
            <div class="form-group">
                <label for="price">Harga (per kopi)</label>
                <input type="text" id="price" value="<?= number_format($coffee['price'], 2) ?>" disabled>
            </div>
            <div class="form-group">
                <label for="quantity">Jumlah</label>
                <input type="number" name="quantity" id="quantity" min="1" value="1" required>
            </div>

            <!-- Display the coffee image -->
            <div class="form-group">
                <label for="coffee_image">Gambar Kopi</label>
                <div id="coffee_image">
                    <!-- Check if the image path is valid and display it -->
                    <img src="../assets/images/<?= htmlspecialchars($coffee['image']) ?>" alt="<?= htmlspecialchars($coffee['name']) ?>" class="coffee-image">
                </div>
            </div>

            <?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

            <div class="form-group">
                <button type="submit" class="btn-submit">Bayar</button>
            </div>
        </form>
    </div>

</body>
</html>

