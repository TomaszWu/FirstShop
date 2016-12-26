<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';
session_start();
$conn = DB::connect();
if (!isset($_SESSION['adminId'])) {
    echo 'zaloguj się jako administrator';
} else {
    $allUsers = User::loadUsersFromDB($conn);
//    foreach ($allUsers as $user) {
//        var_dump($user->getName());
//    }
//    $orders = Order::loadOrder($conn, 1);
//    var_dump($orders);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include('includes/header.php');
        ?>
        <script src="js/adminUsers.js"></script>
    </head>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">
        <?php
        include('includes/navbarOutsideTheMainPage.php');
        ?>
        <div class="container">
            <h2>Wybierz klienta:</h2>
            <form method="post" action="#" >
                <div class="form-group">
                    <select name="userId"> <?php
                        foreach ($allUsers as $user) {
                            ?> <option value="<?php echo $user->getUserId() ?>"><?php
                                echo 'Numer klienta: ' . $user->getUserId() . ' Dane: ' .
                                $user->getName() . ' ' . $user->getSurname()
                                ?>
                            </option>
                        <?php } ?>

                    </select> 
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Przejdź dalej</button>
                </div>
            </form>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['userId'])) {
                $userId = $_POST['userId'];
                $userOrders = Order::loadOrder($conn, $userId);
                $_SESSION['userOrders'] = $userOrders;
                ?> 
                <div class="container">
                    <div class="form-group">
                        <a href="deleteUser.php?userIdToDelete=<?php echo $userId 
                                ?>" class="btn btn-danger" role="button">Usuń użytkownika</a>
                    </div>
                    <h2>Wybierz zamówienie:</h2>
                    <form method="post" action="#" id="orders">
                        <div class="form-group">

                            <?php if (count($userOrders) == 0) {
                                ?> 
                                <div class="alert alert-danger alert-dismissable fade in col-xs-5">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Uwaga!</strong> Brak zamówień w systemie dla tego klienta
                                </div>
                            <?php } else {
                                ?>
                                <select name="orderId"> <?php
                                    foreach ($userOrders as $key => $order) {
                                        ?> 
                                        <option value="<?php echo $key ?>"><?php if($key){ echo $key; } else { echo 'koszyk'; } ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Pokaż szczegóły zamówienia</button>
                            </div>

                        </form>
                    </div>
                </div>
                <?php
            }
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['orderId'])) {
                $orderId = $_POST['orderId'];
                $userOrders = $_SESSION['userOrders'];
                ?>
                <div class="container">
                    <h2>Szczegóły zamówienia nr <?php echo $orderId ?> </h2>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nazwa produktu</th>
                                <th>Ilość</th>
                                <th>Cena</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($userOrders[$orderId] as $key => $singleItem) {
                                ?> 
                                <tr>
                                    <td><?php echo $singleItem->getProducts()['product_name'] ?></td>
                                    <td><?php echo $singleItem->getProducts()['quantity'] ?></td>
                                    <td><?php echo $singleItem->getProducts()['price'] ?></td>
                                </tr>
                            <?php } ?>
                        <td></td>
                        <td>Suma:</td>
                        <td></td>
                        </tbody>
                    </table>
                </div>
                <?php
            }
        }
    }
    ?>
</body>
</html>
