<?php

class Product {

    private $id;
    private $category;
    private $price;
    private $description;
    private $quantity;

    public function __construct($id = -1, $category, $price, $description, $quantity) {
        $this->id = $id;
        $this->category = $category;
        $this->price = $price;
        $this->description = $description;
        $this->quantity = $quantity;
    }

    function getPrice() {
        return $this->price;
    }

    function getDescription() {
        return $this->description;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function getId() {
        return $this->id;
    }

    function getCategory() {
        return $this->category;
    }

    function setPrice($price) {
        if ($price >= 0) {
            $this->price = $price;
        } else {
            throw new InvalidArgumentException('Cena nie może być liczbą ujemną');
        }
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setQuantity($quantity) {
        if ($quantity >= 0) {
            $this->quantity = $quantity;
        } else {
            throw new InvalidArgumentException('Liczba zamówionych produktów nie może być ujemna');
        }
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCategory($category) {
        $this->category = $category;
    }

    public function byProduct($quantity) {
        if ($this->quantity -= $quantity < 0) {
            throw new InvalidArgumentException('Liczba zamówionych produktów nie może być ujemna');
        }
    }

    public $query = "CREATE TABLE Products(
    id int NOT NULL AUTO_INCREMENT, 
    id int NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(image_id),
    REFERENCES Images(image_id) );";

}
