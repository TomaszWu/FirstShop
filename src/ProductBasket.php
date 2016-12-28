<?php

require_once __DIR__ . '/../vendor/autoload.php';
class ProductBasket implements Countable{
    private $products;

    public function __construct(){
        $this->products = [];
    }

    public function getTotalPrice(){
        $totalPrice = 0;
        foreach($this->products as $key => $products){
                foreach($products as $quantity => $product){
                    $totalPrice += $quantity * $product->getPrice();
                }
        }
        return $totalPrice;
    }
    
    public function addAProductToTheBasket($product, $quantity){
        if($quantity < $product->getStock()){
            $this->products[] = [$quantity => $product];
        } else {
            echo 'blad';
        }
        
    }
//    public function addAProductToTheBasekt($itemId, $itemPrice, $quantity){
//        $this->basket[$itemId] = [$itemPrice => $quantity ];
//    }
    
    public function count() 
    { 
        return $this->getTotalPrice(); 
    } 
    
}
