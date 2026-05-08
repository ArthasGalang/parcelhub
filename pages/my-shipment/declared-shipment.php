<?php
include('../../database/database.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../pages/login.php');
    exit();
}
$user_id = $_SESSION['user_id'];

$collection = $db->declare_shipment_order_tbl;
$cursor = $collection->find([
    'user_id' => $user_id,
    'declared_shipment' => 1,
    'order_status' => ['$ne' => 'Waiting for payment']
], [
    'projection' => ['tracking_no' => 1, 'timestamp' => 1, 'confirmed_payment' => 1, 'warehouse' => 1]
]);

$warehouseCollection = $db->warehouse_addresses_tbl;
$shipments = [];
foreach ($cursor as $doc) {
    $warehouse = $warehouseCollection->findOne(['country' => $doc['warehouse']], [
        'projection' => ['city' => 1, 'country' => 1, 'img_path' => 1]
    ]);
    if ($warehouse) {
        $shipments[] = [
            'tracking_no' => $doc['tracking_no'],
            'timestamp' => $doc['timestamp'],
            'confirmed_payment' => $doc['confirmed_payment'],
            'city' => $warehouse['city'],
            'country' => $warehouse['country'],
            'img_path' => $warehouse['img_path']
        ];
    }
}
$total_declared_shipments = count($shipments);
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
    <link href="../../css/dashboard.css" rel="stylesheet">
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
                            <a class="sidebard-menu-nav-item-item" href="../dashboard.php">
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
                            <a class="sidebard-menu-nav-item-item" href="../createOrder.php">
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
                            <a class="sidebard-menu-nav-item-item" href="../warehouse.php">
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
                            <a class="sidebard-menu-nav-item-item" href="../account.php">
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
                    <a href="../../pages/dashboard.php">
                        <i class='bx bxs-home'></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a href="../../pages/createOrder.php">
                        <i class='bx bx-package'></i>
                        <span>Create Orders</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a class="active" href="../../pages/manageOrder.php">
                        <i class='bx bx-food-menu'></i>
                        <span>Manage Orders</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a href="../../pages/warehouse.php">
                        <i class="ti ti-building-warehouse"></i>
                        <span>Warehouse</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a href="../../pages/account.php">
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
                    <a class="nav-left" href="shipment.php">
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
                                <a href="../../database/logout.php" class="nav-logout-link">
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
                    <h1 class="declared-shipment-title">Declared Shipments</h1>
                    <div class="declared-shipment">
                        <div class="declared-shipment-container">
                            <div class="declared-shipment-list_number">
                                <span class="declared-shipment-list_counter"><?php echo htmlspecialchars($total_declared_shipments); ?></span>
                                <span class="declared-shipment-list_text"> Shipment </span>
                            </div>
                            <?php if ($total_declared_shipments > 0): ?>
                                <?php foreach ($shipments as $shipment): ?>
                                    <a class="declared-shipment-card_body"
                                        href="../my-shipment/declared-shipment/shipment-details.php?tracking_no=<?php echo htmlspecialchars($shipment['tracking_no']); ?>">
                                        <div class="declared-shipment-location">
                                            <img src="../<?php echo htmlspecialchars($shipment['img_path']); ?>" class="declared-shipment-location-img">
                                            <div class="declared-shipment-location_text"><?php echo htmlspecialchars($shipment['city'] . ', ' . $shipment['country']); ?></div>
                                        </div>
                                        <div class="declared-shipment-list_row">
                                            <span class="declared-shipment-list_title shipment_title">Shipment No.</span>
                                            <span class="declared-shipment-number"><?php echo htmlspecialchars($shipment['tracking_no']); ?></span>
                                        </div>
                                        <div class="declared-shipment-list_row">
                                            <span class="declared-shipment-list_title">Created at</span>
                                            <span class="declared-shipment-date"><?php echo htmlspecialchars($shipment['timestamp']); ?></span>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="declared-shipment-empty">
                            <img src="../../assets/empty-box.png" class="declared-shipment-empty_img">
                            <span class="declared-shipment-empty_text">0 Shipments</span>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>