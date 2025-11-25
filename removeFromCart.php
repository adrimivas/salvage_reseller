<?php
    header('Content-Type: application/json');
    require_once("config.php");
    session_start();
    $user_id = $_SESSION['user']['id'] ?? null;
    if (!$user_id) { http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Not logged in']); exit; }
    $pdo = get_pdo();
    $item_id   = trim($_POST['item_id'] ?? '');
    $errors =[];
    try {

        // Replace "YourTable" and "your_column" with appropriate values

        $stmt = $pdo->prepare("DELETE  FROM cart WHERE customerID=? and item_id = ?");
        $stmt->execute([$user_id,$item_id]);

        echo json_encode(['ok'=>true], JSON_PRETTY_PRINT);
    } catch (PDOException $e) {
        echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    }

?>
