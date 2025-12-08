<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';
$pdo = get_pdo();

if (empty($_SESSION['user'])) {
    header('Location: employeeLogin.php');
    exit;
}

$page_title = 'Delete • JUNKIES';
$active     = 'delete';

$errors  = [];
$success = null;

$item_id = trim($_POST['item_id'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($item_id === '') {
        $errors[] = 'Item ID is required.';
    } elseif (!ctype_digit($item_id)) {
        $errors[] = 'Item ID must be a valid number.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("CALL delete_inventory_item(?)");
            $stmt->execute([$item_id]);

            if ($stmt->rowCount() > 0) {
                $success = "Item with ID {$item_id} was deleted successfully via stored procedure.";
                $item_id = '';
            } else {
                $errors[] = "No item found with ID {$item_id}, or nothing was deleted.";
            }

        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

$content = function () use ($errors, $success, $item_id) {
    $email = $_SESSION['email'] ?? 'Employee';
    ?>
    <main class="page">
        <h1>Delete Inventory Item</h1>
        <p>Logged in as <strong><?= htmlspecialchars($email) ?></strong></p>

        <?php if ($success): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="inventory-form">

            <label>Item ID to Delete</label>
            <input
                type="number"
                name="item_id"
                required
                value="<?= htmlspecialchars($item_id) ?>"
            >

            <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');">
                Delete Item
            </button>
        </form>
    </main>
    <?php
};

require __DIR__ . '/main.php';