
<?php ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Małe co nieco</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="js/adminOrders.js"></script>
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
        <div class="container">
            <div class="jumbotron">
                <h2>Wybierz status zamówień:</h2> 
                <div class="col-sm-3 text-center">
                    <button type="button" class="btn btn-default chooseStatus" id="0">Status 0</button>
                </div>
                <div class="col-sm-3 text-center">
                    <button type="button" class="btn btn-info chooseStatus" id="1">Status 1</button>
                </div>
                <div class="col-sm-3 text-center">
                    <button type="button" class="btn btn-primary chooseStatus" id="2">Status 2</button>
                </div>
                <div class="col-sm-3 text-center">
                    <button type="button" class="btn btn-success chooseStatus" id="3">Status 2</button>
                </div>
            </div>
            <div> 
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <div id="container productList">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Numer zamówienia</th>
                                        <th>Status zamówienia</th>
                                        <th>Zmień status</th>
                                        <th>Usunięcie wiadomość</th>
                                        <th>Wiadomość</th>
                                    </tr>
                                </thead>
                                <tbody id="productList" id="tableToFillUp">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
            </div>




    </body>
</html>