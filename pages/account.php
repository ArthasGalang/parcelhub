<?php
include('../database/database.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link href="../css/dashboard.css" rel="stylesheet">
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
                            <a class="sidebard-menu-nav-item-item" href="../pages/dashboard.php">
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
                            <a class="sidebard-menu-nav-item-item" href="../pages/createOrder.php">
                                <i class='bx bx-package'></i>
                                <span>Create Orders</span>
                            </a>
                        </li>
                        <li class="sidebard-menu-nav-item_list-item">
                            <a class="sidebard-menu-nav-item-item" href="../pages/manageOrder.php">
                                <i class='bx bx-food-menu'></i>
                                <span>Manage Orders</span>
                            </a>
                        </li>
                        <li class="sidebard-menu-nav-item_list-item">
                            <a class="sidebard-menu-nav-item-item" href="../pages/warehouse.php">
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
                            <a class="sidebard-menu-nav-item-item active">
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
                    <a href="../pages/dashboard.php">
                        <i class='bx bxs-home'></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a href="../pages/createOrder.php">
                        <i class='bx bx-package'></i>
                        <span>Create Orders</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a href="../pages/manageOrder.php">
                        <i class='bx bx-food-menu'></i>
                        <span>Manage Orders</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a href="../pages/warehouse.php">
                        <i class="ti ti-building-warehouse"></i>
                        <span>Warehouse</span>
                    </a>
                </li>
                <li class="bottom-bar-nav-item">
                    <a class="active" href="../pages/account.php">
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
                    <div class="nav-left"></div>
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
                    <h1 class="account-setting-title">Account Settings</h1>
                    <div class="account-member-id-container">
                        <span class="account-member-id-text">Member ID: </span>
                        <span class="account-member-id"><?php echo htmlspecialchars(($user['member_id'])) ?></span>
                    </div>
                    <div class="account-setting-tab_container">
                        <a class="account-setting-tab active">
                            <box-icon name='face'></box-icon>
                            My Profile
                        </a>
                        <a class="account-setting-tab" href="../pages/delivery.php">
                            <i class="fa-solid fa-location-dot"></i>
                            My Address
                        </a>
                    </div>
                    <div class="account-setting">
                        <div class="account-setting-profile_editor" id="name-section">
                            <div class="account-setting-profile_editor_left">
                                <div class="account-setting-profile_editor_title">
                                    Name
                                </div>
                                <div class="account-setting-profile_editor_detail">
                                    <?php echo htmlspecialchars(($user['first_name'])) . ' ' . htmlspecialchars(($user['last_name'])) ?>
                                </div>
                                <div class="account-setting-profile_editor_remark">
                                    Your name will be displayed in your delivery address.
                                </div>
                            </div>
                            <button class="account-setting-profile_editor_icon" id="edit-btn">Edit</button>
                        </div>
                        <div class="account-setting-profile_editor" id="name-section-editing">
                            <div class="account-setting-profile_editor_left">
                                <div class="account-setting-profile_editor_title">
                                    Name
                                </div>
                                <div class="account-setting-profile_editor_enter">
                                    Enter your name
                                </div>
                                <div class="account-setting-profile_input_detail">
                                    <input type="text" value="<?php echo htmlspecialchars(($user['first_name'])) . ' ' . htmlspecialchars(($user['last_name'])) ?>" class="account-setting-profile_input">
                                </div>
                                <button class="account-setting-profile_editor_save" id="save-btn">Save</button>
                            </div>
                            <button class="account-setting-profile_editor_icon" id="cancel-btn">Cancel</button>
                        </div>
                        <div class="account-setting-profile__separator"></div>
                        <div class="account-setting-profile_editor">
                            <div class="account-setting-profile_editor_left">
                                <div class="account-setting-profile_editor_title">
                                    Email
                                </div>
                                <div class="account-setting-profile_editor_detail">
                                    <?php echo htmlspecialchars(($user['email'])) ?>
                                </div>
                                <div class="account-setting-profile_editor_remark">
                                    Each account can only be linked to one email address, and this cannot be changed after registration.
                                </div>
                            </div>
                        </div>
                        <div class="account-setting-profile__separator"></div>
                        <div class="account-setting-profile_editor" id="password-section">
                            <div class="account-setting-profile_editor_left">
                                <div class="account-setting-profile_editor_title">
                                    Password
                                </div>
                                <div class="account-setting-profile_editor_detail">
                                    ******
                                </div>
                            </div>
                            <button class="account-setting-profile_editor_icon" id="edit-password-btn">Edit</button>
                        </div>
                        <div class="account-setting-profile_editor" id="password-section-editing" style="display: none;">
                            <div class="account-setting-profile_editor_left">
                                <div class="account-setting-profile_editor_title">
                                    Password
                                </div>
                                <div class="account-setting-profile_editor_enter">
                                    Enter your new password
                                </div>
                                <div class="account-setting-profile_input_detail">
                                    <input type="password" id="password-input" class="account-setting-profile_input" placeholder="Enter new password">
                                </div>
                                <button class="account-setting-profile_editor_save" id="save-password-btn">Save</button>
                            </div>
                            <button class="account-setting-profile_editor_icon" id="cancel-password-btn">Cancel</button>
                        </div>
                    </div>
                    <div class="account-setting-notice_container">
                        <div class="account-setting-notice_title"> ParcelHub Data Privacy Notice </div>
                        <div class="account-setting-notice_detail">
                            ParcelHub intends to use your personal data for direct marketing purposes, including sending you updates on special offers, promotions, discounts or similar communications of our products and services, as well as those of our business partners under the Terms of Use and Privacy Policy.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../js/edit_name.js"></script>
    <script src="../js/edit_password.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>