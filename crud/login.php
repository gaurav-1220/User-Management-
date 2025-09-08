<?php
session_start();
require 'db.php';
$error_message = '';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_identifier = trim($_POST['login_identifier']); //username or email
    $password = trim($_POST['password']);

    if (empty($login_identifier) || empty($password)) {
        $error_message = "Username/Email and password are required.";
    } else {
    
        $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        mysqli_stmt_bind_param($stmt, "ss", $login_identifier, $login_identifier);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password_from_db);
            mysqli_stmt_fetch($stmt);

            //verify the password
            if (password_verify($password, $hashed_password_from_db)) {
                
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                
                header("Location: index.php");
                exit();
            } else {
                
                $error_message = "Invalid username/email or password. 😨";
            }
        } else {
            $error_message = "No user found, Sign up before login. 😑";
        }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Animated User Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="ring">
        <i></i>
        <i></i>
        <i></i>
        <div class="login">
            <h2>User Login</h2>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="login.php" method="post">
                <div class="inputBx">
                    <label for="login_identifier">Username or Email:</label>
                    <input type="text" name="login_identifier" id="login_identifier" autocomplete="off" required>
                </div>
                <div class="inputBx">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="inputBx">
                    <input type="submit" value="Login">
                </div>
            </form>
            <div class="links">
                <p>Don't have an account? <a href="create.php">Sign Up</a></p>
            </div>
        </div>
    </div>
</body>
</html>