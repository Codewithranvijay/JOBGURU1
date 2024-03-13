<?php
require_once(LIB_PATH.DS."config.php");

class Database {
    var $sql_string = '';
    var $error_no = 0;
    var $error_msg = '';
    var $query = '';
    public $conn;
    public $last_query;

    function __construct() {
        $this->open_connection();
    }

    public function open_connection() {
        try {
            $this->conn = new PDO("mysql:host=".server.";dbname=".database_name, user, pass);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Problem in database connection! Contact administrator! " . $e->getMessage();
        }
    }

    function InsertThis($sql='') {
        $this->sql_string = $sql;
        $this->query = $this->conn->prepare($this->sql_string);

        if ($this->query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function setQuery($sql='') {
        $this->sql_string = $sql;
        $this->query = $this->conn->prepare($this->sql_string);
    }

    function executeQuery() {
        $this->query->execute();
    }

    function loadResultList() {
        $results = $this->query->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }

    function loadSingleResultAssoc() {
        $results = $this->query->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

    public function num_rows() {
        return $this->query->rowCount();
    }

    function loadSingleResult() {
        $results = $this->query->fetch(PDO::FETCH_OBJ);
        return $results;
    }

    function getFieldsOnOneTable($tbl_name) {
        $this->setQuery("DESC ".$tbl_name);
        $rows = $this->loadResultList();

        $f = array();
        foreach ($rows as $row) {
            $f[] = $row->Field;
        }

        return $f;
    }

    public function insert_id() {
        // get the last id inserted over the current db connection
        return $this->conn->lastInsertId();
    }

    public function close_connection() {
        $this->conn = null;
    }
}

$mydb = new Database();
?>
