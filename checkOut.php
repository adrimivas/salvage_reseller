<?php
    header('Content-Type: application/json');
    require_once("config.php");
    session_start();
    $user_id = $_SESSION['user']['id'] ?? null;
    if (!$user_id) { http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Not logged in']); exit; }

    try {
        $pdo = get_pdo();

        // Replace "YourTable" and "your_column" with appropriate values
        $sql3 = $pdo->prepare(" INSERT INTO Orders (Purchase_Date, ItemID, User_ID) SELECT CURDATE(), c.item_id, c.customerId FROM cart AS c WHERE c.customerId = ?");
        $sql3->execute([$user_id]);

        $stmt = $pdo->prepare("UPDATE Inventory JOIN cart on cart.item_id=Inventory.item_id SET Inventory.quantity=Inventory.quantity -1 WHERE cart.customerId= ?");
        $stmt->execute([$user_id]);

        $sql2 = $pdo->prepare("DELETE FROM cart WHERE customerId = ?");
        $sql2->execute([$user_id]);
        

        echo json_encode(["message" => "Purchase completed and inventory updated"]);
    } catch (PDOException $e) {
        echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    }

?>
