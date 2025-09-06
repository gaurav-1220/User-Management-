<?php
require 'db.php';
$id = $_GET['id'];
$name = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = trim($_POST['name']);
    $newEmail = trim($_POST['email']);
    
    $sql_update = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt, "ssi", $newName, $newEmail, $id);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit();
    }
    mysqli_stmt_close($stmt);
}


$sql_fetch = "SELECT name, email FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql_fetch);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $name, $email); //autofill
mysqli_stmt_fetch($stmt); //autofill
mysqli_stmt_close($stmt);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <style>
        body { background-color: #f8f9fa; }
        h2 { color: #023047; }
        .btn-custom-warning { background-color: #FFB703; border-color: #FFB703; color: #023047; }
    </style>
</head>
<body>
    <div class="container mt-5" style="max-width: 600px;">
        <h2 class="mb-4">Edit User</h2>
        <form action="update.php?id=<?php echo $id; ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" autocomplete="off" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" autocomplete="off" required>
            </div>
            <button type="submit" class="btn btn-custom-warning">Update User</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>