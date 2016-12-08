<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';
$conn = DB::connect();
$i = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name']) && strlen(trim($_POST['name'])) > 0 && 
            isset($_POST['surname']) && strlen(trim($_POST['surname'])) > 0 
            && isset($_POST['email']) && strlen(trim($_POST['email'])) >= 5
            && isset($_POST['password']) && strlen(trim($_POST['password'])) > 5 && isset($_POST['retyped_password']) 
            && trim($_POST['password']) == trim($_POST['retyped_password']) 
        && isset($_POST['address']) && strlen(trim($_POST['address'])) > 0 ) {
        var_dump($_POST['password']);
        $emailToCheck = $_POST['email'];
        $query = "SELECT email FROM Users WHERE email = '$emailToCheck'";
        $checkIfThereIsAProblemWithAnEmail = $conn->query($query);
        $user = new User();
        $user->setName(trim($_POST['name']));
        $user->setSurname(trim($_POST['surname']));
        $user->setEmail(trim($_POST['email']));
        $user->setHashedPassword(trim($_POST['password']));
        $user->setAddress(trim($_POST['address']));
        if (!filter_var($emailToCheck, FILTER_VALIDATE_EMAIL) === false) {
            if ($user->saveTheUserToDB($conn)) {
                echo 'Udało się zarejstrować';
//                header('Location: index.php');
            } elseif ($checkIfThereIsAProblemWithAnEmail && $checkIfThereIsAProblemWithAnEmail->num_rows > 0) {
                echo 'Ten email już istnieje w bazie danych. Prosimy podać nowy.';
                $i++;
            } else {
                echo "Blad rejestracji";
                $i++;
            }
        } else {
            echo "$emailToCheck nie jest poprawnym adresem mailowym";
            $i++;
        }
    } else {
        $i++;
    }
}
?>


<html>
    <head>
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
            <div class="center-block Absolute-Center is-Responsive">.col-sm-4

                <form method="POST">
                    <div class="form-group input-group">
                        <label>
                            Name:<br>
                            <input type="text" name="name">
                        </label>
                    </div>
                    <div class="form-group input-group">
                        <label>
                            Name:<br>
                            <input type="text" name="surname">
                        </label>
                    </div>
                    <div class="form-group input-group">
                        <label>
                            E-mail:<br>
                            <input type="text" name="email">
                        </label>
                    </div>
                    <div class="form-group input-group">
                        <label>
                            Password:<br>
                            <input type="password" name="password">
                        </label>
                    </div>
                    <div class="form-group input-group">
                        <label>
                            Retype password:<br>
                            <input type="password" name="retyped_password">
                        </label>
                    </div>
                    <div class="form-group input-group">
                        <label>
                            Adres:<br>
                            <input type="text" name="address">
                        </label>
                    </div>
                    <input type="submit" value="Register">    
                    <br>
                    <a href="login.php">Wróć do logowania</a>
                </form>
                <?php if ($i == 1) { ?>
                    <div class="alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Uwaga!</strong> Błędne dane formularza!
                    </div>
                <?php } ?>
                
            </div>
        </div>
    </body>
</html>

