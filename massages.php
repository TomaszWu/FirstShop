<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
use src\Product;
use src\Massage;
use src\Db;
$conn = Db::connect();
$userId = unserialize($_SESSION['userId']);
$massages = Massage::loadMassagesFromDB($conn, $userId);
$i = 1;
?>
<!DOCTYPE html>
<html>
    <head> 
        <?php include('includes/header.php'); ?>
        <script src="js/massages.js"></script>
        <style>
            body { 
                padding-top: 65px; 
            }
        </style>
    </head>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">
        <?php
        include('includes/navbarOutsideTheMainPage.php');
        ?>
        <div class="container">
            <?php
            ?> <h2>Otrzymane wiadomości</h2>
            <p>Aby zapoznać się z wiadomością klinknij na jej tytuł:</p>
            <div class="panel-group" id="accordion">
            </div> 
            <div class="panel panel-default"><?php
                foreach ($massages as $singleMassage) {
                    ?><div class="panel panel-default" >
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a  class="showMsg" id="<?php echo $singleMassage->getId()
                    ?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i ?>"><?php echo $singleMassage->getTitle();
                                if (!$singleMassage->getStatus()) {
                        ?> <span class="label label-info pull-right showMsg" id="<?php echo $singleMassage->getId() ?>">Nowa wiadomość!</span> <?php } ?> </a>
                            </h4>
                        </div>
                        <div id="collapse<?php echo $i ?>" class="panel-collapse collapse">
                            <div class="panel-body">
                                <?php echo $singleMassage->getText() ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $i++;
                }
                ?>
            </div> 
        </div>
    </body>
</html>