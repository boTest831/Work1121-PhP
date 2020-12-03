<?php


class database {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "assignment2");
    }

    public function query($sql) {

        if ($sql == "") {
            $this->show_error("Wrong sql");
        }
        $this->sql = $sql;

        $result = $this->conn->query($sql);

        if (!$result) {
            if ($this->show_error) {
                $this->show_error("Wrong sql：", $this->sql);
            }
        } else {
            $this->result = $result;
        }
        return $this->result;

    }
}

?>