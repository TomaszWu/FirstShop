<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Category {

    public $id;
    public $categoryId;
    public $categoryName;

    public function __construct($id = -1, $categoryId = null, $categoryName = null) {
        $this->id = $id;
        $this->setCategoryId($categoryId);
        $this->setCategoryName($categoryName);
    }

    public static function loadAllProductFromParticularCategory(mysqli $connection, $categoryId) {
        $query = "SELECT * FROM Products JOIN Categories ON Products.id = Categories.product_id
            LEFT JOIN Pictures ON Products.id = Pictures.Product_id
                WHERE Categories.category_id = '$categoryId'";
        $productsWithoutPictures = [];
        $productsWithPictures = [];
        $result = $connection->query($query);
        if ($result == true && $result->num_rows > 0) {
            foreach ($result as $row) {
                $product = new Product();
                $product->productId = $row['id'];
                $product->name = $row['name'];
                $product->description = $row['description'];
                $product->categoryId = $categoryId;
                $product->price = $row['price'];
                $product->stock = $row['stock'];
                $product->pictures['picture_link'] = [];
                if (!in_array($product, $productsWithoutPictures)) {

                    $productsWithoutPictures[] = $product;
                }
            }
            foreach ($productsWithoutPictures as $product) {
                $test = Product::getAllPcituresOfTheItem($connection, $product->getProductId());
                $product->setPictures($test);
                $productsWithPictures[] = $product;
            }
            return $productsWithoutPictures;
        }
    }

    public static function getAllCategories(mysqli $connection) {
        $query = "SELECT * FROM Categories";
        $categories = [];
        $result = $connection->query($query);
        if ($result == true && $result->num_rows > 0) {
            foreach ($result as $row) {
                $category = new Category();
                $category->id = $row['id'];
                $category->categoryId = $row['category_id'];
                $category->categoryName = $row['category_name'];
                $categories[] = $category;
            }
        }
        return $categories;
    }

    function getCategoryName() {
        return $this->categoryName;
    }

    function setCategoryName($categoryName) {
        $this->categoryName = $categoryName;
    }

    function getCategoryId() {
        return $this->categoryId;
    }

    function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

}
