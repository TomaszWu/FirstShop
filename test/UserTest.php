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
        return $this->createFlatXMLDataSet(__DIR__ . '/../dataset/Users.xml');
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

    public function testIfLoginReturnsIdWithCorrectParams() {
//        logowanie będzie statyczne
        $this->assertEquals(1, User::login(self::$connection, 'testowy@rmail.com', 'testoweaslo'));
    }

    public function testIfGetUserByEmailReturnCorrectUser() {
        $user = new User(2, 'annabe@op.com');
        $user->setPassword('$2y$10$eNXbN9zbEi.qBzafH9afBOG7M1lQlw05ZhJy8DCZGhejaAtmoLXrK', false);
        $userFromDB = User::getUserByEmail(self::$connection, 'annabe@op.com');

        $this->assertEquals($user, $userFromDB);
    }
    
//    public function testIfUserIsCreatedProperly(){
//        $userToTest = new User('1', 'testname', 'testsurname' , 'testowy@rmail.com', '$2y$10$tSMHHuzxB4mmEHow7kjy4uq8toS3PA6Ey55PQstVp9Vu7PhEtBIpS',  'testowyadres');
//        $userFromDB = User::loadUsersFromDB(self::$connection, 1);
//        $this->assertEquals($userToTest, $userFromDB[0]);
//    }
    

}
