<?php

require_once __DIR__ . '/../vendor/autoload.php';

class CategoryTest extends PHPUnit_Extensions_Database_TestCase {

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
        return $this->createFlatXMLDataSet(__DIR__ . '/../dataset/Categories.xml');
    }

    public static function setUpBeforeClass() {
        self::$connection = new mysqli(
                $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_NAME']
        );

        if (self::$connection->connect_error) {
            die(self::$connection->connect_error);
        }
    }

    public function testIfLoadedCategoryIsTheSameLikeTheCreatedOne() {
        $newCategory = new Category(1, 'category1');
        $newCategory->addNewCategory(self::$connection);
        $loadedCategory = Category::getCategoryById(self::$connection, 1);
        $this->assertEquals($newCategory, $loadedCategory);
    }

    public function testIfLoadedAllCategoriesAreCorrect() {
        $category1 = new Category(1, 'category1');
        $category2 = new Category(2, 'category2');
        $category3 = new Category(3, 'category3');

        $category1->addNewCategory(self::$connection);
        $category2->addNewCategory(self::$connection);
        $category3->addNewCategory(self::$connection);


        $allCategories[] = $category1;
        $allCategories[] = $category2;
        $allCategories[] = $category3;

        $loadedCategories = Category::getAllCategories(self::$connection);

        $this->assertEquals($loadedCategories, $allCategories);
    }

    public function testIfDeletedCategoryIsInFactDeleted() {
        $category1 = new Category(1, 'category1');
        $category1->addNewCategory(self::$connection);
        Category::deleteCategory(self::$connection, 1);
        $deletedCategory = Category::getCategoryById(self::$connection, 1);
        $this->assertNull($deletedCategory);
    }

    public function testIfLoadedProductsByCategoryIdAreCorrect() {

        $product1 = new Product();
        $product1->setProductId((string)1);
        $product1->setCategoryId(1);
        $product1->setName('pralka');
        $product1->setDescription('nowy model turbo2000');
        $product1->setPrice((string)999.99);
        $product1->setStock((string)6);
        $product1->setPictures(null);
//        $product1->addAProductToTheDB(self::$connection);

        $product2 = new Product();
        $product2->setProductId((string)2);
        $product2->setCategoryId(1);
        $product2->setName('TV');
        $product2->setDescription('Sharp Extra');
        $product2->setPrice((string)1999.99);
        $product2->setStock((string)7);
        $product2->setPictures(null);
//        $product2->addAProductToTheDB(self::$connection);


        $arrayWithProductsWithCategoryIdOne[] = $product1;
        $arrayWithProductsWithCategoryIdOne[] = $product2;
        
        $loadedProductsWithCategory1 = Category::loadAllProductFromParticularCategory(self::$connection, 1);
        $this->assertEquals($arrayWithProductsWithCategoryIdOne, $loadedProductsWithCategory1);
    }

}
