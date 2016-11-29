
<?php
require_once __DIR__ . '/../vendor/autoload.php';
class Order {

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

    public function manageTheOrderInTheDB(mysqli $connection, $status) {
        if ($this->orderId == -1) {
            $userId = $this->getUserId();
            $query = "INSERT INTO Orders (user_id, order_status)
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
    
    public static function loadTheBasket(mysqli $connection, $userId) {
        var_dump($userId);
        $query = "SELECT Orders.order_id, Orders.user_id, Orders.order_status, orders_products.product_id, orders_products.product_price, orders_products.product_quantity, Products.stock FROM Orders
                JOIN orders_products ON Orders.order_id = orders_products.order_id
                JOIN Products ON Products.id = orders_products.product_id
                 WHERE user_id = '$userId' AND order_status = '1'";
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
                $loadedOrder->products['stock'] = $row['stock'];
                $basket[] = $loadedOrder;
                }
        }
        return $basket;
    }
    
    


    static public function loadOrderById(mysqli $connection, $orderId) {
        $query = "SELECT * FROM Orders
                WHERE order_id = '" . $connection->real_escape_string($orderId) . '"
        SELECT * FROM Orders."';
//                
//                JOIN orders_products ON Orders.order_id = orders_products.order_id
//                JOIN Products ON Products.id = orders_products.product_id
        $res = $connection->query($query);
        if ($res == true && $res->num_rows == 1) {
            $row = $res->fetch_assoc();
            $loadedOrder = new Order();
            $loadedOrder->id = $row['order_id'];
            $loadedOrder->userId = $row['user_id'];

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

}
