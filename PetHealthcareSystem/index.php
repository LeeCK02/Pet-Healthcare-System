<?php 
include 'header.php';
?>
<style>
    .main {
        padding: 100px 100px;
    }
    .section {
        display: flex;
        align-items: center;
        margin-bottom: 40px; 
    }
    .section:nth-child(even) {
        flex-direction: row-reverse;
    }
    .section img {
        max-width: 100%;
        height: auto;
        width: 50%; 
    }
    .section .text-content {
        padding: 20px;
        width: 50%; 
    }
    .section h2 {
        font-size: 2rem;
        color: #ff0066; 
    }
    .section p {
        font-size: 1.125rem;
    }
    .banner {
        width: 100%;
        height: 500px;
        margin-top: 20px;
        background-image: url('img/pet_banner1.jpg'); 
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-align: center;
    }
    .banner h1 {
        font-size: 3rem;
        margin: 0;
    }
</style>

<div class="banner">
    <h1>Welcome to Pet Healthcare</h1>
</div>

<div class="main">
    <!-- First Section -->
    <div class="section">
        <img src="img/PetHealthcare1.jpg" alt="Pet Healthcare Image 1"> <!-- Replace with actual image -->
        <div class="text-content">
            <h2>Welcome to Pet Healthcare</h2>
            <p>At Pet Healthcare, we are dedicated to providing exceptional care and love for your furry companions. Our state-of-the-art facility is equipped with the latest technology to ensure that your pets receive the best possible treatment. Discover a range of services designed to keep your pets happy and healthy.</p>
        </div>
    </div>

    <!-- Second Section -->
    <div class="section">
        <img src="img/PetHealthcare2.jpg" alt="Pet Healthcare Image 2"> <!-- Replace with actual image -->
        <div class="text-content">
            <h2>Our Services</h2>
            <p>From routine check-ups to emergency care, our experienced team of veterinarians offers a wide range of services to meet the needs of your pets. We specialize in preventive care, surgical procedures, and advanced diagnostics to ensure that your pets are well taken care of.</p>
        </div>
    </div>

    <!-- Third Section -->
    <div class="section">
        <img src="img/PetHealthcare3.png" alt="Pet Healthcare Image 3"> <!-- Replace with actual image -->
        <div class="text-content">
            <h2>Contact Us</h2>
            <p>We’re here to answer any questions you may have and to schedule appointments for your pets. Our friendly staff is always ready to assist you with any concerns. Contact us today to learn more about our services or to book a visit to our clinic.</p>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>