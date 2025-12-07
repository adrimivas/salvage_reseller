<?php
session_start();

// Only allow logged-in employees (email stored in session) 
//also changed email to user below
if (!isset($_SESSION['user'])) {
    header("Location: employeeLogin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Dashboard</title>

    <!-- Employee styling -->
    <link rel="stylesheet" href="employees.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="employee-header">
    <h1>Employee Dashboard</h1>

    <nav class="employee-nav">

        <!-- 1. ADD -->
        <a href="addEmp.php" class="Submit-button">Add</a>

        <!-- 2. DELETE -->
        <a href="delEmp.php" class="Submit-button">Delete</a>

        <!-- 3. ANALYTICS -->
        <a href="analyticsEmp.php" class="Submit-button">Analytics</a>

        <!-- 4. UPDATE -->
        <a href="update.php" class="Submit-button">Update Item</a>

        <!-- 5. View Orders -->
        <a href="viewOrders.php" class="Submit-button">View Orders</a>

        <!-- 6. View Customers -->
        <a href="viewCustomers.php" class="Submit-button">View Customers</a>

        <!-- 7. View Inventory -->
        <a href="viewInventory.php" class="Submit-button">View Inventory</a>

    </nav>
</header>
<!-- ============================================ -->

<main>
    <h2 class="welcome-text">Welcome!</h2>
    <p>Select any option above to manage the system.</p>
</main>

</body>
</html>

