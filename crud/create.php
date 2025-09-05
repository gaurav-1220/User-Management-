<?php
require 'db.php';
$name = '';
$email = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (empty($name)) {
        $error_message = "Name cannot be empty.";
    } else {
        // Checking for existing email
        $sql_check = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error_message = "Email already exists.";
        } else {

            $sql_insert = "INSERT INTO users (name, email) VALUES (?, ?)";
            $stmt_insert = mysqli_prepare($conn, $sql_insert);
            mysqli_stmt_bind_param($stmt_insert, "ss", $name, $email);
            
            if (mysqli_stmt_execute($stmt_insert)) {
                header("Location: index.php");
                exit();
            } else {
                
                $error_message = "Error: " . mysqli_error($conn);
            }
            
            mysqli_stmt_close($stmt_insert);
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
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="create.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" autocomplete="off" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" autocomplete="off" required>
            </div>
            <button type="submit" class="btn btn-custom-primary">Add User</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>