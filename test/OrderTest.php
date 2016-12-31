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
    
    
     public function testIfAddedOrderIsCorrect(){
        $order = new Order(1, 1, 2, 2, 22);
        $order->addAnOrderToTheDB(self::$connection);
        $userFromDB = Order::loadOrder(self::$connection, null, '1')[0];
        $this->assertEquals($order, $userFromDB);
        
    }
    
    
    
    
    
}