<?php

session_status() === PHP_SESSION_ACTIVE ?: session_start();

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	require_once "config/dbop.php";
	require_once "config/method.php";

	try {
		$db1 = new DbOp(1);

		$employeeCode = testInput($_POST["txtEmployeeCode"]);
		$password = testInput($_POST["txtPassword"]);

		$selQry = "SELECT password FROM setting WHERE username=?";
		$res = $db1->select($selQry, "s", [$employeeCode]);

		// check first if the user exists in admin table
		if (count($res) === 1) {
			$row = $res[0];
			$hashed_password = $row["password"];

			if (password_verify($password, $hashed_password)) {
				header('Location: hash-password.php');
				exit;
			} else {
				$loginError = "Incorrect administrator password.";
			}
		} else {
			$selQry = "SELECT * FROM employee WHERE EmployeeCode=? AND IsActive=1";
			$prm = array($employeeCode);
			$res = $db1->select($selQry, "s", $prm);

			if (!empty($res)) {
				foreach ($res as $row) {
					$hashed_password = $row["PasswordHash"];

					if (password_verify($password, $hashed_password)) {
						$_SESSION['loggedin'] = true;
						$_SESSION['employeeId'] = $row['EmployeeId'];
						$_SESSION['employeeCode'] = $row['EmployeeCode'];
						$_SESSION['employeeName'] = $row['EmployeeName'];
						$_SESSION['departmentId'] = $row['DepartmentId'];
						$_SESSION['teamId'] = $row['TeamId'];
						$_SESSION['positionId'] = $row['PositionId'];
						$_SESSION['isHrRecords'] = $row['IsHrRecords'];
						header('Location: dtrviewer.php');
						exit;
					} else {
						$loginError = "Incorrect employee code or password.";
					}
				}
			} else {
				$loginError = "Incorrect employee code or password.";
			}
		}
	} catch (Exception $e) {
		globalExceptionHandler($e);
	}
}
