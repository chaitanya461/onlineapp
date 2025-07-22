<?php
require 'config.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

$id = $_GET['id'] ?? 0;
$phone = null;

// Fetch phone details
$stmt = $pdo->prepare("SELECT * FROM phones WHERE id = ?");
$stmt->execute([$id]);
$phone = $stmt->fetch();

if (!$phone) {
    header('Location: admin_dashboard.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_phone'])) {
    $model = $_POST['model'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    
    $image_url = $phone['image_url'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_url = uploadToS3($_FILES['image'], $s3);
    }
    
    $stmt = $pdo->prepare("UPDATE phones SET model = ?, brand = ?, description = ?, price = ?, image_url = ? WHERE id = ?");
    $stmt->execute([$model, $brand, $description, $price, $image_url, $id]);
    
    header('Location: admin_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Phone</title>
</head>
<body>
    <h1>Edit Phone</h1>
    
    <form method="POST" enctype="multipart/form-data">
        <div>
            <label>Model:</label>
            <input type="text" name="model" value="<?= htmlspecialchars($phone['model']) ?>" required>
        </div>
        <div>
            <label>Brand:</label>
            <input type="text" name="brand" value="<?= htmlspecialchars($phone['brand']) ?>" required>
        </div>
        <div>
            <label>Description:</label>
            <textarea name="description"><?= htmlspecialchars($phone['description']) ?></textarea>
        </div>
        <div>
            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($phone['price']) ?>" required>
        </div>
        <div>
            <label>Current Image:</label>
            <?php if ($phone['image_url']): ?>
                <img src="<?= htmlspecialchars($phone['image_url']) ?>" width="100">
            <?php endif; ?>
        </div>
        <div>
            <label>New Image (leave blank to keep current):</label>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" name="update_phone">Update Phone</button>
    </form>
    
    <p><a href="admin_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
