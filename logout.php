<?php

session_start();

if (isset($_SESSION['userId'])) {
    unset($_SESSION['userId']);
}

if (isset($_SESSION['adminId'])) {
    unset($_SESSION['adminId']);
}

header('Location: index.php');
