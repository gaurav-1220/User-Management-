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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        h2 { color: #023047; }
        .btn-custom-primary { background-color: #219EBC; border-color: #219EBC; color: white; }
    </style>
</head>
<body>
    <div class="container mt-5" style="max-width: 500px;">
        <h2 class="text-center mb-4">User Login</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="login_identifier" class="form-label">Username or Email:</label>
                <input type="text" name="login_identifier" id="login_identifier" class="form-control" autocomplete="off" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-custom-primary w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <p>Don't have an account? <a href="create.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>