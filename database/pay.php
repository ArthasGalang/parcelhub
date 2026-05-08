<?php

require_once __DIR__ . '/../vendor/autoload.php';
$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
$client = new MongoDB\Client($uri);
$db = $client->parcelhub;

if (!isset($_GET['tracking_no'])) {
    header('Location: ../../../pages/declared-shipment.php');
    exit();
}

$tracking_no = $_GET['tracking_no'];
$user_id = $_SESSION['user_id'];

// Fetch declared shipment and warehouse data from MongoDB
$declareCollection = $db->declare_shipment_order_tbl;
$shipmentDoc = $declareCollection->findOne([
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
    'timestamp' => isset($shipmentDoc['timestamp']) ? $shipmentDoc['timestamp'] : date('Y-m-d H:i:s'),
    'totalPrice' => $shipmentDoc['totalPrice'],
    'total_weight_lb' => $shipmentDoc['total_weight_lb'] ?? 0,
    'no_of_order' => $shipmentDoc['no_of_order'] ?? 0,
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

$fee_per_lb = 5;
$total_weight_lb = $shipment['total_weight_lb'];
$rounded_weight = ceil($total_weight_lb);
$total_shipping_fee = $rounded_weight * $fee_per_lb;
$order_status = 'Processing';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['paymentMethod'] ?? '';
    
    // Delete from declare_shipment_order_tbl
    $deleteResult = $declareCollection->deleteOne([
        'user_id' => $user_id,
        'tracking_no' => $tracking_no
    ]);
    
    if ($deleteResult->getDeletedCount() > 0) {
        // Generate a new order_id in the same style as existing paid_orders_tbl entries
        $lastPaidOrder = $db->paid_orders_tbl->findOne([], [
            'sort' => ['order_id' => -1],
            'projection' => ['order_id' => 1]
        ]);
        $nextOrderId = ($lastPaidOrder['order_id'] ?? 0) + 1;

        // Insert into paid_orders_tbl with exact expected field names
        $paidOrdersCollection = $db->paid_orders_tbl;
        
        $paidOrdersCollection->insertOne([
            'order_id' => $nextOrderId,
            'user_id' => $user_id,
            'tracking_no' => $tracking_no,
            'total_weight_lb' => floatval($total_weight_lb),
            'no_of_order' => intval($shipment['no_of_order']),
            'totalPrice' => floatval($shipment['totalPrice']),
            'total_shipping_fee' => floatval($total_shipping_fee),
            'payment_method' => $payment_method,
            'base_shipping_fee_per_lb' => floatval($fee_per_lb),
            'name' => $default_address['full_name'] ?? '',
            'address' => $default_address['address'] ?? '',
            'mobile_no' => $default_address['mobile_number'] ?? '',
            'order_status' => $order_status,
            'dateTime' => date('Y-m-d H:i:s'),
            'warehouse' => $shipment['warehouse'],
        ]);
        
        header('Location: ../shipment.php');
        exit();
    } else {
        die("Failed to process payment: Order not found.");
    }
}
