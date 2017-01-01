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
    public function testIfItIsPossibleToBuyProductWhichIsOutOfStock() {
        $productToTest = new Product;
        $productToTest->setStock(2);
        $productToTest->buyAProduct(self::$connection, 3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfItIsPossibleToGivePriceLowerThanZero() {
        $productFromDB = Product::loadProductFromDb(self::$connection, 1)[0];
        $productFromDB->changeProductPrice(self::$connection, -1);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfItIsPossibleToGiveEptyDescription() {
        $productFromDB = Product::loadProductFromDb(self::$connection, 1)[0];
        $productFromDB->changeProductDescription(self::$connection, '');
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfItIsPossibleToGiveStockLowerThanZero() {
        $productFromDB = Product::loadProductFromDb(self::$connection, 1)[0];
        $productFromDB->changeProductStock(self::$connection, -1);
    }
    

    public function testIfProductAddedToTheDbIsCorrenct() {
        $product1 = new Product((string) 1);
        $product1->setCategoryId((string) 1);
        $product1->setName('pralka');
        $product1->setDescription('nowy model turbo2000');
        $product1->setPrice((string) 999.99);
        $product1->setStock((string) 6);
        $product1->setPictures(null);
        

//        $product2 = new Product();
//        $product2->setProductId((string)2);
//        $product2->setCategoryId(1);
//        $product2->setName('TV');
//        $product2->setDescription('Sharp Extra');
//        $product2->setPrice((string)1999.99);
//        $product2->setStock((string)7);
//        $product2->setPictures(null);
//        $product1->addAProductToTheDB(self::$connection);
        $productFromDB = Product::loadProductFromDb(self::$connection, 1)[0];
        $this->assertEquals($product1, $productFromDB);
    }
    
    
    public function testIfIsPossibleToDeleteItem() {
        
        
        $product1 = (Product::loadProductFromDb(self::$connection, 3)[0]);
        $product1->deleteTheItem(self::$connection);
        $deletedItem = Product::loadProductFromDb(self::$connection, 3);
        $this->assertEmpty($deletedItem);
    }
    
    public function testIfStockChangesWhenItemIsBought() {
        $productBeforeBuying = Product::loadProductFromDb(self::$connection, 2)[0];
        $stockBeforeBuying = $productBeforeBuying->getStock();
        $productBeforeBuying->buyAProduct(self::$connection, 2);
        $productfterBuying = Product::loadProductFromDb(self::$connection, 2)[0];
        $stockAfterBuying = $productfterBuying->getStock();
        $this->assertEquals($stockBeforeBuying, $stockAfterBuying + 2);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testIfThrowAnInvalidExceptionWhenStockIsToSmallToBuyNeededQuantity() {
        $productBeforeBuying = Product::loadProductFromDb(self::$connection, 3)[0];
        $stockBeforeBuying = $productBeforeBuying->getStock();
        $productBeforeBuying->buyAProduct(self::$connection, 2);
        $productfterBuying = Product::loadProductFromDb(self::$connection, 10)[0];
        $stockAfterBuying = $productfterBuying->getStock();
        $this->assertEquals($stockBeforeBuying, $stockAfterBuying + 3);
    }
    
    public function testIfIsPossibleToLoadProductByCategory() {
        
        $product1 = new Product((string)1);
        $product1->setCategoryId((string)1);
        $product1->setName('pralka');
        $product1->setDescription('nowy model turbo2000');
        $product1->setPrice((string)999.99);
        $product1->setStock((string)6);
        
        // zdjęcia są nulle. Tutaj jest źle zaprogramowana ta klasa. Zdjęcia powinny
        //być dodawane jako oddzielne obiety a nie przechowywane w  tablicy
        // przypisanej do tablicy. Ten nie popełnia błędów kto nic nnie robic ;)
        $product1->setPictures(null);
        
        $product2 = new Product((string)2);
        $product2->setCategoryId((string)1);
        $product2->setName('TV');
        $product2->setDescription('Sharp Extra');
        $product2->setPrice((string)1999.99);
        $product2->setStock((string)7);
        $product2->setPictures(null);
        
        $productsToCompare[] = $product1;
        $productsToCompare[] = $product2;
                
        $productsFromSecondCategory = Product::loadProductFromDb(self::$connection, 'null', 1);
        $this->assertEquals($productsToCompare, $productsFromSecondCategory);
        
        
    }
    
    

}
