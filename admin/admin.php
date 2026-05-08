<?php
include('../admin/database/database.php');

use MongoDB\BSON\ObjectId;

/* =========================
   UPDATE SHIPMENT - DECLARE
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['declare'])) {

    $tracking_no = $_POST['tracking_no'];
    $total_weight_lb = floatval($_POST['total_weight_lb']);

    $collection = $db->declare_shipment_order_tbl;

    $updateResult = $collection->updateOne(
        ['tracking_no' => $tracking_no],
        ['$set' => [
            'declared_shipment' => 1,
            'total_weight_lb' => $total_weight_lb
        ]]
    );

    if ($updateResult->getModifiedCount() > 0) {
        $successMessage = "Shipment declared successfully!";
    } else {
        $errorMessage = "No changes were made.";
    }
}

/* =========================
   DELETE SHIPMENT
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {

    $tracking_no = $_POST['tracking_no'];
    $collection = $db->declare_shipment_order_tbl;

    $deleteResult = $collection->deleteOne(['tracking_no' => $tracking_no]);

    if ($deleteResult->getDeletedCount() > 0) {
        $successMessage = "Shipment deleted successfully!";
    } else {
        $errorMessage = "Could not delete shipment.";
    }
}

/* =========================
   SET TO PAYMENT
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment'])) {

    $tracking_no = $_POST['tracking_no'];

    $collection = $db->declare_shipment_order_tbl;

    $collection->updateOne(
        ['tracking_no' => $tracking_no],
        ['$set' => [
            'order_status' => 'Waiting for payment'
        ]]
    );

    $successMessage = "Order status set to 'Waiting for payment'!";
}

/* =========================
   REVERT DECLARED SHIPMENT
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['revert_declared'])) {

    $tracking_no = $_POST['tracking_no'];

    $collection = $db->declare_shipment_order_tbl;

    $collection->updateOne(
        ['tracking_no' => $tracking_no],
        ['$set' => [
            'declared_shipment' => 0
        ]]
    );

    $successMessage = "Shipment reverted successfully!";
}

/* =========================
   CONFIRM PAYMENT
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm'])) {

    $tracking_no = $_POST['tracking_no'];

    // Get the record from declare_shipment_order_tbl
    $declare_collection = $db->declare_shipment_order_tbl;
    $record = $declare_collection->findOne(['tracking_no' => $tracking_no]);

    if ($record) {
        // Insert into paid_orders_tbl with order_status = Processing
        $paid_collection = $db->paid_orders_tbl;
        
        $paid_collection->insertOne([
            'tracking_no' => $record['tracking_no'],
            'no_of_order' => $record['no_of_order'] ?? 0,
            'totalPrice' => $record['totalPrice'] ?? 0,
            'warehouse' => $record['warehouse'] ?? '',
            'total_weight_lb' => $record['total_weight_lb'] ?? 0,
            'payment_method' => $record['payment_method'] ?? '',
            'base_shipping_fee_per_lb' => $record['base_shipping_fee_per_lb'] ?? 0,
            'total_shipping_fee' => $record['total_shipping_fee'] ?? 0,
            'name' => $record['name'] ?? '',
            'address' => $record['address'] ?? '',
            'mobile_no' => $record['mobile_no'] ?? '',
            'dateTime' => $record['dateTime'] ?? '',
            'order_status' => 'Processing'
        ]);

        // Mark as confirmed in the original table
        $declare_collection->updateOne(
            ['tracking_no' => $tracking_no],
            ['$set' => ['confirmed_payment' => 1]]
        );

        $successMessage = "Shipment confirmed and moved to Processing.";
    }
}

/* =========================
   REVERT FROM PAYMENT
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['revert_payment'])) {

    $tracking_no = $_POST['tracking_no'];

    $collection = $db->declare_shipment_order_tbl;

    $collection->updateOne(
        ['tracking_no' => $tracking_no],
        ['$set' => [
            'order_status' => 'Pending'
        ]]
    );

    $successMessage = "Shipment reverted to Pending!";
}

/* =========================
   FETCH ALL SHIPMENTS (PENDING PROCESSING)
========================= */
$collection = $db->declare_shipment_order_tbl;

$cursor = $collection->find([
    'confirmed_payment' => 0
]);

$rows = [];
foreach ($cursor as $doc) {
    $rows[] = $doc;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link href="../admin/css/dashboard.css" rel="stylesheet">
    <style>
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .search-container {
            position: relative;
            flex: 1;
            max-width: 300px;
        }
        
        .search-container input {
            width: 100%;
            padding: 8px 35px 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .search-container i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        
        .filter-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .filter-container label {
            font-weight: 500;
            color: #333;
            margin-right: 5px;
        }
        
        .filter-container select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            min-width: 120px;
        }
        
        .sort-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sort-container label {
            font-weight: 500;
            color: #333;
        }
        
        .sort-container select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            min-width: 120px;
        }
    </style>
    <title>Admin Dashboard</title>
</head>

<body>
<section class="dashboard">

    <!-- SIDEBAR -->
<div class="sidebar">
        <div class="sidebar-logo">
            <h1> PARCELHUB ADMIN </h1>
        </div>

        <div class="sidebar-menu-nav">



            <div class="sidebar-menu-nav-section">
                <ul class="sidebard-menu-nav-item_list">
                    <li class="sidebard-menu-nav-item_list-item">
                        <a class="sidebard-menu-nav-item-item active" href="../admin/declared-shipment.php">
                            <span>Shipments</span>
                        </a>
                    </li>

                    <li class="sidebard-menu-nav-item_list-item">
                        <a class="sidebard-menu-nav-item-item" href="../admin/orders-dashboard.php">
                            <span>Orders</span>
                        </a>
                    </li>

                    <li class="sidebard-menu-nav-item_list-item">
                        <a class="sidebard-menu-nav-item-item" href="../admin/all-user.php">
                            <span>All User</span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- MAIN -->
    <div class="whole-container">
        <div class="container-main">

            <div class="content">

                <h1>All Shipments</h1>

                <?php if (isset($successMessage)) : ?>
                    <p class="message success"><?php echo $successMessage; ?></p>
                <?php endif; ?>

                <?php if (isset($errorMessage)) : ?>
                    <p class="message error"><?php echo $errorMessage; ?></p>
                <?php endif; ?>

                <!-- Search and Filter Controls -->
                <div class="table-controls">
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="Search shipments...">
                        <i class="bx bx-search"></i>
                    </div>
                    
                    <div class="filter-container">
                        <label for="statusFilter">Filter by Status:</label>
                        <select id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="Pending">Pending</option>
                            <option value="Waiting for payment">Waiting for payment</option>
                        </select>
                        
                        <label for="stateFilter">Filter by State:</label>
                        <select id="stateFilter">
                            <option value="">All States</option>
                            <option value="Not Declared">Not Declared</option>
                            <option value="Declared">Declared</option>
                            <option value="Awaiting Confirmation">Awaiting Confirmation</option>
                        </select>
                    </div>
                    
                    <div class="sort-container">
                        <label for="sortSelect">Sort by:</label>
                        <select id="sortSelect">
                            <option value="no_of_order">No of Orders</option>
                            <option value="totalPrice">Total Price</option>
                            <option value="warehouse">Warehouse</option>
                            <option value="total_weight_lb">Weight</option>
                            <option value="order_status">Status</option>
                            <option value="state">State</option>
                        </select>
                    </div>
                </div>

                <table border="1" id="shipmentsTable">
                    <thead>
                        <tr>
                            <th>Tracking No</th>
                            <th>No of Orders</th>
                            <th>Total Price</th>
                            <th>Warehouse</th>
                            <th>Total Weight (lb)</th>
                            <th>Order Status</th>
                            <th>State</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <tr>

                                <form method="POST">

                                    <td><?php echo $row['tracking_no'] ?? ''; ?></td>
                                    <td><?php echo $row['no_of_order'] ?? 0; ?></td>
                                    <td><?php echo $row['totalPrice'] ?? 0; ?></td>
                                    <td><?php echo $row['warehouse'] ?? ''; ?></td>

                                    <td>
                                        <?php if (($row['declared_shipment'] ?? 0) == 0) : ?>
                                            <input type="number"
                                                   name="total_weight_lb"
                                                   step="0.01"
                                                   min="0"
                                                   value="<?php echo $row['total_weight_lb'] ?? 0; ?>">
                                        <?php else : ?>
                                            <?php echo $row['total_weight_lb'] ?? 0; ?>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['order_status'] ?? 'Pending'; ?>
                                    </td>

                                    <td>
                                        <?php 
                                            if (($row['declared_shipment'] ?? 0) == 0) {
                                                echo "Not Declared";
                                            } elseif (($row['order_status'] ?? '') == 'Pending') {
                                                echo "Declared";
                                            } elseif (($row['order_status'] ?? '') == 'Waiting for payment') {
                                                echo "Awaiting Confirmation";
                                            }
                                        ?>
                                    </td>

                                    <td>
                                        <input type="hidden" name="tracking_no" value="<?php echo $row['tracking_no']; ?>">
                                        
                                        <?php if (($row['declared_shipment'] ?? 0) == 0) : ?>
                                            <button type="submit" name="declare">Declare Shipment</button>
                                            <button type="submit" name="delete">Delete</button>
                                        <?php elseif (($row['order_status'] ?? '') == 'Pending') : ?>
                                            <button type="submit" name="payment">Payment</button>
                                            <button type="submit" name="revert_declared">Revert</button>
                                        <?php elseif (($row['order_status'] ?? '') == 'Waiting for payment') : ?>
                                            <button type="submit" name="revert_payment">Revert</button>
                                        <?php endif; ?>
                                    </td>

                                </form>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const stateFilter = document.getElementById('stateFilter');
    const sortSelect = document.getElementById('sortSelect');
    const table = document.getElementById('shipmentsTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    function filterAndSortRows() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const stateValue = stateFilter.value;
        const sortBy = sortSelect.value;
        const columnIndex = getColumnIndex(sortBy);
        
        // Filter rows
        const filteredRows = rows.filter(row => {
            const text = row.textContent.toLowerCase();
            const statusCell = row.cells[5].textContent.trim(); // Order Status column
            const stateCell = row.cells[6].textContent.trim(); // State column
            
            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !statusValue || statusCell === statusValue;
            const matchesState = !stateValue || stateCell === stateValue;
            
            return matchesSearch && matchesStatus && matchesState;
        });
        
        // Sort filtered rows
        filteredRows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();
            
            if (sortBy === 'no_of_order' || sortBy === 'totalPrice' || sortBy === 'total_weight_lb') {
                // Sort numbers
                return parseFloat(aValue) - parseFloat(bValue);
            } else {
                // Sort strings
                return aValue.localeCompare(bValue);
            }
        });
        
        // Hide all rows first
        rows.forEach(row => row.style.display = 'none');
        
        // Show filtered and sorted rows
        filteredRows.forEach(row => {
            row.style.display = '';
            tbody.appendChild(row);
        });
    }

    // Add event listeners
    searchInput.addEventListener('input', filterAndSortRows);
    statusFilter.addEventListener('change', filterAndSortRows);
    stateFilter.addEventListener('change', filterAndSortRows);
    sortSelect.addEventListener('change', filterAndSortRows);

    function getColumnIndex(sortBy) {
        const columns = ['tracking_no', 'no_of_order', 'totalPrice', 'warehouse', 'total_weight_lb', 'order_status', 'state'];
        return columns.indexOf(sortBy);
    }
});
</script>

</body>
</html>