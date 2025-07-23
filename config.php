<?php
// Database configuration
define('DB_HOST', 'database-1.cwdkc2q88d2r.us-east-1.rds.amazonaws.com');
define('DB_PORT', '5432');
define('DB_NAME', 'dynamic1');
define('DB_USER', 'postgres');
define('DB_PASS', 'Satyasai17');

// AWS S3 configuration
define('AWS_REGION', 'us-east-1');
define('S3_BUCKET', 'onlineapplications3 ');

// Initialize AWS SDK
require 'vendor/autoload.php';
use Aws\S3\S3Client;

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => AWS_REGION,
]);

// Database connection
try {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper function to upload images to S3
function uploadToS3($file, $s3) {
    $key = 'images/' . uniqid() . '_' . basename($file['name']);
    
    try {
        $result = $s3->putObject([
            'Bucket' => S3_BUCKET,
            'Key'    => $key,
            'SourceFile' => $file['tmp_name'],
        ]);
        
        return $result->get('ObjectURL');
    } catch (Aws\S3\Exception\S3Exception $e) {
        error_log("S3 Upload Error: " . $e->getMessage());
        return false;
    }
}
?>
