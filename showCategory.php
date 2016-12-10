<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';
$conn = DB::connect();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $_SESSION['category'] = $_GET['id'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['productId']) && isset($_POST['price'])) {
        $productId = $_POST['productId'];
        $price = $_POST['price'];
        $userId = unserialize($_SESSION['userId']);
        $order1 = new Order;
    }
}
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
<?php include('includes/navbarOutsideTheMainPage.php'); ?>
        <?php
        $categoryId = $_SESSION['category'];
        $products = Product::loadProductFromDb($conn, null, $categoryId);
        foreach ($products as $product) {
            $productId = $product->getProductId();
            $name = $product->getName();
            $description = $product->getDescription();
            $price = $product->getPrice();
            $stock = $product->getStock();
            $pictures = $product->getPictures();
            for ($i = 0; $i < count($pictures); $i ++) {
                if ($i == 0) {
                    ?>

                    <div class="panel-group">
                        <div class="panel-heading">
                            <div id="myCarouse<?php echo $productId ?>" class="carousel slide">
                                <div class="carousel-inner" role="listbox">
                                    <ol class="carousel-indicators">
                                        <li data-target="#myCarouse<?php echo $productId ?>" data-slide-to="<?php echo $i ?>" class="active"></li>
        <?php } else {
            ?>
                                        <li data-target="#myCarouse<?php echo $productId ?>" data-slide-to="<?php echo $i ?>"></li>
                                        <?php
                                    }
                                }
                                ?>
                            </ol>

    <?php
    for ($i = 0; $i < count($pictures); $i ++) {
        if ($i == 0) {
            ?>
                                    <div class="item active ">
                                        <a href="productDetails.php?productId=<?php echo $productId ?>"><img  src="/FirstShop/<?php echo $pictures[$i]; ?>" alt="Chania"></a>
                                        <h3><?php echo $name ?></h3>
                                        <p>Beatiful flowers in Kolymbari, Crete.</p>
                                    </div>
        <?php } else {
            ?>
                                    <div class="item ">
                                        <a href="productDetails.php?productId=<?php echo $productId ?>"><img src="/FirstShop/<?php echo $pictures[$i]; ?>" alt="Chania"></a>
                                        <h3><?php echo $name ?></h3>
                                        <!--<p>Beatiful flowers in Kolymbari, Crete.</p>-->
                                    </div>
            <?php
        }
        ?>

                                <a class="left carousel-control" href="#myCarouse<?php echo $productId ?>" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#myCarouse<?php echo $productId ?>" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
    <?php } ?>
                        </div>
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
                    <form method="POST" action="">
                        <input type="submit" class="btn btn-info" name="productId" value="Submit Button">
                        <input type="hidden" class="btn btn-info" name="productId" value="<?php echo $productId ?>">
                        <input type="hidden" class="btn btn-info" name="price" value="<?php echo $price ?>">
                    </form>
                </div>

            </div>




<?php } ?>
    </body>
