<?php
session_start();

// Agar user login nahi hai to login page par redirect karo
if (!isset($_SESSION['usertype'])) {
    header("Location: login.php"); // same folder me hai
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Page</title>
</head>
<body>
    <h2>This is user login</h2>
    <a href="../logout.php">Logout</a> <!-- root folder me hai -->
</body>
</html>
