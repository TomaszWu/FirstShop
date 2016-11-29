<?php

class Massage {

    private $id;
    private $massage;
    private $user_id;

    public function __construct($id = -1, $massage = null, $user_id = null) {
        $this->id = $id;
        $this->setMassage($massage);
        $this->setUser_id($user_id);
    }

    public function addAMassageToDB(mysqli $connection) {
        if ($this->id == -1) {
            $query = "INSERT INTO Massages (massage, user_id) VALUES ('" . mysqli_real_escape_string($connection, $this->text) . "' , '" . mysqli_real_escape_string($connection, $this->user_id) . "')";
            if ($connection->query($query)) {
                $this->id = $connection->insert_id;
                return true;
            } else {
                return false;
            }
        }
    }

    public static function loadMassagesFromDB(mysqli $conn, $user_id) {
//        if (is_null($id)) {
//            $result = $conn->query('SELECT * FROM Massages');
//        } else {
        $result = $conn->query("SELECT * FROM Massages WHERE user_id='" . intval($user_id) . "'");
//        }

        $massagesList = [];

        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $dbMassage = new Massage();
                $dbMassage->id = $row['massage_id'];
                $dbMassage->massage = $row['massage'];
                $dbMassage->user_id = $row['user_id'];
                $massagesList[] = $dbMassage;
            }
        }
        return $massagesList;
    }

    function getId() {
        return $this->id;
    }

    function getText() {
        return $this->text;
    }

    function getUser_Id() {
        return $this->user_Id;
    }

    function setMassage($massage) {
        $this->massage = $massage;
    }

    function setUser_Id($user_id) {
        $this->user_id = $user_id;
    }

}