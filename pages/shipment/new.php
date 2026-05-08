<?php
include('../../database/database.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../pages/login.php');
    exit();
}

// Fetch warehouse addresses from MongoDB
$warehouses = $db->warehouse_addresses_tbl->find([], [
    'projection' => ['img_path' => 1, 'city' => 1, 'country' => 1, '_id' => 0]
]);
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
                            <a class="sidebard-menu-nav-item-item active">
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
                    <a class="active" href="../../pages/createOrder.php">
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
                    <a class="nav-left" href="../createOrder.php">
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
                    <h1 class="new-shipment-title">Declare your shipment</h1>
                    <div class="new-shipment">

                        <div class="add-new-shipment">
                            <div>
                                <label class="text-field">
                                    <div class="text-field-dropdown">
                                        <div class="text-field_label">Oversea Warehouse</div>
                                        <select class="text-field_select_warehouse">
                                            <?php
                                            $warehousesArray = $warehouses->toArray();
                                            if (count($warehousesArray) > 0) {
                                                foreach ($warehousesArray as $row) {
                                                    $img_path = htmlspecialchars($row['img_path']);
                                                    $city = htmlspecialchars($row['city']);
                                                    $country = htmlspecialchars($row['country']);
                                                    $label = $city . ", " . $country;
                                            ?>
                                                    <option value="<?php echo $country; ?>">
                                                        <?php echo $label; ?>
                                                    </option>
                                            <?php
                                                }
                                            } else {
                                                echo '<option value="" disabled>No Warehouses Available</option>';
                                            }
                                            ?>
                                        </select>
                                        <div class="text-field_under"></div>
                                    </div>
                                </label>
                            </div>
                            <div>
                                <label class="text-field">
                                    <div class="text-field_label">Tracking No.</div>
                                    <input type="text" name="track_no" placeholder="Fill in the tracking no. of your shipment" class="text-field_input">
                                    <div class="text-field_under"></div>
                                </label>
                            </div>
                            <div>
                                <div class="item-list_container">
                                    <div class="item-list_top">
                                        <div class="item-list_count">
                                            <span>0 Items</span>
                                        </div>
                                        <a class="add-item-btn">
                                            <box-icon name='plus' class="add-item-btn-icon"></box-icon>
                                            <span class="add-item-btn-text">ADD</span>
                                        </a>
                                    </div>
                                    <div class="item-list_detail_container">
                                    </div>
                                    <div class="add-item-container" id="add-item-editing">
                                        <div class="add-item_detail" id="product-type">
                                            <div class="add-item-product_title">
                                                Product Type
                                            </div>
                                            <div class="add-item-product_input_detail">
                                                <select class="add-item-product_input" name="product_type[]" required>
                                                    <option value="">Please select</option>
                                                    <option value="Home & Pets">Home & Pets</option>
                                                    <option value="Sports & Outdoors">Sports & Outdoors</option>
                                                    <option value="Health & Beauty">Health & Beauty</option>
                                                    <option value="Auto & Industrial">Auto & Industrial</option>
                                                    <option value="Books">Books</option>
                                                    <option value="Kids & Toys">Kids & Toys</option>
                                                    <option value="Handmade">Handmade</option>
                                                    <option value="Movies, Music $ Games">Movies, Music $ Games</option>
                                                    <option value="Fashion">Fashion</option>
                                                    <option value="Electronics">Electronics</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="add-item_detail" id="product-name">
                                            <div class="add-item-product_title">
                                                Product Name
                                            </div>
                                            <div class="add-item-product_input_detail">
                                                <input type="text" value="" name="product_name[]" class="add-item-product_input" placeholder="Please fill the name of the item" required>
                                            </div>
                                        </div>
                                        <div class="add-item_detail" id="product-quantity">
                                            <div class="add-item-product_title">
                                                Quantity
                                            </div>
                                            <div class="add-item-product_input_detail">
                                                <input type="number" name="product_quantity[]" value="1" class="add-item-product_input" id="add-product_quantity" required>
                                            </div>
                                        </div>
                                        <div class="add-item_detail" id="product-quantity" required>
                                            <div class="add-item-product_title">
                                                Unit Price
                                            </div>
                                            <div class="add-item-product_input_detail">
                                                <input type="number" value="" name="product_price[]" class="add-item-product_input" placeholder="Please fill the unit price of the item" required>
                                            </div>
                                        </div>
                                        <button class="add-item-btn_save" id="save-btn">Save</button>
                                    </div>
                                    <div class="item-list_content">
                                        <div class="item-list_empty">
                                            <div class="item-list_empty_img">
                                                <img src="../../assets/product-list.png">
                                            </div>
                                            <div class="item-list_empty_text">
                                                You have not yet add your shipment.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-list_total_container">
                                        <span class="item-list_total_text">Total
                                            <box-icon name='error-circle' class="item-list_total_icon"></box-icon>
                                        </span>
                                        <span class="item-list_total_value">US$ 0</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="terms_container">
                                    <div class="terms_checkbox_container">
                                        <input type="checkbox" class="terms_checkbox">
                                    </div>
                                    <div class="terms_checkbox_label">
                                        <div class="terms_checkbox_label_text">
                                            I agree to the terms and conditions, including the terms and conditions on fees, prohibited goods, size limits, the difference between dimensional weight and actual weight and fee calculation.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bottom-new-address-container">
                                <div class="button-address-action">
                                    <a class="button-address-cancel" href="../createOrder.php">
                                        <p class="button-address-cancel_text">Cancel</p>
                                    </a>
                                    <div class="button-address-submit">
                                        <button type="submit" class="button-address-submit_text">
                                            <span class="button-address-submit_text_2">Submit</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../../js/new-shipment.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>