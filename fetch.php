
<?php
// include database config and helper function
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=salvage_reseller;charset=utf8mb4',
        'kdubey',
        'Sevh2ypol',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'DB connection failed']);
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', '1');

$make   = trim($_POST['make'] ?? '');
$product_type   = trim($_POST['product_type'] ?? '0');
$errors =[];

if ( $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Use POST'], JSON_PRETTY_PRINT);
    exit;
        
}

else {
    if ($make ===''){ //change made here from empty to ' ' 
        //change made heere from inventory to v_available_inventory
        $stmt = $pdo->prepare('
        SELECT  price,item_condition, item_name, model, product_type,item_id,quantity 
        FROM v_available_inventory  
        WHERE   product_type = ? 
        LIMIT 10
        ');
        $stmt->execute([$product_type]);
        
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cars, JSON_PRETTY_PRINT);
        exit;
    }
    else{
        $stmt = $pdo->prepare('
        SELECT  price,item_condition, item_name, model, product_type,item_id,quantity 
        FROM v_available_inventory 
        WHERE make LIKE ?  
            AND product_type = ? 
        LIMIT 10
        ');
        $pattern = "%{$make}%";
        $stmt->execute([$pattern, $product_type ]);
        
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cars, JSON_PRETTY_PRINT);
        exit;

    }
    
}

?>