<?php
include('../../database/database.php');
include('../../database/shipment.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
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
                    <a class="nav-left" href="../manageOrder.php">
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
                    <h1 class="my-shipment-title">My Shipment</h1>
                    <div class="my-shipment">
                        <div class="my-shipment-container">
                            <div class="my-shipment-body">
                                <div class="my-shipment-banner">
                                    <div class="my-shipment-banner_text">
                                        <span class="my-shipment-banner_text_title">Manage My Shipment</span>
                                        <span class="my-shipment-banner_text_description">Declare, Track & Consolidate your shipment.</span>
                                    </div>
                                </div>
                                <nav>
                                    <div class="my-shipment-body_card declared_shipment">
                                        <a class="my-shipment-body_card_wrapper" href="declared-shipment.php">
                                            <span class="my-shipment-body_card_img"><span class="my-shipment-body_card_img_icon" style="background-image: url('../../assets/declaration.png');"></span></span>
                                            <span class="my-shipment-body_card_title">Declared Shipments</span>
                                            <?php if ($total_shipments > 0) {
                                                echo '<span class="my-shipment-body_card_number"> ' . htmlspecialchars($total_shipments) . ' </span>';
                                            }
                                            ?>
                                            <span class="my-shipment-body_card_arrow"><box-icon name='chevron-right' class="my-shipment-body_card_arrow_icon"></box-icon></span>
                                        </a>
                                    </div>
                                    <div class="my-shipment-body_card at_warehouse_shipment">
                                        <a class="my-shipment-body_card_wrapper" href="oversea-warehouse.php">
                                            <span class="my-shipment-body_card_img"><span class="my-shipment-body_card_img_icon" style="background-image: url('../../assets/warehouse_1.png');"></span></span>
                                            <span class="my-shipment-body_card_title">At oversea warehouse </span>
                                            <?php if ($total_declared_shipment > 0) {
                                                echo '<span class="my-shipment-body_card_number"> ' . htmlspecialchars($total_declared_shipment) . ' </span>';
                                            }
                                            ?>
                                            <span class="my-shipment-body_card_arrow"><box-icon name='chevron-right' class="my-shipment-body_card_arrow_icon"></box-icon></span>
                                        </a>
                                    </div>
                                    <div class="my-shipment-body_card ready_consolidate">
                                        <a class="my-shipment-body_card_wrapper" href="ready-pay.php">
                                            <span class="my-shipment-body_card_img"><span class="my-shipment-body_card_img_icon" style="background-image: url('../../assets/packaging.png');"></span></span>
                                            <span class="my-shipment-body_card_title">Ready to pay</span>
                                            <?php if ($total_confirmed_payment > 0) {
                                                echo '<span class="my-shipment-body_card_number"> ' . htmlspecialchars($total_confirmed_payment) . ' </span>';
                                            }
                                            ?>
                                            <span class="my-shipment-body_card_arrow"><box-icon name='chevron-right' class="my-shipment-body_card_arrow_icon"></box-icon></span>
                                        </a>
                                    </div>
                                    <div class="my-shipment-body_card route_shipment">
                                        <a class="my-shipment-body_card_wrapper" href="shipping.php">
                                            <span class="my-shipment-body_card_img"><span class="my-shipment-body_card_img_icon" style="background-image: url('../../assets/airplane.png');"></span></span>
                                            <span class="my-shipment-body_card_title">En route to PH warehouse</span>
                                            <?php if ($total_shipping_order > 0) {
                                                echo '<span class="my-shipment-body_card_number"> ' . htmlspecialchars($total_shipping_order) . ' </span>';
                                            }
                                            ?>
                                            <span class="my-shipment-body_card_arrow"><box-icon name='chevron-right' class="my-shipment-body_card_arrow_icon"></box-icon></span>
                                        </a>
                                    </div>
                                </nav>
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