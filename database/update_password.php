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

// Check for POST request with JSON data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Validate input
    if (!isset($data['password']) || empty(trim($data['password']))) {
        echo json_encode(['success' => false, 'message' => 'Password cannot be empty.']);
        exit();
    }

    $new_password = password_hash(trim($data['password']), PASSWORD_BCRYPT); // Hash the password for security

    $updateResult = $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($user_id)],
        ['$set' => ['password' => $new_password]]
    );
    if ($updateResult->getModifiedCount() === 1) {
        echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

