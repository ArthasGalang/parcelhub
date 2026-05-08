<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
$client = new MongoDB\Client($uri);
$db = $client->parcelhub;
$collection = $db->deliveryaddress_tbl;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];

    // Collect POST data (no need for real_escape_string with MongoDB)
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $mobile_number = trim($_POST['mobile_number']);
    $address = trim($_POST['address']);
    $zip_code = trim($_POST['zip_code']);

    $insertResult = $collection->insertOne([
        'user_id' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'mobile_number' => $mobile_number,
        'address' => $address,
        'zip_code' => $zip_code
    ]);

    if ($insertResult->getInsertedCount() === 1) {
        header("Location: ../pages/delivery.php");
        exit;
    } else {
        echo "Error: Failed to add address.";
    }
}
