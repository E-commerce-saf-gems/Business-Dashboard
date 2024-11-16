<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../login.css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="logo-container">
        <img src="../images/logo.png" alt="SAF GEMS Logo" class="logo">
    </div>
    <div class="login-container">
        <div class="login-form">
            <h2>Login</h2>

            <!-- Display error message as an alert if set -->
            <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']); // Clear the error after showing it
            }
            ?>
            
            <form id="loginForm" action="./login.php" method="POST">
                <div class="input-group">
                    <i class="bx bx-user"></i>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <i class="bx bx-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="login-btn">Log In</button>
            </form>
        </div>
    </div>

    <script src="../login.js"></script>
</body>
</html>
