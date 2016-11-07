<?php
var_dump($_GET);
var_dump($_FILES);

?>


<form method="POST" enctype="multipart/form-data">
    <input type="file" name="fileToUpload">
    <br>
    <input type="submit" value="Upload file">
</form>

