<?php


$user_id = $_SESSION['user_id'];
require_once __DIR__ . '/../vendor/autoload.php';
$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
$client = new MongoDB\Client($uri);
$db = $client->parcelhub;
$collection = $db->deliveryaddress_tbl;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_address_id'])) {
        $delete_id = $_POST['delete_address_id'];
        $collection->deleteOne([
            '_id' => new MongoDB\BSON\ObjectId($delete_id),
            'user_id' => $user_id
        ]);
    } elseif (isset($_POST['default_address_id'])) {
        $default_id = $_POST['default_address_id'];
        // Reset all to not default
        $collection->updateMany(['user_id' => $user_id], ['$set' => ['is_default' => 0]]);
        // Set selected as default
        $collection->updateOne([
            '_id' => new MongoDB\BSON\ObjectId($default_id),
            'user_id' => $user_id
        ], ['$set' => ['is_default' => 1]]);
    } elseif (isset($_POST['update_address_id'])) {
        $update_id = $_POST['update_address_id'];
        $is_default = $_POST['is_default'];
        if ($is_default) {
            $collection->updateMany(['user_id' => $user_id], ['$set' => ['is_default' => 0]]);
        }
        $collection->updateOne([
            '_id' => new MongoDB\BSON\ObjectId($update_id),
            'user_id' => $user_id
        ], ['$set' => ['is_default' => (int)$is_default]]);
    }
}

// Fetch addresses for user
$addresses = [];
$cursor = $collection->find(['user_id' => $user_id]);
foreach ($cursor as $doc) {
    $addresses[] = [
        'id' => (string)$doc['_id'],
        'full_name' => $doc['first_name'] . ' ' . $doc['last_name'],
        'address' => $doc['address'],
        'mobile_number' => $doc['mobile_number'],
        'zip_code' => $doc['zip_code'],
        'is_default' => isset($doc['is_default']) ? $doc['is_default'] : 0
    ];
}
