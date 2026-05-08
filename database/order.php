<?php
include('database.php');
$user_id = $_SESSION['user_id'];

$collection = $db->paid_orders_tbl;

$total_delivery_order = $collection->countDocuments([
    'user_id' => $user_id,
    'order_status' => 'Out for delivery'
]);

$total_delivered_order = $collection->countDocuments([
    'user_id' => $user_id,
    'order_status' => 'Delivered'
]);
