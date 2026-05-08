<?php
include('../../../database/database.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../pages/login.php');
    exit();
}

if (!isset($_GET['tracking_no'])) {
    header('Location: ../../../pages/declared-shipment.php');
    exit();
}

$tracking_no = $_GET['tracking_no'];
$user_id = $_SESSION['user_id'];

// Fetch shipment and warehouse data from MongoDB
$shipmentCollection = $db->declare_shipment_order_tbl;
$shipmentDoc = $shipmentCollection->findOne([
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
    'city' => $warehouse['city'],
    'country' => $warehouse['country'],
    'img_path' => $warehouse['img_path'],
    'order_status' => $shipmentDoc['order_status']
];

// Fetch product details from MongoDB
$productCollection = $db->order_tbl;
$products = $productCollection->find(['tracking_no' => $tracking_no])->toArray();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $tracking_no = $_POST['tracking_no'];
    
    // Update confirmed_payment in MongoDB
    $updateResult = $shipmentCollection->updateOne(
        ['tracking_no' => $tracking_no],
        ['$set' => ['confirmed_payment' => 1]]
    );
    
    if ($updateResult->getModifiedCount() > 0 || $updateResult->getMatchedCount() > 0) {
        header('Location: ../shipment.php');
        exit();
    } else {
        die("Failed to update payment confirmation.");
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="../../../css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <title>Warehouse</title>
</head>

<body>
    <section class="dashboard">
        <div class="sidebar">
            <div class="sidebar-logo">
                <div class="back-icon">
                    <i class='bx bx-chevron-left bx-md'></i>
                </div>
                <h1>PARCELHUB</h1>
            </div>
            <div class="sidebar-menu-nav">
                <div class="sidebar-menu-nav-section">
                    <ul class="sidebard-menu-nav-item_list">
                        <li class="sidebard-menu-nav-item_list-item">
                            <a class="sidebard-menu-nav-item-item" href="../../dashboard.php">
                                <i class='bx bxs-home'></i>
                                <span>Home</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-menu-nav-section">
                    <div class="sidebar-devider">
                    </div>
                    <h3 class="sidebar-section-text">Start Shipping</h3>
                    <ul class="sidebard-menu-nav-item_list">
                        <li class="sidebard-menu-nav-item_list-item">
                            <a class="sidebard-menu-nav-item-item" href="../../createOrder.php">
                                <i class='bx bx-package'></i>
                                <span>Create Orders</span>
                            </a>
                        </li>
                        <li class="sidebard-menu-nav-item_list-item">
                            <a class="sidebard-menu-nav-item-item active">
                                <i class='bx bx-food-menu'></i>
                                <span>Manage Orders</span>
                            </a>
                        </li>
                        <li class="sidebard-menu-nav-item_list-item">
                            <a class="sidebard-menu-nav-item-item" href="../../warehouse.php">
                                <i class='fas fa-warehouse' style='font-size:12px;'></i>
                                <span>Warehouse Addresses</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-menu-nav-section">
                    <div class="sidebar-devider">
                    </div>
                    <h3 class="sidebar-section-text">Settings and Help</h3>
                    <ul class="sidebard-menu-nav-item_list">
                        <li class="sidebard-menu-nav-item_list-item">
                            <a class="sidebard-menu-nav-item-item" href="../../account.php">
                                <i class='bx bx-cog'></i>
                                <span>Account Settings</span>
                            </a>
                        </li>
                        <li class="sidebard-menu-nav-item_list-item">
                            <div class="sidebard-menu-nav-item-item">
                                <i class='bx bx-help-circle'></i>
                                <span>Contact Us</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="bottom-bar">
            <ul class="bottom-bar-nav">
                <li class="bottom-bar-nav-item">
                    <a href="../../../pages/dashboard.php">
                        <i class='bx bxs-home'></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a href="../../../pages/createOrder.php">
                        <i class='bx bx-package'></i>
                        <span>Create Orders</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a class="active" href="../../../pages/manageOrder.php">
                        <i class='bx bx-food-menu'></i>
                        <span>Manage Orders</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a href="../../../pages/warehouse.php">
                        <i class="ti ti-building-warehouse"></i>
                        <span>Warehouse</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a href="../../../pages/account.php">
                        <i class='bx bx-cog'></i>
                        <span>Account</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="whole-container">
            <nav class="nav-bg"></nav>
            <div class="container-main">
                <div class="nav-container">
                    <a class="nav-left" href="../oversea-warehouse.php">
                        <box-icon name='chevron-left' class="back-btn"></box-icon>
                    </a>
                    <div class="nav-profile">
                        <div class="nav-profile-avatar">
                            <div class="nav-profile-avatar_text">
                                <?php echo htmlspecialchars($user['first_letter']); ?>
                            </div>
                        </div>
                        <div class="nav-profile-avatar-dropdown">
                            <div class="nav-overlay"></div>
                            <div class="nav-logout">
                                <a href="../../../database/logout.php" class="nav-logout-link">
                                    <div class="nav-logout-icon">
                                        <i class='bx bx-log-out'></i>
                                    </div>
                                    <div class="nav-logout-text">Log out</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <h1 class="shipment-details-title">Shipment Details</h1>
                    <div class="shipment-details">
                        <div class="shipment-details-container">
                            <div class="shipment-details-main">
                                <div class="shipment-details-main-header">
                                    <div class="shipment-details-main-header">
                                        <box-icon type='solid' name='truck' class="shipment-details-main-header_icon"></box-icon>
                                    </div>
                                    <div class="shipment-details-main-header_title">
                                        Shipment Details
                                    </div>
                                </div>
                                <div class="shipment-details-main-body">
                                    <div class="shipment-details-main-content">
                                        <div class="shipment-details-main-content_title location_title"> Overseas Warehouse </div>
                                        <div class="shipment-details-main-content_value location_value"><?php echo htmlspecialchars($shipment['city'] . ', ' . $shipment['country']); ?></div>
                                    </div>
                                    <div class="shipment-details-main-content">
                                        <div class="shipment-details-main-content_title order_status_title"> Order Status </div>
                                        <div class="shipment-details-main-content_value order_status_value"><?php echo htmlspecialchars($shipment['order_status']); ?></div>
                                    </div>
                                    <div class="shipment-details-main-content">
                                        <div class="shipment-details-main-content_title shipment_no_title"> Shipment No. </div>
                                        <div class="shipment-details-main-content_value shipment_no_value"><?php echo htmlspecialchars($shipment['tracking_no']); ?></div>
                                    </div>
                                    <div class="shipment-details-main-content">
                                        <div class="shipment-details-main-content_title created_at_title">Created At</div>
                                        <div class="shipment-details-main-content_value created_at_value">
                                            <?php echo htmlspecialchars($shipment['timestamp']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="shipment-detail-product">
                                <div class="shipment-details-main-header shipment-details-body_item">
                                    <div class="shipment-details-main-header">
                                        <box-icon type='solid' name='package' class="shipment-details-main-header_icon"></box-icon>
                                    </div>
                                    <div class="shipment-details-main-header_title">
                                        Declaration Details
                                    </div>
                                </div>
                                <?php if (count($products) > 0): ?>
                                    <?php foreach ($products as $product): ?>
                                        <div class="shipment-details-product_items_container">
                                            <div class="shipment-details-product_items">
                                                <div class="shipment-details-product_items_img">
                                                    <i class='bx bx-task shipment-details-product_items_icon'></i>
                                                </div>
                                                <div class="shipment-details-product_items_content">
                                                    <div class="shipment-details-body-content">
                                                        <div class="shipment-details-body-content_title"> Name </div>
                                                        <div class="shipment-details-body-content_value">
                                                            <?php echo htmlspecialchars($product['product_name']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="shipment-details-body-content">
                                                        <div class="shipment-details-body-content_title"> Quantity </div>
                                                        <div class="shipment-details-body-content_value">
                                                            <?php echo htmlspecialchars($product['quantity']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="shipment-details-body-content">
                                                        <div class="shipment-details-body-content_title"> Unit Price(<?php echo $currency_map[$shipment['country']][0] ?>) </div>
                                                        <div class="shipment-details-body-content_value">
                                                            <?php echo $currency_map[$shipment['country']][1] . ' ' . number_format($product['unit_price'], 2); ?>
                                                        </div>
                                                    </div>
                                                    <div class="shipment-details-body-content">
                                                        <div class="shipment-details-body-content_title"> Product Type </div>
                                                        <div class="shipment-details-body-content_value">
                                                            <?php echo htmlspecialchars($product['product_type']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="shipment-details-total_price">
                                    <div class="shipment-details-total_price_label"> Total Price </div>
                                    <div class="shipment-details-total_price_value"> <?php echo $currency_map[$shipment['country']][1] . ' ' . number_format($shipment['totalPrice'], 2); ?> </div>
                                </div>
                            </div>
                            <div class="shipment-details-confirmation">
                                <form method="POST">
                                    <input type="hidden" name="tracking_no" value="<?php echo htmlspecialchars($shipment['tracking_no']); ?>">
                                    <button type="submit" name="confirm_payment" class="shipment-details-confirmation_btn">
                                        <span>READY TO PAY</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>