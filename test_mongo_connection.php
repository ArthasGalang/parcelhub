<?php
require 'vendor/autoload.php'; // Composer autoload

try {
    $uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
    $client = new MongoDB\Client($uri);
    // Optionally select a database:
    // $db = $client->selectDatabase('parcelhub');
    echo "Connected successfully to MongoDB Atlas!";
} catch (Exception $e) {
    echo "MongoDB connection failed: " . $e->getMessage();
}
?>