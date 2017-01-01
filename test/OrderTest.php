<?php

require_once __DIR__ . '/../vendor/autoload.php';

class UserTest extends PHPUnit_Extensions_Database_TestCase {

    protected static $connection;

    public function getConnection() {
        //nowy obiekt tej klasy:
        $conn = new PDO(
                $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']
        );
        // zwraca obiekt z połączeniem testowym.
        return new PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection($conn, $GLOBALS['DB_NAME']);
    }

    // 2)
    public function getDataSet() {
        return $this->createFlatXMLDataSet(__DIR__ . '/../dataset/Orders.xml');
    }

    public static function setUpBeforeClass() {
        self::$connection = new mysqli(
                $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_NAME']
        );

        if (self::$connection->connect_error) {
            die(self::$connection->connect_error);
        }
    }

    public static function tearDownAfterClass() {
        self::$connection->close();
        self::$connection = null;
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfThrowExceptionOrderIdIsLessThenZero() {
        $newOrder = new Order;
        $newOrder->setOrderId(-5);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfThrowExceptionWhenStatusIsLessThenZero() {
        $newOrder = new Order;
        $newOrder->setStatus(-5);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfThrowExceptionWhenUserIdIsLessThenZero() {
        $newOrder = new Order;
        $newOrder->setUserId(-5);
    }
    

//    public function testIfAddingItemsToBasketWorks() {
//        $order = new Order((string)1);
//        
//        $product = new Product();
//        $product->setPrice((string)999.99);
//        $product->setQuantity((string)1);
//        $product->product_id = (string)1;
//        $product->setName('pralka');
//        $product->setStock((string)6);
//        $order->products = $product;
//        $order->addTheItemInTheDB(self::$connection, (string)1, (string)1, (string)0, (string)1);
//        
//        $loadedBasket = (Order::loadTheBasket(self::$connection, 1));
//        $decodeBasket = json_decode($loadedBasket[0]);
//        $orderFromTheBasekt = $decodeBasket;
//        
//       $this->assertEquals($order, $orderFromTheBasekt);
//    }

}
