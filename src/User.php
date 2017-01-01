<?php

require_once __DIR__ . '/../vendor/autoload.php';

class User implements JsonSerializable {

    private $userId;
    private $name;
    private $surname;
    private $email;
    private $hashedPassword;
    private $address;

    public function __construct($userId = -1, $name = null, $surname = null, $email = null, $hashedPassword = null, $address = null) {
        $this->userId = $userId;
        $this->setName($name);
        $this->setSurname($surname);
        $this->setEmail($email);
        $this->setHashedPassword($hashedPassword);
        $this->address = ($address);
    }
    
    public function jsonSerialize() {
        //funkcja zwraca nam dane z obiketu do json_encode
        return [
            'userId' => $this->userId,
            'setName' => $this->name,
            'setSurname' => $this->Surname,
            'setEmail' => $this->email,
            'description' => $this->description,
            'setHashedPassword' => $this->hashedPassword,
            'address' => $this->address,
        ];
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
    
    static public function login(mysqli $connection, $email, $password) {
        $user = self::loadByEmail($connection, $email);
        if($user && password_verify($password, $user->hashedPassword)){
            return $user;
        } else {
            return false;
        }
    }
    
    static public function loadByEmail(mysqli $connection, $email){
        $query = "SELECT * FROM Users WHERE email = '".$connection->real_escape_string($email)."'";
        
        $res = $connection->query($query);
        if($res && $res->num_rows == 1){
            $row = $res->fetch_assoc();
            $user = new User();
            $user->setUserId($row['id']);
            $user->setName($row['name']);
            $user->setSurname($row['surname']);
            $user->setEmail($row['email']);
            $user->hashedPassword = $row['hashed_password'];
            $user->setAddress($row['address']);
            
            
            return $user;
        }
        return null;
    }

    
    public function addCookiesToTheBasket($cookie) {
        $this->basket[] = $cookie;
    }

    public function saveTheUserToDB(mysqli $connection) {
        if ($this->userId == -1) {
            $query = "INSERT INTO Users (name, surname, email, hashed_password, address) 
                    VALUES ('$this->name', '$this->surname', '$this->email', '$this->hashedPassword', '$this->address') ";
            if ($connection->query($query)) {
                $this->userId = $connection->insert_id; // ostatni dodoany!!
                return true;
            } else {
                return false;
            }
        } else {
            // modyfikacja obiektu, na wypadek, gdyby istniał już dany email
            $query = "UPDATE Users SET 
                    name = '$this->name', email = '$this->email', hashed_password = '$this->hashedPassword'
                    WHERE id = $this->userId";

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

    public static function loadUsersFromDB(mysqli $connection, $id = null) {
        if (is_null($id)) {
            $result = $connection->query('SELECT * FROM Users');
        } else {
            $result = $connection->query("SELECT * FROM Users WHERE id='" . intval($id) . "'");
        }

        $usersList = [];

        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $dbUser = new User();
                $dbUser->userId = $row['id'];
                $dbUser->name = $row['name'];
                $dbUser->surname = $row['surname'];
                $dbUser->email = $row['email'];
                $dbUser->hashedPassword = $row['hashed_password'];
                $dbUser->address = $row['address'];
                $usersList[] = $dbUser;
            }
        }
        return $usersList;
    }
    
    public function deleteUser(mysqli $connection){
        $query = "DELETE FROM Users WHERE id = '$this->userId'";
        if ($connection->query($query)) {
            return true;
        } else {
            return false;
        }
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

    function getHashedPassword() {
        return $this->hashedPassword;
        
    }

    function getAddress() {
        return $this->address;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }
    
    function setBasket($basket) {
        $this->basket = $basket;
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
//        else {
//            throw new InvalidArgumentException('Stany nie mogą być poniżej zera!!!');
//        }
    }

//    public function setPassword($password) {
//        if (is_string($password) && strlen(trim($password)) > 5) {
//            $this->hashedPassword = password_hash($password, PASSWORD_DEFAULT);
//        }
//        return $this; // gdyby toś chciał wywołać kilka seterów ?? oO
//    }
    public function setHashedPassword($password, $hashPassword = true) {
        if (is_string($password)) {
            if ($hashPassword) {
                $this->hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            } else {
                $this->hashedPassword = $password;
            }
        }
    }


//    public function setHashedPassword($password) {
//        if (is_string($password) && strlen(trim($password)) > 5) {
//            $this->hashedPassword = password_hash($password, PASSWORD_DEFAULT);
//        }
//        return $this; // gdyby toś chciał wywołać kilka seterów ?? oO
//    }

    public static function getUserByEmail(mysqli $connection, $email) {
        //        $email = $connection->real_escape_string($mail); tak też można 
        $query = "SELECT * FROM Users WHERE email ='" . mysqli_real_escape_string($connection, $email) . "'";
        $res = $connection->query($query);
        if ($res->num_rows == 1) {
            $row = $res->fetch_assoc();
            $user = new User((int) $row['id'], $row['email']);
            $user->setHashedPassword($row['hashed_password'], false);
            return $user;
        }
    }

}
