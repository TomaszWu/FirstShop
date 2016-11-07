<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ .'/../src/Db.php';
$conn = DB::connect();

$productToTest = new Product();
        $productToTest->setName('test1');
        $productToTest->setDescription('test2');
        $productToTest->setPrice(5);
        $productToTest->setStock(10);
        var_dump($productToTest);
        var_dump($productToTest->addAProductToTheDB($conn));
        $id = $productToTest->getId();
        $test1 = $productToTest->addAPictureToTheDB($conn, 'http://www.oleole.pl/foto/2/8992910192/820cc0e9f985b10407fef5709ae55b4f/8992910192_6.jpg');
        $test2 = $productToTest->getAllPcituresOfTheItem($conn, 3);
var_dump($id);
var_dump($test2);
//var_dump($test2);{
    
