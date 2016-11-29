<?php

class Basket implements Countable {

    private $products;

    public function __construct() {
        $this->products = [];
    }

    public function getTotalPrice() {
        $totalPrice = 0;
        foreach ($this->products as $product) {
            $totalPrice += $product->getStock() * $product->getPrice();
        }
        return $totalPrice;
    }

    public function addAProductToTheBasket($product) {
        $this->products[] = $product;
        return $this->products;
    }

    public function count() {
        return $this->getTotalPrice();
    }

}
