<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start(); //added session start due to login messing up(Isaiah)

require_once __DIR__ . '/config.php';
$pdo = get_pdo();

$errors = [];
$login_success = false;
$email  = trim($_POST['Email'] ?? '');
$pass   = $_POST['password_hash'] ?? '';

$typed_id = '';    
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
  }
  if ($pass === '') {
    $errors[] = 'Please enter your password.';
  }
  if (!$errors) {
    $stmt = $pdo->prepare('SELECT Admin_ID, Email, password_hash FROM Employees WHERE Email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //doesn't require hashed passwords anymore, just text fam (adri)
    if ($user && $pass === $user['password_hash']) {
      $login_success = true;

      $_SESSION['user'] = [
      'id'    => (int)$user['Admin_ID'],
      ];
      $_SESSION['email'] = $user['Email'];
      session_regenerate_id(true);
      header('Location: employees.php'); 
      exit;
    } else {
      $errors[] = 'Email or password is incorrect.';
    } 
  } 

}
