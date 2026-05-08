<?php
include('../admin/database/database.php');

/* =========================
   UPDATE / DELETE USER (MONGODB)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $collection = $db->account_tbl;

    if (isset($_POST['delete'])) {

        $user_id = $_POST['user_id'];

        $collection->deleteOne([
            'member_id' => $user_id
        ]);

        header("Location: ../admin/all-user.php");
        exit;

    } elseif (isset($_POST['update'])) {

        $user_id = $_POST['user_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];

        $collection->updateOne(
            ['member_id' => $user_id],
            ['$set' => [
                'first_name' => $first_name,
                'last_name' => $last_name
            ]]
        );

        header("Location: ../admin/all-user.php");
        exit;
    }
}

/* =========================
   FETCH USERS (MONGODB)
========================= */
$collection = $db->account_tbl;

$cursor = $collection->find([]);

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
    <title>All Users</title>
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
                        <a class="sidebard-menu-nav-item-item" href="../admin/orders-dashboard.php">
                            <span>Orders</span>
                        </a>
                    </li>

                    <li class="sidebard-menu-nav-item_list-item">
                        <a class="sidebard-menu-nav-item-item active" href="../admin/all-user.php">
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

<h1>All Users</h1>

<table border="1">

<thead>
<tr>
    <th>Member ID</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Email</th>
    <th>Created At</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php foreach ($rows as $row) : ?>

<tr>

<form method="POST">

    <td><?= $row['member_id'] ?? ''; ?></td>

    <td>
        <?= htmlspecialchars($row['first_name'] ?? ''); ?>
    </td>

    <td>
        <?= htmlspecialchars($row['last_name'] ?? ''); ?>
    </td>

    <td><?= htmlspecialchars($row['email'] ?? ''); ?></td>
    <td>
        <?php 
            if (isset($row['created_at'])) {
                if ($row['created_at'] instanceof MongoDB\BSON\UTCDateTime) {
                    echo $row['created_at']->toDateTime()->format('Y-m-d');
                } else {
                    echo htmlspecialchars($row['created_at']);
                }
            } else {
                echo '';
            }
        ?>
    </td>

    <td>
        <input type="hidden" name="user_id" value="<?= $row['member_id']; ?>">
        <button type="submit" name="delete" class="delete-btn">Delete</button>
    </td>

</form>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>
</div>
</div>

</section>

</body>
</html>