<?php
// main.php — page layout shell

// 1) Load shared config ONCE (DB creds, helpers, constants)
require_once __DIR__ . '/config.php';

// 2) These variables can be set by the calling page:
$page_title = $page_title ?? 'JUNKIES';   // default title if not set
$active     = $active     ?? null;        // which nav link is active (optional)
$content    = $content    ?? function () { };

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>

  <!-- Global styles -->
  <link rel="stylesheet" href="styles.css">

  <!-- Lottie (for animated icons) -->
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js" defer></script>
</head>
<body>
<?php require_once __DIR__ . '/navbar.php'; ?>

<main class="page">
  <?php
    // Call the page-provided function to render the body
    if (isset($content) && is_callable($content)) {
      $content();
    } else {
      echo "<p>Empty page.</p>";
    }
  ?>
</main>
</body>
</html>
