<?php
session_start();
include "db.php"; // database connection

// Default query
$sql = "SELECT * FROM products ORDER BY id DESC";

// Search functionality
if (isset($_GET['search']) && !empty($_GET['my_search'])) {
    $search_value = mysqli_real_escape_string($conn, $_GET['my_search']);
    $sql = "SELECT * FROM products 
            WHERE title LIKE '%$search_value%' 
            OR description LIKE '%$search_value%' 
            ORDER BY id DESC";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Ecommerce</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
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
        .my_cover {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .my_card h3 {
            text-align: center;
            font-size: 25px;
            padding: 20px;
            font-weight: bold;
        }
        .card {
            border: none;
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        .card img {
            height: 200px;
            object-fit: cover;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border: 2px solid #007bff;
        }
        .btn-primary {
            border-radius: 20px;
            padding: 5px 15px;
        }
        .search-box {
            text-align: center;
            margin-bottom: 30px;
        }
        .search-box input[type="text"] {
            padding: 8px;
            width: 250px;
            border-radius: 20px;
            border: 1px solid #ccc;
            outline: none;
        }
        .search-box input[type="submit"] {
            padding: 8px 20px;
            border-radius: 20px;
            border: none;
            background: #007bff;
            color: white;
            cursor: pointer;
        }
        .search-box input[type="submit"]:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand my_logo" href="index.php">My Ecommerce</a>
        <ul class="navbar-nav flex-row d-none d-lg-flex">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Product</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>

            <?php if (isset($_SESSION['email'])) { ?>
                 <li class="nav-item"><a class="nav-link" href="index.php?email=<?php echo $_SESSION['email'] ?> ">Orders</a></li>
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

<!-- Offcanvas Menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="product.php">Products</a></li>
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

<!-- Banner -->

<!-- Product Section -->
<div class="my_card">
    <h3>Products</h3>

    <!-- Search Form -->
    <div class="search-box">
        <form action="" method="GET">
            <input type="text" name="my_search" placeholder="Search your products ..." value="<?php echo isset($_GET['my_search']) ? htmlspecialchars($_GET['my_search']) : ''; ?>">
            <input type="submit" name="search" value="Search">
        </form>
    </div>

    <div class="container">
        <div class="row g-4">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100">
                            <?php if (!empty($row['image'])) { ?>
                                <img src="admin/uploads/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="Product">
                            <?php } else { ?>
                                <img src="https://via.placeholder.com/200" class="card-img-top" alt="Product">
                            <?php } ?>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                <p class="fw-bold">₹<?php echo htmlspecialchars($row['price']); ?></p>
                                
                                <?php if (isset($_SESSION['email'])) { ?>
                                    <a href="my_order.php?id=<?php echo htmlspecialchars($row['id']); ?>&email=<?php echo htmlspecialchars($_SESSION['email']); ?>" class="btn btn-primary btn-sm">Buy Now</a>
                                <?php } else { ?>
                                    <a href="home/login.php" class="btn btn-primary btn-sm"> Buy Now</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-center text-muted'>No products available</p>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
