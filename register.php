<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';
$conn = DB::connect();
$i = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name']) && strlen(trim($_POST['name'])) > 0 &&
            isset($_POST['surname']) && strlen(trim($_POST['surname'])) > 0 &&
            isset($_POST['email']) && strlen(trim($_POST['email'])) >= 5 &&
            isset($_POST['password']) && strlen(trim($_POST['password'])) > 5 &&
            isset($_POST['retyped_password']) && trim($_POST['password']) == trim($_POST['retyped_password']) && isset($_POST['address']) && strlen(trim($_POST['address'])) > 0) {
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
        <?php include('includes/header.php'); ?>
        <style>

            .Absolute-Center {
                margin: auto;
                position: absolute;
                top: 0; left: 0; bottom: 0; right: 0;
            }
            .Absolute-Center.is-Responsive {
                width: 50%; 
                height: 80%;
                min-width: 200px;
                max-width: 400px;
                padding: 40px;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="center-block Absolute-Center is-Responsive">
                <div class="panel panel-default">
                    <div class="panel-heading"
                         <h1>Zaloguj się</h1>     
                    </div>
                    <div class="panel-body">
                        <form method="POST">
                            <div class="form-group input-group">
                                <label>
                                    Imię:<br>
                                    <input type="text" name="name">
                                </label>
                            </div>
                            <div class="form-group input-group">
                                <label>
                                    Nazwisko:<br>
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
                                    Hasło:<br>
                                    <input type="password" name="password">
                                </label>
                            </div>
                            <div class="form-group input-group">
                                <label>
                                    Powtórz hasło:<br>
                                    <input type="password" name="retyped_password">
                                </label>
                            </div>
                            <div class="form-group input-group">
                                <label>
                                    Adres:<br>
                                    <input type="text" name="address">
                                </label>
                            </div>
                            <div>
                                <input type="submit" value="Register">    
                            </div>

                        </form>
                    </div>
                    <div class="panel-footer">
                        <a href="login.php">Powrót do rejestracji</a>
                    </div>
                    <?php if ($i == 1) { ?>
                        <div class="alert alert-danger fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Uwaga!</strong> Błędne dane formularza!
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </body>
</html>

