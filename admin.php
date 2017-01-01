<?php 
session_start();






?>
<!DOCTYPE html>
<?php include('includes/header.php') ?>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">
<?php include('includes/navbarOutsideTheMainPage.php'); ?>

<div class="container">
  <h2>Wybierz aktywność:</h2>
  <div class="list-group">
    <a href="#" class="list-group-item active">Zarządzaj grupami</a>
    <a href="#" class="list-group-item">Zarządzaj przedmiotami</a>
    <a href="#" class="list-group-item">Zarządzaj użytkownikami</a>
    <a href="#" class="list-group-item">Zarządzaj zamówieniami</a>
  </div>
</div>

</body>
</html>
