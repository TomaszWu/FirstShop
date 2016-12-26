<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Admin {

    private $id;
    private $email;
    private $hashedPassword;

    private function __construct($id = -1, $email = null, $hashedPassword = null) {
        $this->id = $id;
        $this->setEmail($email);
        $this->setHashedPassword($hashedPassword);
    }

    static public function loginAdmin(mysqli $connection, $email, $password) {
        $admin = self::loadByEmail($connection, $email);
        if ($admin && password_verify($password, $admin->hashedPassword)) {
            return $admin;
        } else {
            return false;
        }
    }

    static public function loadByEmail(mysqli $connection, $email) {
        $query = "SELECT * FROM Admin WHERE email = '" . $connection->real_escape_string($email) . "'";

        $res = $connection->query($query);
        if ($res && $res->num_rows == 1) {
            $row = $res->fetch_assoc();
            $admin = new Admin();
            $admin->setId($row['id']);
            $admin->setEmail($row['email']);
            $admin->hashedPassword = $row['hashed_password'];
            return $admin;
        }
        return null;
    }

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getEmail() {
        return $this->email;
    }

    function getHashedPassword() {
        return $this->hashedPassword;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setHashedPassword($hashedPassword) {
        $this->hashedPassword = $hashedPassword;
    }

}
