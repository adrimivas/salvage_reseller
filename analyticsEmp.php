<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

// Only allow logged-in employees
if (empty($_SESSION['user'])) {
    header('Location: employeeLogin.php');
    exit;
}

$page_title = 'Analytics • JUNKIES';
$active = 'add';

// If you don't actually use the layout system (main.php) for employees,
// you can skip the closure and just output HTML directly. But I'll keep
// your pattern for now:
$content = function () {
    $employeeId = $_SESSION['user']['id'] ?? null;
    $email = $_SESSION['email'] ?? 'Unknown';

    // $pdo = get_pdo(); // use this later when you actually add to DB
    ?>
    <main class="page">
        <h1>Sales in a Given Month</h1>
        <p>You are logged in as: <strong><?= htmlspecialchars($email) ?></strong></p>

        <p>Here is where your "sales analytics" form will eventually go.</p>
    </main>
    <?php
};

require __DIR__ . '/main.php';
