<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
$client = new MongoDB\Client($uri);
$db = $client->parcelhub;
$collection = $db->account_tbl;


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (!isset($data['name']) || empty(trim($data['name']))) {
        echo json_encode(['success' => false, 'message' => 'Name cannot be empty.']);
        exit();
    }

    $full_name = trim($data['name']);
    $name_parts = explode(' ', $full_name, 2);
    $first_name = $name_parts[0];
    $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

    $updateResult = $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($user_id)],
        ['$set' => ['first_name' => $first_name, 'last_name' => $last_name]]
    );
    if ($updateResult->getModifiedCount() === 1) {
        echo json_encode(['success' => true, 'message' => 'Name updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update name.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
