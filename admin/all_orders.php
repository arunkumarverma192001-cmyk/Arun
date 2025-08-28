<?php
session_start();
include "../db.php"; // ✅ Database connection

// Agar admin login nahi hai to redirect
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != "admin") {
    header("Location: ../home/login.php");
    exit();
}

// ✅ Agar status change request aayi hai
if (isset($_GET['deliver_id'])) {
    $order_id = intval($_GET['deliver_id']);
    $update_sql = "UPDATE orders SET status='Delivered' WHERE id=$order_id";
    mysqli_query($conn, $update_sql);
    header("Location: all_orders.php"); // Refresh after update
    exit();
}

// Orders fetch
$order_sql = "SELECT * FROM orders ORDER BY id DESC";
$order_result = mysqli_query($conn, $order_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Orders</title>
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
    .table img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 6px;
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
        <li class="nav-item"><a href="all_orders.php" class="nav-link active"><i class="fa fa-shopping-cart"></i> Orders</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="header">
        <h4>Welcome, Admin</h4>
        <a href="../logout.php" class="btn btn-danger btn-sm"><i class="fa fa-sign-out-alt"></i> Logout</a>
      </div>
      
      <div class="content mt-4">
        <h2 class="mb-4">📦 All Orders</h2>

        <div class="card shadow">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover align-middle">
                <thead class="table-dark">
                  <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Change Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($order_result && mysqli_num_rows($order_result) > 0): ?>
                    <?php while ($order = mysqli_fetch_assoc($order_result)): ?>
                      <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                        <td><?php echo htmlspecialchars($order['email']); ?></td>
                        <td><?php echo htmlspecialchars($order['address']); ?></td>
                        <td><?php echo htmlspecialchars($order['phone']); ?></td>
                        <td><?php echo htmlspecialchars($order['title']); ?></td>
                        <td><?php echo htmlspecialchars($order['price']); ?> ₹</td>
                        <td>
                          <?php if (!empty($order['image'])): ?>
                            <img src="../admin/uploads/<?php echo htmlspecialchars($order['image']); ?>" alt="Product">
                          <?php else: ?>
                            <span class="text-muted">No Image</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php 
                            $status = $order['status'];
                            $badgeClass = "bg-secondary";
                            if ($status == "Pending") $badgeClass = "bg-warning text-dark";
                            elseif ($status == "In Progress") $badgeClass = "bg-info";
                            elseif ($status == "Delivered") $badgeClass = "bg-success";
                          ?>
                          <span class="badge <?php echo $badgeClass; ?>">
                            <?php echo htmlspecialchars($status); ?>
                          </span>
                        </td>
                        <td>
                          <?php if ($order['status'] != "Delivered"): ?>
                            <a href="all_orders.php?deliver_id=<?php echo $order['id']; ?>" 
                               class="btn btn-sm btn-success"
                               onclick="return confirm('Mark this order as Delivered?');">
                               <i class="fa fa-check"></i> Delivered
                            </a>
                          <?php else: ?>
                            <span class="text-muted">✔ Already Delivered</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="10" class="text-center text-muted">No orders found</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
