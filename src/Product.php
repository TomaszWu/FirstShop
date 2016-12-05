<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Product implements JsonSerializable {

    public $productId;
    public $name;
    public $description;
    public $categoryId;
    public $price;
    public $orderedQuantity;
    public $keyInTheBasket;
    public $stock;
    public $pictures;

    public function __construct($productId = -1, $name = null, $description = null, $categoryId = null, $price = null, $orderedQuantity = null, $keyInTheBasket = null, $stock = null) {
        $this->productId = $productId;
        $this->setName($name);
        $this->setDescription($description);
        $this->setCategoryId($categoryId);
        $this->setPrice($price);
        $this->setOrderedQuantity($orderedQuantity);
        $this->setKeyInTheBasket($keyInTheBasket);
        $this->setStock($stock);
        $this->pictures = [];
//        $this->position = 0;
    }

    public function jsonSerialize() {
        return [
            'productId' => $this->productId,
            'name' => $this->name,
            'description' => $this->description,
            'categoryId' => $this->categoryId,
            'price' => $this->price,
            'orderedQuantity' => $this->orderedQuantity,
            'keyInTheBasket' => $this->keyInTheBasket,
            'stock' => $this->stock,
            'pictures' => $this->pictures,
        ];
    }

    public function addTheItemInTheDB(mysqli $connection, $userId, $status) {
        if ($this->orderId == -1) {
            $userId = $this->getUserId();
            $query = "INSERT INTO Orders (user_id, order_status)
                    VALUES ('$userId', '$status')";
            if ($connection->query($query)) {
                $this->orderId = $connection->insert_id;
                $orderId = $this->getOrderId();

                $query = "INSERT INTO orders_products (product_id, order_id, product_quantity) VALUES ('$this->getId()', '$orderId', '1')";
                if ($connection->query($query)) {
                    echo '<br>tak2';
                } else {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        }
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
        foreach ($basket as $product) {
//            $productsPriceFromDB = new Product();
            $productsPriceFromDB = Product::loadProductFromDb($connection, $product['itemId']);
            $priceToConfirm = $productsPriceFromDB->getPrice();
            $quantityToConfirm = $productsPriceFromDB->getStock();
            var_dump($priceToConfirm);
            var_dump($product['itemPrice']);

            if ($priceToConfirm > $product['itemPrice'] && $quantityToConfirm > $product['itemQuantity']) {
                echo 'tak';
            } else {
                echo 'nie';
            }
        }
    }

    public function changeProductPrice(mysqli $connection, $newPrice) {
        $productId = $this->productId;
        $query = "UPDATE Products SET price = '$newPrice' WHERE id = '$this->productId'";
        if ($connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function addAProductToTheDB(mysqli $connection) {
        if ($this->productId == -1) {
            $query = "INSERT INTO Products (name, description, category_id, price, stock)
                    VALUES ( '$this->name', '$this->description', '$this->categoryId', '$this->price', '$this->stock'
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
        $query = "SELECT Pictures.picture_link FROM Products JOIN Pictures ON Products.id = Pictures.Product_id WHERE Products.id = '$item_id'";
        $pictures = [];
        $result = $connection->query($query);
        if ($result == true && $result->num_rows > 0) {
            foreach ($result as $row) {
                $pictures[] = $row['picture_link'];
            }
            return $pictures;
        }
    }

    public static function loadProductFromDb(mysqli $connection, $id = null, $categoryId = null) {
        if (is_null($id) && is_null($categoryId)) {
            $query = "SELECT * FROM Products LEFT JOIN Pictures ON Products.id = Pictures.Product_id";
        } elseif ($categoryId) {
            $query = "SELECT * FROM Products LEFT JOIN Pictures ON Products.id = Pictures.Product_id WHERE category_id = '$categoryId'";
        } else {
            $query = "SELECT * FROM Products LEFT JOIN Pictures ON Products.id = Pictures.Product_id WHERE Products.id = '$id'";
        }


        $productsWithoutPictures = [];
        $productsWithPictures = [];
        $result = $connection->query($query);
        if ($result == true && $result->num_rows > 0) {
            foreach ($result as $row) {
                $product = new Product();
                $product->productId = $row['id'];
                $product->name = $row['name'];
                $product->description = $row['description'];
                $product->categoryId = $row['category_id'];
                $product->price = $row['price'];
                $product->stock = $row['stock'];
                $product->pictures['picture_link'] = [];
                if (!in_array($product, $productsWithoutPictures)) {

                    $productsWithoutPictures[] = ($product);
                }
            }
            foreach ($productsWithoutPictures as $product) {
                $test = Product::getAllPcituresOfTheItem($connection, $product->getProductId());
                $product->setPictures($test);
                $productsWithPictures[] = ($product);
            }
        }
        return $productsWithPictures;
    }

    public static function loadProductFromDbJson(mysqli $connection, $id = null, $categoryId = null) {
        if (is_null($id) && is_null($categoryId)) {
            $query = "SELECT * FROM Products LEFT JOIN Pictures ON Products.id = Pictures.Product_id";
        } elseif ($categoryId) {
            $query = "SELECT * FROM Products LEFT JOIN Pictures ON Products.id = Pictures.Product_id WHERE category_id = '$categoryId'";
        } else {
            $query = "SELECT * FROM Products LEFT JOIN Pictures ON Products.id = Pictures.Product_id WHERE Products.id = '$id'";
        }
        $productsWithoutPictures = [];
        $productsWithPictures = [];
        $result = $connection->query($query);
        if ($result == true && $result->num_rows > 0) {
            foreach ($result as $row) {
                $product = new Product();
                $product->productId = $row['id'];
                $product->name = $row['name'];
                $product->description = $row['description'];
                $product->categoryId = $row['category_id'];
//                $product->orderedQuantity = $row['ordered_quantity'];
                $product->price = $row['price'];
                $product->stock = $row['stock'];
                $product->pictures['picture_link'] = [];
                if (!in_array($product, $productsWithoutPictures)) {

                    $productsWithoutPictures[] = ($product);
                }
            }
            foreach ($productsWithoutPictures as $product) {
                $pictures = Product::getAllPcituresOfTheItem($connection, $product->getProductId());
                $product->setPictures($pictures);
                $productsWithPictures[] = ($product);
            }
        }
        return $productsWithPictures;
    }

//    public static function loadProductFromDb2(mysqli $connection, $id) {
//        $query = "SELECT * FROM Products LEFT JOIN Pictures ON Products.id = Pictures.Product_id WHERE Products.id = '$id'";
//        $products = [];
//        $result = $connection->query($query);
//        if ($result == true && $result->num_rows > 0) {
//            foreach ($result as $row) {
//                if (!$products) {
//                    $products = new Product();
//                    $products->productId = $row['id'];
//                    $products->name = $row['name'];
//                    $products->description = $row['description'];
//                    $products->price = $row['price'];
//                    $products->stock = $row['stock'];
//                    $products->pictures['picture_link'] = [];
//                }
//                if ($row['picture_link']) {
//                    $products->pictures['picture_link'][] = $row['picture_link'];
//                }
//            }
//            return $products;
//        }
//    }


    public function addAProductToTheBasket(mysqli $connection, $productId, $userId) {
        
    }

    function setPictures($pictures) {
        $this->pictures = $pictures;
    }

    function setOrderedQuantity($orderedQuantity) {
        $this->orderedQuantity = $orderedQuantity;
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

    function getKeyInTheBasket() {
        return $this->getKeyInTheBasket;
    }

    function getCategoryId() {
        return $this->categoryId;
    }

    function getOrderedQuantity() {
        return $this->orderedQuantity;
    }

    function getStock() {
        return $this->stock;
    }

    function getPictures() {
        return $this->pictures;
    }

    function setProductId($productId) {
        $this->productId = $productId;
    }

    function setKeyInTheBasket($keyInTheBasket) {
        $this->keyInTheBasket = $keyInTheBasket;
    }

    function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
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

    