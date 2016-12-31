<?php

require_once __DIR__ . '/../vendor/autoload.php';

class AdminTest extends PHPUnit_Extensions_Database_TestCase {

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
        return $this->createFlatXMLDataSet(__DIR__ . '/../dataset/Admins.xml');
    }

    public static function setUpBeforeClass() {
        self::$connection = new mysqli(
                $GLOBALS['DB_HOST'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'], $GLOBALS['DB_NAME']
        );

        if (self::$connection->connect_error) {
            die(self::$connection->connect_error);
        }
    }

   
    public function testIfLoadedAdminIsCorrect() {
        $admin = new Admin();
        $admin->setId((string)4);
        $admin->setEmail('email@email.com1');
        $admin->setPassword('$2y$10$8Sf6oJk/pO6Xeca9l.OYveuTN863t9LRr.p4fj1h09Dzk20ljy2Ui', false);
        $adminToCompare = Admin::loadByEmail(self::$connection, 'email@email.com1');
        $this->assertEquals($admin, $adminToCompare);
    }

}
