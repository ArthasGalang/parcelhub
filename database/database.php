<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';
$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
$client = new MongoDB\Client($uri);
$db = $client->parcelhub;
$collection = $db->account_tbl;

// Fetch user details only when necessary
$user_id = $_SESSION['user_id'];
$user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($user_id)]);
if ($user) {
    $member_since = '';
    if (isset($user['created_at']) && $user['created_at'] instanceof MongoDB\BSON\UTCDateTime) {
        $dt = $user['created_at']->toDateTime();
        $member_since = $dt->format('F Y');
    } else {
        $member_since = 'Unknown';
    }
    $user = [
        'member_id' => $user['member_id'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'email' => $user['email'],
        'member_since' => $member_since,
        'first_letter' => strtoupper(substr($user['first_name'], 0, 1))
    ];
}
