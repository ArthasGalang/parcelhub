<?php

if (!isset($_GET['tracking_no'])) {
    header('Location: ../../../pages/declared-shipment.php');
    exit();
}

$tracking_no = $_GET['tracking_no'];
$user_id = $_SESSION['user_id'];

// Fetch shipment and warehouse data from MongoDB
$paidOrdersCollection = $db->paid_orders_tbl;
$shipmentDoc = $paidOrdersCollection->findOne([
    'user_id' => $user_id,
    'tracking_no' => $tracking_no
]);

if (!$shipmentDoc) {
    header('Location: ../../../pages/declared-shipment.php');
    exit();
}

$warehouseCollection = $db->warehouse_addresses_tbl;
$warehouse = $warehouseCollection->findOne(['country' => $shipmentDoc['warehouse']]);

if (!$warehouse) {
    die("Warehouse not found.");
}

$shipment = [
    'tracking_no' => $shipmentDoc['tracking_no'],
    'dateTime' => isset($shipmentDoc['dateTime']) ? $shipmentDoc['dateTime'] : date('Y-m-d H:i:s'),
    'totalPrice' => $shipmentDoc['totalPrice'],
    'total_shipping_fee' => $shipmentDoc['total_shipping_fee'],
    'payment_method' => $shipmentDoc['payment_method'],
    'total_weight_lb' => $shipmentDoc['total_weight_lb'],
    'no_of_order' => $shipmentDoc['no_of_order'],
    'warehouse' => $shipmentDoc['warehouse'],
    'city' => $warehouse['city'],
    'country' => $warehouse['country'],
    'img_path' => $warehouse['img_path'],
    'order_status' => $shipmentDoc['order_status']
];

// Fetch product details from MongoDB
$productCollection = $db->order_tbl;
$products = $productCollection->find(['tracking_no' => $tracking_no])->toArray();

// Currency map
$currency_map = [
    'Canada' => ['CAD', 'C$'],
    'United States' => ['USD', '$'],
    'United Kingdom' => ['GBP', '£'],
    'Japan' => ['JPY', '¥'],
    'South Korea' => ['KRW', '₩'],
    'Australia' => ['AUD', '$'],
    'China' => ['CNY', '¥'],
    'Taiwan' => ['TWD', 'NT$'],
];

// Fetch default delivery address from MongoDB
$addressCollection = $db->deliveryaddress_tbl;
$addressDoc = $addressCollection->findOne([
    'user_id' => $user_id,
    'is_default' => 1
]);

$default_address = null;
if ($addressDoc) {
    $default_address = [
        'full_name' => ($addressDoc['first_name'] ?? '') . ' ' . ($addressDoc['last_name'] ?? ''),
        'address' => $addressDoc['address'] ?? '',
        'mobile_number' => $addressDoc['mobile_number'] ?? ''
    ];
}
