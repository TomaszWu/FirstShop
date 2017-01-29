<?php
namespace src;


class Picture {
    
    private $id;
    private $picture_link;
    private $product_id;
    private $category_id;
    
    
    public function __construct($id = -1, $picture_link = null, $product_id = null, 
            $category_id = null) {
        $this->id = $id;
        $this->setPicture_link($picture_link);
        $this->setProduct_id($product_id);
        $this->setCategory_id($category_id);
    }
    
    
    public static function getPhotoForMainPageForOneCategory(\mysqli $connection, $categoryId){
        $result = $connection->query("SELECT * FROM PicturesForCategory WHERE category_id = '" . 
                $connection->real_escape_string($categoryId) . "'");
        $picturesForMainPage = [];
        if ($result == true && $result->num_rows > 0) {
            foreach ($result as $row) {
                $newPicture = new Picture();
                $newPicture->picture_link = $row['picture_link'];
                $newPicture->category_id = $row['category_id'];
                $picturesForMainPage[] = $newPicture;
            }
        }
        return $picturesForMainPage;
    }
    
    function getId() {
        return $this->id;
    }

    function getPicture_link() {
        return $this->picture_link;
    }

    function getProduct_id() {
        return $this->product_id;
    }

    function getCategory_id() {
        return $this->category_id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setPicture_link($picture_link) {
        $this->picture_link = $picture_link;
    }

    function setProduct_id($product_id) {
        $this->product_id = $product_id;
    }

    function setCategory_id($category_id) {
        $this->category_id = $category_id;
    }


    
    
}


