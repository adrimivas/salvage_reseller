<?php
$page_title = 'Profile • JUNKIES';
$active     = 'profile';

$content = function () {
  ?>
  <main class="page">
    <h1>My Profile</h1>
<form method="GET"> 
<!-- Replace "name_column" with the name of a searchable column (customer name, product name) --> 
<label for="Email">Email:</label> 
<input type="text" name="Email" id="Email"> 
<input type="submit" value="Search"> 
</form> 
<?php 
if (isset($_GET['Email'])) { 
$search = "%" . $_GET['Email'] . "%"; 

try { 
$pdo = get_pdo(); 

// Replace "YourTable" and "your_column" with appropriate values 
$stmt = $pdo->prepare("SELECT Orders.ItemID, Orders.Quantity, Customers.Name, Customers.Email, Customers.PhoneNumber FROM Customers JOIN Orders WHERE Email LIKE ?"); 
$stmt->execute([$search]); 

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 

echo "<p>Query executed successfully. " . count($rows) . " result(s) found.</p>"; 
echo makeTable($rows); 
} catch (PDOException $e) { 
echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>"; 
} 
} 
?> 

<?php echo "</main></div></body></html>"; ?> 
  </main>
  <?php
};

require __DIR__ . '/main.php';