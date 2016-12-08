
<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Order implements JsonSerializable {

    private $orderId;
    private $products;
    private $userId;
    private $status;

    public function __construct($orderId = -1, $products = null, $userId = null, $status = null) {
        $this->orderId = $orderId;
        $this->products = [];
        $this->setUserId($userId);
        $this->setStatus($status);
    }

    public function jsonSerialize() {
        //funkcja zwraca nam dane z obiketu do json_encode
        return [
            'orderId' => $this->orderId,
            'products' => $this->products,
            'userId' => $this->userId,
            'status' => $this->status
        ];
    }

    public function changeTheQuantity(mysqli $connection, $quantitiy) {
        $query = "UPDATE Orders SET Product_quantity = '$quantitiy' WHERE id ='$this->orderId'";
        if ($connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function addTheItemInTheDB(mysqli $connection, $productId, $userId, $status, $productQuantity) {
        if ($this->orderId == -1) {
            $query = "INSERT INTO Orders (user_id, order_status, product_quantity)
                    VALUES ('$userId', '$status', '$productQuantity')";
            if ($connection->query($query)) {
                $this->orderId = $connection->insert_id;
                $orderId = $this->getOrderId();
                $query = "INSERT INTO Orders_products (product_id, order_id) VALUES ('$productId', '$this->orderId')";
                if ($connection->query($query)) {
                    return true;
                } else {
                    return false;
                }
                return true;
            }
        }
    }

    public static function deleteTheItemFromBasket(mysqli $connection, $orderId) {
        $query = "DELETE FROM Orders WHERE Orders.id = '$orderId'";
        if ($connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function manageTheOrderInTheDB(mysqli $connection, $status) {
        if ($this->orderId == -1) {
            $userId = $this->getUserId();
            $query = "INSERT INTO Orders (user_id, status)
                    VALUES ('$userId', '$status')";
            if ($connection->query($query)) {
                $this->orderId = $connection->insert_id;
                $orderId = $this->getOrderId();

                foreach ($this->getProducts() as $product) {
                    $productPrice = $product['itemPrice'];
                    $productQuantity = $product['itemQuantity'];
//                    if($productQuantity > $details['stock']){
//                        echo 'blad';
//                    }
                    $productId = $product['itemId'];
                    $query = "INSERT INTO orders_products (product_id, order_id, product_price, product_quantity) VALUES ('$productId', '$orderId', '$productPrice', '$productQuantity')";
                    if ($connection->query($query)) {
                        echo '<br>tak2';
                    } else {
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        }
    }

//    public function manageTheOrderInTheDB(mysqli $connection, $userId, $status) {
//        if ($this->orderId == -1) {
//            $query = "INSERT INTO Orders (user_id, order_status)
//                    VALUES ('$userId', '$status')";
//            if ($connection->query($query)) {
//                $this->orderId = $connection->insert_id;
//                $orderId = $this->getOrderId();
//                
//                foreach ($basket as $product) {
//                    $details = $product->getProducts();
//                    $productPrice = $details['price'];
//                    $productQuantity = $details['quantity'];
//                    if($productQuantity > $details['stock']){
//                        echo 'blad';
//                    }
//                    $productId = $details['product_id'];
//                    $query = "INSERT INTO orders_products (product_id, order_id, product_price, product_quantity) VALUES ('$productId', '$orderId', '$productPrice', '$productQuantity')";
//                    if ($connection->query($query)) {
//                        echo 'tak';
//                    } else {
//                        return false;
//                    }
//                }
//                return true;
//            } else {
//                return false;
//            }
//        }
//    }

    public static function confirmTheBasket(mysqli $connection, $userId) {
        $result = $connection->query("SELECT * FROM Orders where order_status = 0 and user_id = '$userId' LIMIT 1");
        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $orderIdForTheWholeBasket = $row['id'];
            }
            $result = $connection->query("UPDATE Orders_products
JOIN Orders ON Orders.id=Orders_products.order_id
SET Orders_products.order_id = '$orderIdForTheWholeBasket'
WHERE Orders.user_id = '$userId' AND Orders.order_status = 0");
            if ($result) {
                $result = $connection->query("UPDATE Orders
JOIN Orders_products ON Orders.id=Orders_products.order_id
SET Orders.order_status = 1
WHERE Orders.user_id = '$userId' AND Orders_products.order_id = '$orderIdForTheWholeBasket'");
                if ($result) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public static function loadTheBasket(mysqli $connection, $userId) {
        $query = "SELECT Orders.user_id, Orders.order_status, Orders.id as order_id, 
            Products.price as product_price, Orders.product_quantity, 
            Products.id as product_id, Products.stock, Products.name as product_name FROM Orders
                JOIN Orders_products ON Orders.id = Orders_products.order_id
                JOIN Products ON Products.id = Orders_products.product_id
                 WHERE Orders.user_id = '$userId' AND Orders.order_status = 0";
        $basket = [];
        $result = $connection->query($query);
        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $loadedOrder = new Order();
                $loadedOrder->userId = $row['user_id'];
                $loadedOrder->status = $row['order_status'];
                $loadedOrder->orderId = $row['order_id'];
                $loadedOrder->products['price'] = $row['product_price'];
                $loadedOrder->products['quantity'] = $row['product_quantity'];
                $loadedOrder->products['product_id'] = $row['product_id'];
                $loadedOrder->products['product_name'] = $row['product_name'];
                $loadedOrder->products['stock'] = $row['stock'];
                $basket[] = json_encode($loadedOrder);
            }
        }
        return $basket;
    }

    static public function loadOrderById(mysqli $connection, $orderId) {
        $query = "SELECT Orders.user_id, Orders.order_status, Orders.id as order_id, 
            Products.price as product_price, Orders.product_quantity, 
            Products.id as product_id, Products.stock, Products.name as product_name FROM Orders
                JOIN Orders_products ON Orders.id = Orders_products.order_id
                JOIN Products ON Products.id = Orders_products.product_id
                 WHERE Orders.id = '" . $connection->real_escape_string($orderId) . "'";
        $res = $connection->query($query);
        if ($res == true && $res->num_rows == 1) {
            $row = $res->fetch_assoc();
            $loadedOrder = new Order();
            $loadedOrder->userId = $row['user_id'];
            $loadedOrder->status = $row['order_status'];
            $loadedOrder->orderId = $row['order_id'];
            $loadedOrder->products['price'] = $row['product_price'];
            $loadedOrder->products['quantity'] = $row['product_quantity'];
            $loadedOrder->products['product_id'] = $row['product_id'];
            $loadedOrder->products['product_name'] = $row['product_name'];
            $loadedOrder->products['stock'] = $row['stock'];

            return $loadedOrder;
        }
        return null;
    }

    function getOrderId() {
        return $this->orderId;
    }

    function getItem() {
        return $this->item;
    }

    function getUserId() {
        return $this->userId;
    }

    function getProducts() {
        return $this->products;
    }

    function getStatus() {
        return $this->status;
    }

    function setProducts($products) {
        $this->products = $products;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function getId() {
        return $this->id;
    }

//    function setId($id) {
//        $this->id = $id;
//    }
}
