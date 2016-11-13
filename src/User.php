<?php
require_once __DIR__ . '/../vendor/autoload.php';
class User {

    private $userId;
    private $name;
    private $surname;
    private $email;
    private $password;
    private $address;
    private $basket;

    public function __construct($userId = -1, $name = null, $surname = null, $email = null, $password = null, $address = null, $basket = null) {
        $this->userId = $userId;
        $this->setName($name);
        $this->setSurname($surname);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setAddress($address);
        $this->basket = [];
    }
//
    
    public function loadTheBasket(mysqli $connection) {
        $userId = $this->userId;
        var_dump($userId);
        $query = "SELECT user_id, order_status, orders_products.order_id, orders_products.product_id, orders_products.product_price, orders_products.product_quantity FROM Orders
                JOIN orders_products ON Orders.order_id = orders_products.order_id
                JOIN Products ON Products.id = orders_products.product_id
                 WHERE user_id = '$userId' AND order_status = '1'";
        $basket = [];
        $result = $connection->query($query);
        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $basket[] = $row;
            }
        }
        return $basket;
    }
    
    public function addTheItemToTheBasket($itemId, $price, $quantity) {
    $this->basket[] = ['itemId' => $itemId,
        'itemQuantity' => $quantity,
        'itemPrice' => $price];    
    
    return $this->basket;
}
    
    

    public function saveTheUserToDB(mysqli $connection) {
        if ($this->id == -1) {
            $query = "INSERT INTO Users (name, surname, email, password, address) 
                    VALUES ('$this->name', '$this->surname', '$this->email', '$this->password', '$this->address') ";
            if ($connection->query($query)) {
                $this->id = $connection->insert_id; // ostatni dodoany!!
                return true;
            } else {
                return false;
            }
        } else {
            // modyfikacja obiektu, na wypadek, gdyby istniał już dany email
            $query = "UPDATE Users SET 
                    name = '$this->name', email = '$this->email', hashed_password = '$this->hashedPassword'
                    WHERE id = $this->id";

            if ($connection->query($query)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function loadAllMassagesSentToTheUser(mysqli $conn) {
        $query = "SELECT * FROM Massages JOIN Users ON Massages.user_id = Users.id WHERE user_id = '" . $this->id . "'";
        $massages = [];
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $massages[] = $row;
            }
        }
        return $massages;
    }

    public function loadAllOrders(mysqli $conn) {
        $query = "SELECT * FROM Users JOIN Orders ON Users.id = Orders.user_id WHERE id ='" . $this->userId . "'";
        $usersOrders = [];
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $usersOrders[] = $row;
            }
        }
        return $usersOrders;
    }

    public static function loadUsersFromDB(mysqli $conn, $id = null) {
        if (is_null($id)) {
            $result = $conn->query('SELECT * FROM Users');
        } else {
            $result = $conn->query("SELECT * FROM Users WHERE id='" . intval($id) . "'");
        }

        $usersList = [];

        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $dbUser = new User();
                $dbUser->userId = $row['id'];
                $dbUser->name = $row['name'];
                $dbUser->surname = $row['surname'];
                $dbUser->email = $row['email'];
                $dbUser->password = $row['password'];
                $dbUser->address = $row['address'];
                $usersList[] = $dbUser;
            }
        }
        return $usersList;
    }

    public function login(mysqli $connection, $email, $password) {
//        $email = $connection->real_escape_string($mail); tak też można
        $query = "SELECT * FROM Users WHERE email ='" . mysqli_real_escape_string($connection, $email) . "'";
        $res = $connection->query($query);
        if ($res->num_rows == 1) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                return $row['id'];
            } else {
                return false;
            }
        }
        return false;
    }


    function getUserId() {
        return $this->userId;
    }

    function getBasket() {
        return $this->basket;
    }

    function getName() {
        return $this->name;
    }

    function getSurname() {
        return $this->surname;
    }

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function getAddress() {
        return $this->address;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setSurname($surname) {
        $this->surname = $surname;
    }

    function setAddress($address) {
        $this->address = $address;
    }

    public function setEmail($email) {
        if (is_string($email) && strlen(trim($email)) > 5) {
            $this->email = trim($email);
        }
        return $this;
    }

//    public function setPassword($password) {
//        if (is_string($password) && strlen(trim($password)) > 5) {
//            $this->hashedPassword = password_hash($password, PASSWORD_DEFAULT);
//        }
//        return $this; // gdyby toś chciał wywołać kilka seterów ?? oO
//    }

    public function setPassword($password, $hashPassword = true) {
        if (is_string($password)) {
            if ($hashPassword) {
                $this->password = password_hash($password, PASSWORD_DEFAULT);
            } else {
                $this->password = $password;
            }
        }
    }

    public static function getUserByEmail(mysqli $connection, $email) {
        //        $email = $connection->real_escape_string($mail); tak też można 
        $query = "SELECT * FROM Users WHERE email ='" . mysqli_real_escape_string($connection, $email) . "'";
        $res = $connection->query($query);
        if ($res->num_rows == 1) {
            $row = $res->fetch_assoc();
            $user = new User((int) $row['id'], $row['email']);
            $user->setPassword($row['password'], false);
            return $user;
        }
    }

}
