<?php 
require_once __DIR__ . '/vendor/autoload.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include('includes/header.php'); ?>
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
            }chooseStatus
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
                    <button type="button" class="btn btn-success chooseStatus" id="3">Status 3</button>
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


            <div class="container">
                <!--                <h2>Modal Example</h2>
                                 Trigger the modal with a button 
                                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id="test">Open Modal</button>-->

                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">

                        Modal content
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" >&times;</button>
                                <h4 id="" class="modal-title">Modal Header</h4>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="#">
                                    <div class="form-group">
                                        <label for="title">Tytuł:</label>
                                        <input type="text" if="title" name="title" class="form-control" placeholder="Wpisz tytuł" id="titleToAdd">
                                    </div>
                                    <div class="form-group">
                                        <label for="massage">Wiadomość:</label>
                                        <textarea class="form-control" if="massage" rows="5" name="massage" placeholder="Wpisz wiadomość" id="msgToSend"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="sendMsg">Submit</button>
                                </form>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>



    </body>
</html>