<?php
session_start();
include "../db.php"; // database connection

// Agar login nahi hai to redirect
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != "admin") {
    header("Location: login.php");
    exit();
}

// Users fetch
$is_user = "user";
$sql = "SELECT * FROM users WHERE usertype = '$is_user' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      min-height: 100vh;
      background: #343a40;
      color: white;
    }
    .sidebar h2 {
      padding: 20px;
      font-size: 1.5rem;
      text-align: center;
      border-bottom: 1px solid #495057;
    }
    .sidebar a {
      color: #adb5bd;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background: #495057;
      color: white;
    }
    .header {
      background: #fff;
      border-bottom: 1px solid #dee2e6;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .content {
      padding: 20px;
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <nav class="col-md-3 col-lg-2 sidebar d-md-block">
      <h2>Ecom Admin</h2>
      <ul class="nav flex-column">
        <li class="nav-item"><a href="adminpage.php" class="nav-link"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="nav-item"><a href="users.php" class="nav-link"><i class="fa fa-users"></i> Users</a></li>
        <li class="nav-item"><a href="add_product.php" class="nav-link"><i class="fa fa-plus"></i> Add Products</a></li>
        <li class="nav-item"><a href="display_product.php" class="nav-link"><i class="fa fa-box"></i> View Products</a></li>
        <li class="nav-item"><a href="all_orders.php" class="nav-link"><i class="fa fa-box"></i> Orders</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="header">
        <h4>Welcome, Admin</h4>
        <a href="../logout.php" class="btn btn-danger btn-sm"><i class="fa fa-sign-out-alt"></i> Logout</a>
      </div>

      <div class="content">
        <h2 class="mb-4">All Users</h2>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
              </tr>
            </thead>
            <tbody>
              <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                  <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center">No users found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
