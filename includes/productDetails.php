<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Db.php';
require_once (__DIR__ . '/../src/Product.php');
$conn = DB::connect();
$pictures = Product::getAllPcituresOfTheItem($conn, 30);
$product = Product::loadProductFromDb($conn, 1);
?>
<?php
?>
<!DOCTYPE html>
<html>
    <?php include('header.php'); ?>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">
        <?php include('navbar.php'); ?>
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->

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
                        <div class="item active ">
                            <img  src="/FirstShop/<?php echo $pictures[$i]; ?>" alt="Chania">
                        </div>
                    <?php } else {
                        ?>
                        <div class="item ">
                            <img src="/FirstShop/<?php echo $pictures[$i]; ?>" alt="Chania">
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
        
        


<!--        <div class="container">
            <h2>Pagination</h2>
            <p>The .pagination class provides pagination links:</p>
            <ul class="pagination">
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
            </ul>
        </div>-->
    </body>
