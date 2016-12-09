<?php ?>

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
                <a class="navbar-brand" href="index.php">WebSiteName</a>
            </div>
            <div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li><a href="showCategory.php?id=1">Samochody</a></li>
                        <li><a href="showCategory.php?id=2">Kwiaty</a></li>
                        <li><a href="showCategory.php?id=3">Narkotyki</a></li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (isset($_SESSION['userId'])) { ?>
                        <li><a href="register.php"><span class="glyphicon glyphicon-envelope"></span> Wiadomości</a></li>
                        <?php } 
                        if (isset($_SESSION['userId'])) { ?>
                        <li><a href="basket.php"><span class="glyphicon glyphicon-shopping-cart"></span> Koszyk</a></li>
                        <?php } 
                        if (!isset($_SESSION['userId'])) { ?>
                        <li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                        <?php 
                        }
                        if (!isset($_SESSION['userId'])) { ?>
                        <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                        <?php } 
                         if (isset($_SESSION['userId'])) { ?>
                        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                         <?php } ?>
                    </ul>
                </div>

            </div>
        </div>
    </nav>
    
    <!--</body>-->
