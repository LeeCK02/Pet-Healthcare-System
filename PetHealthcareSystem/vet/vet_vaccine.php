<?php
include 'vet_header.php';

$user = mysqli_query($conn, "SELECT * FROM user order by user_id");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pet Healthcare &bull; Vaccine</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="admin_css/main.css">
    </head>

    <style>
        .user-display{
            margin:2rem 0;
            margin-bottom: 100px;
        }

        .user-display .user-display-table{
            width: 70%;
            margin: 0 auto;
            text-align: center;
            border-width: 1px;
            border-style: solid;
            border-color: black;
        }

        .user-display .user-display-table thead{
            background: #21088c;
        }

        .user-display .user-display-table thead tr th{
            border-bottom: 2px solid black; /* Adds a bottom border to the header row */
            color: white;
        }

        .user-display .user-display-table th{
            padding:1rem;
            font-size: 2rem;
            border-bottom: none;
        }


        .user-display .user-display-table td {
            padding: 1rem;
            font-size: 1rem;
            border-bottom: 1px solid black; 
            text-align: center; /* Center the content in each cell */
            vertical-align: middle; /* Vertically center the content */
            background: #c9bdff;
        }


        .view-btn{
            display: inline-block;
            width: 100px; /* Set a fixed width for consistency */
            height: 40px; /* Adjust height for consistency */
            line-height: 30px; /* Center text vertically within the button */
            cursor: pointer;
            border-radius: 0.5rem;
            margin: 0 auto; /* Centers the button horizontally within its cell */
            font-size: 1rem;
            background-color: white; 
            color: black; 
            border: 2px solid green;
            padding: 5px 10px; 
            color: black;
            text-align: center;
            text-decoration: none;
        }

        .view-btn:hover{
            background-color: green;
            color: white;
            transform: translateY(-2px);
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

        <div style="padding: 1px; margin-left: 100px; text-align:center;">
                <br>
                    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search">

                <div class="user-display">
                    <h1 style="color:black; padding-top: 50px; padding-bottom: 50px;">User Lists</h1>
                    <table class="user-display-table" id="user-table">
                        <thead>
                        <tr>
                            <th>UserID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Pet</th>
                        </tr>
                        </thead>
                        <?php while($row = mysqli_fetch_assoc($user)){ ?>
                        <tr>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                            <a href="vet_view_pet.php?user=<?php echo $row['user_id'];?>" class="view-btn"> View Pet </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </table>
                </div>
        </div>

        <script>

            function myFunction() {
                // Declare variables
                var input, filter, table, tr, td, i, j, txtValue;
                input = document.getElementById("myInput");
                filter = input.value.toUpperCase();
                table = document.getElementById("user-table");
                tr = table.getElementsByTagName("tr");

                // Start looping from the second row (index 1)
                for (i = 1; i < tr.length; i++) {
                    // Loop through all table columns
                    var found = false;
                    for (j = 0; j < 3; j++) {  // Assuming you have 3 columns (Model Type, Color, Stock)
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