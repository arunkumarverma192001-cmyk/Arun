<?php
session_start();

if (!isset($_SESSION['usertype'])) {
    header("Location: login.php");
    exit();
}
include "../db.php"; // database connection

// ---- Delete Logic ----
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Get image name first to delete from folder
    $imgQuery = mysqli_query($conn, "SELECT image FROM products WHERE id=$id");
    if ($imgQuery && mysqli_num_rows($imgQuery) > 0) {
        $imgRow = mysqli_fetch_assoc($imgQuery);
        $imagePath = "uploads/" . $imgRow['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // delete image file
        }
    }

    // Delete product from database
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    header("Location: display_product.php?msg=deleted");
    exit();
}
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
    .content {
      padding: 20px;
    }
    img.product-img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid #ddd;
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
        <h3 class="mb-4">All Products</h3>

        <?php if (isset($_GET['msg']) && $_GET['msg']=="deleted") { ?>
          <div class="alert alert-success">Product deleted successfully!</div>
        <?php } ?>

        <!-- Responsive Table -->
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
              <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Image</th>
                <th>Delete</th>
                <th>Update</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT * FROM products ORDER BY id DESC";
              $result = mysqli_query($conn, $sql);

              if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      ?>
                      <tr>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['description']); ?></td>
                        <td><?= htmlspecialchars($row['quantity']); ?></td>
                        <td>$<?= htmlspecialchars($row['price']); ?></td>
                        <td>
                          <?php if (!empty($row['image'])) { ?>
                            <img src="uploads/<?= htmlspecialchars($row['image']); ?>" class="product-img">
                          <?php } else { ?>
                            <img src="https://via.placeholder.com/70" class="product-img">
                          <?php } ?>
                        </td>
                        <td>
                          <a href="display_product.php?delete=<?= $row['id']; ?>" 
                             onclick="return confirm('Are you sure you want to delete this product?');" 
                             class="btn btn-sm btn-danger">
                             <i class="fa fa-trash"></i> Delete
                          </a>
                        </td>
                        <td>
                          <!-- Update Button (opens modal) -->
                          <button 
                            class="btn btn-sm btn-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editModal<?= $row['id']; ?>">
                            <i class="fa fa-edit"></i> Update
                          </button>
                        </td>
                      </tr>

                      <!-- Edit Modal -->
                      <div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                              <h5 class="modal-title">Edit Product - <?= htmlspecialchars($row['title']); ?></h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="update_product.php" method="POST" enctype="multipart/form-data">
                              <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">

                                <div class="mb-3">
                                  <label class="form-label">Title</label>
                                  <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($row['title']); ?>" required>
                                </div>

                                <div class="mb-3">
                                  <label class="form-label">Description</label>
                                  <textarea name="description" class="form-control" required><?= htmlspecialchars($row['description']); ?></textarea>
                                </div>

                                <div class="mb-3">
                                  <label class="form-label">Quantity</label>
                                  <input type="number" name="quantity" class="form-control" value="<?= htmlspecialchars($row['quantity']); ?>" required>
                                </div>

                                <div class="mb-3">
                                  <label class="form-label">Price</label>
                                  <input type="text" name="price" class="form-control" value="<?= htmlspecialchars($row['price']); ?>" required>
                                </div>

                                <div class="mb-3">
                                  <label class="form-label">Image</label><br>
                                  <?php if (!empty($row['image'])) { ?>
                                    <img src="uploads/<?= htmlspecialchars($row['image']); ?>" width="100" class="mb-2"><br>
                                  <?php } ?>
                                  <input type="file" name="image" class="form-control">
                                  <small class="text-muted">Leave empty if you don't want to change image</small>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="submit" name="update" class="btn btn-success">Update Product</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      <?php
                  }
              } else {
                  echo "<tr><td colspan='7' class='text-center text-muted'>No products found</td></tr>";
              }
              ?>
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
