<?php

session_status() === PHP_SESSION_ACTIVE ?: session_start();

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require "config/dbcon.php";

	$employeeCode = testInput($_POST["txtEmployeeCode"]);
	$password = testInput($_POST["txtPassword"]);
	$isActive = 1;

	// uncomment and save this code to hash password of all employees
	// try {
	// 	$query = "SELECT EmployeeId, Password FROM dbo.Employee";
	// 	$result = sqlsrv_query($conn, $query);

	// 	if ($result === false) {
	// 		echo(FormatErrors(sqlsrv_errors(), true));
	// 	}

	// 	// check if there rows in result set
	// 	if ($result){
	// 		$rows = sqlsrv_has_rows($result);
	// 		if ($rows === true)
	// 			echo "there are rows. <br />";
	// 		else
	// 			echo "there are no rows. <br />";
	// 	}

	// 	$row_count = sqlsrv_num_rows($result);

	// 	if ($row_count === false) {
	// 		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
	// 			$id = $row['EmployeeId'];
	// 			$password = $row['Password'];
	// 			$hashed_password = password_hash($password, PASSWORD_DEFAULT);

	// 			$upd_query = "UPDATE Employee SET PasswordHash=? WHERE EmployeeId=?";
	// 			$stmt = sqlsrv_prepare($conn, $upd_query, array($hashed_password, $id));

	// 			if (!$stmt) {
	// 				die(FormatErrors(sqlsrv_errors(), true));
	// 			}

	// 			sqlsrv_execute($stmt);

	// 			if (sqlsrv_rows_affected($stmt) > 0) {
	// 				echo "Password updated successfully for user ID: $id<br>";
	// 			} else {
	// 				echo "Error updating password for user ID: $id<br>";
	// 			}
	// 		}
	// 		exit();
	// 	} else {
	// 		echo "No users found.";
	// 	}

	// 		sqlsrv_free_stmt($result);
	// 		sqlsrv_close($conn);
	// 	} catch (Exception $e) {
	// 		die(FormatErrors(sqlsrv_errors()));
	// 	}

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
					header('Location: home.php');
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
