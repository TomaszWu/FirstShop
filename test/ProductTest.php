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

//    /**
//     * @expectedException InvalidArgumentException
//     */
//    public function testIfItIsPossibleToGiveTheWrongDescriptionToTheProduct() {
//        $productToTest = new Product;
//        $productToTest->setDescription('');
//    }
//
//    /**
//     * @expectedException InvalidArgumentException
//     */
//    public function testIfItIsPossibleToGiveTheWrongNameToTheProduct() {
//        $productToTest = new Product;
//        $productToTest->setName('dsa');
//    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfThrowExceptionWhenWhenTheWrongLinkIsPassed() {
        $productToTest = new Product;
        $productToTest->addAPictureToTheDB(self::$connection, 'WRONG URL');
    }

    public function testIfForParticularPictureProperArrialWithPicturesIsReceived() {
        $productToTest = new Product();
        $productToTest->setName('test');
        $productToTest->setDescription('test2');
        $productToTest->setPrice(5);
        $productToTest->setStock(10);
        $productToTest->addAProductToTheDB(self::$connection);
        $id = $productToTest->getId();
        $test1 = $productToTest->addAPictureToTheDB(self::$connection, 'http://www.oleole.pl/foto/2/8992910192/820cc0e9f985b10407fef5709ae55b4f/8992910192_6.jpg');
        $test2 = $productToTest->getAllPcituresOfTheItem(self::$connection, $id);
        $this->assertInternalType('array', $productToTest->getAllPcituresOfTheItem(self::$connection, $id));
    }

}
