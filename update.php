<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// AUTH CHECK - must match your login session structure
if (empty($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    header('Location: employeeLogin.php');
    exit();
}

// Database connection (mysqli)
$mysqli = new mysqli('localhost', 'root', '', 'salvage_reseller');

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$error_msg = '';
$item = null;

//  HANDLE POST (UPDATE) 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $item_condition = trim($_POST['item_condition'] ?? '');

    if ($item_id && $item_condition !== '') {
        $stmt = $mysqli->prepare(
            "UPDATE Inventory 
             SET item_condition = ? 
             WHERE item_id = ?"
        );
        $stmt->bind_param("si", $item_condition, $item_id);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Item condition updated successfully.';
            $stmt->close();
            $mysqli->close();

            header('Location: employees.php');
            exit();
        } else {
            $error_msg = 'Error updating item: ' . $mysqli->error;
        }

        $stmt->close();
    } else {
        $error_msg = 'Invalid item ID or condition.';
    }
}

// HANDLE GET (LOAD ITEM) 
if (isset($_GET['item_id'])) {
    $item_id = filter_input(INPUT_GET, 'item_id', FILTER_VALIDATE_INT);

    if ($item_id) {
        $stmt = $mysqli->prepare(
            "SELECT item_id, item_condition, item_name 
             FROM Inventory 
             WHERE item_id = ?"
        );
        $stmt->bind_param("i", $item_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $item = $result->fetch_assoc();

        $stmt->close();
    }
}

$mysqli->close();

//  LAYOUT VARS 
$page_title = 'Update Item Condition • JUNKIES';
$active = 'update'; // or 'inventory', depending on your navbar
$content = function () use ($item, $error_msg) {
    $current = $item['item_condition'] ?? '';
    ?>
        <h2>Update Item Condition</h2>

        <?php if (!empty($error_msg)): ?>
            <p style="color: red;"><?= htmlspecialchars($error_msg) ?></p>
        <?php endif; ?>

        <?php if ($item): ?>
            <form method="POST">
                <input type="hidden" name="item_id" value="<?= (int)$item['item_id']; ?>">

                <p>
                    <strong>Item ID:</strong> <?= (int)$item['item_id']; ?><br>
                    <?php if (!empty($item['item_name'])): ?>
                        <strong>Name:</strong> <?= htmlspecialchars($item['item_name']); ?>
                    <?php endif; ?>
                </p>

                <label for="item_condition">Item Condition:</label>
                <select name="item_condition" id="item_condition" required>
                    <option value="">Select Condition</option>
                    <option value="New"      <?= $current === 'New' ? 'selected' : ''; ?>>New</option>
                    <option value="Like New" <?= $current === 'Like New' ? 'selected' : ''; ?>>Like New</option>
                    <option value="Good"     <?= $current === 'Good' ? 'selected' : ''; ?>>Good</option>
                    <option value="Fair"     <?= $current === 'Fair' ? 'selected' : ''; ?>>Fair</option>
                    <option value="Poor"     <?= $current === 'Poor' ? 'selected' : ''; ?>>Poor</option>
                </select>
                <br><br>

                <button type="submit">Update</button>
                <a href="employees.php">Cancel</a>
            </form>
        <?php else: ?>
            <p>Item not found.</p>
            <p><a href="employees.php">Back to Employee Dashboard</a></p>
        <?php endif; ?>
    <?php
};

require __DIR__ . '/main.php';
