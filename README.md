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

------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
sudo dnf update -y
sudo dnf install -y httpd php php-pgsql php-mbstring php-xml php-cli php-json unzip git curl

sudo dnf install -y httpd
sudo systemctl start httpd
sudo systemctl enable httpd

curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
unzip awscliv2.zip
sudo ./aws/install

aws sts get-caller-identity 

sudo chown -R apache:apache /var/www/html
sudo chmod 2775 /var/www/html
find /var/www/html -type d -exec sudo chmod 2775 {} \;
find /var/www/html -type f -exec sudo chmod 0664 {} \;

cd /var/www/html
sudo dnf install -y composer

composer require aws/aws-sdk-php

sudo chown -R ec2-user:ec2-user /var/www/html
cd /var/www/html
composer require aws/aws-sdk-php

sudo dnf install php-pgsql


sudo chown -R apache:apache /var/www/html
sudo chmod -R 755 /var/www/html

sudo systemctl restart httpd

---------–_----------------------------------------------------------++++++----------------
--(setup_db.php)
<?php
require 'config.php';

try {
    // Create phones table
    $sql = "CREATE TABLE IF NOT EXISTS phones (
        id SERIAL PRIMARY KEY,
        model VARCHAR(100) NOT NULL,
        brand VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        specs TEXT,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    
    // Create admin users table
    $sql = "CREATE TABLE IF NOT EXISTS admin_users (
        id SERIAL PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    
    echo "Database tables created successfully!";
    
} catch (PDOException $e) {
    die("Error creating tables: " . $e->getMessage());
}
?>


php setup_db.php
