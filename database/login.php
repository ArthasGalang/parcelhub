<?php
session_start();


require_once __DIR__ . '/../vendor/autoload.php';
$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
$client = new MongoDB\Client($uri);
$db = $client->parcelhub;
$collection = $db->account_tbl;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $remember_me = isset($_POST['remember_me']) ? true : false;

    $user = $collection->findOne(['email' => $email]);
    if ($user) {
        $hashed_password = $user['password'];
        if (password_verify($password, $hashed_password)) {
            // Use MongoDB's _id as user_id for session
            $_SESSION['user_id'] = (string)$user['_id'];

            if ($remember_me) {
                setcookie('user_email', $email, time() + (86400 * 7), "/"); 
            } else {
                setcookie('user_email', '', time() - 3600, "/"); 
            }
            header("Location: ../pages/dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Invalid password.";
            header("Location: ../pages/login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "No account found with that email.";
        header("Location: ../pages/login.php");
        exit;
    }
}
