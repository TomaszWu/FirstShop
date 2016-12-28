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
                $mainPagePhotos = Picture::getPhotoForMainPageForOneCategory($conn, $categoryId)
                ?></h1>
            <div id="myCarouse<?php echo $i ?>" class="carousel slide" data-ride="carouse<?php echo $i ?>">


                <ol class="carousel-indicators">
                    <?php
                    for ($j = 0; $j < count($mainPagePhotos); $j++) {
                        if ($j == 0) {
                            ?>
                            <li data-target="#myCarouse<?php echo $i ?>" data-slide-to="<?php echo $j ?>" class="active"></li>
                        <?php } else {
                            ?>
                            <li data-target="#myCarouse<?php echo $i ?>" data-slide-to="<?php echo $j ?>"></li>

                            <?php
                        }
                    }
                    ?>
                </ol>



                <!--Wrapper for slides -->
                <div class = "carousel-inner" role = "listbox">
                    <?php
                    for ($j = 0; $j < count($mainPagePhotos); $j++) {
                        if ($j == 0) {
                            ?>
                            <div class = "item active">
                                <a href = "showCategory.php?id=<?php echo $i ?>"><img src = "<?php echo $mainPagePhotos[$j]->getPicture_link(); ?>" alt = "Chania" width = "460" height = "345"></a>
                                <div class = "carousel-caption">
                                    <h3><?php
                                        echo
                                        $singleCategory->getCategoryName()
                                        ?></h3>
                                    <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                                </div>
                            </div>
        <?php } else { ?>
                            <div class="item">
                                <a href="showCategory.php?id=<?php echo $i ?>"><img src="<?php echo $mainPagePhotos[$j]->getPicture_link(); ?>" alt="Chania" width="460" height="345"></a>
                                <div class="carousel-caption">
                                    <h3>Samochody</h3>
                                    <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                                </div>
                            </div>
                        <?php
                        }
                    }
                    ?>

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
</body>
</html>