<?php
session_start();
include "../db.php"; // Database connection

if (isset($_POST['login'])) {
    $u_email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass    = $_POST['password']; // plain password from form

    // Pehle sirf email check karo
    $sql    = "SELECT * FROM users WHERE email = '$u_email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Agar password plain text (sirf admin ke case me) hai
        if ($row['password'] === $pass) {
            $_SESSION['email']    = $row['email'];
            $_SESSION['usertype'] = $row['usertype'];

            if ($row['usertype'] == "user") {
                header("Location: ../index.php");
                exit();
            } elseif ($row['usertype'] == "admin") {
                header("Location: ../admin/adminpage.php");
                exit();
            }

        // Agar password hash ke form me hai (user accounts)
        } elseif (password_verify($pass, $row['password'])) {
            $_SESSION['email']    = $row['email'];
            $_SESSION['usertype'] = $row['usertype'];

            if ($row['usertype'] == "user") {
                header("Location: ../index.php");
                exit();
            } elseif ($row['usertype'] == "admin") {
                header("Location: ../admin/adminpage.php");
                exit();
            }
        } else {
            echo "<script>alert('Invalid Password'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found'); window.location.href='login.php';</script>";
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Page</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome (for icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: Arial, sans-serif;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0px 6px 20px rgba(0,0,0,0.2);
    }
    .btn-custom {
      background: #2575fc;
      color: white;
      border-radius: 25px;
    }
    .btn-custom:hover {
      background: #1a5edb;
      color: #fff;
    }
    .form-control {
      border-radius: 10px;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card p-4">
          <div class="text-center mb-3">
            <i class="fa fa-user-circle fa-3x text-primary"></i>
            <h3 class="mt-2">Login</h3>
          </div>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <div class="d-grid">
              <button type="submit" name="login" class="btn btn-custom">Login</button>
            </div>
          </form>
          <p class="text-center mt-3">
            Don’t have an account? <a href="./register.php">Register</a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
