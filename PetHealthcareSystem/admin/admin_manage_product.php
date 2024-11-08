<?php
include 'admin_header.php';

$products = mysqli_query($conn, "SELECT * FROM product ORDER BY product_id");

if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $type = $_POST['type'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_FILES['image']['name'];
    $target = "uploaded_img/" . basename($image);

    // Check if a record with the same name exists
    $checkDuplicate = mysqli_query($conn, "SELECT * FROM product WHERE name = '$name'");

    if (mysqli_num_rows($checkDuplicate) > 0) {
        header("Location: admin_manage_product.php?error=duplicate");
        exit;
    } else {
        // Add new product
        $insert = "INSERT INTO product (name, description, type, price, quantity, image) 
            VALUES ('$name', '$description', '$type', '$price', '$quantity', '$image')";

        $upload = mysqli_query($conn, $insert);

        if ($upload) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
            echo "<script>
                    alert('New product added successfully!');
                    window.location.href = 'admin_manage_product.php';
                  </script>";
        } else {
            echo "<script>alert('Could not add the product. Please try again.');</script>";
        }
    }
}

if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM product WHERE product_id = $deleteId");
    echo "<script>
            alert('Product deleted successfully!');
            window.location.href = 'admin_manage_product.php';
          </script>";
}

// Display an alert for duplicate entry
if (isset($_GET['error']) && $_GET['error'] === 'duplicate') {
    echo "<script>alert('A product with the same name already exists.');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pet Healthcare &bull; Manage Products</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_css/main.css">
</head>
<style>
    .add-vet{
        background-color: #2ab06b; 
        color:#fff; 
        border-radius: 5px; 
        padding: 10px 10px; 
        margin-bottom: 30px;
        width: 300px;
        height: 50px;
        font-size: 18px;
    }

    .add-vet:hover {
        background-color: #a6ffd1;
        color: #000;
        -webkit-transform: translateY(-55px);
        -ms-transform: translateY(-5px);
        transform: translateY(-5px);
    }

    .hide{
        background-color: red; 
        color:white; 
        border-radius: 5px; 
        padding: 10px 10px; 
        margin-bottom: 30px;
        float: right;
    }


    .add-box h2 {
        font-size: 30px;
        margin-top: 20px;
        color: #198c8c;
        text-shadow:2px 2px 10px white;
    }

    .add-box form {
        margin-top: 35px;
        padding: 30px;
    }

    .add-box label {
        display:block;
        font-weight: bold;
        margin-bottom: 5px;
        text-shadow:2px 2px 10px cyan;
    }

    .add-box input[type="text"], textarea, .add-box input[type="email"], .add-box input[type="password"], .add-box select, .add-box input[type="number"], .add-box input[type="file"]{
        padding: 5px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #888;
        width: 60%;
        height: 38px;
        box-sizing: border-box;
        margin-bottom: 50px;
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

    .add-box{
        margin-left: 20px;
        background-image: url("img/pet_product_image.jpg"); 
        background-repeat: no-repeat;
        background-size: cover;
    }


    .vet-display{
        margin:2rem 0;
        margin-bottom: 100px;
    }

    .vet-display .vet-display-table{
        width: 70%;
        margin: 0 auto;
        text-align: center;
        border-width: 1px;
        border-style: solid;
        border-color: black;
    }

    .vet-display .vet-display-table thead{
        background: #2ab06b;
    }

    .vet-display .vet-display-table thead tr th{
        border-bottom: 2px solid black; 
        color: white;
    }

    .vet-display .vet-display-table th{
        padding:1rem;
        font-size: 2rem;
    }


    .vet-display .vet-display-table td{
        padding: 1rem;
        font-size: 1rem;
        border-bottom: 1px solid black; 
        text-align: center; 
        vertical-align: middle; 
        background: #a6ffd1;
        max-width: 300px; 
        white-space: normal; 
        overflow-wrap: break-word; /*break long words */
        height: auto; 
    }

    .edit-btn, .delete-btn {
        display: inline-block;
        width: 120px;
        height: 20px;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 2px;
        margin-left:5px;
        font-size: 1rem;
        padding: 0.5rem 0.5rem;
        text-align: center;
        font-weight: bold;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .edit-btn {
        background-color: #59EA76;
        color: white;
        border: 2px solid #59EA76;
    }

    .edit-btn:hover {
        background-color: white;
        color: #59EA76;
        transform: translateY(-3px);
    }

    .delete-btn {
        background-color: red;
        color: white;
        border: 2px solid red;
    }

    .delete-btn:hover {
        background-color: white;
        color: red;
        transform: translateY(-3px);
    }

    .back-btn{
        background-color: white; 
        color: black; 
        border: 2px solid #96ffff;
        border-radius: 5px;
        padding: 15px;
        font-size: 20px;
        text-decoration: none;
        margin-left: 30px;
    }

    .back-btn:hover{
        background-color: #ff0000;
        color: white;
    }

    .vet-display .button-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%; /* Ensures the buttons span the entire table cell */
    }

    .vet-display .edit-btn, .vet-display .delete-btn {
        width: 40%; /* Adjust the width so the buttons have some space between them */
    }

    #myInput {
        background: white;
        background-position: 10px 12px; 
        background-repeat: no-repeat;
        border: 1px solid black;
        width: 60%; 
        margin: 50px auto;
        font-size: 16px; 
        padding: 12px 20px 12px 40px; 
        margin-bottom: 12px; 
    }
</style>

<body>
<div style="padding: 1px; margin-left: 100px; text-align:center;">
    <h1 align="center" style="margin-top: 50px; margin-bottom: 50px;">
        Manage Products
    </h1>

    <button type="submit" class="add-vet" onclick="addProduct();">Create New Product</button><br>

    <div id="add-box" class="add-box" style="display: none; padding: 10px 10px; border: 1px solid; width: 50%; margin: 50px auto;">
        <button type="submit" class="hide" onclick="hideBox();">X</button>
        <h2>Create New Product</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3" required></textarea>
            <br>
            <label for="type">Type</label>
            <select id="type" name="type" required>
                <option value="Food">Food</option>
                <option value="Toys">Toys</option>
                <option value="Accessories">Accessories</option>
                <option value="Grooming">Grooming</option>
                <option value="Health">Health</option>
            </select>
            <br>
            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" required>
            <br>
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" required>
            <br>
            <label for="image">Image</label>
            <input type="file" id="image" name="image" required>
            <br>
            <br>

            <input type="submit" name="add_product" value="Add Product" style="margin-bottom: 30px;">
        </form>
    </div>
    <br>
    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search">

    <div class="vet-display">
        <h1 style="color:black; padding-top: 50px; padding-bottom: 50px;">Product Lists</h1>
        <table class="vet-display-table" id="product-table">
            <thead>
            <tr>
                <th>ProductID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Type</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Edit/Delete</th>
            </tr>
            </thead>
            <?php while($row = mysqli_fetch_assoc($products)){ ?>
            <tr>
                <td><?php echo $row['product_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['type']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><img src="uploaded_img/<?php echo $row['image']; ?>" alt="Product Image" style="width: 100px; height: auto;"></td>
                <td>
                    <div class="button-container">
                        <a href="admin_edit_product.php?edit=<?php echo $row['product_id'];?>" class="edit-btn">Edit</a>
                        <a href="admin_manage_product.php?delete=<?php echo $row['product_id'];?>" class="delete-btn" 
                        onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </div>
                </td>

            </tr>
        <?php } ?>
        </table>
    </div>
</div>

<script>
    function addProduct() {
        document.getElementById("add-box").style.display = "block";
    }

    function hideBox() {
        document.getElementById("add-box").style.display = "none";
    }

    function myFunction() {
        // Declare variables
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("product-table");
        tr = table.getElementsByTagName("tr");

        // Start looping from the second row (index 1)
        for (i = 1; i < tr.length; i++) {
            // Loop through all table columns
            var found = false;
            for (j = 0; j < 7; j++) { 
                td = tr[i].getElementsByTagName("td")[j];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;  // Break out of the column loop if a match is found
                    }
                }
            }
            // Show or hide the row based on whether a match was found in any column
            if (found) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>

</body>
</html>
