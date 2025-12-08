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

$page_title = 'Add • JUNKIES';
$active     = 'add';

$errors  = [];
$success = null;

$item_condition = trim($_POST['item_condition'] ?? '');
$price          = trim($_POST['price'] ?? '');
$make           = trim($_POST['make'] ?? '');
$quantity       = trim($_POST['quantity'] ?? '');
$product_type   = trim($_POST['product_type'] ?? '');
$item_name      = trim($_POST['item_name'] ?? '');
$model          = trim($_POST['model'] ?? '');
$year           = trim($_POST['year'] ?? '');
$acquisition    = trim($_POST['acquisition'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($item_name === '')               $errors[] = 'Item name is required.';
    if ($product_type === '')            $errors[] = 'Product type is required.';
    if ($item_condition === '')          $errors[] = 'Item condition is required.';
    if ($make === '')                    $errors[] = 'Make is required.';
    if ($model === '')                   $errors[] = 'Model is required.';
    if ($year === '' || !ctype_digit($year))   $errors[] = 'Year must be a valid number.';
    if ($price === '' || !ctype_digit($price)) $errors[] = 'Price must be a number.';
    if ($quantity === '' || !ctype_digit($quantity)) $errors[] = 'Quantity must be a whole number.';
    if ($acquisition === '')             $errors[] = 'Acquisition source is required.';

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO Inventory 
                (item_condition, price, make, quantity, product_type, item_name, model, created_date, year, acquisition)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $item_condition,
                $price,
                $make,
                $quantity,
                $product_type,
                $item_name,
                $model,
                date('Y-m-d'),
                $year,
                $acquisition
            ]);

            $success = "Item added successfully!";

            // this clears form after success
            $item_condition = $price = $make = $quantity = $product_type =
            $item_name = $model = $year = $acquisition = '';

        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

$content = function () use (
    $errors, $success, $item_condition, $price, $make, $quantity,
    $product_type, $item_name, $model, $year, $acquisition
) {
    $email = $_SESSION['email'] ?? 'Employee';
    ?>
    <main class="page">
        <h1>Add Inventory Item</h1>
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

            <label>Item Name</label>
            <input type="text" name="item_name" required value="<?= htmlspecialchars($item_name) ?>">

            <label>Product Type</label>
            <input type="text" name="product_type" required value="<?= htmlspecialchars($product_type) ?>">

            <label>Condition</label>
            <input type="text" name="item_condition" required value="<?= htmlspecialchars($item_condition) ?>">

            <label>Make</label>
            <input type="text" name="make" required value="<?= htmlspecialchars($make) ?>">

            <label>Model</label>
            <input type="text" name="model" required value="<?= htmlspecialchars($model) ?>">

            <label>Year</label>
            <input type="number" name="year" required value="<?= htmlspecialchars($year) ?>">

            <label>Price</label>
            <input type="number" name="price" required value="<?= htmlspecialchars($price) ?>">

            <label>Quantity</label>
            <input type="number" name="quantity" required value="<?= htmlspecialchars($quantity) ?>">

            <label>Acquisition</label>
            <input type="text" name="acquisition" required value="<?= htmlspecialchars($acquisition) ?>">

            <button type="submit">Add Item</button>
        </form>
    </main>
    <?php
};

require __DIR__ . '/main.php';
