<?php
session_start();
include('database.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (
        empty($data['warehouse']) ||
        empty($data['trackNumber']) ||
        !isset($data['termsAccepted']) ||
        empty($data['products'])
    ) {
        echo json_encode(['success' => false, 'message' => 'Incomplete shipment data.']);
        exit();
    }

    // Extract shipment data
    $warehouse = $data['warehouse'];
    $trackNumber = $data['trackNumber'];
    $declareShipment = false;
    $itemCount = $data['itemCount'];
    $totalValue = $data['totalValue'];
    $products = $data['products'];
    $order_status = 'Pending';
    $confirmed_payment = false;

    try {
        // Start a MongoDB session for transaction
        $session = $client->startSession();
        $session->startTransaction();

        $shipmentCollection = $db->declare_shipment_order_tbl;
        $shipmentDoc = [
            '_id' => new MongoDB\BSON\ObjectId(),
            'user_id' => $user_id,
            'tracking_no' => $trackNumber,
            'total_weight_lb' => 0,
            'declared_shipment' => 0,
            'no_of_order' => $itemCount,
            'totalPrice' => $totalValue,
            'warehouse' => $warehouse,
            'order_status' => $order_status,
            'timestamp' => date('Y-m-d H:i:s', (new MongoDB\BSON\UTCDateTime())->toDateTime()->getTimeStamp()),
            'confirmed_payment' => 0,
        ];
        $result = $shipmentCollection->insertOne($shipmentDoc, ['session' => $session]);
        $shipment_id = $result->getInsertedId();

        $orderCollection = $db->order_tbl;
        foreach ($products as $product) {
            $productDoc = [
                'user_id' => $user_id,
                'tracking_no' => $trackNumber,
                'product_type' => $product['type'],
                'product_name' => $product['name'],
                'quantity' => $product['quantity'],
                'unit_price' => $product['unitPrice']
            ];
            $orderCollection->insertOne($productDoc, ['session' => $session]);
        }

        $session->commitTransaction();
        echo json_encode(['success' => true, 'message' => 'Shipment and products inserted successfully.']);
    } catch (Exception $e) {
        $session->abortTransaction();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        $session->endSession();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
