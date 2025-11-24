<?php
// include database config and helper functions
require_once("config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUNKIES</title>
    <link rel="stylesheet" href="styles.css">
    <!--Load Animated Icons -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

</head>
<body>
        <nav class="nav-bar">
            <div class="nav-bar-links-container">
                <a class="nav-bar-link" href="login.php">Login</a>
                <a class="nav-bar-link" href="register.php">Register</a>
                <a class="nav-bar-link" href="profile.php">Profile</a>
                <a class="nav-bar-link" href="logout.php">Logout</a>
            </div>
            <div class="nav-bar-icons-container">
                <a href="#" class="nav-icon" title="Cart">
                    <lottie-player
                        src="assets/icons/cart.json"
                        background="transparent"
                        speed="1"
                        hover
                        class="icon-lottie"
                    ></lottie-player>
                </a>
                <a href="#" class="nav-icon" title="Profile">
                    <lottie-player
                        src="assets/icons/profileIcon.json"
                        background="transparent"
                        speed="1"
                        hover
                        class="icon-lottie"
                    ></lottie-player>
                </a>
            </div>
            

        </nav>
</body>
