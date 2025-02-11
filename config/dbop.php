<?php

set_time_limit(0);

class DbOp
{
    private $conn;

    public function __construct($dbConnection)
    {
        // database connection
        $serverName = "localhost";
        $username = "nbpcomph_nbp";
        $password = "k6k^9aDLD=oN";
        $db1 = "nbpcomph_nbp"; // dtr viewer, leave
        $db2 = "nbpcomph_nbc"; // canteen (cashless)
        $db3 = "nbpcomph_nbc"; // canteen (cashless)

        // create connection
        switch ($dbConnection) {
            case "1";
                $this->conn = new mysqli($serverName, $username, $password, $db1);
                mysqli_query($this->conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
                break;

            case "2";
                $this->conn = new mysqli($serverName, $username, $password, $db2);
                mysqli_query($this->conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
                break;

            case "3";
                $this->conn = new mysqli($serverName, $username, $password,  $db3);
                mysqli_query($this->conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
                break;

            default:
                $this->conn = new mysqli($serverName, $username, $password, $db1);
                mysqli_query($this->conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
                break;
        }

        // Check connection
        if ($this->conn->connect_error) {
            die(formatErrors("Connection failed: " . $this->conn->connect_error));
        }
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

    public function set($query)
    {
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->execute();
            $stmt->close();
        } else {
            return false;
        }
    }

    public function getAutocompleteName($query, $departmentId)
    {
        $sql = "SELECT employeeid, employeename FROM employee WHERE employeename LIKE CONCAT('%', ?, '%') AND departmentid = ? AND isactive = ?";
        $types = "sii";
        $params = [$query, $departmentId, 1];

        return $this->select($sql, $types, $params);
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
