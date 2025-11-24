<?php
session_start();
$page_title = 'Home • JUNKIES';

// Define the body as a function:
$content = function () {
  // You can mix PHP and HTML here, or just include a fragment:
  require __DIR__ . '/homepage.php';
};

require __DIR__ . '/main.php';
