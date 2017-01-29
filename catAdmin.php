<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
use src\Db;
use src\Category;
$conn = Db::connect();
if (!isset($_SESSION['adminId'])) {
    echo 'zaloguj się jako administrator';
} else {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['newCategory'])) {
            $newCategory = new Category();
            $newCategoryName = $_POST['newCategory'];
            $newCategory->setCategoryName($newCategoryName);
            $newCategory->addNewCategory($conn);
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['id'])) {
            $categoryIdToDelete = $_GET['id'];
            Category::deleteCategory($conn, (string) $categoryIdToDelete);
        }
    }
    ?>
    <!DOCTYPE html>
    <html>
        <head> 
            <?php include('includes/header.php'); ?>
        </head>
        <body data-spy="scroll" data-target=".navbar" data-offset="50">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <?php
                include('includes/navbarOutsideTheMainPage.php');

                $categories = Category::getAllCategories($conn);
                ?>
                <div class="well well-lg">Kategorie w sklepie:</div>
                <table class="table table-condensed">
                    <tbody>
                        <?php
                        foreach ($categories as $category) {

//                    var_dump($category);
                            ?> 
                            <tr>
                                <td><a href="showCategory.php?id=<?php echo $category->getCategoryId();
                            ?>"><?php echo $category->getCategoryName() ?> 
                                    </a></td><td><a href="catAdmin.php?id=<?php
                                    echo $category->getCategoryId()
                                    ?>">Skasuj kategorię</a><td><a href="addPhotoForMainPage.php?categoryId=<?php echo $category->getCategoryId(); ?>">Dodaj zdjęcie na stonę główną</a></td></td>
                            </tr>
                        <?php } ?> 
                    </tbody>
                </table> <?php
                    }
                    ?>
            <div class="panel-body">
                <form method="POST">
                    <div class="form-group input-group">
                        <label>
                            Dodaj nową categorię:<br>
                            <input type="text" name="newCategory">
                        </label>
                    </div>
                    <div>
                        <input type="submit" value="Dodaj nową kategorię">    
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </body>
</html>

