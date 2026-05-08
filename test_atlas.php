<?php
require_once __DIR__ . '/vendor/autoload.php';
$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
try {
    $client = new MongoDB\Client($uri);
    echo "✓ Connected to MongoDB Atlas\n";
} catch (Exception $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
}
?>