<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';
$conn = DB::connect();

$categories = Category::getAllCategories($conn);

if (isset($_SESSION['adminId'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['name']) && strlen(trim($_POST['name'])) > 0 &&
                isset($_POST['description']) &&
                strlen(trim($_POST['description'])) > 0 &&
                isset($_POST['price']) && $_POST['price'] > 0 &&
                isset($_POST['stock']) && $_POST['stock'] > 0 &&
                isset($_POST['categoryId']) && $_POST['categoryId'] > 0) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $categoryId = $_POST['categoryId'];
            $productToAdd = new Product();
            $productToAdd->setName($name);
            $productToAdd->setDescription($description);
            $productToAdd->setPrice($price);
            $productToAdd->setStock($stock);
            $productToAdd->setCategoryId($categoryId);
            $productToAdd->addAProductToTheDB($conn);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['idToDelete'])) {
            $idToDelete = $_GET['idToDelete'];
            $productToDelete = Product::loadProductFromDb($conn, $idToDelete)[0];
            $productToDelete->deleteTheItem($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <?php include('includes/header.php'); ?>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">
        <?php
        include('includes/navbarOutsideTheMainPage.php');
        ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <h2>Dodaj nową pozycję:</h2>
                    <form method="POST" action="#">
                        <div class="form-group">
                            <label for="name">Nazwa:</label>
                            <input type="text" name="name" class="form-control" placeholder="Podaj nazwę">
                        </div>
                        <div class="form-group">
                            <label for="description">Opis:</label>
                            <input type="text" name="description" class="form-control" placeholder="Podaj opis">
                        </div>
                        <div class="form-group">
                            <label for="price">Cena:</label>
                            <input type="number" name="price" class="form-control" placeholder="Podaj cenę">
                        </div>
                        <div class="form-group">
                            <label for="stock">Stan:</label>
                            <input type="number" name="stock" class="form-control" placeholder="Podaj stan">
                        </div>
                        <div class="form-group">
                            <label for="sel1">Select list (select one):</label>
                            <select class="form-control" id="sel1" name="categoryId">
                                <?php foreach ($categories as $category) { ?>
                                    <option value="<?php echo $category->getCategoryId() ?>"><?php echo $category->getCategoryName() ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-7">
                    <h2>Wybierz kategorię:</h2>
                    <form method="POST" action="#">
                        <div class="form-group">
                            <select class="form-control" id="sel1" name="categoryId">
                                <?php foreach ($categories as $category) { ?>
                                    <option value="<?php echo $category->getCategoryId() ?>"><?php echo $category->getCategoryName() ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Wybierz</button>
                        </div>
                    </form>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nazwa</th>
                                <th>Ilość na stanie</th>
                                <th></th>
                                <th>Usunięcie</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                if (isset($_POST['categoryId']) && $_POST['categoryId'] > 0) {
                                    $categoryId = $_POST['categoryId'];
                                    $productsToShow = Category::loadAllProductFromParticularCategory($conn, $categoryId);
                                    foreach ($productsToShow as $product) {
                                        ?> 
                                        <tr> 
                                            <?php
                                            ?><td><a href="=?<?php echo $product->getProductId()
                                            ?>"><?php echo $product->getName()
                                            ?></a></td><td><?php echo $product->getStock()
                                            ?> </td><td><a href="productDetails.php?productId=<?php echo $product->getProductId()
                                            ?>">Zmodyfikuj pozycję</a></td><td><a href="deleteItem.php?idToDelete=<?php echo $product->getProductId() ?>">Skasuj pozycję</a></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>

