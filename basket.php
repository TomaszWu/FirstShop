<?php 
session_start();
require_once __DIR__ . '/vendor/autoload.php';
?>
<!DOCTYPE html>
<html>
    <head> 
        <?php include('includes/header.php'); ?>
        <script src="js/basket.js"></script>
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
            .item {

            }
            .item img{
                max-height:285px;
                width:100%;
                object-fit: contain;
            }

            body { 
                padding-top: 65px; 
            }


        </style>
    </head>

    <body>
        <?php include('includes/navbarOutsideTheMainPage.php'); ?>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <div id="container productList">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="productList">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>

    </body>
</html>