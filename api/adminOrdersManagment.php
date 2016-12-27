<?php

session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Db.php';
$conn = DB::connect();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['statusToReceive'])) {
        $status = $_GET['statusToReceive'];
        $ordersByStatus = Order::loadOrder($conn, null, null, $status);
        $ordersToPass = [];
        //niestety konstrukcja sklepu sprawiła, że potrzebne jest poniższe rozróżnienie.
        //zamówienia potwierdzone mają współny nr zamówienia, zamówienia ze statusem 0,
        // czyli znajdujące się w koszyku mają tymczasowe nr zamówienia wynikające
        // z ich id.
        switch ($status) {
            case(0):
                foreach ($ordersByStatus as $key => $element) {
                    foreach ($element as $basketOrder) {
                        $ordersToPass[$basketOrder->getId()] = $basketOrder->getUserId();
                    }
                }
                break;
            case(true):
                foreach ($ordersByStatus as $key => $element) {
                    $ordersToPass[$key] = $element[0]->getUserId();
                }
                break;
        }
    }
    echo json_encode($ordersToPass);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //zgodnie z rest POST dodaje dane
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents('php://input'), $put_vars);
    if (isset($put_vars['newStatus']) && isset($put_vars['orderId'])) {
        $newOrderStatus = $put_vars['newStatus'];
        $orderId = $put_vars['orderId'];
        Order::changeOrderStatus($conn, $orderId, $newOrderStatus);
        $confirmation = [0 => 'ok'];
    }
    $confirmation = [0 => 'ok'];
    echo json_encode($confirmation);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $del_vars);
    if (isset($del_vars['orderIdToDelete'])) {
        $orderIdToDelete = $del_vars['orderIdToDelete'];
        Order::deleteTheItemFromBasket($conn, $orderIdToDelete);
        $confirmation = [0 => 'skasowane'];
    }
    echo json_encode($confirmation);
}