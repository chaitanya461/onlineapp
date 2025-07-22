<?php
require 'config.php';

// Get all phones with secure error handling
try {
    $stmt = $pdo->query("SELECT id, brand, model, description, price, image_url, created_at FROM phones ORDER BY created_at DESC");
    $phones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $phones = []; // Fallback to empty array
    $error_message = "We're experiencing technical difficulties. Please try again later.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Cell Phones | TechStore</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --error: #dc2626;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #64748b;
            --gray-light: #e2e8f0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        body {
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        header {
            text-align: center;
            margin-bottom: 3rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-light);
        }
        
        h1 {
            font-size: 2.25rem;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            color: var(--gray);
            font-size: 1.1rem;
        }
        
        .error-alert {
            background-color: #fee2e2;
            color: var(--error);
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .phone-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .phone-card {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        
        .phone-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .phone-image-container {
            height: 200px;
            background: var(--gray-light);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .phone-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .phone-details {
            padding: 1.25rem;
        }
        
        .phone-brand {
            color: var(--primary);
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }
        
        .phone-model {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .phone-description {
            color: var(--gray);
            font-size: 0.875rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .phone-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-dark);
        }
        
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .empty-state h2 {
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: var(--gray);
        }
        
        .admin-actions {
            text-align: center;
            margin-top: 2rem;
        }
        
        .admin-link {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: var(--dark);
            color: white;
            text-decoration: none;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: background 0.2s ease;
        }
        
        .admin-link:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Premium Cell Phones</h1>
            <p class="subtitle">Find your perfect device at competitive prices</p>
        </header>
        
        <?php if (isset($error_message)): ?>
            <div class="error-alert">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>
        
        <div class="phone-grid">
            <?php if (empty($phones)): ?>
                <div class="empty-state">
                    <h2>No Phones Available</h2>
                    <p>Our inventory is currently empty. Please check back soon!</p>
                </div>
            <?php else: ?>
                <?php foreach ($phones as $phone): ?>
                    <div class="phone-card">
                        <div class="phone-image-container">
                            <img src="<?= !empty($phone['image_url']) ? htmlspecialchars($phone['image_url']) : 'https://via.placeholder.com/300x200.png?text=No+Image' ?>" 
                                 alt="<?= htmlspecialchars($phone['brand']) ?> <?= htmlspecialchars($phone['model']) ?>" 
                                 class="phone-image">
                        </div>
                        <div class="phone-details">
                            <div class="phone-brand"><?= htmlspecialchars($phone['brand']) ?></div>
                            <h3 class="phone-model"><?= htmlspecialchars($phone['model']) ?></h3>
                            <p class="phone-description"><?= htmlspecialchars($phone['description']) ?></p>
                            <div class="phone-price">$<?= number_format($phone['price'], 2) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="admin-actions">
            <a href="admin_login.php" class="admin-link">Admin Portal</a>
        </div>
    </div>
</body>
</html>
