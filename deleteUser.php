<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';
session_start();
$conn = DB::connect();

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_GET['userIdToDelete'])){
        $userIdToDelete = $_GET['userIdToDelete'];
        $userToDelete = User::loadUsersFromDB($conn, $userIdToDelete)[0];
        if($userToDelete->deleteUser($conn)){
            header('Location: adminUsers.php');
        }
    }
}