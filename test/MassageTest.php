<?php

require_once __DIR__ . '/../vendor/autoload.php';

class MassageTest extends PHPUnit_Extensions_Database_TestCase {

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
        return $this->createFlatXMLDataSet(__DIR__ . '/../dataset/Massages.xml');
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
    
    
//    public function testIfMassageAddedToTheDB() {
//        $massage1 = new Massage;
//        $massage1->setReceiver(2);
//        $massage1->setText('rower2');
//    }
    public function testIfReturnsArrayType(){
        $this->assertInternalType('array', Massage::loadMassagesFromDB(self::$connection, 2));
    }
    
    public function testIfReturnsProperMassagesIfLoadedByUserId(){
        $sendMassageOne = new Massage((string)5);
        $sendMassageOne->setTitle('title5');
        $sendMassageOne->setText('text5');
        $sendMassageOne->setUser_Id(3);
        $sendMassageOne->setStatus(3);
        $sendMassageTwo = new Massage((string)6, 'title6', 'text6', (string)3, (string)3);
        $sendMassageOne->addAMassageToDB(self::$connection);
        $sendMassageTwo->addAMassageToDB(self::$connection);
        
        $arrayWithMassages[] = $sendMassageTwo;
        $arrayWithMassages[] = $sendMassageOne;
        
        $massageFromDB = Massage::loadMassagesFromDB(self::$connection, 3);
        $this->assertEquals($arrayWithMassages, $massageFromDB);
    }
    
    public function testIfLoadedMassageIsTheSameLikeTheAddedOne() {
        $sendMassageOne = new Massage((string)5, 'title5', 'text5', (string)3, (string)3);
        $sendMassageOne->addAMassageToDB(self::$connection);
        $loadedMassage = Massage::loadMassagesFromDB(self::$connection, null, 5)[0];
        $this->assertEquals($sendMassageOne, $loadedMassage);
    }
    
    public function testIfChangingTheStatusOfTheMassageWors() {
        $sendMassageOne = new Massage((string)5, 'title5', 'text5', (string)3, (string)1);
        $sendMassageOne->addAMassageToDB(self::$connection);
        $newStatus = 2;
        $sendMassageOne->changeTheStatus(self::$connection, $newStatus);
        $loadedMassage = Massage::loadMassagesFromDB(self::$connection, null, 5)[0];
        $statusToCompare = $loadedMassage->getStatus();
        $this->assertEquals($newStatus, $statusToCompare);
    }
    
    
    

}
