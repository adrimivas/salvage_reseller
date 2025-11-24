<?php
// app/auth.php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

// check if logged in
function current_user(): ?array {
  return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool {
  return isset($_SESSION['user']);
}

function require_login(): void {
  if (!is_logged_in()) {
    header('Location: /login.php');
    exit;
  }
}
