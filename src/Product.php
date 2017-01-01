<?php


require_once __DIR__ . '/../../vendor/autoload.php';

class Product implements JsonSerializable {

    public $productId;
    public $name;
    public $description;
    public $categoryId;
    public $price;
    public $stock;
    public $pictures;

    public function __construct($productId = -1, $name = null, $description = null, $categoryId = null, $price = null, $stock = null) {
        $this->productId = $productId;
        $this->setName($name);
        $this->setDescription($description);
        $this->setCategoryId($categoryId);
        $this->setPrice($price);
        $this->setStock($stock);
        $this->pictures = [];
    }

    public function jsonSerialize() {
        return [
            'productId' => $this->productId,
            'name' => $this->name,
            'description' => $this->description,
            'categoryId' => $this->categoryId,
            'price' => $this->price,
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

    public function changeProductPrice(mysqli $connection, $newPrice) {
        if ($newPrice > 0) {
            $productId = $this->productId;
            $query = "UPDATE Products SET price = '$newPrice' WHERE id = '$this->productId'";
            if ($connection->query($query)) {
                return true;
            }
        } else {
            throw new InvalidArgumentException('Cena nie może być poniżej zera');
        }
    }

    public function changeProductDescription(mysqli $connection, $newDescription) {
        if (strlen(trim($newDescription)) > 0) {
            $query = "UPDATE Products SET description = '$newDescription' WHERE id = '$this->productId'";
            if ($connection->query($query)) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new InvalidArgumentException('Opis jest pusty');
        }
    }

    public function changeProductStock(mysqli $connection, $newStock) {
        if ($newStock > 0) {
            $productId = $this->productId;
            $query = "UPDATE Products SET stock = '$newStock' WHERE id = '$this->productId'";
            if ($connection->query($query)) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new InvalidArgumentException('Stany nie mogą być poniżej zera');
        }
    }

    public function deleteTheItem(mysqli $connection) {
        $query = "DELETE FROM Products WHERE id = '$this->productId'";
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
        } else {
            $query = "UPDATE Products 
                    SET  name = '$this->name', description = '$this->description', "
                    . " category_id = '$this->categoryId', price = '$this->price', "
                    . " stock = '$this->stock'
                    WHERE id = '$this->productId'";
        }
        if ($connection->query($query)) {
            $this->productId = $connection->insert_id;
            return true;
        } else {
            return false;
        }
    }

    public function buyAProduct(mysqli $connection, $quantity) {

        if ($this->stock - $quantity < 0) {
            throw new InvalidArgumentException('Niestety, produkt nie jest dostępny w podanej ilości');
        } else {
            $this->stock = $this->stock - $quantity;
            $query = "UPDATE Products SET stock = '$this->stock ' WHERE id = '$this->productId'";
            if ($connection->query($query)) {
//                $this->id = $connection->insert_id;
                return true;
            } else {
                return false;
            }
        }
    }

    public function addAPictureToTheDB(mysqli $connection, $link) {
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            $query = "INSERT INTO Pictures (picture_link, product_id) VALUES ('" . $connection->real_escape_string($link) . "', '" . $this->getId() . "')";
            if ($connection->query($query)) {
                $this->id = $connection->insert_id;
                return true;
//            }
            } else {
                throw new InvalidArgumentException('Bledny adres linku');
            }
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
            $query = "SELECT Products.id, Products.name, Products.description, "
                    . "Products.category_id,  Products.price, Products.stock, "
                    . "Pictures.picture_link FROM Products LEFT JOIN Pictures "
                    . "ON Products.id = Pictures.Product_id WHERE category_id = '$categoryId'";
        } else {
            $query = "SELECT Products.id, Products.name, Products.description, "
                    . "Products.category_id,  Products.price, Products.stock, "
                    . "Pictures.picture_link FROM Products LEFT JOIN Pictures "
                    . "ON Products.id = Pictures.Product_id WHERE Products.id = '$id'";
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
                $pictures = Product::getAllPcituresOfTheItem($connection, $product->getProductId());
                $product->setPictures($pictures);
                $productsWithPictures[] = ($product);
            }
        }
        return $productsWithPictures;
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

    function getCategoryId() {
        return $this->categoryId;
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

    function setCategoryId($categoryId) {
        if ($categoryId >= 0) {
        $this->categoryId = $categoryId;
        } else {
            throw new InvalidArgumentException('Cena nie może być liczbą ujemną');
        }
    }

    function setName($name) {
            $this->name = $name;
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
//            throw new InvalidArgumentException('Bledny dopis');
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
        }
//        else {
//            throw new InvalidArgumentException('Liczba zamówionych produktów nie może być ujemna');
//        }
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

    