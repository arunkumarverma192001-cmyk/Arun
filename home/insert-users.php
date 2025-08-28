<?php
include "../db.php"; // path adjust करें

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';

    // Required fields check
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($password)) {
        echo "error: Missing required fields";
        exit;
    }

    // Email exists check
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "exists";
        $check->close();
        exit;
    }
    $check->close();

    // Insert user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, address, password) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "error: " . $conn->error;
        exit;
    }

    $stmt->bind_param("sssss", $name, $email, $phone, $address, $hashed_password);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "error: Invalid request";
}
?>
