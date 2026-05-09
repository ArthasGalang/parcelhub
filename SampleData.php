<?php
require_once __DIR__ . '/vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
$collections = [
    'account_tbl' => 'account_tbl.json',
    'order_tbl' => 'order_tbl.json',
    'deliveryaddress_tbl' => 'deliveryaddress_tbl.json',
    'declare_shipment_order_tbl' => 'declare_shipment_order_tbl.json',
    'paid_orders_tbl' => 'paid_orders_tbl.json',
    'warehouse_addresses_tbl' => 'warehouse_addresses_tbl.json'
];

try {
    echo "Connecting to MongoDB Atlas...\n";
    $client = new MongoDB\Client($uri, [], ["serverSelectionTimeoutMS" => 5000]);
    
    function convertObjectIds($value) {
        if (!is_array($value)) {
            return $value;
        }

        if (isset($value['_id']) && is_string($value['_id']) && preg_match('/^[0-9a-fA-F]{24}$/', $value['_id'])) {
            $value['_id'] = new MongoDB\BSON\ObjectId($value['_id']);
        }

        foreach ($value as $key => $item) {
            $value[$key] = convertObjectIds($item);
        }

        return $value;
    }
    
    // Test the connection
    $client->listDatabases();
    $db = $client->parcelhub;
    echo "Connected!\n\n";

    $total = 0;
    foreach ($collections as $collName => $jsonFile) {
        $filePath = __DIR__ . '/data_samples/' . $jsonFile;
        if (!file_exists($filePath)) { 
            echo "✗ $jsonFile not found\n"; 
            continue; 
        }
        
        echo "Processing $collName...\n";
        
        $data = json_decode(file_get_contents($filePath), true);
        if ($data === null) {
            echo "  ✗ Failed to decode JSON\n";
            continue;
        }
        
        $data = convertObjectIds($data);
        
        $collection = $db->selectCollection($collName);
        
        try {
            $deleteResult = $collection->deleteMany([]);
            echo "  ✓ Cleared {$deleteResult->getDeletedCount()} old records\n";
        } catch (Exception $e) {
            echo "  ⚠ Could not clear old records: " . $e->getMessage() . "\n";
        }
        
        if (is_array($data[0] ?? null)) {
            $result = $collection->insertMany($data);
            $count = count($result->getInsertedIds());
        } else {
            $collection->insertOne($data);
            $count = 1;
        }
        
        echo "✓ $collName: $count records\n";
        $total += $count;
    }
    
    echo "\n✅ Imported $total records to MongoDB Atlas!\n";
    
    // Verification: Count documents in each collection
    echo "\n--- Verification ---\n";
    foreach ($collections as $collName => $jsonFile) {
        $collection = $db->selectCollection($collName);
        $count = $collection->countDocuments();
        echo "$collName: $count documents\n";
    }
} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
    echo "❌ CONNECTION TIMEOUT - MongoDB Atlas cluster not reachable\n";
    echo "Possible causes:\n";
    echo "1. Your IP is not whitelisted in MongoDB Atlas\n";
    echo "2. MongoDB Atlas cluster is paused\n";
    echo "3. Network firewall is blocking the connection\n";
    echo "\nError: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
?>
