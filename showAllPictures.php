<<?php ?>
<!DOCTYPE html>
<html>
    <head> 
        <?php include('includes/header.php'); ?>
        <style>
            body {
                position: relative;
            }
            #section1 {padding-top:50px;height:500px;color: #fff; background-color: #1E88E5;}
            #section2 {padding-top:50px;height:500px;color: #fff; background-color: #673ab7;}
            #section3 {padding-top:50px;height:500px;color: #fff; background-color: #ff9800;}
            #section41 {padding-top:50px;height:500px;color: #fff; background-color: #00bcd4;}
            #section42 {padding-top:50px;height:500px;color: #fff; background-color: #009688;}

            .carousel-inner > .item > img,
            .carousel-inner > .item > a > img {
                width: 70%;
                margin: auto;
            }
            .product .img-responsive {
                margin: 0 auto;
            }

        </style>
    </head>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->

            <div class="carousel-inner" role="listbox">
                <?php
                include(__DIR__ . '/src/Db.php');
                $conn = DB::connect();
                require_once (__DIR__ . '/src/Product.php');
                $pictues = Product::getAllPcituresOfTheItem($conn, 2);
                ?>
                <ol class="carousel-indicators">
                    <?php
                    for ($i = 0; $i < count($pictues); $i ++) {
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
                for ($i = 0; $i < count($pictues); $i ++) {
                    if ($i == 0) {
                        ?>
                        <div class="item active">
                            <img src="<?php echo $pictues[$i]; ?>" alt="Chania">
                        </div>
                    <?php } else {
                        ?>
                        <div class="item">
                            <img src="<?php echo $pictues[$i]; ?>" alt="Chania">
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


        <div class="container">
            <h2>Pagination</h2>
            <p>The .pagination class provides pagination links:</p>
            <ul class="pagination">
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
            </ul>
        </div>
    </body>
