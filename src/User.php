<?php

class User {

    private $id;
    private $name;
    private $surname;
    private $email;
    private $password;
    private $address;

    public function __construct($id = -1, $name, $surname, $email, $password, $address) {
        $this->id = $id;
        $this->setName($name);
        $this->setSurname($surname);
        $this->setEmail($email);
        $this->password($password);
        $this->address($address);
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getSurname() {
        return $this->surname;
    }

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function getAddress() {
        return $this->address;
    }

    function setId($id) {
        $this->id = $id;
    }
    function setName($name) {
        $this->name = $name;
    }

    function setSurname($surname) {
        $this->surname = $surname;
    }

    public function setEmail($email) {
        if (is_string($email) && strlen(trim($email)) > 5) {
            $this->email = trim($email);
        }
        return $this;
    }

    public function setPassword($password) {
        if (is_string($password) && strlen(trim($password)) > 5) {
            $this->hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        }
        return $this; // gdyby toś chciał wywołać kilka seterów ?? oO
    }

    function setAddress($address) {
        $this->address = $address;
    }
    
    
    
    

}
