<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

use src\Category;
use src\Db;
use src\Picture;
$conn = Db::connect();
$allCategies = Category::getAllCategories($conn);
?>
<!DOCTYPE html>
<head> 
    <?php include('includes/header.php'); ?>
</head>
<script src="js/index.js"></script>
<body data-spy="scroll" data-target=".navbar" data-offset="50">
    <?php
    include('includes/navbar.php');
    $i = 1;
    foreach ($allCategies as $singleCategory) {
        $categoryId = $singleCategory->getCategoryId();
        $mainPagePhotos = Picture::getPhotoForMainPageForOneCategory($conn, $categoryId);
        if ($mainPagePhotos) {
            ?>



            <div id="section<?php echo $i ?>" class="container-fluid snlgCat">
                <h1><?php
                    echo
                    $singleCategory->getCategoryName();
                    ?></h1>
                <div id="myCarouse<?php echo $i ?>" class="carousel slide" data-ride="carouse<?php echo $i ?>">
                    <ol class="carousel-indicators">
                        <?php
                        $j = 0;
                        foreach ($mainPagePhotos as $singlePhoto) {
                            if ($j == 0) {
                                ?>
                                <li data-target="#myCarouse<?php echo $i ?>" data-slide-to="<?php echo $j ?>" class="active"></li>
                            <?php } else {
                                ?>
                                <li data-target="#myCarouse<?php echo $i ?>" data-slide-to="<?php echo $j ?>"></li>

                                <?php
                            }
                            $j++;
                        }
                        ?>
                    </ol>
                    <div class = "carousel-inner" role = "listbox">
                        <?php
                        $j = 0;
                        foreach ($mainPagePhotos as $singlePhoto) {
                            if ($j == 0) {
                                ?>
                                <div class = "item active">
                                    <a href = "showCategory.php?id=<?php echo $i ?>"><img src = "<?php echo $singlePhoto->getPicture_link(); ?>" alt = "Chania" width = "460" height = "345"></a>
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
                                    <a href="showCategory.php?id=<?php echo $i ?>"><img src="<?php echo $singlePhoto->getPicture_link(); ?>" alt="Chania" width="460" height="345"></a>
                                    <div class="carousel-caption">
                                        <h3>Samochody</h3>
                                        <p>The atmosphere in Chania has a touch of Florence and Venice.</p>
                                    </div>
                                </div>
                                <?php
                            }
                            $j++;
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
        }
        ?>
</body>
</html>