<?php
session_start();
include "db.php"; // db connection

// URL se values lena
$p_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$u_email = isset($_GET['email']) ? mysqli_real_escape_string($conn, $_GET['email']) : '';

$order_message = "";

// STEP 1: Product fetch
$p_sql = "SELECT * FROM products WHERE id = $p_id LIMIT 1";
$p_result = mysqli_query($conn, $p_sql);

if ($p_result && mysqli_num_rows($p_result) > 0) {
    $row = mysqli_fetch_assoc($p_result);
    $product_title = $row['title'];
    $product_price = $row['price'];
    $product_image = $row['image'];
    $product_desc  = $row['description'];
} else {
    $product_title = "Product Not Found";
    $product_price = "";
    $product_desc  = "";
    $product_image = "";
}

// STEP 2: User fetch
$u_sql = "SELECT * FROM users WHERE email = '$u_email' LIMIT 1";
$u_result = mysqli_query($conn, $u_sql);
if ($u_result && mysqli_num_rows($u_result) > 0) {
    $u_row = mysqli_fetch_assoc($u_result);
    $u_name = $u_row['name'];
    $u_phone = $u_row['phone'];
    $u_address = $u_row['address'];
} else {
    $u_name = "Unknown User";
    $u_phone = "";
    $u_address = "";
}

// STEP 3: Order Insert
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $status = "In Progress";

    $order_sql = "INSERT INTO orders (title, price, image, username, email, phone, address, status) 
                  VALUES ('$product_title', '$product_price', '$product_image', '$u_name', '$u_email', '$u_phone', '$u_address', '$status')";
    
    if (mysqli_query($conn, $order_sql)) {
        $order_message = "<div class='alert alert-success mt-3'>✅ Order placed successfully!</div>";
    } else {
        $order_message = "<div class='alert alert-danger mt-3'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Order Page</title>
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
            letter-spacing: 1px;
            white-space: nowrap;
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
        .offcanvas {
            background: linear-gradient(180deg, #ff4d4d, #cc0000);
            color: white;
            width: 250px;
        }
        .offcanvas a {
            color: white;
            font-size: 1.1rem;
            text-transform: uppercase;
            padding: 0.6rem 0;
        }
        .offcanvas a:hover {
            color: yellow;
            padding-left: 10px;
        }
        .menu-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.4rem;
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
            <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>

            <?php if (isset($_SESSION['email'])) { ?>
            	 <li class="nav-item"><a class="nav-link" href="order.php?email=<?php echo $_SESSION['email'] ?> ">Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            <?php } else { ?>
                <li class="nav-item"><a class="nav-link" href="home/register.php">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="home/login.php">Login</a></li>
            <?php } ?>
        </ul>
        <button class="menu-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu">
            <i class="fa fa-bars"></i>
        </button>
    </div>
</nav>

<!-- ✅ Offcanvas Menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Product</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
            <?php if (isset($_SESSION['email'])) { ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            <?php } else { ?>
                <li class="nav-item"><a class="nav-link" href="home/register.php">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="home/login.php">Login</a></li>
            <?php } ?>
        </ul>
    </div>
</div>

<!-- ✅ Order Section -->
<div class="container py-5 mt-5">
	<h2 class="mb-4">Order Page</h2>

    <?php echo $order_message; ?>

	<div class="card p-4 shadow">
        <?php if ($product_image): ?>
            <img src="admin/uploads/<?php echo htmlspecialchars($product_image); ?>" width="200" class="mb-3">
        <?php endif; ?>

		<h4>Product: <?php echo htmlspecialchars($product_title); ?></h4>
		<p><strong>Price:</strong> <?php echo htmlspecialchars($product_price); ?> ₹</p>
		<p><strong>Description:</strong> <?php echo htmlspecialchars($product_desc); ?></p>

		<hr>
		<h5>User Details</h5>
		<p><strong>Name:</strong> <?php echo htmlspecialchars($u_name); ?></p>
		<p><strong>Email:</strong> <?php echo htmlspecialchars($u_email); ?></p>
		<p><strong>Phone:</strong> <?php echo htmlspecialchars($u_phone); ?></p>
		<p><strong>Address:</strong> <?php echo htmlspecialchars($u_address); ?></p>

        <?php if ($product_title !== "Product Not Found"): ?>
		<form method="post">
			<button type="submit" class="btn btn-primary">Place Order</button>
		</form>
        <?php endif; ?>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
