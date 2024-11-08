<?php 

session_start();
session_destroy();

header("Location: vet_login.php");

?>
