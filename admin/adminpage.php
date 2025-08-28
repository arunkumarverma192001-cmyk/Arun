<?php
session_start();
include "../db.php"; // Database connection

// Agar admin login nahi hai to redirect
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Total Users
$user_sql = "SELECT COUNT(*) AS total_users FROM users WHERE usertype='user'";
$user_result = mysqli_query($conn, $user_sql);
$user_count = mysqli_fetch_assoc($user_result)['total_users'];

// Total Products
$product_sql = "SELECT COUNT(*) AS total_products FROM products";
$product_result = mysqli_query($conn, $product_sql);
$product_count = mysqli_fetch_assoc($product_result)['total_products'];

// Total Orders
$order_sql = "SELECT COUNT(*) AS total_orders FROM orders";
$order_result = mysqli_query($conn, $order_sql);
$order_count = mysqli_fetch_assoc($order_result)['total_orders'];

// Total Delivered Orders
$delivered_sql = "SELECT COUNT(*) AS total_delivered FROM orders WHERE status='Delivered'";
$delivered_result = mysqli_query($conn, $delivered_sql);
$delivered_count = mysqli_fetch_assoc($delivered_result)['total_delivered'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
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
    .dashboard-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      text-align: center;
      padding: 20px;
      background: #fff;
    }
    .card h3 {
      font-size: 20px;
      margin-bottom: 10px;
    }
    .card p {
      font-size: 24px;
      font-weight: bold;
      color: #007bff;
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
        <li class="nav-item"><a href="all_orders.php" class="nav-link"><i class="fa fa-shopping-cart"></i> Orders</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="header">
        <h4>Welcome, Admin</h4>
        <a href="../logout.php" class="btn btn-danger btn-sm"><i class="fa fa-sign-out-alt"></i> Logout</a>
      </div>

      <!-- Dashboard Stats -->
      <div class="dashboard-cards">
        <div class="card">
          <h3>Total Users</h3>
          <hr>
          <p><?php echo $user_count; ?></p>
        </div>
        <div class="card">
          <h3>Total Products</h3>
          <hr>
          <p><?php echo $product_count; ?></p>
        </div>
        <div class="card">
          <h3>Total Orders</h3>
          <hr>
          <p><?php echo $order_count; ?></p>
        </div>
        <div class="card">
          <h3>Total Delivered</h3>
          <hr>
          <p><?php echo $delivered_count; ?></p>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
