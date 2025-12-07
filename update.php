<?php
session_start();

// Auth check
if (empty($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    header('Location: employeeLogin.php');
    exit();
}

// Connect to DB
$mysqli = new mysqli('localhost', 'root', '', 'salvage_reseller');

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$error_msg = '';
$item = null;

// ======================= STEP 3: HANDLE POST (UPDATE) =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item'])) {
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $item_condition = trim($_POST['item_condition'] ?? '');

    if ($item_id && $item_condition !== '') {

        $stmt = $mysqli->prepare("UPDATE Inventory SET item_condition = ? WHERE item_id = ?");
        $stmt->bind_param("si", $item_condition, $item_id);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Item #$item_id updated successfully!";
            header('Location: employees.php');
            exit();
        } else {
            $error_msg = "Error updating item: " . $mysqli->error;
        }

        $stmt->close();
    } else {
        $error_msg = "Invalid item ID or condition.";
    }
}

// ======================= STEP 2: LOOK UP ITEM =======================
if (isset($_GET['lookup'])) {
    $lookup_id = filter_input(INPUT_GET, 'item_id', FILTER_VALIDATE_INT);

    if ($lookup_id) {
        $stmt = $mysqli->prepare("SELECT item_id, item_condition, item_name FROM Inventory WHERE item_id = ?");
        $stmt->bind_param("i", $lookup_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $item = $result->fetch_assoc();

        $stmt->close();

        if (!$item) {
            $error_msg = "Item not found.";
        }
    } else {
        $error_msg = "Please enter a valid item ID.";
    }
}

$mysqli->close();
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

<!-- ======================= STEP 1: ENTER ITEM ID ======================= -->
<?php if (!$item): ?>
<form method="GET" action="update.php">
    <label for="item_id">Enter Item ID:</label>
    <input type="number" name="item_id" id="item_id" required>
    <button type="submit" name="lookup">Look Up Item</button>
    <a href="employees.php">Cancel</a>
</form>

<?php endif; ?>

<!-- ======================= STEP 2: SHOW ITEM & UPDATE FORM ======================= -->
<?php if ($item): ?>
    <?php $current = $item['item_condition']; ?>

    <h3>Updating Item #<?= $item['item_id'] ?></h3>
    <p><strong>Name:</strong> <?= htmlspecialchars($item['item_name']) ?></p>

    <form method="POST" action="update.php">
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
