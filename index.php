<?php
require 'config.php';

// Get all phones
$stmt = $pdo->query("SELECT * FROM phones ORDER BY created_at DESC");
$phones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cell Phone Store</title>
    <style>
        .phone-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .phone-card {
            border: 1px solid #ddd;
            padding: 15px;
            width: 250px;
        }
        .phone-card img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Welcome to Our Cell Phone Store</h1>
    
    <div class="phone-list">
        <?php foreach ($phones as $phone): ?>
        <div class="phone-card">
            <?php if ($phone['image_url']): ?>
                <img src="<?= htmlspecialchars($phone['image_url']) ?>" alt="<?= htmlspecialchars($phone['model']) ?>">
            <?php endif; ?>
            <h3><?= htmlspecialchars($phone['brand']) ?> <?= htmlspecialchars($phone['model']) ?></h3>
            <p><?= htmlspecialchars($phone['description']) ?></p>
            <p><strong>Price: $<?= number_format($phone['price'], 2) ?></strong></p>
        </div>
        <?php endforeach; ?>
    </div>
    
    <p><a href="admin_login.php">Admin Login</a></p>
</body>
</html>
