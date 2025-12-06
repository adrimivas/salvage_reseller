<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

/***************
 * login.php — DB-backed login using password_hash
 ***************/
require_once __DIR__ . '/config.php';
$pdo = get_pdo();



// your PHP login logic here

// --- 1) state for rendering ---
$errors = [];
$email  = trim($_POST['email'] ?? '');
$pass   = $_POST['password'] ?? '';
$login_success = false;
// --- 2) known credentials (in real life, fetch from DB) ---
$typed_id = '';     // remember what user typed so we can re-fill the form
// --- 2) if form submitted, validate + verify ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // (a) cheap input validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
  }
  if ($pass === '') {
    $errors[] = 'Please enter your password.';
  }

  // (b) query DB only if inputs look sane
  if (!$errors) {
    $stmt = $pdo->prepare('SELECT Admin_ID, Email, password_hash FROM Employees WHERE Email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(); // row or false

    if ($user && password_verify($pass, $user['password_hash'])) {
      $login_success = true;
     session_start();
      $_SESSION['user'] = [
      'id'    => (int)$user['Admin_ID'], // adapt to your PK column
      ];
      session_regenerate_id(true);
      header('Location: employees.php'); // change to your destination
      exit;
    } else {
      $errors[] = 'Email or password is incorrect.';
    } 
  } 

}
?>
