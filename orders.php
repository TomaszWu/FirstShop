<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
use src\Order;
use src\Db;
$conn = Db::connect();
$userId = unserialize($_SESSION['userId']);
?>

<!DOCTYPE html>
<html>
    <head> 
        <?php include('includes/header.php'); ?>
    </head>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">
        <?php include('includes/navbarOutsideTheMainPage.php'); ?>


        <div class="container">
            <div class="jumbotron">
                <h1>Historia zamówień</h1>      
                <p>Witamy w sekcji, w której możesz sprawdzić szczegóły swoich zamówień:</p>
            </div>
            <div class="center-block Absolute-Center is-Responsive">
                <!--<div class="jumbotron">-->
                <!--<h1>Historia zamówień:</h1>--> 
                <div class="list-group">
                    <?php
                    $orders = Order::loadOrder($conn, $userId);

                    foreach ($orders as $key => $order) {
                        ?>
                        <li class="list-group-item"><h2>Numer zamówienia: <?php echo $key ?></h2><span><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal<?php echo $key ?>">Szczegóły zamówienia</button><span> Status zamówienia: <?php echo $order[0]->status ?> </span></span></li>
                        <div class="modal fade" id="myModal<?php echo $key ?>" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Zamówienie numer <?php echo $key ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-hover">
                                            <thead>

                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>Produkt</th>
                                                    <th>Ilość</th>
                                                    <th>Cena</th>
                                                </tr>
                                                <?php $finalPrice = 0;
                                                foreach ($order as $product) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $product->products['product_name'] ?></td>
                                                        <td><?php echo $product->products['quantity'] ?></td>
                                                        <td><?php echo $product->products['price'];
                                            $finalPrice += $product->products['price'] ?></td>
                                                    </tr>
    <?php } ?>
                                                <tr>
                                                    <th></th>
                                                    <th>Cena za całość:</th>
                                                    <th><?php echo $finalPrice ?></th>
                                                </tr>
                                        </table>
                                        </tbody>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

<?php } ?>
                </div>
            </div>
        </div>
    </body>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</html>