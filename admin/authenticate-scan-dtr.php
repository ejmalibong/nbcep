<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="icon" type="image/png" href="../img/favicon.png">
</head>

<?php

session_status() === PHP_SESSION_ACTIVE ?: session_start();

require_once "config/dbop.php";
require_once "config/method.php";

$errorPrompt = '';

$db1 = new DbOp(1);
$db2 = new DbOp(2);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	try {
		$employeeCode = testInput($_POST["txtEmployeeCode"]);

		$selQry3 = "SELECT * FROM `members` WHERE rfid_no=?";
		$prm3 = array($employeeCode);
		$res3 = $db2->select($selQry3, "s", $prm3);

		if (!empty($res3)) {
			foreach ($res3 as $row3) {
				$selQry = "SELECT * FROM `employee` WHERE employeecode=? AND isactive=1";
				$prm = array($row3['emp_no']);
				$res = $db1->select($selQry, "s", $prm);

				if (!empty($res)) {
					foreach ($res as $row) {
						$_SESSION['loggedIn'] = true;
						$_SESSION['employeeId'] = $row['employeeid'];
						$_SESSION['employeeCode'] = $row['employeecode'];
						$_SESSION['employeeName'] = $row['employeename'];
						$_SESSION['departmentId'] = $row['departmentid'];
						$_SESSION['teamId'] = $row['teamid'];
						$_SESSION['positionId'] = $row['positionid'];
						$_SESSION['isApprover'] = $row['isapprover'];
						$_SESSION['isHrRecords'] = $row['ishrrecords'];
						$_SESSION['isEmployee'] = $row['isemployee'];
						$_SESSION['isHoliday'] = $row['isholiday'];
						$_SESSION['isAllowEdit'] = $row['isallowedit'];
						$_SESSION['isAllowDelete'] = $row['isallowdelete'];
						$_SESSION['isAdmin'] = $row['isadmin'];
						$_SESSION['isActive'] = $row['isactive'];

						header('Location: dtr/viewer.php');
						exit;
					}
				} else {
					$errorPrompt = "Employee ID not registered.";
				}
			}
		} else {
			$errorPrompt = "Employee ID not registered.";
		}
	} catch (Exception $e) {
		globalExceptionHandler($e);
	}
}
