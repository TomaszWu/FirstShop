<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Admin {

    private $name;
    private $mail;
    private $password;

    private function __construct($id = -1, $name = null, $email = null, $password = null) {
        $this->id = $id;
        $this->setName($name);
        $this->email($email);
        $this->password($password);
    }
    
    

    function getId() {
        return $this->id;
    } 
    
    function getName() {
        return $this->name;
    }

    function getMail() {
        return $this->mail;
    }

    function getPassword() {
        return $this->password;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setMail($mail) {
        $this->mail = $mail;
    }

    function setPassword($password) {
        $this->password = $password;
    }


    
    
    
}
