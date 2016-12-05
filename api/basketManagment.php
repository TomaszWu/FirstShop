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
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    
}