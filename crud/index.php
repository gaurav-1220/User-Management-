<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        h1 { color: #023047; }
        .btn-custom-primary { background-color: #219EBC; border-color: #219EBC; color: white; }
        .btn-custom-primary:hover { background-color: #1a8db3; border-color: #1a8db3; }
        .btn-custom-warning { background-color: #FFB703; border-color: #FFB703; color: #023047; }
        .btn-custom-warning:hover { background-color: #e6a502; border-color: #e6a502; }
        .btn-custom-danger { background-color: #FB8500; border-color: #FB8500; color: white; }
        .btn-custom-danger:hover { background-color: #e27800; border-color: #e27800; }
        .table thead { background-color: #8ECAE6; color: #023047; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">User List</h1>
            <div>
                <!-- welcome message -->
                <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! 🥳</span>

                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>

        <a href="create.php" class="btn btn-custom-primary mb-3">Add New User</a>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, name, username, email FROM users ORDER BY id ASC";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td>
                                <a href='update.php?id=" . $row["id"] . "' class='btn btn-sm btn-custom-warning'>Edit</a> 
                                <a href='delete.php?id=" . $row["id"] . "' class='btn btn-sm btn-custom-danger' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No users found</td></tr>";
                }
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>