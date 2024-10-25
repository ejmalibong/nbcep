<?php

require_once 'method.php';

class DbSql
{
    private $conn;

    public function __construct($dbConnection)
    {
        // database connection
        $serverName =  gethostname() . "\SQLEXPRESS";
        $connectionInfo = array("Database" => "LeaveFiling", "Uid" => "sa", "PWD" => "Nbc12#");
        $connectionInfoJs = array("Database" => "NBCTECHDB", "Uid" => "sa", "PWD" => "Nbc12#");

        // create connection
        if ($dbConnection === 1) {
            $this->conn = sqlsrv_connect($serverName, $connectionInfo);
        } else if ($dbConnection === 2) {
            $this->conn = sqlsrv_connect($serverName, $connectionInfoJs);
        }

        // if ($this->conn) {
        //     echo "Connection established to the database.<br />";
        // } else {
        //     echo "Connection could not be established to to the database.<br />";
        //     die(print_r(sqlsrv_errors(), true));
        // }
    }

    public function rdQuery($com, $query, $params = [])
    {
        // prepare and execute the query
        $stmt = sqlsrv_query($this->conn, $query, $params);
        if ($stmt === false) {
            die(formatErrors(sqlsrv_errors()));
        }

        // determine the type of query or stored procedure
        if ($com === 1) {
            $comType = strtoupper(substr(trim($query), 0, 6));
        } else if ($com === 2) {
            $comType = strtoupper(substr(trim($query), 0, 7));
        } else {
            $comType = strtoupper(substr(trim($query), 0, 6));
        }

        //handle SELECT queries and READ stored procedures
        if ($comType === 'SELECT' || $com === 1) {
            $results = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $results[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($this->conn);
            return $results;
        }

        if ($comType === 'EXEC Rd' || $com === 2) {
            $results = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $results[] = $row;
            }
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($this->conn);
            return $results;
        }
    }

    public function execQuery($com, $query, $params = [])
    {
        // prepare and execute the query
        $stmt = sqlsrv_query($this->conn, $query, $params);
        if ($stmt === false) {
            die(formatErrors(sqlsrv_errors()));
        }

        // handle INSERT, UPDATE, DELETE queries
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($this->conn);
        return true;
    }
}
