<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';
$conn = DB::connect();
require_once (__DIR__ . '/src/Product.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['productId'])) {
        $_SESSION['productId'] = $_GET['productId'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump($_POST);
    if (isset($_POST['productId']) && isset($_POST['price'])) {
//        $productId = $_SESSION['productId'];
        $productId = $_SESSION['productId'];
        $price = $_POST['price'];
        $userId = unserialize($_SESSION['userId']);
        $order1 = new Order;
        $order1->addTheItemInTheDB($conn, $productId, $userId, 0, 1);
    } else {
        // po zalogowaniu jako admin ta część kodu odpowiada za wnoszenie zmian do danej pozycji.
        if (isset($_SESSION['adminId'])) {
            $itemId = $_SESSION['productId'];
            $prodToModify = Product::loadProductFromDb($conn, $itemId)[0];

            if (isset($_POST['newDescription']) && strlen(trim($_POST['newDescription'])) > 0) {
                $newDescription = $_POST['newDescription'];
                $prodToModify->changeProductDescription($conn, $newDescription);
            }
            if (isset($_POST['newPrice']) && strlen(trim($_POST['newPrice'])) > 0) {
                $newPrice = $_POST['newPrice'];
                $prodToModify->changeProductPrice($conn, $newPrice);
            }
            if (isset($_POST['newStock']) && strlen(trim($_POST['newStock'])) > 0) {
                $newStock = $_POST['newStock'];
                $prodToModify->changeProductStock($conn, $newStock);
            }
            $allowedFileTypes = ['image/png', 'image/jpeg', 'image/gif'];
            if (isset($_FILES['fileToUpload']) &&
                    $_FILES['fileToUpload']['size'] > 0 && in_array($_FILES['fileToUpload']['type'], $allowedFileTypes)) {
                $productId = $_SESSION['productId'];
                $uploadDir = 'Pictures';

                if (is_dir($uploadDir . '/' . $productId)) {
                    echo $uploadDir . '/' . $productId . 'exists<br>';
                } else {
                    mkdir($uploadDir . '/' . $productId);
                    echo $uploadDir . '/' . $productId . 'created<br>';
                }


                $destination = $uploadDir;
                $destinationFileName = $destination . '/' . $productId . '/' . $_FILES['fileToUpload']['name'];
                $query = "INSERT INTO Pictures (picture_link, product_id) VALUES ('" . quotemeta($destinationFileName) . "', '" . $productId . "')";

                if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $destinationFileName)) {
//            echo 'File upload success<br>';
                    if ($conn->query($query)) {
                        // tutaj koniecznie trzeba dodać jakieś wyjątki!!!
                        echo 'File upload to DB success<br>';
                    } else {
                        echo 'File upload to DB failed!!!';
                    }
//            echo '<a href="showImage.php?file=' . base64_encode($destinationFileName) . '">Show image</a>';
                } else {
                    echo 'File upload to DB failed!!!';
                }
            }
        }
    }
}
//}
?>
<!DOCTYPE html>
<html>

    <?php include('includes/header.php'); ?>
    <style>
        body { 
            padding-top: 65px; 
        }


    </style>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">

        <?php
        include('includes/navbarOutsideTheMainPage.php');
//if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//    if (isset($_GET['productId'])) {
        $productId = $_SESSION['productId'];
        $productToShow = Product::loadProductFromDb($conn, $productId)[0];
        $name = $productToShow->getName();
        $description = $productToShow->getDescription();
        $price = $productToShow->getPrice();
        $stock = $productToShow->getStock();
        $pictures = $productToShow->getPictures();
//    }
//}
        ?>
        <div>
            <div class="container-fluid">
                <h1><?php echo $name ?></h1>
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <ol class="carousel-indicators">
                            <?php
                            for ($i = 0; $i < count($pictures); $i ++) {
                                if ($i == 0) {
                                    ?>
                                    <li data-target="#myCarousel" data-slide-to="<?php echo $i ?>" class="active"></li>
                                <?php } else {
                                    ?>
                                    <li data-target="#myCarousel" data-slide-to="<?php echo $i ?>"></li>
                                    <?php
                                }
                            }
                            ?>
                        </ol>

                        <?php
                        for ($i = 0; $i < count($pictures); $i ++) {
                            if ($i == 0) {
                                ?>
                                <div class="item active">
                                    <img src="<?php echo $pictures[$i]; ?>" alt="Chania">
                                </div>
                            <?php } else {
                                ?>
                                <div class="item">
                                    <img src="<?php echo $pictures[$i]; ?>" alt="Chania">
                                </div>
                                <?php
                            }
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
                <table class="table">
                    <tr>
                        <th>Opis:</th>
                        <td><?php echo $description ?></td>
                    </tr>
                    <tr>
                        <th>Cena:</th>
                        <td><?php echo $price ?></td>
                    </tr>
                    <tr>
                        <th>Stan:</th>
                        <td><?php echo $stock ?></td>
                    </tr>

                </table>

                <?php if (isset($_SESSION['adminId'])) { ?>
                    <table class="table table-condensed">
                        <tr>
                            <td>
                                <div class="container-fluid">
                                    <h3>Wprowadź zmiany</h3>
                                    <form method="POST" action="">
                                        <div class="form-group col-sm-6">
                                            <div class="form-group">
                                                <label>Opis</label>
                                                <textarea class="form-control" rows="5" name="newDescription"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Cena</label>
                                                <input type="number" step="any" class="form-control" name="newPrice">
                                            </div>
                                            <div class="form-group">
                                                <label>Stan magazynowy</label>
                                                <input type="number" class="form-control" name="newStock">
                                            </div>
                                            <button type="submit" class="btn btn-danger">Zmień</button>
                                            <a href="itemsAdmin.php">Powrót do zarządzania przedmiotami</a>
                                        </div>
                                    </form>
                            </td>
                            <td>
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
                                </div>
                            </td>
                        </tr>
                    </table>
                <?php } else { ?>
                    <form method="POST" action="">
                        <?php if (isset($_SESSION['userId'])) { ?>
                            <input type="submit" class="btn btn-info" name="productId" value="Dodaj do koszyka">
                            <input type="hidden" class="btn btn-info" name="productId" value="<?php echo $productId ?>">
                            <input type="hidden" class="btn btn-info" name="price" value="<?php echo $price ?>">
                        <?php } else { ?>
                            <button type="button" class="btn btn-primary disabled"><abbr title="Zaloguj się aby móc kontynuować zakupy">Dodaj do koszyka</abbr></button>
                        <?php } ?>
                    </form>
                <?php } ?>
            </div>
        </div>

    </body>
