<?php

// require_once '/config/method.php';

class DbOp
{
    private $conn;

    public function __construct($dbConnection)
    {
        // database connection
        $serverName = "localhost";
        $username = "sa";
        $password = "Nbc12#";
        $db1 = "eportal";
        $db2 = "nbctechdb";

        // create connection
        if ($dbConnection === 1) {
            $this->conn = new mysqli($serverName, $username, $password, $db1);
        } else if ($dbConnection === 2) {
            $this->conn = new mysqli($serverName, $username, $password, $db2);
        }

        // Check connection
        // if ($this->conn->connect_error) {
        //     die(formatErrors("Connection failed: " . $this->conn->connect_error));
        // }
    }

    public function select($query, $types = "", $params = [])
    {
        if ($stmt = $this->conn->prepare($query)) {
            if ($types && $params) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $data;
        } else {
            return false;
        }
    }

    public function insert($query, $types, $params)
    {
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $insertId = $stmt->insert_id;
            $stmt->close();
            return $insertId;
        } else {
            return false;
        }
    }

    public function update($query, $types, $params)
    {
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            return $affectedRows;
        } else {
            return false;
        }
    }

    public function delete($query, $types, $params)
    {
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            return $affectedRows;
        } else {
            return false;
        }
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
