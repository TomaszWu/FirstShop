<?php

class Massage implements JsonSerializable {

    private $id;
    private $title;
    private $text;
    private $user_id;
    private $status;

    public function __construct($id = -1, $title = null, $text = null, $user_id = null, $status = null) {
        $this->id = $id;
        $this->setText($text);
        $this->setTitle($title);
        $this->setUser_id($user_id);
        $this->setStatus($status);
    }

    public function addAMassageToDB(mysqli $connection) {
        if ($this->id == -1) {
            $query = "INSERT INTO Massages (title, massage, user_id, status) VALUES ("
                    . "'" . mysqli_real_escape_string($connection, $this->title) .
                    "' ,'" . mysqli_real_escape_string($connection, $this->text) .
                    "', '" . mysqli_real_escape_string($connection, $this->user_id) .
                    "', '" . mysqli_real_escape_string($connection, $this->status) . "')";
            if ($connection->query($query)) {
                $this->id = $connection->insert_id;
                return true;
            } else {
                return false;
            }
        }
    }

    public function jsonSerialize() {
        //funkcja zwraca nam dane z obiketu do json_encode
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'user_id' => $this->user_id,
            'status' => $this->status
        ];
    }

    public function changeTheStatus(mysqli $connection, $status) {
        $query = "UPDATE Massages SET Status = '" . mysqli_real_escape_string($connection, $status) . "' WHERE massage_id = '" . intval($this->id) . "'";

        if ($connection->query($query)) {
            $this->id = $connection->insert_id;
            return true;
        } else {
            return false;
        }
    }

    public static function loadMassagesFromDB(mysqli $connection, $user_id = null, $msgId = null) {
        if ($user_id) {
            $result = $connection->query("SELECT * FROM Massages WHERE user_id='" . intval($user_id) . "' "
                    . " ORDER BY massage_id DESC ");
        } elseif ($msgId) {
            $result = $connection->query("SELECT * FROM Massages WHERE massage_id='" . intval($msgId) . "'");
        }
        $massagesList = [];

        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $dbMassage = new Massage();
                $dbMassage->id = $row['massage_id'];
                $dbMassage->title = $row['title'];
                $dbMassage->text = $row['massage'];
                $dbMassage->user_id = $row['user_id'];
                $dbMassage->status = $row['status'];
                $massagesList[] = ($dbMassage);
            }
        }
        return $massagesList;
    }

    public static function loadMassagesByStatus(mysqli $connection, $status, $userId) {
        $result = $connection->query("SELECT * FROM Massages WHERE status='" . 
                intval($status) . "' AND user_id = '" . intval($userId) . "'");
        $massagesList = [];

        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $dbMassage = new Massage();
                $dbMassage->id = $row['massage_id'];
                $dbMassage->title = $row['title'];
                $dbMassage->text = $row['massage'];
                $dbMassage->user_id = $row['user_id'];
                $dbMassage->status = $row['status'];
                $massagesList[] = json_encode($dbMassage);
            }
        }
        return $massagesList;
    }

    function getId() {
        return $this->id;
    }

    function getTitle() {
        return $this->title;
    }

    function getText() {
        return $this->text;
    }

    function getUser_Id() {
        return $this->user_Id;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setText($text) {
        $this->text = $text;
    }

    function setUser_Id($user_id) {
        $this->user_id = $user_id;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function getStatus() {
        return $this->status;
    }

}
