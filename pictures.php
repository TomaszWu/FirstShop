<?php
session_start();
include(__DIR__ . '/src/Db.php');
require_once __DIR__ . '/vendor/autoload.php';
$conn = DB::connect();
var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump($_FILES);
    $allowedFileTypes = ['image/png', 'image/jpeg', 'image/gif'];
    if (isset($_POST['pictureId']) && isset($_FILES['fileToUpload']) &&
            $_FILES['fileToUpload']['size'] > 0 && in_array($_FILES['fileToUpload']['type'], $allowedFileTypes)) {
        $productId = $_POST['pictureId'];
        var_dump($productId);
        $uploadDir = 'Pictures';

        if (is_dir($uploadDir . '/' . $productId)) {
            echo $uploadDir . '/' . $productId . 'exists<br>';
        } else {
            mkdir($uploadDir . '/' . $productId);
            echo $uploadDir . '/' . $productId . 'created<br>';
        }


        $destination = $uploadDir;
        $destinationFileName = $destination . '/' . $productId . '/' . $_FILES['fileToUpload']['name'];
        var_dump(quotemeta($destinationFileName));
        $query = "INSERT INTO Pictures (picture_link, product_id) VALUES ('" . quotemeta($destinationFileName) . "', '" . $productId . "')";

        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $destinationFileName)) {
//            echo 'File upload success<br>';
            if ($conn->query($query)) {
                echo 'File upload to DB success<br>';
            } else {
                echo 'File upload to DB failed!!!';
            }
//            echo '<a href="showImage.php?file=' . base64_encode($destinationFileName) . '">Show image</a>';
        } else {
            echo 'File upload to DB failed!!!';
        }
    }
}
?>
<html>
    <head> 
        <?php include('includes/header.php'); ?>
    </head>
    <body


        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="fileToUpload">
            <br>
            <input type="number" name="pictureId">
            <br>
            <input type="submit" value="Upload file">
        </form>
    </body>
</html>
