<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Product {

    private $id;
    private $name;
    private $description;
    private $price;
    private $stock;

    public function __construct($id = -1, $name = null, $description = null, $price = null, $stock = null) {
        $this->id = $id;
        $this->setName($name);
        $this->setDescription($description);
        $this->setPrice($price);
        $this->setStock($stock);
    }

    public function addAProductToTheDB(mysqli $connection) {
        if ($this->id == -1) {
            $query = "INSERT INTO Products (name, description, price, stock)
                    VALUES ( '$this->name', '$this->description', '$this->price', '$this->stock'
                    )";
            if ($connection->query($query)) {
                $this->id = $connection->insert_id;
                return true;
            } else {
                return false;
            }
        }
    }

    public function buyAProduct($quantity) {
        if ($this->stock -= $quantity < 0) {
            throw new InvalidArgumentException('Niestety, produkt nie jest dostępny w podanej ilości');
        } else {
            $this->stock -= $quantity;
        }
    }

    public function addAPictureToTheDB(mysqli $connection, $link) {
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            $query = "INSERT INTO Pictures (picture_link, product_id) VALUES ('" . $connection->real_escape_string($link) . "', '" . $this->getId() . "')";
            if ($connection->query($query)) {
                $this->id = $connection->insert_id;
                return true;
            }
        } else {
            throw new InvalidArgumentException('Bledny adres linku');
        }
    }

    public function getAllPcituresOfTheItem(mysqli $connection, $item_id) {
        $query = "SELECT Pictures.Picture_link FROM Products JOIN Pictures ON Products.id = Pictures.Product_id WHERE Products.id = '$item_id'";
        $pictures = [];
        $result = $connection->query($query);
        if ($result == true && $result->num_rows > 0) {
            foreach ($result as $row) {
                $pictures[] = $row['Picture_link'];
            }
            return $pictures;
        }
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getPrice() {
        return $this->price;
    }

    function getDescription() {
        return $this->description;
    }

    function getStock() {
        return $this->stock;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        if (strlen(trim($name)) > 0) {
            $this->name = $name;
        } 
//        else {
//            throw new InvalidArgumentException('Bledna nazwa');
//        }
    }

    function getQuantity() {
        return $this->quantity;
    }


    function setPrice($price) {
        if ($price >= 0) {
            $this->price = $price;
        } else {
            throw new InvalidArgumentException('Cena nie może być liczbą ujemną');
        }
    }

    function setDescription($description) {
        if (strlen(trim($description)) > 0) {
            $this->description = $description;
        } 
//        else {
//            throw new InvalidArgumentException('Bledny opis');
//        }
    }

    function setStock($stock) {
        if ($stock >= 0) {
            $this->stock = $stock;
        } else {
            throw new InvalidArgumentException('Stany nie mogą być poniżej zera!!!');
        }
    }

    function setQuantity($quantity) {
        if ($quantity >= 0) {
            $this->quantity = $quantity;
        } else {
            throw new InvalidArgumentException('Liczba zamówionych produktów nie może być ujemna');
        }
    }


}

/*

      public $query = "CREATE TABLE Products(
      id int NOT NULL AUTO_INCREMENT,
      id int NOT NULL,
      PRIMARY KEY(id),
      FOREIGN KEY(image_id),
      REFERENCES Images(image_id) );";

     */

    