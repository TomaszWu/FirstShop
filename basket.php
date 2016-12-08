<?php ?>
<html>
    <head>
        <title>Books Shelf</title>
        <?php include('includes/header.php') ?>
        <script src="js/basket.js"></script>
    </head>
    <body>
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