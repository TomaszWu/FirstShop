<?php

session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Db.php';
$conn = DB::connect();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $userId = unserialize($_SESSION['userId']);
    $basket = Order::loadTheBasket($conn, $userId);
    echo json_encode($basket);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //zgodnie z rest POST dodaje dane
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    //pobieramy przeslane dane
    parse_str(file_get_contents('php://input'), $put_vars);
    if (isset($put_vars['orderId']) && isset($put_vars['newQnt'])) {
        $orderId = $put_vars['orderId'];
        $newQnt = $put_vars['newQnt'];
        $orderToChangeQnt = Order::loadOrderById($conn, $orderId);
        $orderToChangeQnt->changeTheQuantity($conn, $newQnt);
//        $confirmation = ['statusToConfirm' => 'Ilość zmieniona'];
    } elseif (isset($put_vars['confirmTheBasket'])) {
        $userId = unserialize($_SESSION['userId']);
        $basket = Order::loadTheBasket($conn, $userId);
        foreach ($basket as $order) {
            $singleItem = json_decode($order);
            $productId = $singleItem->products->product_id;
            $quantity = $singleItem->products->quantity;
            $stock = $singleItem->products->stock;
            $product = Product::loadProductFromDb($conn, $productId)[0];
            $product->buyAProduct($conn, $quantity);
        }
        $orderId = Order::confirmTheBasket($conn, $basket, $userId);
        $confirmation = ['orderId' => $orderId];
       
    } 
     echo json_encode($confirmation);
    
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $del_vars);
    if (isset($del_vars['idToDelete'])) {
        $idToDelete = $del_vars['idToDelete'];
        Order::deleteTheItemFromBasket($conn, $idToDelete);
        $productId = $order->getProducts()['product_id'];
    }
    $confirmationDelete = ['statusToConfirm' => 'Produkt skasowany'];
    echo json_encode($confirmationDelete);
}