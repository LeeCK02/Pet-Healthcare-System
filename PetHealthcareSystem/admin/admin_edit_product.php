<?php
include 'config/config.php';

$id = $_GET['edit'];

if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $imageTmpName = $_FILES['image']['tmp_name'];
    $target = "uploaded_img/" . basename($image);

    // Retrieve the existing image
    $select = mysqli_query($conn, "SELECT image FROM product WHERE product_id = '$id'");
    $row = mysqli_fetch_assoc($select);
    $existingImage = $row['image'];

    // Check for duplicate product name
    $checkDuplicate = mysqli_query($conn, "SELECT * FROM product WHERE name = '$name' AND product_id != '$id'");
    if (mysqli_num_rows($checkDuplicate) > 0) {
        echo "<script>alert('A product with the same name already exists. Please choose a different name.');</script>";
    } else {
        if (!empty($image)) {
            // If a new image is uploaded, update the image
            $update_data = "UPDATE product SET name='$name', description='$description', type='$type', price='$price', quantity='$quantity', image='$image' WHERE product_id='$id'";
            // Move the uploaded file to the target directory
            move_uploaded_file($imageTmpName, $target);

            // Optionally delete the old image file if you want to clean up
            if (!empty($existingImage) && file_exists("uploaded_img/" . $existingImage)) {
                unlink("uploaded_img/" . $existingImage);
            }
        } else {
            // If no new image is uploaded, keep the existing image
            $update_data = "UPDATE product SET name='$name', description='$description', type='$type', price='$price', quantity='$quantity' WHERE product_id='$id'";
        }

        $upload = mysqli_query($conn, $update_data);

        if ($upload) {
            // Use JavaScript alert for successful update
            echo "<script>
                    alert('Product updated successfully!');
                    window.location.href = 'admin_manage_product.php';
                  </script>";
        } else {
            echo "<script>alert('Update failed. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pet Healthcare &bull; Update Product</title>
    <meta charset="UTF-8">
    <link rel="icon" href="img/honda-icon.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_css/main.css">
</head>

<style>
    .add-box h2 {
        font-size: 30px;
        margin-top: 20px;
        color: #198c8c;
        text-shadow: 2px 2px 10px white;
    }

    .add-box form {
        margin-top: 35px;
        padding: 30px;
    }

    .add-box label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        text-shadow: 2px 2px 10px cyan;
    }

    .add-box input[type="text"], textarea, .add-box input[type="file"], .add-box select {
        padding: 5px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #888;
        width: 60%;
        height: 38px;
        box-sizing: border-box;
        margin-bottom: 20px;
        text-align: center;
    }

    .add-box input[type="submit"] {
        background-color: white;
        color: black;
        border: 2px solid #96ffff;
        border-radius: 5px;
        padding: 15px;
        font-size: 20px;
    }

    .add-box input[type="submit"]:hover {
        background-color: #2ab06b;
        color: white;
    }

    .add-box {
        background: #abffd4;
    }

    .back-btn {
        display: inline-block;
        background-color: white;
        color: black;
        border: 2px solid #96ffff;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 16px;
        text-decoration: none;
        margin-top: 10px;
    }

    .back-btn:hover {
        background-color: #ff0000;
        color: white;
    }
</style>

<body>
<div style="padding: 1px; margin: 0 auto; text-align: center; margin-top: 150px;">
    <?php
        $select = mysqli_query($conn, "SELECT * FROM product WHERE product_id = '$id'");
        $row = mysqli_fetch_assoc($select);
    ?>
    <div id="add-box" class="add-box" style="padding: 10px 10px; border: 1px solid; margin-top: 30px; margin-bottom: 60px; width: 50%; margin: 0 auto;">
        <h2>Update Product</h2>
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return confirmUpdate();">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            <br>
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3" required><?php echo htmlspecialchars($row['description']); ?></textarea>
            <br>
            <label for="type">Type</label>
            <select id="type" name="type" required>
                <option value="Food" <?php echo $row['type'] === 'Food' ? 'selected' : ''; ?>>Food</option>
                <option value="Toys" <?php echo $row['type'] === 'Toys' ? 'selected' : ''; ?>>Toys</option>
                <option value="Accessories" <?php echo $row['type'] === 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
                <option value="Grooming" <?php echo $row['type'] === 'Grooming' ? 'selected' : ''; ?>>Grooming</option>
                <option value="Health" <?php echo $row['type'] === 'Health' ? 'selected' : ''; ?>>Health</option>
            </select>
            <br>
            <label for="price">Price</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required>
            <br>
            <label for="quantity">Quantity</label>
            <input type="text" id="quantity" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>" required>
            <br>
            <label for="image">Image</label>
            <?php if (!empty($row['image'])): ?>
                <div>
                    <img src="uploaded_img/<?php echo htmlspecialchars($row['image']); ?>" alt="Current Image" style="max-width: 200px; margin-bottom: 10px;">
                    <p>Change image (optional)</p>
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image">
            <br>
            <br>
            <input type="submit" name="update_product" value="Update Product">
            <a href="admin_manage_product.php" class="back-btn">Back</a>
        </form>
    </div>
</div>

<script>
    function confirmUpdate() {
        return confirm('Are you sure you want to update this product?');
    }
</script>
</body>
</html>
