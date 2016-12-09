<?php

session_start();
var_dump($_SESSION);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Db.php';

$conn = DB::connect();
var_dump(unserialize($_SESSION['userId']));
$userId = unserialize($_SESSION['userId']);


$order = Order::loadTheBasket($conn, 37);
var_dump($order);

//foreach($order as $product){
//    $test = json_decode($product);
//    var_dump($test);
//    var_dump($test->products);
//    var_dump($test->products->stock);
//}

function confirmTheBasket(mysqli $connection, $order, $userId) {
    $result = $connection->query("SELECT * FROM Orders WHERE "
            . " order_status = 0 and user_id = '$userId' LIMIT 1");
    if ($result && $result->num_rows > 0) {
        foreach ($result as $row) {
            $orderIdForTheWholeBasket = $row['id'];
        }
    }
    foreach ($order as $productDetails) {
        $singleProduct = json_decode($productDetails);
        var_dump($singleProduct);
        var_dump($singleProduct->orderId);
        $result = $connection->query("UPDATE Orders
JOIN Orders_products ON Orders.id=Orders_products.order_id
SET Orders.order_id = '$orderIdForTheWholeBasket'
WHERE Orders.user_id = '$userId' AND Orders.order_status = 0 AND Orders.id = '$singleProduct->orderId'");
        if ($result) {
            $result = $connection->query("UPDATE Orders
JOIN Orders_products ON Orders.id=Orders_products.order_id
SET Orders.order_status = 1
WHERE Orders.user_id = '$userId' AND Orders.order_status = 0 AND Orders.id = '$singleProduct->orderId'");
            if ($result) {
                echo 'jest ok';
            } else {
                echo 'lipa';
            }
        }
    }
}

var_dump(confirmTheBasket($conn, $order, $userId));



//
//$query =   "SET Orders.order_status = 3
//   WHERE orders_products.order_id = 1";

//
//$result = $conn->query("SELECT * FROM Orders where order_status = 0 limit 1");
//    if($result) {
//            echo 'OK';
//            
//        } else {
//            echo 'Error <br>';
//        }
//
//foreach($result as $element){
//   $id = $element['order_id'];
//}
//var_dump($id);
//
//$result = $conn->query("SELECT * FROM Orders where order_status = 0 limit 1");




/*W
    
         


UPDATE  Orders

JOIN orders_products ON Orders.order_id=orders_products.order_id


   
  SET Orders.order_status = 3

   WHERE Orders.user_id = 36 AND Orders.order_status = 0
        
        
         * 
//PIERWSZY wgrywam koszyk
//    SELECT * FROM Orders
//  JOIN orders_products ON Orders.order_id=orders_products.order_id
//
//  JOIN Products  ON Products.id=orders_products.product_id
//  WHERE Orders.user_id = 36 AND Orders.order_status = 0
// */
//        
//        dwa i trzy:
//    
/*
        UPDATE Orders

JOIN Orders ON Orders.order_id=Orders_products.order_id


         SET Orders_products.order_id = 12
        
   WHERE Orders.user_id = 37 AND Orders.order_status = 0
  
         UPDATE Orders

JOIN Orders_products ON Orders.order_id=Orders_products.order_id


       
         SET Orders.order_status = 1
                 WHERE Orders.user_id = 37 AND Orders_products.order_id = 12
 * 
 * 
 * 
 */
//        
//        cztery:
/*  
  SELECT * FROM Orders_products

 JOIN Orders ON Orders.order_id=Orders_products.order_id

 JOIN Products  ON Products.id=Orders_products.product_id
  WHERE Orders.user_id = 37 AND Orders_products.order_id= 0
 * 
 * 
 * 
 * 
 * 
 * 
//    
/*
           UPDATE Orders

JOIN Orders_products ON Orders.order_id=Orders_products.order_id


         SET Orders_products.order_id = 32
        
   WHERE Orders.user_id = 37 AND Orders_products.order_status = 0
  
 * 
 * 
 * 
 */
//1)
//         UPDATE Orders
//
//JOIN Orders_products ON Orders.order_id=Orders_products.order_id
//
//2)
//       
//         SET Orders_products.order_status = 1
//                 WHERE Orders.user_id = 37 AND Orders_products.order_id = 39
//                 
//                 
//                 UPDATE Orders
//
//JOIN Orders_products ON Orders.order_id=Orders_products.order_id
//
//
//  3)     
//         SET Orders.order_status = 1
//                 WHERE Orders.user_id = 37 AND Orders_products.order_id = 39
//                 
//    4)             
//                 DELETE FROM Orders
//                  WHERE Orders.user_id = 37 AND Orders.status = 0