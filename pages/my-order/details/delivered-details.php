<?php
include('../../../database/database.php');
include('../../../database/shipping.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../pages/login.php');
    exit();
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
                    <a class="nav-left" href="../delivered.php">
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
                                        <div class="shipment-details-main-content_value order_status_value">
                                            <?php
                                            if ($shipment['order_status'] == 'Delivered')
                                                echo '<div style="color: #078f26;">' . htmlspecialchars($shipment['order_status']) . '</div>';
                                            else {
                                                echo htmlspecialchars($shipment['order_status']);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="shipment-details-main-content">
                                        <div class="shipment-details-main-content_title shipment_no_title"> Shipment No. </div>
                                        <div class="shipment-details-main-content_value shipment_no_value"><?php echo htmlspecialchars($shipment['tracking_no']); ?></div>
                                    </div>
                                    <div class="shipment-details-main-content">
                                        <div class="shipment-details-main-content_title created_at_title">Created At</div>
                                        <div class="shipment-details-main-content_value created_at_value">
                                            <?php echo htmlspecialchars($shipment['dateTime']); ?>
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
                                        Product Details
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
                            <div class="shipment-detail-product">
                                <div class="shipment-details-main-header shipment-details-body_item">
                                    <div class="shipment-details-main-header">
                                        <div class="shipment-details-main-header_img">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </div>
                                        <div class="shipment-details-main-header_title">
                                            Address Details
                                        </div>
                                    </div>
                                    <div class="change-address-btn">
                                    </div>
                                </div>
                                <div class="shipment-details-product_items_container">
                                    <div class="shipment-details-product_items">
                                        <div class="shipment-details-product_items_content delivery_details">
                                            <?php if ($default_address): ?>
                                                <div class="shipment-details-main-content">
                                                    <div class="shipment-details-main-content_title">Name</div>
                                                    <div class="shipment-details-main-content_value"><?php echo htmlspecialchars($default_address['full_name']); ?></div>
                                                </div>
                                                <div class="shipment-details-main-content">
                                                    <div class="shipment-details-main-content_title">Address</div>
                                                    <div class="shipment-details-main-content_value"><?php echo htmlspecialchars($default_address['address']); ?></div>
                                                </div>
                                                <div class="shipment-details-main-content">
                                                    <div class="shipment-details-main-content_title">Phone Number</div>
                                                    <div class="shipment-details-main-content_value"><?php echo htmlspecialchars($default_address['mobile_number']); ?></div>
                                                </div>
                                            <?php else: ?>
                                                <div class="shipment-details-main-content">
                                                    <div class="shipment-details-main-content_value">
                                                        No default delivery address set.
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="shipment-detail-product">
                                    <div class="shipment-details-main-header shipment-details-body_item">
                                        <div class="shipment-details-main-header">
                                            <box-icon type='solid' name='spreadsheet' class="shipment-details-main-header_icon"></box-icon>
                                        </div>
                                        <div class="shipment-details-main-header_title">
                                            Payment Details
                                        </div>
                                    </div>
                                    <div class="shipment-details-product_items_container">
                                        <div class="shipment-details-product_items">
                                            <div class="shipment-details-product_items_content">
                                                <div class="shipment-details-body-content">
                                                    <div class="shipment-details-body-content_title"> Total Weight </div>
                                                    <div class="shipment-details-body-content_value">
                                                        <?php echo htmlspecialchars($shipment['total_weight_lb']); ?> (lb)
                                                    </div>
                                                </div>
                                                <div class="shipment-details-body-content">
                                                    <div class="shipment-details-body-content_title"> Shipping fee per pound(lb) </div>
                                                    <div class="shipment-details-body-content_value">
                                                        ₱ 5
                                                    </div>
                                                </div>
                                                <div class="shipment-details-body-content">
                                                    <div class="shipment-details-body-content_title"> Payment Method </div>
                                                    <div class="shipment-details-body-content_value">
                                                        <?php echo htmlspecialchars($shipment['payment_method']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="shipment-details-total_price">
                                        <div class="shipment-details-total_price_label"> Total Shipping Fee </div>
                                        <div class="shipment-details-total_price_value"> ₱ <?php echo htmlspecialchars($shipment['total_shipping_fee']); ?> </div>
                                    </div>
                                </div>
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