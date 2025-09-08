<?php
session_start();
require 'db.php';
$name = ''; $username = ''; $email = ''; $password = ''; $error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    
    if (empty($username)) {
        $error_message = "Username cannot be empty.";
    } elseif (empty($name)) {
        $error_message = "Name cannot be empty.";
    } elseif (empty($password)) {
        $error_message = "Password cannot be empty.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $error_message = "Password must contain at least one special character (e.g., !, @, #, $, %)";
    } else {
    
        
        $sql_check_username = "SELECT id FROM users WHERE username = ?";
        $stmt_username = mysqli_prepare($conn, $sql_check_username);
        mysqli_stmt_bind_param($stmt_username, "s", $username);
        mysqli_stmt_execute($stmt_username);
        mysqli_stmt_store_result($stmt_username);

        if (mysqli_stmt_num_rows($stmt_username) > 0) {
            $error_message = "Username is already taken. Please choose another.";
        } else {
            $sql_check_email = "SELECT id FROM users WHERE email = ?";
            $stmt_email = mysqli_prepare($conn, $sql_check_email);
            mysqli_stmt_bind_param($stmt_email, "s", $email);
            mysqli_stmt_execute($stmt_email);
            mysqli_stmt_store_result($stmt_email);

            if (mysqli_stmt_num_rows($stmt_email) > 0) {
                $error_message = "Email already exists.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql_insert = "INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)";
                $stmt_insert = mysqli_prepare($conn, $sql_insert);
                mysqli_stmt_bind_param($stmt_insert, "ssss", $name, $username, $email, $hashed_password);
                
                if (mysqli_stmt_execute($stmt_insert)) {
                    $new_user_id = mysqli_insert_id($conn);
                    $_SESSION['user_id'] = $new_user_id;
                    $_SESSION['username'] = $username;
                    header("Location: index.php");
                    exit();
                } else {
                    $error_message = "Error: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt_insert);
            }
            mysqli_stmt_close($stmt_email);
        }
        mysqli_stmt_close($stmt_username);
    
    /*
    }
    */
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        h2 { color: #023047; }
        .btn-custom-primary { background-color: #219EBC; border-color: #219EBC; color: white; }
    </style>
</head>
<body>
    <div class="container mt-5" style="max-width: 600px;">
        <h2 class="mb-4">Add New User</h2>
        <div id="error-message-container" class="alert alert-danger" style="display: none;">
            <p id="error-text"></p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form action="create.php" method="post" id="signup-form">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control" autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-custom-primary">Add User</button>
            <a href="login.php" class="btn btn-secondary">Cancel</a> 
        </form>
    </div>

    <script src="validation.js"></script>
</body>
</html>