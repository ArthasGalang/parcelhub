<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\Client;

// MongoDB Atlas connection URI
$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";

// Create client
$client = new Client($uri);

// Select database
$db = $client->parcelhub;

// Optional: define commonly used collections (cleaner structure)
$users_tbl = $db->account_tbl;
$shipment_tbl = $db->declare_shipment_order_tbl;
$order_tbl = $db->order_tbl;
$warehouse_tbl = $db->warehouse_addresses_tbl;
?>