<?php
// app/partials/head.php
// Start by including config if you need DB access or global vars
require_once __DIR__ . '/config.php';

// Optionally, define defaults so pages don't break
$page_title = $page_title ?? 'JUNKIES';
$include_auth_css = $include_auth_css ?? false;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title><?= htmlspecialchars($page_title) ?></title>

  <!-- Global styles for all pages -->
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="login.css">

  <!-- Conditionally include auth.css for login/register pages -->
  <?php if ($include_auth_css): ?>
    <link rel="stylesheet" href="/assets/css/auth.css">
  <?php endif; ?>

  
</head>
<body>
