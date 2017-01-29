<?php
namespace src;

class Order implements \JsonSerializable {

    private $id;
    private $orderId;
    public $products;
    private $userId;
    public $status;

    public function __construct($id = -1, $orderId = null, $userId = null, $status = null) {
        $this->id = $id;
        $this->setOrderId($orderId);
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

    public function changeTheQuantity(\mysqli $connection, $quantitiy) {
        $query = "UPDATE Orders SET product_quantity = '$quantitiy' WHERE id ='$this->id'";
        if ($connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    //poniższa metoda dodaje pozyjcę do koszyka
    public function addTheItemInTheDB(\mysqli $connection, $productId, $userId, $status, $productQuantity) {
        if ($this->id == -1) {
            $query = "INSERT INTO Orders (user_id, order_status, product_quantity)
                    VALUES ('$userId', '$status', '$productQuantity')";
            if ($connection->query($query)) {
                $this->id = $connection->insert_id;
                $orderId = $this->getId();
                $query = "INSERT INTO Orders_products (product_id, order_id) VALUES ('$productId', '$orderId')";
                if ($connection->query($query)) {
                    return true;
                } else {
                    return false;
                }
                return true;
            }
        }
    }

// konstrukacja tworzenia zamówien wymaga takiego rozwiązania. Zamówienia potwiedzone mają
//    wspólny nr zamówienia, zamówienia niepotwierdzone, w koszyki, w ogóle nie mają nr
//    zamówienia. Stąd też w pierwszej kolejności sprawdzam, że czy zamówienie potwierdzone
//    istnieje w db, jeżeli nie, oznacza to, że to jest zamówienie koszykowe. 
    public static function deleteTheItemFromBasket(mysqli $connection, $orderId) {
        $checkIfOrderWithThisOrderIdExists = self::loadOrder($connection, null, $orderId);
        var_dump($checkIfOrderWithThisOrderIdExists);
        if (!$checkIfOrderWithThisOrderIdExists) {
            $query = "DELETE FROM Orders WHERE Orders.id = '$orderId'";
        } else {
            $query = "DELETE FROM Orders WHERE Orders.order_id = '$orderId'";
        }
        if ($connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    public static function changeOrderStatus(\mysqli $connection, $orderId, $newStatus) {
        $query = "UPDATE Orders SET order_status = '" . $connection->real_escape_string($newStatus) . "'"
                . " WHERE order_id = '" . $connection->real_escape_string($orderId) . "'";
        if ($connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function manageTheOrderInTheDB(\mysqli $connection, $status) {
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

    public static function confirmTheBasket(\mysqli $connection, $order, $userId) {
        $result = $connection->query("SELECT * FROM Orders WHERE "
                . " order_status = 0 and user_id = '$userId' LIMIT 1");
        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $orderIdForTheWholeBasket = $row['id'];
            }
        }
        foreach ($order as $productDetails) {
            $singleProduct = json_decode($productDetails);
            $result = $connection->query("UPDATE Orders
JOIN Orders_products ON Orders.id=Orders_products.order_id
SET Orders.order_id = '$orderIdForTheWholeBasket'
WHERE Orders.user_id = '$userId' AND Orders.order_status = 0 AND Orders.id = '$singleProduct->id'");
            if ($result) {
                $result = $connection->query("UPDATE Orders
JOIN Orders_products ON Orders.id=Orders_products.order_id
SET Orders.order_status = 1
WHERE Orders.user_id = '$userId' AND Orders.order_status = 0 AND Orders.id = '$singleProduct->id'");
            }
        } if ($result) {
            return $orderIdForTheWholeBasket;
        } else {
            return false;
        }
    }

    public static function loadTheBasket(\mysqli $connection, $userId) {
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
                $loadedOrder->id = $row['order_id'];
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

    static public function loadOrder(\mysqli $connection, $userId = null, $orderId = null, $status = null) {
        if ($orderId) {
            $query = "SELECT Orders.user_id, Orders.order_status, Orders.id, Orders.order_id,
            Products.price as product_price, Orders.product_quantity, 
            Products.id as product_id, Products.stock, Products.name as product_name FROM Orders
                JOIN Orders_products ON Orders.id = Orders_products.order_id
                JOIN Products ON Products.id = Orders_products.product_id
                 WHERE Orders.order_id = '" . $connection->real_escape_string($orderId) . "'";
        } elseif ($userId) {
            $query = "SELECT Orders.user_id, Orders.order_status, Orders.id, Orders.order_id,
            Products.price as product_price, Orders.product_quantity, 
            Products.id as product_id, Products.stock, Products.name as product_name FROM Orders
                JOIN Orders_products ON Orders.id = Orders_products.order_id
                JOIN Products ON Products.id = Orders_products.product_id
                 WHERE Orders.user_id ='$userId'";
        } elseif ($status || $status == 0) {
            $query = "SELECT Orders.user_id, Orders.order_status, Orders.id, Orders.order_id,
            Products.price as product_price, Orders.product_quantity, 
            Products.id as product_id, Products.stock, Products.name as product_name FROM Orders
                JOIN Orders_products ON Orders.id = Orders_products.order_id
                JOIN Products ON Products.id = Orders_products.product_id
                WHERE Orders.order_status = '" . $connection->real_escape_string($status) . "'";
        } else {
            $query = "SELECT Orders.user_id, Orders.order_status, Orders.id, Orders.order_id,
            Products.price as product_price, Orders.product_quantity, 
            Products.id as product_id, Products.stock, Products.name as product_name FROM Orders
                JOIN Orders_products ON Orders.id = Orders_products.order_id
                JOIN Products ON Products.id = Orders_products.product_id";
        }

        $res = $connection->query($query);
        $loadedOrders = [];
        if ($res == true && $res->num_rows > 0) {
            foreach ($res as $row) {
                if (!isset($loadedOrders[$row['order_id']])) {
                    $loadedOrders[$row['order_id']] = [];
                }
                $loadedOrder = new Order();
                $loadedOrder->id = $row['id'];
                $loadedOrder->userId = $row['user_id'];
                $loadedOrder->status = $row['order_status'];
                $loadedOrder->orderId = $row['order_id'];
                $loadedOrder->products['price'] = $row['product_price'];
                $loadedOrder->products['quantity'] = $row['product_quantity'];
                $loadedOrder->products['product_id'] = $row['product_id'];
                $loadedOrder->products['product_name'] = $row['product_name'];
                $loadedOrder->products['stock'] = $row['stock'];
                if ($row['order_id'] == [$row['order_id']][0]) {
                    $loadedOrders[$row['order_id']][] = $loadedOrder;
                }
            }
        }
        return $loadedOrders;
    }

    static public function loadOrdersByOrderId(\mysqli $connection, $orderId) {
        $query = "SELECT Orders.user_id, Orders.order_status, Orders.id, Orders.order_id,
            Products.price as product_price, Orders.product_quantity, 
            Products.id as product_id, Products.stock, Products.name as product_name FROM Orders
                JOIN Orders_products ON Orders.id = Orders_products.order_id
                JOIN Products ON Products.id = Orders_products.product_id
                 WHERE Orders.id = '" . $connection->real_escape_string($orderId) . "'";
        $res = $connection->query($query);

        if ($res == true && $res->num_rows == 1) {
            $row = $res->fetch_assoc();
            $loadedOrder = new Order();
            $loadedOrder->id = $row['id'];
            $loadedOrder->userId = $row['user_id'];
            $loadedOrder->status = $row['order_status'];
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
        if ($userId > 0) {
            $this->orderId = $orderId;
        }
//        else {
//            throw new InvalidArgumentException('Nr zamówienia nie może być liczbą ujemną');
//        }
    }

    function setStatus($status) {
        if ($status > 0) {
            $this->orderId = $orderId;
        }
//        else {
//            throw new InvalidArgumentException('Nr zamówienia nie może być liczbą ujemną');
//        }
    }

    function setOrderId($orderId) {
        if ($orderId > 0) {
            $this->orderId = $orderId;
        }
//        else {
//            throw new InvalidArgumentException('Nr zamówienia nie może być liczbą ujemną');
//        }
    }

    function getId() {
        return $this->id;
    }

//    function setId($id) {
//        $this->id = $id;
//    }
}
