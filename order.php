<?php
session_start();
include "db.php"; // db connection

// Agar user login nahi hai to login page par bhejo
if (!isset($_SESSION['email'])) {
    header("Location: home/login.php");
    exit();
}

$u_email = $_SESSION['email'];

// User ke orders fetch karo
$order_sql = "SELECT * FROM orders WHERE email = '$u_email' ORDER BY id DESC";
$order_result = mysqli_query($conn, $order_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>My Orders</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <style>
        nav.navbar {
            background: linear-gradient(90deg, #0044cc, #007bff);
            padding: 0.6rem 1rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            height: 60px;
            z-index: 1046;
        }
        .my_logo {
            color: white;
            font-size: 1.4rem;
            font-weight: bold;
        }
        .navbar-nav .nav-link {
            color: white !important;
            text-transform: uppercase;
            font-weight: 500;
            margin-left: 1rem;
        }
        .navbar-nav .nav-link:hover {
            color: yellow !important;
        }
        .order-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 15px;
            padding: 15px;
            background: #fff;
        }
        .order-card img {
            border-radius: 8px;
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand my_logo" href="index.php">My Ecommerce</a>
        <ul class="navbar-nav flex-row d-none d-lg-flex">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Product</a></li>
            <li class="nav-item"><a class="nav-link" href="order.php"> Orders</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<!-- ✅ Orders Section -->
<div class="container py-5 mt-5">
	<h2 class="mb-4">My Orders</h2>

    <?php if ($order_result && mysqli_num_rows($order_result) > 0): ?>
        <?php while ($order = mysqli_fetch_assoc($order_result)): ?>
            <div class="order-card d-flex align-items-center shadow-sm">
                <img src="admin/uploads/<?php echo htmlspecialchars($order['image']); ?>" alt="Product">
                <div class="ms-3">
                    <h5><?php echo htmlspecialchars($order['title']); ?></h5>
                    <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($order['price']); ?></p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-info text-dark"><?php echo htmlspecialchars($order['status']); ?></span>
                    </p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-warning">⚠️ You have not placed any orders yet.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
