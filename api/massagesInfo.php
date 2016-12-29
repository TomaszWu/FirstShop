<?php

session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Db.php';
$conn = DB::connect();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $userId = unserialize($_SESSION['userId']);
    if ($userId) {
        $userMassages = Massage::loadMassagesByStatus($conn, 0, $userId);
    }
    echo json_encode($userMassages);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //zgodnie z rest POST dodaje dane
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents('php://input'), $put_vars);
    if (isset($put_vars['msgId'])) {
        $msgId = $put_vars['msgId'];
        $massage = Massage::loadMassagesFromDB($conn, null, $msgId)[0];
        $massage->changeTheStatus($conn, 1);
        $confirmation = [0 => 'ok'];
    }
    echo json_encode($confirmation);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    
}