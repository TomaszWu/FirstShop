<?php

require_once __DIR__ . '/../vendor/autoload.php'; 

class Product {

    private $id;
    private $name;
    private $description;
    private $price;
    private $stock;

    public function __constructor($id = -1, $category, $name, $description, $price, $stock) {
        $this->id = $id;
        $this->setName($name);
        $this->setDescription($description);
        $this->setPrice($price);
        $this->setStock($stock);
    }
    
    
    
    
    public function buyAProduct($quantity) {
        if ($this->stock -= $quantity < 0) {
            throw new InvalidArgumentException('Niestety, produkt nie jest dostępny w podanej ilości');
        } else {
            $this->stock -= $quantity;
        }
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getPrice() {
        return $this->price;
    }

    function getStock() {
        return $this->stock;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        if (!is_string($this->name)) {
            throw new InvalidArgumentException('Bledna nazwa');
        } else {
            $this->name = $name;
        }
    }

    function setDescription($description) {
        if (!is_string($this->description)) {
            throw new InvalidArgumentException('Bledny opis');
        } else {
            $this->description = $description;
        }
    }

    function setPrice($price) {
        if ($price > 0) {
            $this->price = $price;
        } else {
            throw new InvalidArgumentException('Cena nie może być poniżej zera!!!');
        }
    }

    function setStock($stock) {
        if ($stock >= 0) {
            $this->stock = $stock;
        } else {
            throw new InvalidArgumentException('Stany nie mogą być poniżej zera!!!');
        }
    }

}



/*
 * CREATE TABLE Pictures
 */