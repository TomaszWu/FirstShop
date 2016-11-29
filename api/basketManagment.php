<?php

session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Db.php';
$conn = DB::connect();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $userId = unserialize($_SESSION['userId']);
    if ($_COOKIE['basket' . $userId]) {
        
        $basket = (unserialize($_COOKIE['basket' . $userId]));
        $basketToPass = [];
        foreach($basket as $singleProduct){
            $basketToPass[] = json_encode($singleProduct);
        }
        
        $basketWithObjectsToSend = [];
        foreach ($basket as $product) {
//            $item = Product::loadProductFromDbJson($conn, $product['itemId'])[0];
//            $item->setOderedQuantity();
//            $basketWithObjectsToSend[] = $item;
            $item = Product::loadProductFromDbJson($conn, $product['itemId'])[0];
            $item->setOrderedQuantity((string)$product['itemQuantity']);
            $item->setKeyInTheBasket($product['itemId']);
            $basketWithObjectsToSend[] = json_encode($item);
        }
    }
    $dataToPass = [
        '0' => $basketWithObjectsToSend,
        '1' => $basketToPass
    ];
    echo json_encode($dataToPass);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //zgodnie z rest POST dodaje dane
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    //pobieramy przeslane dane
    parse_str(file_get_contents('php://input'), $put_vars);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    
}