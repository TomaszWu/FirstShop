<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Db.php';
$conn = DB::connect();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && strlen(trim($_POST['email'])) > 3 && 
            isset($_POST['password']) && strlen(trim($_POST['password'])) > 3) {

        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $admin = Admin::loginAdmin($conn, $email, $password);
        if ($admin) {
            $_SESSION['adminId'] = $admin->getId();
            header('Location: index.php');
        } else {
            echo 'Niepoprawne dane logowania';
        }
    }
}


//$conn->close();
//$conn = null;
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

            <div class="col-sm-6 center-block Absolute-Center is-Responsive">
                <div class="panel panel-default">
                    <div class="panel-heading"
                         <h1>Zaloguj się jako admin</h1>     
                    </div>
                    <div class="panel-body">
                        <form action="#" method="POST" id="loginForm">
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input class="form-control" type="text" name='email' placeholder="email"/>          
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input class="form-control" type="password" name='password' placeholder="password"/>     
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-def btn-block" value="Zaloguj się">
                            </div>
                    </div>

                    </form> 


                </div>
            </div>
        </div>
    </div>






    <!--  <form method="POST">
                <label>
                    E-mail:<br>
                    <input type="text" name="email">
                </label>
                <br>
                <label>
                    Password:<br>
                    <input type="password" name="password">
                </label>
                <br>
                <input type="submit" value="Login">
            </form>
            <a href="login.php?registration=createNewAccount">Przejście to strony rejestracji</a>-->


</body> 