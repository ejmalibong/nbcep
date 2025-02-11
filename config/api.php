<?php

require_once "dbop.php";
require_once "method.php";

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'autocomplete' && isset($_GET['query']) && isset($_GET['departmentId'])) {
        $query = $_GET['query'];
        $departmentId = $_GET['departmentId'];

        if (!isset($departmentId)) {
            echo json_encode(["error" => "Department ID not set in session"]);
            exit;
        }

        $db = new DbOp(1);
        $results = $db->getAutocompleteName($query, $departmentId);

        if (!empty($results)) {
            echo json_encode(array_column($results, 'employeeid', 'employeename'));
        } else {
            echo json_encode([]);
        }
    }
} else {
    echo json_encode(["error" => "Invalid action"]);
}
