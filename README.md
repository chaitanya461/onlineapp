CREATE TABLE phones (
    id SERIAL PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
cd /var/www/html
cat > create_admin.php << 'EOL'
<?php
require 'config.php';
$username = "admin1";
$password = "1234567890";
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
$stmt->execute([$username, $hash]);
echo "Admin user created successfully\n";
?>
EOL

php create_admin.php
rm create_admin.php  # Remove the file after use for security
