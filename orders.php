<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';

$conn = DB::connect();
$userId = unserialize($_SESSION['userId']);
?>

<!DOCTYPE html>
<html>
    <?php include('includes/header.php'); ?>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">
        <?php include('includes/navbarOutsideTheMainPage.php'); ?>
        <div class="jumbotron">
  <h1>  Historia zamówień</h1>      
  <p>  Witamy w sekcji, gdzie możesz sprawdzić szczgóły swoich zamówień:</p>
</div>

        <div class="container">
            <!--<div class="jumbotron">-->
                <!--<h1>Historia zamówień:</h1>--> 
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Numer zamówienia</th>
                            <th></th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $orders = Order::loadOrder($conn, $userId);
                        
                        foreach ($orders as $key => $order) {
                            ?>
                            <tr>
                                <td>
                                    <h2><?php echo $key ?></h2>
                                </td>
                                <td>
                                    <a href="#" data-toggle="tooltip" title="Kliknij, aby dowiedzieć się więcej!"><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Szczegóły zamówienia</button></a>
                                </td>
                                <td>
                                    <h2> <?php echo $order[0]->status ?> <h2>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <!--</div>-->
        </div>
    </body>
    <script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
</html>