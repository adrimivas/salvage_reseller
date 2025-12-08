<?php
    header('Content-Type: application/json');
    require_once("config.php");
    session_start();
    $user_id = $_SESSION['user']['id'] ?? null;
    if (!$user_id) { http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Not logged in']); exit; }

    try {
        $pdo = get_pdo();

        $stmt = $pdo->prepare(" CALL GetCartDetails(?);");
        $stmt->execute([$user_id]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['ok'=>true,'data'=>$rows], JSON_PRETTY_PRINT);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['ok' =>false,'error'=>$e->getMessage()]);
    }

?>
