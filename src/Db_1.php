<?php

class DB {

    static private $conn = null; //własnosc przechowująca polaczenie

    static public function connect() {
        if (!is_null(self::$conn)) {
            //polaczenie istnieje
            return self::$conn;
        } else {
            //łączenie z bazą danych
            self::$conn = new mysqli('localhost', 'root', 'coderslab', 'FirstShop');
            //ustawiamy kodowanie połaczenia na unicode
            self::$conn->set_charset('utf8');
            if (self::$conn->connect_error) {
                die('Connection error: ' . self::$conn->connect_errno);
            }

            //zwracamy połaczenie 
            return self::$conn;
        }
    }

    static public function disconnect() {
        //zamykamy połaczenie z baza danych
        self::$conn->close();
        self::$conn = null;

        return true;
    }

}
