<?php
include('../database/database.php');
include('../database/delivery.php');
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
    <link href="../css/dashboard.css" rel="stylesheet">
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
                        <a class="account-setting-tab" href="../pages/account.php">
                            <box-icon name='face'></box-icon>
                            My Profile
                        </a>
                        <a class="account-setting-tab active">
                            <i class="fa-solid fa-location-dot"></i>
                            My Address
                        </a>
                    </div>
                    <div class="delivery">
                        <a class="delivery-address-action" href="../pages/address/new.php">
                            <box-icon name='plus' class="delivery-address-action-icon"></box-icon>
                        </a>
                        <div class="delivery-address-list">
                            <div class="delivery-address-list_container">
                                <?php if (empty($addresses)): ?>
                                    <p></p>
                                <?php else: ?>
                                    <?php foreach ($addresses as $address): ?>
                                        <div class="delivery-address-card">
                                            <div class="delivery-address-card-item">
                                                <div class="delivery-address-card-item_icon">
                                                    <box-icon name='user'></box-icon>
                                                </div>
                                                <div class="delivery-address-card-item_text">
                                                    <?php echo htmlspecialchars($address['full_name']); ?>
                                                </div>
                                            </div>
                                            <div class="delivery-address-card-item">
                                                <div class="delivery-address-card-item_icon">
                                                    <box-icon name='map'></box-icon>
                                                </div>
                                                <div class="delivery-address-card-item_text">
                                                    <?php echo htmlspecialchars($address['address']); ?>
                                                </div>
                                            </div>
                                            <div class="delivery-address-card-item">
                                                <div class="delivery-address-card-item_icon">
                                                    <box-icon name='phone'></box-icon>
                                                </div>
                                                <div class="delivery-address-card-item_text">
                                                    <?php echo htmlspecialchars($address['mobile_number']); ?>
                                                </div>
                                            </div>
                                            <div class="delivery-address-card_form">
                                                <div class="delivery-address-card-item_setting">
                                                    <form method="POST" action="">
                                                        <label class="switch">
                                                            <input type="checkbox" class="is-default-switch" data-id="<?php echo $address['id']; ?>" <?php echo ($address['is_default'] ? 'checked' : ''); ?>>
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <span>Set as Default</span>
                                                    </form>
                                                </div>
                                                <div class="delivery-address-card-item_setting">
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="delete_address_id" value="<?php echo $address['id']; ?>">
                                                        <button type="submit" class="delivery-address-card-item_icon">
                                                            <box-icon name='trash' class="delivery-address-delete-icon"></box-icon>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div class="address-list_add">
                                    <a class="address-button_add" href="../pages/address/new.php">
                                        <span>ADD AN ADDRESS</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const switches = document.querySelectorAll('.is-default-switch');

            switches.forEach(function(switchElement) {
                switchElement.addEventListener('change', function() {
                    const addressId = switchElement.dataset.id;
                    const isDefault = switchElement.checked ? 1 : 0;

                    switches.forEach(s => {
                        if (s !== switchElement) {
                            s.checked = false;
                        }
                    });

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            console.log('Address default status updated');
                        }
                    };
                    xhr.send('update_address_id=' + addressId + '&is_default=' + isDefault);
                });
            });
        });
    </script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>