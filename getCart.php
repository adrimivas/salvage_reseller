<?php
    header('Content-Type: application/json');
    require_once("config.php");
    session_start();
    $user_id = $_SESSION['user']['id'] ?? null;
    if (!$user_id) { http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Not logged in']); exit; }

    try {
        $pdo = get_pdo();

        // Replace "YourTable" and "your_column" with appropriate values

        $stmt = $pdo->prepare("SELECT Inventory.item_id, Inventory.product_type,Inventory.item_name, Inventory.model,Inventory.price,Inventory.quantity  FROM cart NATURAL JOIN   Inventory WHERE cart.customer_Id =?");
        $stmt->execute([$user_id]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['ok'=>true,'data'=>$rows], JSON_PRETTY_PRINT);
    } catch (PDOException $e) {
        echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    }

?>
