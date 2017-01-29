<?php
namespace src;

class Admin {

    private $id;
    private $email;
    private $password;

    public function __construct($id = -1, $email = null, $password = null) {
        $this->id = $id;
        $this->setEmail($email);
        $this->setPassword($password);
    }

    static public function loginAdmin(\mysqli $connection, $email, $password) {
        $admin = self::loadByEmail($connection, $email);
        if ($admin && password_verify($password, $admin->password)) {
            return $admin;
        } else {
            return false;
        }
    }

    static public function loadByEmail(\mysqli $connection, $email) {
        $query = "SELECT * FROM Admin WHERE email = '" . $connection->real_escape_string($email) . "'";

        $res = $connection->query($query);
        if ($res && $res->num_rows == 1) {
            $row = $res->fetch_assoc();
            $admin = new Admin();
            $admin->setId((int)$row['id']);
            $admin->setEmail($row['email']);
            $admin->setPassword($row['hashed_password'], false);
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

    function password() {
        return $this->password;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    
    public function setPassword($password, $hashPassword = true) {
        if (is_string($password)) {
            if ($hashPassword) {
                $this->password = password_hash($password, PASSWORD_DEFAULT);
            } else {
                $this->password = $password;
            }
        }
    }

}
