<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If user not logged in, send them to login page


$page_title = 'Home • JUNKIES';

// Define the body as a function:
$content = function () {
    require __DIR__ . '/homepage.php';
};

require __DIR__ . '/main.php';