<?php
include "../db.php";
session_start();

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);

    // Handle image upload
    $imageName = "";
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $target = "uploads/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        // delete old image
        $oldImgQuery = mysqli_query($conn, "SELECT image FROM products WHERE id=$id");
        if ($oldImgQuery && mysqli_num_rows($oldImgQuery) > 0) {
            $oldImg = mysqli_fetch_assoc($oldImgQuery)['image'];
            if ($oldImg && file_exists("uploads/" . $oldImg)) {
                unlink("uploads/" . $oldImg);
            }
        }

        $updateSql = "UPDATE products SET title='$title', description='$description', quantity='$quantity', price='$price', image='$imageName' WHERE id=$id";
    } else {
        $updateSql = "UPDATE products SET title='$title', description='$description', quantity='$quantity', price='$price' WHERE id=$id";
    }

    if (mysqli_query($conn, $updateSql)) {
        header("Location: display_product.php?msg=updated");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
