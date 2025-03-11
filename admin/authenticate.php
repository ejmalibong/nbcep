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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	try {
		$employeeCode = testInput($_POST["txtEmployeeCode"]);
		$password = testInput($_POST["txtPassword"]);

		$selQry = "SELECT password FROM `setting` WHERE username=?";
		$res = $db1->select($selQry, "s", [$employeeCode]);

		// check first if the user exists in admin table
		if (count($res) === 1) {
			$row = $res[0];
			$hashed_password = $row["password"];

			if (password_verify($password, $hashed_password)) {
				header('Location: admin/pass-hash.php');
				exit;
			} else {
				$errorPrompt = "Incorrect administrator password.";
			}
		} else {
			$selQry = "SELECT * FROM `employee` WHERE employeecode=? AND isactive=1";
			$prm = array($employeeCode);
			$res = $db1->select($selQry, "s", $prm);

			if (!empty($res)) {
				foreach ($res as $row) {
					$hashed_password = $row["passwordhash"];

					if (password_verify($password, $hashed_password)) {
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

						$selQry2 = "SELECT `isdefaultpassword` FROM `employee` WHERE employeeid=?";
						$prmSel2 = array($_SESSION['employeeId']);
						$res2 = $db1->select($selQry2, "i", $prmSel2);

						if (!empty($res2)) {
							foreach ($res2 as $row2) {
								if ($row['isdefaultpassword'] == 1) {
									echo '<script> alert("Please change your default password."); window.location.href="user/change-password.php"; </script>';
								} else {
									header('Location: dtr/viewer.php');
								}
							}
						}
						exit;
					} else {
						$errorPrompt = "Incorrect employee code or password.";
					}
				}
			} else {
				$errorPrompt = "Incorrect employee code or password.";
			}
		}
	} catch (Exception $e) {
		globalExceptionHandler($e);
	}
}
