<?php
session_start();
include 'config/config.php'; // Database connection
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vet Page</title>
    <link rel="stylesheet" href="vet_css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <h1>Veterinary</h1>
        </div>
        <ul class="nav-links">
            <li><a href="vet_index.php">Home</a></li>
            <li><a href="vet_appointment.php">Appointments</a></li>
            <li><a href="vet_chat_list.php">Consultations</a></li>
            <li><a href="vet_vaccine.php">Vaccine Records</a></li>
            <li><a href="vet_vaccine.php">Medical Records</a></li>
            <li><a href="vet_room_list.php">Hospitalization</a></li>
        </ul>
        <div class="dropdown" style="margin-left: auto;">
            <?php if (isset($_SESSION['vet_name'])): ?>
                <a class="dropdown-toggle" href="#" role="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span style="margin-left: 5px;">Welcome, <?php echo htmlspecialchars($_SESSION['vet_name']); ?></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                    <li><a class="dropdown-item" href="vet_account.php">Account</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            <?php else: ?>
                <a href="vet_login.php" class="btn-login">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
