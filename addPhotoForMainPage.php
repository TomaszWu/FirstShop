<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
use src\Picture;
use src\Category;
use src\Db;
$conn = Db::connect();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['categoryId'])) {
        $_SESSION['categoryId'] = $_GET['categoryId'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['adminId'])) {
        $allowedFileTypes = ['image/png', 'image/jpeg', 'image/gif'];
        $catgoryId = ($_SESSION['categoryId']);
        if (isset($_FILES['fileToUpload']) &&
                $_FILES['fileToUpload']['size'] > 0 && in_array($_FILES['fileToUpload']['type'], $allowedFileTypes)) {
            $catgoryId = $_SESSION['categoryId'];
            $uploadDir = 'PicturesOnMainPage';

            if (is_dir($uploadDir . '/' . $catgoryId)) {
                echo $uploadDir . '/' . $catgoryId . 'exists<br>';
            } else {
                mkdir($uploadDir . '/' . $catgoryId);
                echo $uploadDir . '/' . $catgoryId . 'created<br>';
            }


            $destination = $uploadDir;
            $destinationFileName = $destination . '/' . $catgoryId . '/' . $_FILES['fileToUpload']['name'];
            $query = "INSERT INTO PicturesForCategory (picture_link, category_id) VALUES ('" . quotemeta($destinationFileName) . "', '" . $catgoryId . "')";

            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $destinationFileName)) {
                if ($conn->query($query)) {
                    // tutaj koniecznie trzeba dodać jakieś wyjątki!!!
                    echo 'File upload to DB success<br>';
                } else {
                    echo 'File upload to DB failed!!!';
                }
            } else {
                echo 'File upload to DB failed!!!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

    <heade>
        <?php include('includes/header.php'); ?>
    </heade>
    <style>
        body { 
            padding-top: 65px; 
        }


    </style>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">

        <?php
        include('includes/navbarOutsideTheMainPage.php');
        $categoryId = $_SESSION['categoryId'];
        $picturesOnMainPage = Picture::getPhotoForMainPageForOneCategory($conn, $categoryId);
        $category = Category::getCategoryById($conn, $categoryId);
        ?>
        <div>
            <div class="container-fluid">
                <h1><?php echo $category->getCategoryName() ?></h1>
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <ol class="carousel-indicators">
                            <?php
                            $i = 0;
                            foreach ($picturesOnMainPage as $picture) {
                                if ($i == 0) {
                                    ?>
                                    <li data-target="#myCarousel" data-slide-to="<?php echo $i ?>" class="active"></li>
                                <?php } else {
                                    ?>
                                    <li data-target="#myCarousel" data-slide-to="<?php echo $i ?>"></li>
                                    <?php
                                }
                                $i++;
                            }
                            ?>
                        </ol>

                        <?php
                        $j = 0;
                        foreach ($picturesOnMainPage as $picture) {
                            if ($j == 0) {
                                ?>
                                <div class="item active">
                                    <img src="<?php echo $picture->getPicture_link(); ?>" alt="Chania">
                                </div>
                            <?php } else {
                                ?>
                                <div class="item">
                                    <img src="<?php echo $picture->getPicture_link(); ?>" alt="Chania">
                                </div>
                                <?php
                            }
                            $j++;
                        }
                        ?>

                    </div>
                    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <?php if (isset($_SESSION['adminId'])) { ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <h3>Dodaj zdjęcie</h3>
                            <div class="form-group">
                                <input type="file" name="fileToUpload">
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Upload file">
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
            
            <a href="catAdmin.php">Powrót od zarządzania grupami</a>
    </body>
</html>
