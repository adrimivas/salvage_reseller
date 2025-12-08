<?php include("main.php"); ?> 
<h2>Register</h2> 

<form method="POST"> 

    <label for="Name">Enter Full Name:</label> 
    <input type="text" name="Name" id="Name" required><br> 

    <label for="Email">Enter email:</label> 
    <input type="text" name="Email" id="Email" required><br> 

    <label for="PhoneNumber">Enter phone number:</label> 
    <input type="text" name="PhoneNumber" id="PhoneNumber" required><br> 

    <label for="password_hash">Create Password:</label> 

    <input type="password" name="password_hash" id="password_hash" required><br> 
     
    <label for="DefaultShip">Enter shipping address:</label> 
    <input type="text" name="DefaultShip" id="DefaultShip" required><br> 

    <input type="submit" value="Create Account"> 
</form> 

<?php 
if ($_SERVER["REQUEST_METHOD"] === "POST") { 
    try { 
        $pdo = get_pdo(); 
        $stmt = $pdo->prepare("CALL register_customer(?, ?, ?, ?, ?)"); 
        $stmt->execute([ 
            $_POST['Name'], 
            $_POST['Email'], 
            $_POST['PhoneNumber'], 
            $_POST['password_hash'], 
            $_POST['DefaultShip'], 
        ]); 

        echo "<p>Account Created Successfully!</p>"; 

        $stmt->closeCursor();

    } catch (PDOException $e) { 
        echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>"; 
    } 
} 
?> 

<?php echo "</main></div></body></html>"; ?>
