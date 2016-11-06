<?php

require_once __DIR__ . '/../vendor/autoload.php';

class ProductTest extends PHPUnit_Extensions_Database_TestCase {

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
        return $this->createFlatXMLDataSet(__DIR__ . '/../dataset/Products.xml');
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
    public function testIfThrowExceptionWhenSetPriceIsLessThenZero() {
        $productToTest = new Product;
        $productToTest->setPrice(-5);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfThrowExceptionWhenSetStockIsLessThenZero() {
        $productToTest = new Product;
        $productToTest->setPrice(-5);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfItIsPossibleToBuyProductWhichIsOutOfStock() {
        $productToTest = new Product;
        $productToTest->setStock(2);
        $productToTest->buyAProduct(3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfItIsPossibleToGiveTheWrongDescriptionToTheProduct() {
        $productToTest = new Product;
        $productToTest->setDescription('test');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfItIsPossibleToGiveTheWrongNameToTheProduct() {
        $productToTest = new Product;
        $productToTest->setName('test');
    }

    public function testIfCreatedProductIsCorrect() {
        $productToTest = new Product;
        $productToTest->setPrice(10);
//        $this->assertEquals(10, $productToTest->getCategory());
//        $this->assertEquals(10, $productToTest->getDescription());
//        $this->assertEquals(10, $productToTest->getName());
        $this->assertEquals(10, $productToTest->getPrice());
//        $this->assertEquals(10, $productToTest->getStock());
    }

}
