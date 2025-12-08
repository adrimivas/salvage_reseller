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

$page_title = 'Update • JUNKIES';
$active     = 'update';

$errors  = [];
$success = null;

$item    = null;         
$item_id_lookup = '';     // this is id into lookup form
// saves update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item'])) {
    $item_id        = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $item_condition = trim($_POST['item_condition'] ?? '');

    if (!$item_id) {
        $errors[] = 'Invalid item ID.';
    }
    if ($item_condition === '') {
        $errors[] = 'Item condition is required.';
    }

    if (!$errors) {
        try {
  
            $stmt = $pdo->prepare("CALL update_inventory_condition(:item_id, :item_condition)");
            $stmt->execute([
                ':item_id'        => $item_id,
                ':item_condition' => $item_condition,
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $affected = $result['affected_rows'] ?? 0;

            while ($stmt->nextRowset()) {
            }

            if ($affected > 0) {
                $success = "Item #$item_id updated successfully.";
            } else {
                $errors[] = "No item was updated. Check that the item ID exists.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT * FROM Inventory WHERE item_id = ?");
        $stmt->execute([$item_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['lookup'])) {
    $item_id_lookup = trim($_GET['item_id'] ?? '');

    if ($item_id_lookup === '' || !ctype_digit($item_id_lookup)) {
        $errors[] = 'Please enter a valid numeric item ID.';
    } else {
        $id = (int)$item_id_lookup;
        $stmt = $pdo->prepare("SELECT * FROM Inventory WHERE item_id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            $errors[] = "Item with ID $id not found.";
        }
    }
}

$content = function () use ($errors, $success, $item, $item_id_lookup) {
    $email = $_SESSION['email'] ?? 'Employee';
    ?>
    <main class="page">
        <h1>Update Inventory Item Condition</h1>
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
<!-- looks up by id-->
        <section class="lookup-form">
            <h2>Find Item to Update</h2>
            <form method="get">
                <label for="item_id">Item ID:</label>
                <input type="number" name="item_id" id="item_id"
                       required value="<?= htmlspecialchars($item_id_lookup) ?>">
                <button type="submit" name="lookup">Look Up Item</button>
            </form>
        </section>

        <!-- if item is found, show update form -->
        <?php if ($item): ?>
            <?php $currentRaw = $item['item_condition'] ?? '';
             $current = strtolower(trim($currentRaw));?>

            <section class="update-form">
                <h2>Update Item #<?= (int)$item['item_id']; ?></h2>
                <?php if (!empty($item['item_name'])): ?>
                    <p><strong>Name:</strong> <?= htmlspecialchars($item['item_name']); ?></p>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="item_id" value="<?= (int)$item['item_id']; ?>">

                    <label for="item_condition">Condition:</label>
                    <select name="item_condition" id="item_condition" required>
                        <option value="">Select Condition</option>
                        <option value="New"      <?= $current === 'new' ? 'selected' : ''; ?>>New</option>
                        <option value="Like New" <?= $current === 'like new' ? 'selected' : ''; ?>>Like New</option>
                        <option value="Good"     <?= $current === 'good' ? 'selected' : ''; ?>>Good</option>
                        <option value="Fair"     <?= $current === 'fair' ? 'selected' : ''; ?>>Fair</option>
                        <option value="Poor"     <?= $current === 'poor' ? 'selected' : ''; ?>>Poor</option>
                    </select>

                    <br><br>
                    <button type="submit" name="update_item">Update Item</button>
                    <a href="employees.php">Back to Dashboard</a>
                </form>
            </section>
        <?php endif; ?>

    </main>
    <?php
};

require __DIR__ . '/main.php';