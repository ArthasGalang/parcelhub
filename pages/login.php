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
    <title>Login</title>
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
                    <h4>LOGIN</h4>
                </div>
                <form class="login-form" method="POST" action="../database/login.php">
                    <div class="email-box">
                        <input type="email" name="email" class="form-control" placeholder="Email" required="">
                    </div>
                    <div class="pw-box">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password"
                            required="">
                        <!-- Eye Icon for Show/Hide Password -->
                        <span id="togglePassword" class="eye-icon">
                            <box-icon name="hide"></box-icon>
                        </span>
                    </div>
                    <div class="remember-box">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Remember me
                            </label>
                        </div>
                    </div>
                    <div class="login-btn">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
            <div class="account">
                <p><small>Don't have an account ?</small>
                    <a href="register.php">Create an account</a>
                </p>
            </div>
        </div>
    </section>
    <script src="../js/message.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>