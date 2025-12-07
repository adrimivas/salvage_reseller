<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';
$pdo = get_pdo();

// Only allow logged-in employees
if (empty($_SESSION['user'])) {
    header('Location: employeeLogin.php');
    exit;
}

$page_title = 'Analytics • JUNKIES';
$active     = 'analytics';

// ----- 1) Get selected month/year from GET or default to current -----
$selectedMonth = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$selectedYear  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');

// Make sure month is 1–12
if ($selectedMonth < 1 || $selectedMonth > 12) {
    $selectedMonth = (int)date('m');
}

// Compute date range [start, end)
$startDate = sprintf('%04d-%02d-01', $selectedYear, $selectedMonth);
$endDate   = date('Y-m-d', strtotime($startDate . ' +1 month'));

// ----- 2) Query Inventory for this month, grouped by product_type via stored procedure -----

// product_type: 0 = car, 1 = car part
$stmt = $pdo->prepare("CALL get_inventory_product_type_summary(?, ?)");
$stmt->execute([$startDate, $endDate]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Default totals
$carQty   = 0;
$partQty  = 0;

foreach ($rows as $row) {
    if ($row['product_type'] == 0) {
        $carQty = (int)$row['total_qty'];
    } elseif ($row['product_type'] == 1) {
        $partQty = (int)$row['total_qty'];
    }
}

$totalAll = $carQty + $partQty;

// Labels + data for Chart.js
$labels = ['Cars', 'Car Parts'];
$data   = [$carQty, $partQty];

// Years to show in dropdown (adjust as you like)
$currentYear = (int)date('Y');
$yearOptions = [2024, 2025];

// ----- 3) Page content closure -----
$content = function () use (
    $selectedMonth, $selectedYear, $yearOptions,
    $labels, $data, $carQty, $partQty, $totalAll 
) {
    $email = $_SESSION['email'] ?? 'Employee';

    // Month names for the form
    $monthNames = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
    ];
    ?>
    <main class="page">
        <h1>Inventory Analytics</h1>
        <h2> Shows comparison of cars vs car parts added in a given month </h2>
        <p>Logged in as <strong><?= htmlspecialchars($email) ?></strong></p>

        <section class="filters">
            <form method="get">
                <label>
                    Month:
                    <select name="month">
                        <?php foreach ($monthNames as $num => $name): ?>
                            <option value="<?= $num ?>"
                                <?= $num === $selectedMonth ? 'selected' : '' ?>>
                                <?= htmlspecialchars($name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label>
                    Year:
                    <select name="year">
                        <?php foreach ($yearOptions as $year): ?>
                            <option value="<?= $year ?>"
                                <?= $year === $selectedYear ? 'selected' : '' ?>>
                                <?= $year ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <button type="submit">Update</button>
            </form>
        </section>

        <section class="totals">
            <h2>
                Product Type Breakdown –
                <?= htmlspecialchars($monthNames[$selectedMonth]) . ' ' . $selectedYear ?>
            </h2>

            <?php if ($totalAll === 0): ?>
                <p>No inventory items were added in this month.</p>
            <?php else: ?>
                <ul>
                    <li><strong>Cars (product_type = 0):</strong> <?= $carQty ?></li>
                    <li><strong>Car Parts (product_type = 1):</strong> <?= $partQty ?></li>
                    <li><strong>Total Items:</strong> <?= $totalAll ?></li>
                </ul>

                <div style="max-width: 500px; margin-top: 20px;">
                    <canvas id="productTypeChart"></canvas>
                </div>

                <!-- Chart.js CDN -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    (function () {
                        const ctx = document.getElementById('productTypeChart').getContext('2d');

                        const chartData = {
                            labels: <?= json_encode($labels) ?>,
                            datasets: [{
                                data: <?= json_encode($data) ?>,
                            }]
                        };

                        const chartOptions = {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                title: {
                                    display: true,
                                    text: 'Cars vs Car Parts (by quantity)'
                                }
                            }
                        };

                        new Chart(ctx, {
                            type: 'pie',
                            data: chartData,
                            options: chartOptions
                        });
                    })();
                </script>
            <?php endif; ?>
        </section>
    </main>
    <?php
};

require __DIR__ . '/main.php';
