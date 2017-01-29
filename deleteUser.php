<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
use src\Db;
$conn = Db::connect();

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_GET['userIdToDelete'])){
        $userIdToDelete = $_GET['userIdToDelete'];
        $userToDelete = User::loadUsersFromDB($conn, $userIdToDelete)[0];
        if($userToDelete->deleteUser($conn)){
            header('Location: adminUsers.php');
        }
    }
}