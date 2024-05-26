<?php

session_status() === PHP_SESSION_ACTIVE ?: session_start();

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require "config/dbcon.php";

	$employeeCode = testInput($_POST["txtEmployeeCode"]);
	$password = testInput($_POST["txtPassword"]);
	$isActive = 1;

	// part of first time setup, disable (comment) the session_status at the top
	// uncomment this code and reload the browser to hash password of all employees
	// try {
	// 	$selQry = "SELECT EmployeeId, Password FROM dbo.Employee";
	// 	$res = execQuery(1, 1, $selQry);

	// 	if ($res !== false) {
	// 		foreach ($res as $row) {
	// 			$id = $row['EmployeeId'];
	// 			$password = $row['Password'];
	// 			$hashed_password = password_hash($password, PASSWORD_DEFAULT);

	// 			$delQry = "UPDATE Employee SET PasswordHash=? WHERE EmployeeId=?";
	// 			$params = [$hashed_password, $id];
	// 			$resDelQry = execQuery(1, 1, $delQry, $params);

	// 			if ($resDelQry === false) {
	// 				echo "Password updated successfully for user ID: $id<br>";
	// 			} else {
	// 				echo "Error updating password for user ID: $id<br>";
	// 			}
	// 		}
	// 	} else {
	// 		echo "No users found.";
	// 	}
	// } catch (Exception $e) {
	// 	die(FormatErrors(sqlsrv_errors()));
	// }

	try {
		$proc = "EXEC RdEmployee @EmployeeCode=?, @IsActive=?";
		$params = [$employeeCode, $isActive];
		$res = execQuery(1, 2, $proc, $params);

		if ($res !== false) {
			foreach ($res as $row) {
				$hashedPassword = $row["PasswordHash"];

				if (password_verify($password, $hashedPassword)) {
					$_SESSION['loggedin'] = true;
					$_SESSION['employeeId'] = $row['EmployeeId'];
					$_SESSION['employeeCode'] = $row['EmployeeCode'];
					$_SESSION['employeeName'] = $row['EmployeeName'];
					$_SESSION['departmentId'] = $row['DepartmentId'];
					$_SESSION['teamId'] = $row['TeamId'];
					$_SESSION['positionId'] = $row['PositionId'];
					header('Location: dtrviewer.php');
					exit;
				} else {
					$loginError = "Incorrect employee ID or password.";
				}
			}
		} else {
			$loginError = "Incorrect employee ID or password.";
		}
	} catch (Exception $e) {
		die(FormatErrors(sqlsrv_errors()));
	}
}
