<?php

require_once __DIR__ . '/../vendor/autoload.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !isset($_GET['tracking_no'])) {
    header('Location: ../pages/my-shipment/declared-shipment.php');
    exit();
}

$tracking_no = $_GET['tracking_no'];
$user_id = $_SESSION['user_id'];

$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
$client = new MongoDB\Client($uri);
$db = $client->parcelhub;
$collection = $db->declare_shipment_order_tbl;

$deleteResult = $collection->deleteOne([
    'tracking_no' => $tracking_no,
    'user_id' => $user_id
]);

header('Location: ../pages/my-shipment/declared-shipment.php');
exit();
