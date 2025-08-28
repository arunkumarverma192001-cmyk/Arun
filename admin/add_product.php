<?php
session_start();

if (!isset($_SESSION['usertype'])) {
    header("Location: login.php");
    exit();
}

include '../db.php'; // apna database connection file

if (isset($_POST['add_product'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $qty = $_POST['qty']; // ✅ corrected name

    // Image upload handle
    $image = null;
    if (!empty($_FILES['image']['name'])) {

        // Uploads folder check
        $uploadDir = __DIR__ . "/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $image = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $image;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            echo "<script>alert('Image upload failed!');</script>";
            $image = null;
        }
    }

    // ✅ Corrected $qty instead of $quantity
    $sql = "INSERT INTO products (title, description, price, quantity, image) 
            VALUES ('$title', '$description', '$price', '$qty', '$image')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product added successfully');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background-color: #f8f9fa; }
    .sidebar { min-height: 100vh; background: #343a40; color: white; }
    .sidebar h2 { padding: 20px; font-size: 1.5rem; text-align: center; border-bottom: 1px solid #495057; }
    .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 12px 20px; transition: 0.3s; }
    .sidebar a:hover { background: #495057; color: white; }
    .header { background: #fff; border-bottom: 1px solid #dee2e6; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
    .content { padding: 20px; }
    .card { box-shadow: 0 0 10px rgba(0,0,0,0.1); }
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
         <li class="nav-item"><a href="all_orders.php" class="nav-link"><i class="fa fa-box"></i>Orders</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="header">
        <h4>Welcome, Admin</h4>
        <a href="../logout.php" class="btn btn-danger btn-sm"><i class="fa fa-sign-out-alt"></i> Logout</a>
      </div>

      <div class="content">
        <h3 class="mb-4">Add Product</h3>
        <div class="card">
          <div class="card-body">
            <!-- enctype added for file upload -->
            <form method="post" action="" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="title" class="form-label">Product Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter product title" required>
              </div>

              <div class="mb-3">
                <label for="description" class="form-label">Product Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter product description" required></textarea>
              </div>

              <div class="mb-3">
                <label for="price" class="form-label">Price ($)</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required>
              </div>

              <div class="mb-3">
                <label for="qty" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="qty" name="qty" placeholder="Enter quantity" required>
              </div>

              <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image">
              </div>

              <button type="submit" name="add_product" class="btn btn-primary"><i class="fa fa-plus"></i> Add Product</button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
