<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

// MongoDB connection
$uri = "mongodb+srv://parcelhub_db_user:parcelhub@parcelhub.wkkzxp1.mongodb.net/?appName=ParcelHub";
$client = new Client($uri);
$db = $client->parcelhub;
$collection = $db->paid_orders_tbl;

// =====================
// UPDATE ORDER STATUS - SHIPPED
// =====================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['shipped'])) {

    $tracking_no = $_POST['tracking_no'];

    try {
        $result = $collection->updateOne(
            ['tracking_no' => $tracking_no],
            ['$set' => ['order_status' => 'Shipped']]
        );

        if ($result->getModifiedCount() > 0) {
            $successMessage = "Order status changed to Shipped!";
        } else {
            $errorMessage = "No changes were made.";
        }

    } catch (Exception $e) {
        $errorMessage = "Failed to update: " . $e->getMessage();
    }
}

// =====================
// UPDATE ORDER STATUS - OUT FOR DELIVERY
// =====================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['out_for_delivery'])) {

    $tracking_no = $_POST['tracking_no'];

    try {
        $result = $collection->updateOne(
            ['tracking_no' => $tracking_no],
            ['$set' => ['order_status' => 'Out for delivery']]
        );

        if ($result->getModifiedCount() > 0) {
            $successMessage = "Order status changed to Out for delivery!";
        } else {
            $errorMessage = "No changes were made.";
        }

    } catch (Exception $e) {
        $errorMessage = "Failed to update: " . $e->getMessage();
    }
}

// =====================
// UPDATE ORDER STATUS - DELIVERED
// =====================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delivered'])) {

    $tracking_no = $_POST['tracking_no'];

    try {
        $result = $collection->updateOne(
            ['tracking_no' => $tracking_no],
            ['$set' => ['order_status' => 'Delivered']]
        );

        if ($result->getModifiedCount() > 0) {
            $successMessage = "Order status changed to Delivered!";
        } else {
            $errorMessage = "No changes were made.";
        }

    } catch (Exception $e) {
        $errorMessage = "Failed to update: " . $e->getMessage();
    }
}

// =====================
// FETCH ALL ORDERS
// =====================
$cursor = $collection->find();

$orders = [];
foreach ($cursor as $doc) {
    $orders[] = $doc;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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
    <title>Orders Dashboard</title>
</head>

<body>

<section class="dashboard">

    <div class="sidebar">
        <div class="sidebar-logo">
            <h1> PARCELHUB ADMIN </h1>
        </div>

        <div class="sidebar-menu-nav">

            <div class="sidebar-menu-nav-section">
                <ul class="sidebard-menu-nav-item_list">
                    <li class="sidebard-menu-nav-item_list-item">
                        <a class="sidebard-menu-nav-item-item" href="../admin/admin.php">
                            <span>Shipments</span>
                        </a>
                    </li>

                    <li class="sidebard-menu-nav-item_list-item">
                        <a class="sidebard-menu-nav-item-item active" href="../admin/orders-dashboard.php">
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

    <div class="whole-container">

        <div class="container-main">

            <div class="content">

                <h1 class="declared-shipments-title">All Orders</h1>

                <?php if (isset($successMessage)) : ?>
                    <p class="message success"><?= htmlspecialchars($successMessage) ?></p>
                <?php endif; ?>

                <?php if (isset($errorMessage)) : ?>
                    <p class="message error"><?= htmlspecialchars($errorMessage) ?></p>
                <?php endif; ?>

                <!-- Search and Filter Controls -->
                <div class="table-controls">
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="Search orders...">
                        <i class="bx bx-search"></i>
                    </div>
                    
                    <div class="filter-container">
                        <label for="statusFilter">Filter by Status:</label>
                        <select id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="Processing">Processing</option>
                            <option value="Shipped">Shipped</option>
                            <option value="Out for delivery">Out for delivery</option>
                            <option value="Delivered">Delivered</option>
                        </select>
                        
                        <label for="warehouseFilter">Filter by Warehouse:</label>
                        <select id="warehouseFilter">
                            <option value="">All Warehouses</option>
                            <option value="Australia">Australia</option>
                            <option value="Canada">Canada</option>
                            <option value="China">China</option>
                            <option value="Japan">Japan</option>
                            <option value="South Korea">South Korea</option>
                            <option value="Taiwan">Taiwan</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="United States">United States</option>
                        </select>
                    </div>
                    
                    <div class="sort-container">
                        <label for="sortSelect">Sort by:</label>
                        <select id="sortSelect">
                            <option value="tracking_no">Tracking No</option>
                            <option value="no_of_order">No of Orders</option>
                            <option value="totalPrice">Total Price</option>
                            <option value="warehouse">Warehouse</option>
                            <option value="total_weight_lb">Weight</option>
                            <option value="payment_method">Payment Method</option>
                            <option value="total_shipping_fee">Shipping Fee</option>
                            <option value="name">Name</option>
                            <option value="order_status">Status</option>
                        </select>
                    </div>
                </div>

                <table border="1" id="ordersTable">
                    <thead>
                        <tr>
                            <th>Tracking No</th>
                            <th>No of Orders</th>
                            <th>Total Price</th>
                            <th>Warehouse</th>
                            <th>Total Weight (lb)</th>
                            <th>Payment Method</th>
                            <th>Base Shipping Fee</th>
                            <th>Total Shipping Fee</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Order Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($orders as $row): ?>
                            <tr>
                                <form method="POST">

                                    <td><?= htmlspecialchars($row['tracking_no'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['no_of_order'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['totalPrice'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['warehouse'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['total_weight_lb'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['payment_method'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['base_shipping_fee_per_lb'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['total_shipping_fee'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['address'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['mobile_no'] ?? '') ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['order_status'] ?? '') ?>
                                    </td>



                                    <td>
                                        <input type="hidden" name="tracking_no" value="<?= htmlspecialchars($row['tracking_no'] ?? '') ?>">
                                        
                                        <?php 
                                            $status = $row['order_status'] ?? '';
                                            if ($status == 'Processing') :
                                        ?>
                                            <button type="submit" name="shipped">Shipped</button>
                                        <?php 
                                            elseif ($status == 'Shipped') :
                                        ?>
                                            <button type="submit" name="out_for_delivery">Out for delivery</button>
                                        <?php 
                                            elseif ($status == 'Out for delivery') :
                                        ?>
                                            <button type="submit" name="delivered">Delivered</button>
                                        <?php 
                                            elseif ($status == 'Delivered') :
                                        ?>
                                            <span style="color: green; font-weight: bold;">Complete</span>
                                        <?php 
                                            endif;
                                        ?>
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
    const warehouseFilter = document.getElementById('warehouseFilter');
    const sortSelect = document.getElementById('sortSelect');
    const table = document.getElementById('ordersTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    function filterAndSortRows() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const warehouseValue = warehouseFilter.value;
        const sortBy = sortSelect.value;
        const columnIndex = getColumnIndex(sortBy);
        
        // Filter rows
        const filteredRows = rows.filter(row => {
            const text = row.textContent.toLowerCase();
            const statusCell = row.cells[11].textContent.trim(); // Order Status column
            const warehouseCell = row.cells[3].textContent.trim(); // Warehouse column
            
            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !statusValue || statusCell === statusValue;
            const matchesWarehouse = !warehouseValue || warehouseCell === warehouseValue;
            
            return matchesSearch && matchesStatus && matchesWarehouse;
        });
        
        // Sort filtered rows
        filteredRows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();
            
            if (sortBy === 'no_of_order' || sortBy === 'totalPrice' || sortBy === 'total_weight_lb' || sortBy === 'total_shipping_fee') {
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
    warehouseFilter.addEventListener('change', filterAndSortRows);
    sortSelect.addEventListener('change', filterAndSortRows);

    function getColumnIndex(sortBy) {
        const columns = ['tracking_no', 'no_of_order', 'totalPrice', 'warehouse', 'total_weight_lb', 'payment_method', 'base_shipping_fee', 'total_shipping_fee', 'name', 'address', 'mobile_no', 'order_status'];
        return columns.indexOf(sortBy);
    }
});
</script>

</body>
</html>
