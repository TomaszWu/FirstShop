<?php
namespace src;

class Category 
{

    public $categoryId;
    public $categoryName;

    public function __construct($categoryId = -1, $categoryName = null) {
        $this->categoryId = $categoryId;
        $this->setCategoryName($categoryName);
    }

    public function addNewCategory(mysqli $connection) {
        $query = "INSERT INTO Categories (category_name) VALUES "
                . "('" . $connection->real_escape_string($this->categoryName) . "')";
        $result = $connection->query($query);
        if ($result == true) {
            return true;
        } else {
            return false;
        }
    }

    public static function deleteCategory(mysqli $connection, $categotyId) {
        $query = "DELETE FROM Categories WHERE category_id = "
                . "('" . $connection->real_escape_string($categotyId) . "')";
        $result = $connection->query($query);
        if ($result == true) {
            return true;
        } else {
            return false;
        }
    }

    public static function loadAllProductFromParticularCategory(mysqli $connection, $categoryId) {
        $query = "SELECT * FROM Products
                LEFT JOIN Categories ON Products.category_id = Categories.category_id
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

    public static function getAllCategories( mysqli $connection) {
        $query = "SELECT * FROM Categories ORDER BY category_id";
        $categories = [];
        $result = $connection->query($query);
        if ($result == true && $result->num_rows > 0) {
            foreach ($result as $row) {
                $category = new Category();
                $category->categoryId = $row['category_id'];
                $category->categoryName = $row['category_name'];
                $categories[] = $category;
            }
        }
        return $categories;
    }

    public static function getCategoryById(mysqli $connection, $categoryId) {
        $result = $connection->query("SELECT * FROM Categories WHERE category_id = '" .
                $connection->real_escape_string($categoryId) . "'");
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $category = new Category();
            $category->setCategoryId($row['category_id']);
            $category->setCategoryName($row['category_name']);
            return $category;
        }
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
