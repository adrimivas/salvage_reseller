<?php  
require_once __DIR__ . '/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$page_title = 'Register'; 
$active = 'register'; 

$success = null; 
$error = null; 

if($_SERVER["REQUEST_METHOD"] === "POST"){ 
try{ 
$pdo = get_pdo(); 
$stmt = $pdo->prepare("INSERT INTO CUSTOMER (Email, PhoneNumber, password_hash, DefaultShip) 
VALUES (?, ?, ?, ?)"); 

$stmt-> execute([ 
$_POST['Email'],  
$_POST['PhoneNumber'], 
$_POST['password_hash'], 
$_POST['DefaultShip'],]); 

$success= "Account created successfully"; 
} 
catch(Throwable $e){ 
$error = '<strong>Error:</strong> ' . $e->getMessage(); 
} 
} 
$content = function() use ($success, $error){ 
?> 
<h2>Register</h2> 

<?php if($success):?> 
<p><?= htmlspecialchars($success)?></p> 
<?php elseif($error): ?> 
<p><?= $error ?></p> 
<?php endif; ?> 
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
  <pre><?php echo "DEBUG POST:\n"; var_dump($_POST); ?></pre>
<?php endif; ?>

<form method = "POST"> 

<label for= "Email">Enter email: </label> 
<input type = "email" name= "Email" id = "Email" required><br> 

<label for= "PhoneNumber">Enter Phone number: </label> 
<input type = "text" name= "PhoneNumber" id = "PhoneNumber" required><br> 

<label for= "password_hash">Enter password: </label> 
<input type = "text" name= "password_hash" id = "password_hash" required><br> 

<label for= "DefaultShip">Enter shipping address: </label> 
<input type = "text" name= "DefaultShip" id = "DefaultShip" required><br> 
<input type ='submit' value = 'Create Account'> 
</form> 
<?php 
}; 
require_once __DIR__ . '/main.php';
