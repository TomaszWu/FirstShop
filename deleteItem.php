<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';
$conn = DB::connect();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['idToDelete'])) {
        $idToDelete = $_GET['idToDelete'];
        $productToDelete = Product::loadProductFromDb($conn, $idToDelete)[0];
        $productToDelete->deleteTheItem($conn);
        header('Location: itemsAdmin.php');
    }
}
