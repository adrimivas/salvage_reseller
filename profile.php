<?php 

if (session_status() === PHP_SESSION_NONE){ 
session_start(); 
} 
require_once __DIR__ . '/config.php'; 

if (empty($_SESSION['user'])){ 
header('Location: login.php'); 
exit; 
} 
 
$page_title = 'Profile • JUNKIES'; 
$active = 'profile'; 

$content = function () { 
$user = $_SESSION['user']; 
$pdo = get_pdo(); 
?> 
<main class="page"> 
<h1>My Profile</h1> 

<section> 
<h2>My Account Details</h2> 
<p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p> 
<p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p> 
<p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p> 
<p><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></p> 
</section> 

<hr> 
 
<section> 
<h2> Past Orders: </h2> 
<?php 
try{ 
$stmt = $pdo->prepare("SELECT 
OrderID, Purchase_Date, ItemID, item_name, make, model, year, price, item_condition, product_type
FROM v_customer_order_history 
WHERE User_ID = ? 
ORDER BY Purchase_Date DESC
");  
$stmt->execute([$user['id']]);  

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);  
 
if ($orders){ 
echo "<p>Query executed successfully. " . count($orders) . " result(s) found.</p>";  
echo makeTable($orders);  
}  
else{ 
echo "<p> You havent made any orders yet brokieeeee</p>"; 
} 
} 
catch (PDOException $e) {  
echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";  
}  
?>  
</section> 
</main> 
<?php 
}; 

require __DIR__ . '/main.php'; 