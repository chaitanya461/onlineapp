<?php
require 'config.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle phone addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_phone'])) {
    $model = $_POST['model'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_url = uploadToS3($_FILES['image'], $s3);
    }
    
    $stmt = $pdo->prepare("INSERT INTO phones (model, brand, description, price, image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$model, $brand, $description, $price, $image_url]);
    
    $message = "Phone added successfully!";
}

// Get all phones
$stmt = $pdo->query("SELECT * FROM phones ORDER BY created_at DESC");
$phones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    
    <?php if (isset($message)): ?>
        <p style="color: green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    
    <h2>Add New Phone</h2>
    <form method="POST" enctype="multipart/form-data">
        <div>
            <label>Model:</label>
            <input type="text" name="model" required>
        </div>
        <div>
            <label>Brand:</label>
            <input type="text" name="brand" required>
        </div>
        <div>
            <label>Description:</label>
            <textarea name="description"></textarea>
        </div>
        <div>
            <label>Price:</label>
            <input type="number" step="0.01" name="price" required>
        </div>
        <div>
            <label>Image:</label>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" name="add_phone">Add Phone</button>
    </form>
    
    <h2>Phone List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($phones as $phone): ?>
        <tr>
            <td><?= htmlspecialchars($phone['id']) ?></td>
            <td>
                <?php if ($phone['image_url']): ?>
                    <img src="<?= htmlspecialchars($phone['image_url']) ?>" width="100">
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($phone['brand']) ?></td>
            <td><?= htmlspecialchars($phone['model']) ?></td>
            <td>$<?= number_format($phone['price'], 2) ?></td>
            <td>
                <a href="edit_phone.php?id=<?= $phone['id'] ?>">Edit</a> |
                <a href="delete_phone.php?id=<?= $phone['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
