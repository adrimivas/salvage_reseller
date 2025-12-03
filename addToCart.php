
<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';
session_start();


try {
  $pdo = get_pdo();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'DB connect failed']); exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok'=>false,'error'=>'Use POST']); exit;
}

$user_id = $_SESSION['user']['id'] ?? null;
$item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
if (!$item_id) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>'Missing/invalid item_id']); exit;
}

try {

  $stmt = $pdo->prepare("INSERT IGNORE INTO cart (customerId, item_id) VALUES (?, ?)");
  $stmt->execute([$user_id, $item_id]);
  echo json_encode(['ok'=>true]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}

?>

<?php echo "</main></div></body></html>"; ?>