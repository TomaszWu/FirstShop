
<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Order implements JsonSerializable {

    private $id;
    private $orderId;
    private $products;
    private $userId;
    private $status;

    public function __construct($id = -1, $orderId = null, $products = null, $userId = null, $status = null) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->products = [];
        $this->setUserId($userId);
        $this->setStatus($status);
    }
    
      public function jsonSerialize() {
        //funkcja zwraca nam dane z obiketu do json_encode
        return [
            'id' => $this->id,
            'orderId' => $this->orderId,
            'products' => $this->products,
            'userId' => $this->userId,
            'status' => $this->status
        ];
      }

    public function addTheItemInTheDB(mysqli $connection, $productId, $userId, $status, $productQuantity) {
        if ($this->id == -1) {
            $query = "INSERT INTO Orders (user_id, order_status, product_quantity)
                    VALUES ('$userId', '$status', '$productQuantity')";
            if ($connection->query($query)) {
                $this->id = $connection->insert_id;
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

    function getId() {
        return $this->id;
    }

//    function setId($id) {
//        $this->id = $id;
//    }
    
    
}
