<?php
include 'admin_header.php';

$vet = mysqli_query($conn, "SELECT * FROM veterinary  order by vet_id");

if (isset($_POST['add_vet'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $profile_picture = 'uploads/default_profile_pic.png'; // Set the default profile picture

    // Check if a record with the same name or email exists
    $checkDuplicate = mysqli_query($conn, "SELECT * FROM veterinary WHERE name = '$name' OR email = '$email'");

    if (mysqli_num_rows($checkDuplicate) > 0) {
        header("Location: admin_manage_veterinary.php?error=duplicate");
        exit;
    } else {
        // Add new veterinary, including the default profile picture
        $insert = "INSERT INTO veterinary (name, email, password, profile_picture) VALUES ('$name', '$email', '$password', '$profile_picture')";
        
        $upload = mysqli_query($conn, $insert);

        if ($upload) {
            // Use a JavaScript alert for success
            echo "<script>
                    alert('New veterinary account added successfully!');
                    window.location.href = 'admin_manage_veterinary.php';
                  </script>";
        } else {
            echo "<script>alert('Could not add the veterinary account. Please try again.');</script>";
        }
    }
}



if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM veterinary WHERE vet_id = $deleteId");
    header('location:admin_manage_veterinary.php');
}

// Display an alert for duplicate entry
if (isset($_GET['error']) && $_GET['error'] === 'duplicate') {
    echo "<script>alert('A veterinary account with the name or email already exists.');</script>";
}


?>

<!DOCTYPE html>
<html>


    <head>
        <title>Pet Healthcare &bull; Manage Veterinary</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="admin_css/main.css">
    </head>

    <style>
        .add-box{
            margin-left: 20px;
            background-image: url("img/vet_image.png"); 
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>

        <div style="padding: 1px; margin-left: 100px; text-align:center;">
            <h1 align="center" style="margin-top: 50px; margin-bottom: 50px;">
                Manage Veterinary
            </h1>

            <button type="submit" class="add-vet" onclick="addVet();">Create New Veterinary Account</button><br>

                <div id="add-box" class="add-box" style="display: none; padding: 10px 10px; border: 1px solid; width: 50%; margin: 50px auto;">
                    <button type="submit" class="hide" onclick="hideBox();">X</button>
                    <h2>Create New Veterinary Account</h2>
                    <form action="" method="post">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                        <br>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                        <br>
                        <label for="name">Password</label>
                        <input type="password" id="password" name="password" required>
                        <br>
                        <br>

                        <input type="submit" name="add_vet" value="Add Veterinary Account" style="margin-bottom: 30px;">
                    </form>
                </div>
                <br>
                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search">

                <div class="vet-display">
                    <h1 style="color:black; padding-top: 50px; padding-bottom: 50px;">Veterinary Lists</h1>
                    <table class="vet-display-table" id="vet-table">
                        <thead>
                        <tr>
                            <th>VetID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Edit/Delete</th>
                        </tr>
                        </thead>
                        <?php while($row = mysqli_fetch_assoc($vet)){ ?>
                        <tr>
                            <td><?php echo $row['vet_id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <div class="button-container">
                                    <a href="admin_edit_veterinary.php?edit=<?php echo $row['vet_id']; ?>" class="edit-btn">Edit</a>
                                    <a href="admin_manage_veterinary.php?delete=<?php echo $row['vet_id']; ?>" class="delete-btn" 
                                    onclick="return confirm('Are you sure you want to delete this veterinary account?');">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </table>
                </div>
        </div>

        <script>

            function addVet() {
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
                table = document.getElementById("stock-table");
                tr = table.getElementsByTagName("tr");

                // Start looping from the second row (index 1)
                for (i = 1; i < tr.length; i++) {
                    // Loop through all table columns
                    var found = false;
                    for (j = 0; j < 3; j++) {  
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