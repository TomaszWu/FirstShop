<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';
$conn = DB::connect();
$categories = Category::getAllCategories($conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>

            .Absolute-Center {
                margin: auto;
                position: absolute;
                top: 0; left: 0; bottom: 0; right: 0;
            }
            .Absolute-Center.is-Responsive {
                width: 50%; 
                height: 50%;
                min-width: 200px;
                max-width: 400px;
                padding: 40px;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="col-sm-4 center-block Absolute-Center is-Responsive">
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
                                <option value="<?php echo $category->getCategoryId(); ?>"><?php echo $category->getCategoryName() ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
                <br>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['name']) && strlen(trim($_POST['name'])) > 0 &&
                            isset($_POST['description']) && strlen(trim($_POST['description'])) > 0 && isset($_POST['price']) && $_POST['price'] > 0 && isset($_POST['stock']) && $_POST['stock'] > 0 && isset($_POST['categoryId']) && $_POST['categoryId'] > 0) {
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
                        ?>  <div class="alert alert-success">
                            <strong>Sukces!</strong> Produkt dodany do bazy danych
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="alert alert-danger">
                            <strong>Błąd!</strong> Nie udało się dodać produktu do bazy danych
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>