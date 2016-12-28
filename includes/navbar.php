<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Db.php';
$conn = DB::connect();
$allCategies = Category::getAllCategories($conn);
?>

<!--<body data-spy="scroll" data-target=".navbar" data-offset="50">-->
<div>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid ">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">WebSiteName</a>
            </div>
            <div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <?php
                        $i = 0;
                        foreach ($allCategies as $singleCategory) {
                            // kategoria jest widoczna dopiero, gdy sa da niej wgrane zdjęcia profilowe
                            $categoryId = $singleCategory->getCategoryId();
                            $mainPagePhotos = Picture::getPhotoForMainPageForOneCategory($conn, $categoryId);
                            if ($mainPagePhotos) {
                                ?>
                                <li><a href="#section<?php echo $i ?>"><?php echo $allCategies[$i]->getCategoryName() ?></a></li>

                                <?php
                                $i++;
                            }
                        }
                        ?>
                        <?php if (isset($_SESSION['adminId'])) { ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Panel administratora
                                    <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="catAdmin.php">Zarządzanie grupami</a></li>
                                    <li><a href="itemsAdmin.php">Zarządzanie przedmiotami</a></li>
                                    <li><a href="adminUsers.php">Zarządzanie użytkownikami</a></li>
                                    <li><a href="adminOrders.php">Zarządzanie zamówieniami</a></li>
                                    <li><a href="logout.php">Wyloguj się jako admin</a></li>
                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (isset($_SESSION['userId'])) { ?>
                            <li><a href="massages.php"><span class="glyphicon glyphicon-envelope"></span> Wiadomości</a></li>
                            <?php
                        }
                        if (isset($_SESSION['userId'])) {
                            ?>
                            <li><a href="basket.php"><span class="glyphicon glyphicon-shopping-cart"></span> Koszyk</a></li>
                            <?php
                        }
                        if (isset($_SESSION['userId'])) {
                            ?>
                            <li><a href="orders.php"><span class="glyphicon glyphicon-eye-open"></span> Zamówienia</a></li>
                            <?php
                        }
                        if (!isset($_SESSION['userId'])) {
                            ?>
                            <li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                            <?php
                        }
                        if (!isset($_SESSION['userId'])) {
                            ?>
                            <li><a href="login.php"><span class="glyphicon glyphicon-log-isn"></span> Login</a></li>
                            <?php
                        }
                        if (isset($_SESSION['userId'])) {
                            ?>
                            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                            <?php } ?>
                    </ul>
                </div>

            </div>
        </div>
    </nav>

    <!--</body>-->
