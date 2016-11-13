<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Product {

    private $productId;
    private $name;
    private $description;
    private $price;
    private $stock;

    public function __construct($productId = -1, $name = null, $description = null, $price = null, $stock = null) {
        $this->productId = $productId;
        $this->setName($name);
        $this->setDescription($description);
        $this->setPrice($price);
        $this->setStock($stock);
    }
    
//    public function addTheItemToTheBasket($userId, $quantity) {
////            var_dump($this->userId);
//        $query = "INSERT INTO Orders (user_id, order_status)
//                    VALUES ('$userId', '1')";
//        if ($connection->query($query)) {
//            $this->id = $connection->insert_id;
//            $orderId = $this->id;
//            echo $orderId;
//            $productPrice = $this->getPrice();
//            echo $productPrice;
//            $productId = $this->getProductId();
//            echo $productId;
//            $query = "INSERT INTO orders_products (product_id, order_id, product_price, product_quantity) VALUES ('$productId', '$orderId', '$productPrice', '$quantity')";
//            if ($connection->query($query)) {
//                return true;
//            } else {
//                return false;
//            }
//        } else {
//            return false;
//        }
//    }
    
    public static function confirmTheBasket(mysqli $connection, $basket) {
        foreach($basket as $product){
//            $productsPriceFromDB = new Product();
            $productsPriceFromDB = Product::loadProductFromDb($connection, $product['itemId']);
            $priceToConfirm = $productsPriceFromDB->getPrice();
            $quantityToConfirm = $productsPriceFromDB->getStock();
            var_dump($priceToConfirm);
            var_dump($product['itemPrice']);
            
            if($priceToConfirm > $product['itemPrice'] && $quantityToConfirm > $product['itemQuantity']){
                echo 'tak';
            } else {
                echo 'nie';
            }
        }
    }
    
    public function changeProductPrice(mysqli $connection, $newPrice){
        $productId = $this->productId;
        $query = "UPDATE Products SET price = '$newPrice' WHERE id = '$this->productId'";
         if ($connection->query($query)) {
                return true;
            } else {
                return false;
            }
    }
    
    

    public function addAProductToTheDB(mysqli $connection) {
        if ($this->itemId == -1) {
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

    public static function getAllPcituresOfTheItem(mysqli $connection, $item_id) {
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
    public static function loadProductFromDb(mysqli $connection, $id) {
        $query = "SELECT * FROM Products WHERE id = '$id'";
        $products = [];
        $result = $connection->query($query);
        if ($result == true && $result->num_rows > 0) {
            foreach ($result as $row) {
                $products = new Product();
                $products->productId = $row['id'];
                $products->name = $row['name'];
                $products->description = $row['description'];
                $products->price = $row['price'];
                $products->stock = $row['stock'];
            }
            return $products;
        }
    }
    

    function getProductId() {
        return $this->productId;
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

    function setProductId($productId) {
        $this->productId = $productId;
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


    
    
     function rewind() {
        var_dump(__METHOD__);
        $this->position = 0;
    }

    function current() {
        var_dump(__METHOD__);
        return $this->users[$this->position];
    }

    function key() {
        var_dump(__METHOD__);
        return $this->position;
    }

    function next() {
        var_dump(__METHOD__);
        ++$this->position;
    }

    function valid() {
        var_dump(__METHOD__);
        return isset($this->users[$this->position]);
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

    