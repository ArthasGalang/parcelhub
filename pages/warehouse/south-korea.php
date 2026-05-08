<?php
include('../../database/database.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../pages/login.php');
    exit();
}

// Get country from query parameter
$country = $_GET['country'] ?? null;

if (!$country) {
    header('Location: ../warehouse.php');
    exit();
}

// Fetch warehouse addresses from MongoDB for the selected country
$collection = $db->warehouse_addresses_tbl;
$warehouses = $collection->find(['country' => $country])->toArray();

if (empty($warehouses)) {
    header('Location: ../warehouse.php');
    exit();
}

// Get the first warehouse (or you can display multiple)
$warehouse = $warehouses[0];
$address = htmlspecialchars($warehouse['address'] ?? 'N/A');
$city = htmlspecialchars($warehouse['city'] ?? 'N/A');
$state = htmlspecialchars($warehouse['state'] ?? 'N/A');
$zip_code = htmlspecialchars($warehouse['zip_code'] ?? 'N/A');
$country = htmlspecialchars($warehouse['country'] ?? 'N/A');
$tel = htmlspecialchars($warehouse['tel'] ?? 'N/A');
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
                            <a class="sidebard-menu-nav-item-item" href="../manageOrder.php">
                                <i class='bx bx-food-menu'></i>
                                <span>Manage Orders</span>
                            </a>
                        </li>
                        <li class="sidebard-menu-nav-item_list-item">
                            <a class="sidebard-menu-nav-item-item active">
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
                    <a href="../../pages/manageOrder.php">
                        <i class='bx bx-food-menu'></i>
                        <span>Manage Orders</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a class="active" href="../../pages/warehouse.php">
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
                    <a class="nav-left" href="../warehouse.php">
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
                                <a href="../database/logout.php" class="nav-logout-link">
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
                    <h1 class="warehouse-title"><?php echo isset($city) ? $city : 'N/A'; ?>, South Korea.</h1>
                    <div class="warehouse-container">
                        <div class="announcement">
                            <div class="announcement-header">
                                <div class="announcement-header_logo">
                                    <box-icon name='error-circle'></box-icon>
                                </div>
                                <div class="announcement-title">
                                    Announcement
                                </div>
                            </div>
                            <div class="announcement-content">
                                <p>You are strongly advised to schedule the delivery time within the warehouse’s operating hours, Monday to Friday 9 a.m. - 4 p.m. (UTC -8), while shopping online.</p>
                            </div>
                        </div>
                        <div class="address-info_container">
                            <div class="address-header">
                                <div class="address-header_icon">
                                    <box-icon name='copy' class="address-header_bxicon"></box-icon>
                                </div>
                                <div class="address-header_content">
                                    You can copy the following information by clicking on it directly
                                </div>
                            </div>
                            <div class="warehouse-detail-tab">
                                <div class="warehouse-detail-tab_item">
                                    Primary Address
                                </div>
                            </div>
                            <div class="warehouse-detail-content">
                                <div class="address-method">
                                    <div class="address-method_icon">
                                        <box-icon name='calendar-check'></box-icon>
                                    </div>
                                    <div class="address-method_title">
                                        Address Filling Method
                                    </div>
                                </div>
                                <div class="warehouse-detail-content_line">
                                    <div class="item">Name</div>
                                    <div class="value"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name'] . ' ' . $user['member_id']); ?></div>
                                </div>
                                <div class="warehouse-detail-content_line">
                                    <div class="item">Address</div>
                                    <div class="value"><?php echo isset($address) ? $address : 'N/A'; ?></div>
                                </div>
                                <div class="warehouse-detail-content_line">
                                    <div class="item">City</div>
                                    <div class="value"><?php echo isset($city) ? $city : 'N/A'; ?></div>
                                </div>
                                <div class="warehouse-detail-content_line">
                                    <div class="item">ZIP Code</div>
                                    <div class="value"><?php echo $zip_code; ?></div>
                                </div>
                                <div class="warehouse-detail-content_line">
                                    <div class="item">Country</div>
                                    <div class="value"><?php echo $country; ?></div>
                                </div>
                                <div class="warehouse-detail-content_line">
                                    <div class="item">Tel</div>
                                    <div class="value"><?php echo $tel; ?></div>
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