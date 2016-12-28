<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';
$conn = DB::connect();
$allCategies = Category::getAllCategories($conn);
?>
<!DOCTYPE html>
<?php include('includes/header.php')
?>
<script src="js/index.js"></script>
<body data-spy="scroll" data-target=".navbar" data-offset="50">
    <?php
    include('includes/navbar.php');
    $i = 0;
    foreach ($allCategies as $singleCategory) {
        ?>



        <div id="section<?php echo $i ?>" class="container-fluid snlgCat">
            <h1><?php
                echo
                $singleCategory->getCategoryName();
                $categoryId = $singleCategory->getCategoryId();
                $mainPagePhotos = Category::getPhotoForMainPageForOneCategory($conn, $categoryId)
                ?></h1>
            <div id="myCarouse<?php echo $i ?>" class="carousel slide" data-ride="carouse<?php echo $i ?>">
                <!-- Indicators -->
                <!--                <ol class="carousel-indicators">
                                    <li data-target="#myCarouse<?php echo $i ?>" data-slide-to="0" class="active"></li>
                                    <li data-target="#myCarouse<?php echo $i ?>" data-slide-to="1"></li>
                                    <li data-target="#myCarouse<?php echo $i ?>" data-slide-to="2"></li>
                                    <li data-target="#myCarouse<?php echo $i ?>" data-slide-to="3"></li>
                                </ol>-->

                <ol class="carousel-indicators">
                    <?php
                    for ($j = 0; $j < count($MainPagePhotos); $j++) {
                        if ($j == 0) {
                            ?>
                            <div class="item active">
                                <img src="<?php echo $mainPagePhotos[$j]->getPicture_link(); ?>" alt="Chania">
                            </div>
                        <?php } else {
                            ?>
                            <div class="item">
                                <img src="<?php $mainPagePhotos->getPicture_link(); ?>" alt="Chania">
                            </div>
                        </ol>
                        <?php
                    }
                }
                ?>
                <!--Wrapper for slides -->
                <div class = "carousel-inner" role = "listbox">

                    <div class = "item active">
                        <a href = "showCategory.php?id=<?php echo $i ?>"><img src = "/FirstShop/Pictures/1/29.jpg" alt = "Chania" width = "460" height = "345"></a>
                        <div class = "carousel-caption">
                            <h3><?php
                                echo
                                $singleCategory->getCategoryName()
                                ?></h3>
                            <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                        </div>
                    </div>

                    <div class="item">
                        <a href="showCategory.php?id=<?php echo $i ?>"><<img src="/FirstShop/Pictures/1/29_1.jpg" alt="Chania" width="460" height="345"></a>
                        <div class="carousel-caption">
                            <h3>Samochody</h3>
                            <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                        </div>
                    </div>

                    <div class="item">
                        <a href="showCategory.php?id=<?php echo $i ?>"><<img src="/FirstShop/Pictures/31/31.jpg" alt="Flower" width="460" height="345"></a>
                        <div class="carousel-caption">
                            <h3>Samochody</h3>
                            <p>Beatiful flowers in Kolymbari, Crete.</p>
                        </div>
                    </div>

                    <div class="item">
                        <a href="showCategory.php?id=<?php echo $i ?>"><<img src="/FirstShop/Pictures/33/33_1.jpg" alt="Flower" width="460" height="345"></a>
                        <div class="carousel-caption">
                            <h3>Samochody</h3>
                            <p>Beatiful flowers in Kolymbari, Crete.</p>
                        </div>
                    </div>

                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarouse<?php echo $i ?>" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarouse<?php echo $i ?>" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <?php
        $i++;
    }
    ?>
    <!--    <div id="section2" class="container-fluid">
            <h1>Kwiaty</h1>
            <div id="myCarouse2" class="carousel slide" data-ride="carousel">
                 Indicators 
                <ol class="carousel-indicators">
                    <li data-target="#myCarouse2" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarouse2" data-slide-to="1"></li>
                    <li data-target="#myCarouse2" data-slide-to="2"></li>
                    <li data-target="#myCarouse2" data-slide-to="3"></li>
                </ol>
    
                 Wrapper for slides 
                <div class="carousel-inner" role="listbox">
    
                    <div class="item active">
                        <a href="showCategory.php?id=2"><img src="/FirstShop/Pictures/19/19.jpg" alt="Chania" width="460" height="345"></a>
                        <div class="carousel-caption">
                            <h3>Chania</h3>
                            <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                        </div>
                    </div>
    
                    <div class="item">
                        <a href="showCategory.php?id=2"><img src="/FirstShop/Pictures/26/26.jpg" alt="Chania" width="460" height="345"></a>
                        <div class="carousel-caption">
                            <h3>Chania</h3>
                            <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                        </div>
                    </div>
    
                    <div class="item">
                        <a href="showCategory.php?id=2"><img src="/FirstShop/Pictures/34/34.jpg" alt="Flower" width="460" height="345"></a>
                        <div class="carousel-caption">
                            <h3>Flowers</h3>
                            <p>Beatiful flowers in Kolymbari, Crete.</p>
                        </div>
                    </div>
    
                    <div class="item">
                        <a href="showCategory.php?id=2"><img src="/FirstShop/Pictures/35/35.jpg" alt="Flower" width="460" height="345"></a>
                        <div class="carousel-caption">
                            <h3>Flowers</h3>
                            <p>Beatiful flowers in Kolymbari, Crete.</p>
                        </div>
                    </div>
    
                </div>
    
                 Left and right controls 
                <a class="left carousel-control" href="#myCarouse2" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarouse2" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    
        <div>
            <p>Try to scroll this section and look at the navigation bar whiyle scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
        <p>Tr to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
        </div>
        <div id="section3" class="container-fluid">
            <h1>Narkotyki</h1>
            <div id="myCarouse3" class="carousel slide" data-ride="carousel">
                 Indicators 
                <ol class="carousel-indicators">
                    <li data-target="#myCarouse3" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarouse3" data-slide-to="1"></li>
                    <li data-target="#myCarouse3" data-slide-to="2"></li>
                    <li data-target="#myCarouse3" data-slide-to="3"></li>
                </ol>
    
                 Wrapper for slides 
                <div class="carousel-inner" role="listbox">
    
                    <div class="item active">
                        <a href="showCategory.php?id=3"><img src="Pictures/21/21_1.jpg" alt="Chania" width="460" height="345"></a>
                        <div class="carousel-caption">
                            <h3>Chania</h3>
                            <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                        </div>
                    </div>
    
                    <div class="item">
                        <a href="showCategory.php?id=3"><img src="Pictures/22/22.jpg" alt="Chania" width="460" height="345"></a>
                        <div class="carousel-caption">
                            <h3>Chania</h3>
                            <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                        </div>
                    </div>
    
                    <div class="item">
                        <a href="showCategory.php?id=3"><img src="Pictures/23/23.jpg" alt="Flower" width="460" height="345"></a>
                        <div class="carousel-caption">
                            <h3>Flowers</h3>
                            <p>Beatiful flowers in Kolymbari, Crete.</p>
                        </div>
                    </div>
    
                    <div class="item">
                        <a href="showCategory.php?id=3"><img src="Pictures/24/24.jpg" alt="Flower" width="460" height="345"></a>
                        <div class="carousel-caption">
                            <h3>Flowers</h3>
                            <p>Beatiful flowers in Kolymbari, Crete.</p>
                        </div>
                    </div>
    
                </div>
    
                 Left and right controls 
                <a class="left carousel-control" href="#myCarouse2" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarouse2" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>-->



</body>
</html>