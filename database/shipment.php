<?php
include('database.php');
$user_id = $_SESSION['user_id'];

// Query for un-declared shipments
$collection = $db->declare_shipment_order_tbl;
$total_shipments = $collection->countDocuments([
    'user_id' => $user_id,
    'declared_shipment' => 1,
    'order_status' => ['$ne' => 'Waiting for payment']
]);

// Query for declared shipments with unconfirmed payment
$total_declared_shipment = $collection->countDocuments([
    'user_id' => $user_id,
    'declared_shipment' => 1,
    'confirmed_payment' => 0,
    'order_status' => ['$ne' => 'Waiting for payment']
]);

// Query for confirmed payments waiting for payment
$total_confirmed_payment = $collection->countDocuments([
    'user_id' => $user_id,
    'confirmed_payment' => 0,
    'order_status' => 'Waiting for payment'
]);

// Query for shipping orders
$paidCollection = $db->paid_orders_tbl;
$total_shipping_order = $paidCollection->countDocuments([
    'user_id' => $user_id,
    'order_status' => ['$in' => ['Processing', 'Shipped']]
]);
