<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="../css/login.css" rel="stylesheet">
</head>

<body>
    <section class="bg-login">
        <div class="container">
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div id="message" class="message message-error">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<div id="message" class="message message-success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            ?>
            <div class="box-login">
                <div class="logo-text">
                    <h4>REGISTER</h4>
                </div>
                <form class="login-form" method="POST" action="../database/register.php">
                    <div class="email-box">
                        <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                    </div>

                    <div class="email-box">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                    </div>

                    <div class="email-box">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>

                    <div class="pw-box">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>

                    <div class="pw-box">
                        <input type="password" name="confirm_password" class="form-control"
                            placeholder="Confirm Password" required>
                    </div>

                    <div class="login-btn">
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </div>
                </form>
            </div>
            <div class="account">
                <p><small>Already have an account?</small>
                    <a href="login.php">Login</a>
                </p>
            </div>
        </div>
    </section>
    <script src="../js/message.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>