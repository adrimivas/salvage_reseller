<?php
session_start();

if (empty($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    header('Location: employee_login.php');
    exit();
}

require_once__DIR__ .'/config.php';
$pdp = get_pdo();

$error_msg = '';
$item = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item'])) {
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $item_condition = trim($_POST['item_condition']??'');

    if ($item_id && $item_condition !=='') {
        $stmt = $pdp->prepare("Update inventory SET item_condition = ? WHERE item_id = ?");

        if($stmt-> execute([$item_condition, $item_id])){
            $_SESSION['success_msg'] = "Item #$item_id updated successfully.";
            header('Location: employees.php');
            exit();
        } else{
            $error_msg = "Failed to update item. Please try again.";
        }
    }else{
        $error_msg = "Invalid item ID or condition. Please check the data and try again.";
    }
}

if (isset($_GET['lookup'])) {
    $lookup_id = filter_input(INPUT_GET, 'item_id', FILTER_VALIDATE_INT);

    if ($lookup_id) {
        $stmt = $pdo->prepare("SELECT item_id, item_condition, item_name FROM Inventory WHERE item_id = ?");
        $stmt->execute([$lookup_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            $error_msg = "Item not found.";
        }
    } else {
        $error_msg = "Please enter a valid item ID.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Update Item</title>
</head>
<body>

<h2>Update Item Condition</h2>

<?php if (!empty($error_msg)): ?>
    <p style="color:red;"><?= htmlspecialchars($error_msg) ?></p>
<?php endif; ?>

<!-- STEP 1: Ask for item ID if no item is selected yet -->
<?php if (!$item): ?>
<form method="GET" action="update.php">
    <label for="item_id">Enter Item ID:</label>
    <input type="number" name="item_id" id="item_id" required>
    <button type="submit" name="lookup">Look Up Item</button>
    <a href="employees.php">Cancel</a>
</form>
<?php endif; ?>

<!-- STEP 2: Show update form if item loaded -->
<?php if ($item): ?>
    <h3>Updating Item #<?= $item['item_id'] ?></h3>
    <p><strong>Name:</strong> <?= htmlspecialchars($item['item_name']) ?></p>

    <?php $current = $item['item_condition']; ?>

    <form method="POST">
        <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">

        <label for="item_condition">Condition:</label>
        <select name="item_condition" required>
            <option value="New"      <?= $current === "New" ? 'selected' : '' ?>>New</option>
            <option value="Like New" <?= $current === "Like New" ? 'selected' : '' ?>>Like New</option>
            <option value="Good"     <?= $current === "Good" ? 'selected' : '' ?>>Good</option>
            <option value="Fair"     <?= $current === "Fair" ? 'selected' : '' ?>>Fair</option>
            <option value="Poor"     <?= $current === "Poor" ? 'selected' : '' ?>>Poor</option>
        </select>

        <br><br>
        <button type="submit" name="update_item">Update Item</button>
        <a href="update.php">Look Up Another Item</a>
        <a href="employees.php">Cancel</a>
    </form>
<?php endif; ?>

</body>
</html>