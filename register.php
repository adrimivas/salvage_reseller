<?php  
require_once __DIR__ . '/config.php'; 

$page_title = 'Register';  
$active = 'register';  

$success = null;  
$error = null;  

if($_SERVER["REQUEST_METHOD"] === "POST"){  
try{  
$pdo = get_pdo();  
$stmt = $pdo->prepare("INSERT INTO Customers (Email, PhoneNumber, password_hash, DefaultShip)  
VALUES (?, ?, ?, ?)");  

$stmt-> execute([  
$_POST['Email'],  
$_POST['PhoneNumber'],  
$_POST['password_hash'],  
$_POST['DefaultShip'],]);  

$success= "Account created successfully";  
}  
catch(PDOException $e){  
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
<form method = "POST">  
<label for= "Email">Enter email: </label>  
<input type="email" name="Email" id="Email" required>
<label for= "PhoneNumber">Enter Phone number: </label>  
<input type = "text" name= "PhoneNumber" id = "PhoneNumber" required><br>  

<label for= "password_hash">Enter password: </label>  
<input type="password" name="password" id="password" required>
 
<label for= "DefaultShip">Enter shipping address: </label>  
<input type = "text" name= "DefaultShip" id = "DefaultShip" required><br>  
<input type ='submit' value = 'Create Account'>  
</form>  
<?php  
};  
require_once __DIR__ . '/main.php';  