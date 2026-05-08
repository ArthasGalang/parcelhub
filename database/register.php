<?php
session_start();


require_once __DIR__ . '/../vendor/autoload.php';

$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
$client = new MongoDB\Client($uri);
$db = $client->parcelhub; // Use your database name
$collection = $db->account_tbl; // Collection for user accounts


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: ../pages/register.php");
        exit();
    }
    $member_id = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $existingUser = $collection->findOne(['email' => $email]);
    if ($existingUser) {
        $_SESSION['error'] = "Email already exists.";
        header("Location: ../pages/register.php");
        exit();
    }

    // Insert new user with created_at field
    $insertResult = $collection->insertOne([
        'member_id' => $member_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'password' => $hashed_password,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    if ($insertResult->getInsertedCount() === 1) {
        $_SESSION['success'] = "Registration successful! Your Member ID is: $member_id";
        header("Location: ../pages/login.php");
    } else {
        $_SESSION['error'] = "Error registering account.";
        header("Location: ../pages/register.php");
    }
}
