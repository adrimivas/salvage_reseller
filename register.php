<?php include("main.php"); ?> 
<h2>Register</h2> 

<form method="POST"> 
<!-- Replace these fields with ones that match your chosen table --> 
<label for="Email">Enter email:</label> 
<input type="string" name="Email" id="Email" required><br> 
<label for="PhoneNumber">Enter phone number:</label> 
<input type="text" name="PhoneNumber" id=" PhoneNumber" required><br> 

<label for="password_hash">Create Password:</label> 
<input type="text" name="password_hash" id="password_hash" required><br> 
 
<label for="DefaultShip">Enter shipping address:</label> 
<input type="text" name="DefaultShip" id="DefaultShip" required><br> 
<input type="submit" value="Create Account"> 
</form> 

<?php 
if ($_SERVER["REQUEST_METHOD"] === "POST") { 
try { 
$pdo = get_pdo(); 
// Replace "Rider" and columns with your own 
$stmt = $pdo->prepare("INSERT INTO Customers (Email, PhoneNumber, password_hash, DefaultShip) VALUES (?, ?, ?, ?)"); 
$stmt->execute([ 
$_POST['Email'], 
$_POST['PhoneNumber'], 
$_POST['password_hash'], 
$_POST['DefaultShip'], 
]); 

echo "<p>Account Created Successfully!</p>"; 
} catch (PDOException $e) { 
echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>"; 
} 
} 
?> 
<?php echo "</main></div></body></html>"; ?>