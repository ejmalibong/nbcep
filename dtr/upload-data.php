<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '72000');
ini_set('max_input_time', '72000');

$title = "Data Uploading";
ob_start(); // start output buffering

require_once "../config/dbop.php";
require_once "../config/header.php";
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$errorPrompt = '';
$successPrompt = '';
$db1 = new DbOp(1);
$db2 = new DbOp(2);

?>

<div class="container mt-5">
    <form id="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <div class="row g-3 align-items-center input-group mb-2">
            <div>
                <h5>Employee</h5>
                <input type="file" class="btn btn-secondary form-control" name="btnBrowseEmp[]" accept=".csv" multiple>
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div class="col-auto">
                <input type="submit" class="btn btn-primary form-control" value="Upload Employee" name="btnUploadEmp">
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div>
                <h5>Department</h5>
                <input type="file" class="btn btn-secondary form-control" name="btnBrowseDept[]" accept=".csv" multiple>
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div class="col-auto">
                <input type="submit" class="btn btn-primary form-control" value="Upload Department" name="btnUploadDept">
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div>
                <h5>Team</h5>
                <input type="file" class="btn btn-secondary form-control" name="btnBrowseTeam[]" accept=".csv" multiple>
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div class="col-auto">
                <input type="submit" class="btn btn-primary form-control" value="Upload Team" name="btnUploadTeam">
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div>
                <h5>Position</h5>
                <input type="file" class="btn btn-secondary form-control" name="btnBrowsePos[]" accept=".csv" multiple>
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div class="col-auto">
                <input type="submit" class="btn btn-primary form-control" value="Upload Position" name="btnUploadPos">
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div>
                <h5>Canteen Members (RFID)</h5>
                <input type="file" class="btn btn-secondary form-control" name="btnBrowseMembers[]" accept=".csv" multiple>
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div class="col-auto">
                <input type="submit" class="btn btn-primary form-control" value="Upload Canteen Members (RFID)" name="btnUploadMembers">
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div>
                <h5>Canteen Transactions</h5>
                <input type="file" class="btn btn-secondary form-control" name="btnBrowseTransactions[]" accept=".csv" multiple>
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div class="col-auto">
                <input type="submit" class="btn btn-primary form-control" value="Upload Canteen Transactions" name="btnUploadTransactions">
            </div>
        </div>

        <?php

        // hh:mm:ss AM/PM
        function formatTime($time)
        {
            if (empty($time)) {
                return ""; // Return empty string if time is empty
            }

            // Check if the time is already in the hh:mm:ss AM/PM format
            $timeObj = DateTime::createFromFormat('h:i:s A', $time);

            // If parsing failed, return the original time (or handle error as needed)
            if ($timeObj === false) {
                return $time;
            }

            // Return time formatted as 24-hour (H:i:s)
            return $timeObj->format('H:i:s');
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                // employee uploading
                if (isset($_POST['btnUploadEmp']) && !empty($_FILES['btnBrowseEmp']['name'][0])) {
                    $totalInsertedRecords = 0;
                    $totalUpdatedRecords = 0;

                    foreach ($_FILES['btnBrowseEmp']['tmp_name'] as $file) {
                        if ($file) {
                            $spreadsheet = IOFactory::load($file);
                            $sheet = $spreadsheet->getActiveSheet();
                            $row = 1;

                            $rowUpdated = 0;
                            $rowInserted = 0;

                            while ($sheet->getCell('A' . $row)->getValue() != "") {
                                $employeeId = $sheet->getCell('A' . $row)->getValue();

                                $selQry = "SELECT employeeid FROM `employee` WHERE employeeid=? LIMIT 1";
                                $prmSel = array($employeeId);
                                $resSel = $db1->select($selQry, "i", $prmSel);

                                $createdBy = $sheet->getCell('B' . $row)->getValue();
                                $createdDate = $sheet->getCell('C' . $row)->getValue();
                                $employeeCode = $sheet->getCell('D' . $row)->getValue();
                                $employeeName = $sheet->getCell('E' . $row)->getValue();
                                $firstName = $sheet->getCell('F' . $row)->getValue();
                                $middleName = $sheet->getCell('G' . $row)->getValue();
                                $lastName = $sheet->getCell('H' . $row)->getValue();
                                $nickName = $sheet->getCell('I' . $row)->getValue();
                                $password = $sheet->getCell('J' . $row)->getValue();
                                $birthDate = $sheet->getCell('K' . $row)->getValue();
                                $nbcEmailAddress = $sheet->getCell('L' . $row)->getValue();
                                $emailAddress = $sheet->getCell('M' . $row)->getValue();
                                $contactNumber = $sheet->getCell('N' . $row)->getValue();
                                $addressRegistered = $sheet->getCell('O' . $row)->getValue();
                                $addressLocal = $sheet->getCell('P' . $row)->getValue();
                                $genderId = $sheet->getCell('Q' . $row)->getValue();
                                $maritalStatusId = $sheet->getCell('R' . $row)->getValue();
                                $employmentTypeId = $sheet->getCell('S' . $row)->getValue();
                                $dateHired = $sheet->getCell('T' . $row)->getValue();
                                $dateSeparated = $sheet->getCell('U' . $row)->getValue();
                                $dateRegular = $sheet->getCell('V' . $row)->getValue();
                                $departmentId = $sheet->getCell('W' . $row)->getValue();
                                $teamId = $sheet->getCell('X' . $row)->getValue();
                                $positionId = $sheet->getCell('Y' . $row)->getValue();
                                $bloodType = $sheet->getCell('Z' . $row)->getValue();
                                $emergencyContactName = $sheet->getCell('AA' . $row)->getValue();
                                $emergencyContactNumber = $sheet->getCell('AB' . $row)->getValue();
                                $emergencyContactAddress = $sheet->getCell('AC' . $row)->getValue();
                                $allergies = $sheet->getCell('AD' . $row)->getValue();
                                $modifiedBy = $sheet->getCell('AE' . $row)->getValue();
                                $modifiedDate = $sheet->getCell('AF' . $row)->getValue();
                                $isApprover = $sheet->getCell('AG' . $row)->getValue();
                                $isHrRecords = $sheet->getCell('AH' . $row)->getValue();
                                $isEmployee = $sheet->getCell('AI' . $row)->getValue();
                                $isHoliday = $sheet->getCell('AJ' . $row)->getValue();
                                $isAllowEdit = $sheet->getCell('AK' . $row)->getValue();
                                $isAllowDelete = $sheet->getCell('AL' . $row)->getValue();
                                $isAdmin = $sheet->getCell('AM' . $row)->getValue();
                                $isActive = $sheet->getCell('AN' . $row)->getValue();

                                if (!empty($resSel)) { // exists from database, update record
                                    $updQry = "UPDATE `employee` SET `employeename` = ?, `firstname` = ?, `middlename` = ?, `lastname` = ?, `nickname` = ?, `birthdate` = ?,`nbcemailaddress` = ?, `emailaddress` = ?, `contactnumber` = ?, `addressregistered` = ?, `addresslocal` = ?, `genderid` = ?, `maritalstatusid` = ?, `employmenttypeid` = ?, `datehired` = ?, `dateseparated` = ?, `dateregular` = ?, `departmentid` = ?, `teamid` = ?, `positionid` = ?, `bloodtype` = ?, `emergencycontactname` = ?, `emergencycontactnumber` = ?, `emergencycontactaddress` = ?, `allergies` = ?, `modifiedby` = ?, `modifieddate` = ?, `isemployee` = ?, `isholiday` = ?, `isallowedit` = ?, `isallowdelete` = ?, `isactive` = ?
                                    WHERE `employeeid` = ?";
                                    $db1->update($updQry, "sssssssssssiiisssiiisssssisiiiiii", [$employeeName, $firstName, $middleName, $lastName, $nickName, $birthDate, $nbcEmailAddress, $emailAddress, $contactNumber, $addressRegistered, $addressLocal, $genderId, $maritalStatusId, $employmentTypeId, $dateHired, $dateSeparated, $dateRegular, $departmentId, $teamId, $positionId, $bloodType, $emergencyContactName, $emergencyContactNumber, $emergencyContactAddress, $allergies, $modifiedBy, $modifiedDate, $isEmployee, $isHoliday, $isAllowEdit, $isAllowDelete, $isActive, $employeeId]);

                                    $rowUpdated++;
                                } else { // not exists from database, insert new record
                                    $passwordHash = password_hash($employeeCode, PASSWORD_DEFAULT);
                                    $isDefaultPassword = 1;

                                    $insQry = "INSERT INTO `employee`(`employeeid`,`createdby`,`createddate`,`employeecode`,`employeename`,`firstname`,`middlename`,`lastname`,`nickname`,`password`,`birthdate`,`nbcemailaddress`,`emailaddress`,`contactnumber`,`addressregistered`,`addresslocal`,`genderid`,`maritalstatusid`,`employmenttypeid`,`datehired`,`dateseparated`, `dateregular`,`departmentid`,`teamid`,`positionid`,`bloodtype`,`emergencycontactname`,`emergencycontactnumber`,`emergencycontactaddress`,`allergies`,`modifiedby`,`modifieddate`,`isapprover`,`ishrrecords`,`isemployee`,`isholiday`,`isallowedit`,`isallowdelete`,`isadmin`,`isactive`,`passwordhash`,`isdefaultpassword`)
                                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                    $prmIns = array($employeeId, $createdBy, $createdDate, $employeeCode, $employeeName, $firstName, $middleName, $lastName, $nickName, $password, $birthDate, $nbcEmailAddress, $emailAddress, $contactNumber, $addressRegistered, $addressLocal, $genderId, $maritalStatusId, $employmentTypeId, $dateHired, $dateSeparated, $dateRegular, $departmentId, $teamId, $positionId, $bloodType, $emergencyContactName, $emergencyContactNumber, $emergencyContactAddress, $allergies, $modifiedBy, $modifiedDate, $isApprover, $isHrRecords, $isEmployee, $isHoliday, $isAllowEdit, $isAllowDelete, $isAdmin, $isActive, $passwordHash, $isDefaultPassword);
                                    $db1->insert($insQry, "iissssssssssssssiiisssiiisssssisiiiiiiiiss", $prmIns);

                                    $rowInserted++;
                                }

                                $row++;
                            }

                            $totalInsertedRecords += $rowInserted;
                            $totalUpdatedRecords += $rowUpdated;
                        }

                        if ($totalInsertedRecords + $totalUpdatedRecords === 0) {
                            $errorPrompt = "No employee(s) were uploaded.";
                        } else {
                            $successPrompt = "There are " . $totalInsertedRecords . " employee(s) uploaded and " . $totalUpdatedRecords . " updated.";
                        }
                    }
                }
                // department uploading
                elseif (isset($_POST['btnUploadDept']) && !empty($_FILES['btnBrowseDept']['name'][0])) {
                    $totalInsertedRecords = 0;
                    $totalUpdatedRecords = 0;

                    foreach ($_FILES['btnBrowseDept']['tmp_name'] as $file) {
                        if ($file) {
                            $spreadsheet = IOFactory::load($file);
                            $sheet = $spreadsheet->getActiveSheet();
                            $row = 1;

                            $rowUpdated = 0;
                            $rowInserted = 0;

                            while ($sheet->getCell('A' . $row)->getValue() != "") {
                                $departmentId = $sheet->getCell('A' . $row)->getValue();

                                $selQry = "SELECT departmentid FROM `department` WHERE departmentId=? LIMIT 1";
                                $prmSel = array($departmentId);
                                $resSel = $db1->select($selQry, "i", $prmSel);

                                $createdBy = $sheet->getCell('B' . $row)->getValue();
                                $createdDate = $sheet->getCell('C' . $row)->getValue();
                                $departmentCode = $sheet->getCell('D' . $row)->getValue();
                                $departmentName = $sheet->getCell('E' . $row)->getValue();
                                $modifiedBy = $sheet->getCell('F' . $row)->getValue();
                                $modifiedDate = $sheet->getCell('G' . $row)->getValue();
                                $isActive = $sheet->getCell('H' . $row)->getValue();

                                if (!empty($resSel)) { // exists from database, update record
                                    $updQry = "UPDATE `department` SET `departmentcode` = ?, `departmentname` = ?, `modifiedby` = ?, `modifieddate` = ?, `isactive` = ?
                                    WHERE `departmentid` = ?";
                                    $db1->update($updQry, "ssisii", [$departmentCode, $departmentName, $modifiedBy, $modifiedDate, $isActive, $departmentId]);

                                    $rowUpdated++;
                                } else { // not exists from database, insert new record
                                    $insQry = "INSERT INTO `department`(`departmentid`,`createdby`,`createddate`,`departmentcode`,`departmentname`,`modifiedby`,`modifieddate`,`isactive`) VALUES
                                        (?,?,?,?,?,?,?,?)";
                                    $prmIns = array($departmentId, $createdBy, $createdDate, $departmentCode, $departmentName, $modifiedBy, $modifiedDate, $isActive);
                                    $db1->insert($insQry, "iisssisi", $prmIns);

                                    $rowInserted++;
                                }

                                $row++;
                            }

                            $totalInsertedRecords += $rowInserted;
                            $totalUpdatedRecords += $rowUpdated;
                        }

                        if ($totalInsertedRecords + $totalUpdatedRecords === 0) {
                            $errorPrompt = "No department(s) were uploaded.";
                        } else {
                            $successPrompt = "There are " . $totalInsertedRecords . " department(s) uploaded and " . $totalUpdatedRecords . " updated.";
                        }
                    }
                }
                //team uploading
                elseif (isset($_POST['btnUploadTeam']) && !empty($_FILES['btnBrowseTeam']['name'][0])) {
                    $totalInsertedRecords = 0;
                    $totalUpdatedRecords = 0;

                    foreach ($_FILES['btnBrowseTeam']['tmp_name'] as $file) {
                        if ($file) {
                            $spreadsheet = IOFactory::load($file);
                            $sheet = $spreadsheet->getActiveSheet();
                            $row = 1;

                            $rowUpdated = 0;
                            $rowInserted = 0;

                            while ($sheet->getCell('A' . $row)->getValue() != "") {
                                $teamId = $sheet->getCell('A' . $row)->getValue();

                                $selQry = "SELECT teamid FROM `team` WHERE teamid=? LIMIT 1";
                                $prmSel = array($teamId);
                                $resSel = $db1->select($selQry, "i", $prmSel);

                                $createdBy = $sheet->getCell('B' . $row)->getValue();
                                $createdDate = $sheet->getCell('C' . $row)->getValue();
                                $teamCode = $sheet->getCell('D' . $row)->getValue();
                                $teamName = $sheet->getCell('E' . $row)->getValue();
                                $modifiedBy = $sheet->getCell('F' . $row)->getValue();
                                $modifiedDate = $sheet->getCell('G' . $row)->getValue();
                                $isActive = $sheet->getCell('H' . $row)->getValue();

                                if (!empty($resSel)) { // exists from database, update record
                                    $updQry = "UPDATE `team` SET `teamcode` = ?, `teamname` = ?, `modifiedby` = ?, `modifieddate` = ?, `isactive` = ?
                                    WHERE `teamid` = ?";
                                    $db1->update($updQry, "ssisii", [$teamCode, $teamName, $modifiedBy, $modifiedDate, $isActive, $teamId]);

                                    $rowUpdated++;
                                } else { // not exists from database, insert new record
                                    $insQry = "INSERT INTO `team`(`teamid`,`createdby`,`createddate`,`teamcode`,`teamname`,`modifiedby`,`modifieddate`,`isactive`) VALUES
                                    (?,?,?,?,?,?,?,?)";
                                    $prmIns = array($teamId, $createdBy, $createdDate, $teamCode, $teamName, $modifiedBy, $modifiedDate, $isActive);
                                    $db1->insert($insQry, "iisssisi", $prmIns);

                                    $rowInserted++;
                                }

                                $row++;
                            }

                            $totalInsertedRecords += $rowInserted;
                            $totalUpdatedRecords += $rowUpdated;
                        }

                        if ($totalInsertedRecords + $totalUpdatedRecords === 0) {
                            $errorPrompt = "No team(s) were uploaded.";
                        } else {
                            $successPrompt = "There are " . $totalInsertedRecords . " team(s) uploaded and " . $totalUpdatedRecords . " updated.";
                        }
                    }
                }
                // position uploading
                elseif (isset($_POST['btnUploadPos']) && !empty($_FILES['btnBrowsePos']['name'][0])) {
                    $totalInsertedRecords = 0;
                    $totalUpdatedRecords = 0;

                    foreach ($_FILES['btnBrowsePos']['tmp_name'] as $file) {
                        if ($file) {
                            $spreadsheet = IOFactory::load($file);
                            $sheet = $spreadsheet->getActiveSheet();
                            $row = 1;

                            $rowUpdated = 0;
                            $rowInserted = 0;

                            while ($sheet->getCell('A' . $row)->getValue() != "") {
                                $positionId = $sheet->getCell('A' . $row)->getValue();

                                $selQry = "SELECT positionid FROM `position` WHERE positionid=? LIMIT 1";
                                $prmSel = array($positionId);
                                $resSel = $db1->select($selQry, "s", $prmSel);

                                $createdBy = $sheet->getCell('B' . $row)->getValue();
                                $createdDate = $sheet->getCell('C' . $row)->getValue();
                                $positionCode = $sheet->getCell('D' . $row)->getValue();
                                $positionName = $sheet->getCell('E' . $row)->getValue();
                                $modifiedBy = $sheet->getCell('F' . $row)->getValue();
                                $modifiedDate = $sheet->getCell('G' . $row)->getValue();
                                $isActive = $sheet->getCell('H' . $row)->getValue();

                                if (!empty($resSel)) { // exists from database, update record
                                    $updQry = "UPDATE `position` SET `positioncode` = ?, `positionname` = ?, `modifiedby` = ?, `modifieddate` = ?, `isactive` = ?
                                    WHERE `positionid` = ?";
                                    $db1->update($updQry, "ssisii", [$positionCode, $positionName, $modifiedBy, $modifiedDate, $isActive, $positionId]);

                                    $rowUpdated++;
                                } else { // not exists from database, insert new record
                                    $insQry = "INSERT INTO `position`(`positionid`,`createdby`,`createddate`,`positioncode`,`positionname`,`modifiedby`,`modifieddate`,`isactive`) VALUES
                                    (?,?,?,?,?,?,?,?)";
                                    $prmIns = array($positionId, $createdBy, $createdDate, $positionCode, $positionName, $modifiedBy, $modifiedDate, $isActive);
                                    $db1->insert($insQry, "iisssisi", $prmIns);

                                    $rowInserted++;
                                }

                                $row++;
                            }

                            $totalInsertedRecords += $rowInserted;
                            $totalUpdatedRecords += $rowUpdated;
                        }
                        if ($totalInsertedRecords + $totalUpdatedRecords === 0) {
                            $errorPrompt = "No position(s) were uploaded.";
                        } else {
                            $successPrompt = "There are " . $totalInsertedRecords . " position(s) uploaded and " . $totalUpdatedRecords . " updated.";
                        }
                    }
                }
                // canteen members uploading
                elseif (isset($_POST['btnUploadMembers']) && !empty($_FILES['btnBrowseMembers']['name'][0])) {
                    $totalInsertedRecords = 0;

                    foreach ($_FILES['btnBrowseMembers']['tmp_name'] as $file) {
                        if ($file) {
                            $spreadsheet = IOFactory::load($file);
                            $sheet = $spreadsheet->getActiveSheet();
                            $row = 1;

                            $rowInserted = 0;

                            while ($sheet->getCell('A' . $row)->getValue() != "") {
                                $member_id = $sheet->getCell('A' . $row)->getValue();

                                $selQry = "SELECT member_id FROM `members` WHERE member_id=? LIMIT 1";
                                $prmSel = array($member_id);
                                $resSel = $db2->select($selQry, "i", $prmSel);

                                // exists from database, skip
                                if (!empty($resSel)) {
                                }
                                // not exists from database, insert new record
                                else {
                                    $emp_no = $sheet->getCell('B' . $row)->getValue();
                                    $emp_no = is_null($emp_no) ? "  " : $emp_no;

                                    $fname = $sheet->getCell('C' . $row)->getValue();
                                    $fname = is_null($fname) ? "  " : $fname;

                                    $mname = $sheet->getCell('D' . $row)->getValue();
                                    $mname = is_null($mname) ? "  " : $mname;

                                    $lname = $sheet->getCell('E' . $row)->getValue();
                                    $lname = is_null($lname) ? "  " : $lname;

                                    $address = $sheet->getCell('F' . $row)->getValue();
                                    $address = is_null($address) ? "  " : $address;

                                    $department = $sheet->getCell('G' . $row)->getValue();
                                    $department = is_null($department) ? "  " : $department;

                                    $section = $sheet->getCell('H' . $row)->getValue();
                                    $section = is_null($section) ? "  " : $section;

                                    $position = $sheet->getCell('I' . $row)->getValue();
                                    $position = is_null($position) ? "  " : $position;

                                    $balance = $sheet->getCell('J' . $row)->getValue();
                                    $balance = is_null($balance) ? 0 : $balance;

                                    $type = $sheet->getCell('K' . $row)->getValue();
                                    $type = is_null($type) ? "  " : $type;

                                    $rfid_no = $sheet->getCell('L' . $row)->getValue();
                                    $rfid_no = is_null($rfid_no) ? "  " : $rfid_no;

                                    $qr_code = $sheet->getCell('M' . $row)->getValue();
                                    $qr_code = is_null($qr_code) ? "  " : $qr_code;

                                    $max_credit = $sheet->getCell('N' . $row)->getValue();

                                    $insQry = "INSERT INTO `members`(`member_id`, `emp_no`, `fname`, `mname`, `lname`, `address`, `department`, `section`, `position`, `balance`, `type`, `rfid_no`, `qr_code`, `max_credit`) VALUES
                                    (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                    $prmIns = array($member_id, $emp_no, $fname, $mname, $lname, $address, $department, $section, $position, $balance, $type, $rfid_no, $qr_code, $max_credit);
                                    $db2->insert($insQry, "issssssssdssss", $prmIns);

                                    $rowInserted++;
                                }

                                $row++;
                            }

                            $totalInsertedRecords += $rowInserted;
                        }

                        if ($totalInsertedRecords === 0) {
                            $errorPrompt = "No canteen member(s) were uploaded.";
                        } else {
                            $successPrompt = $totalInsertedRecords . " canteen member(s) were uploaded.";
                        }
                    }
                }
                // canteen transactions uploading
                elseif (isset($_POST['btnUploadTransactions']) && !empty($_FILES['btnBrowseTransactions']['name'][0])) {
                    $totalInsertedRecords = 0;

                    foreach ($_FILES['btnBrowseTransactions']['tmp_name'] as $file) {
                        if ($file) {
                            $spreadsheet = IOFactory::load($file);
                            $sheet = $spreadsheet->getActiveSheet();
                            $row = 1;

                            $rowInserted = 0;

                            while ($sheet->getCell('A' . $row)->getValue() != "") {
                                $transaction_id = $sheet->getCell('A' . $row)->getValue();

                                $selQry = "SELECT transaction_id FROM `transactions` WHERE transaction_id=? LIMIT 1";
                                $prmSel = array($transaction_id);
                                $resSel = $db2->select($selQry, "i", $prmSel);

                                // exists from database, skip
                                if (!empty($resSel)) {
                                }
                                // not exists from database, insert new record
                                else {
                                    $rfid_no = $sheet->getCell('B' . $row)->getValue();
                                    $member_name = $sheet->getCell('C' . $row)->getValue();
                                    $product_id = $sheet->getCell('D' . $row)->getValue();
                                    $product_name = $sheet->getCell('E' . $row)->getValue();
                                    $price = $sheet->getCell('F' . $row)->getValue();
                                    $qty = $sheet->getCell('G' . $row)->getValue();
                                    $amount = $sheet->getCell('H' . $row)->getValue();
                                    $user = $sheet->getCell('I' . $row)->getValue();
                                    $dttm = $sheet->getCell('J' . $row)->getValue();
                                    $active = $sheet->getCell('K' . $row)->getValue();
                                    $active = is_null($active) ? 1 : $active;
                                    $receipt = $sheet->getCell('L' . $row)->getValue();
                                    $isload = $sheet->getCell('M' . $row)->getValue();
                                    $isreserve = $sheet->getCell('N' . $row)->getValue();

                                    $insQry = "INSERT INTO `transactions`(`transaction_id`, `rfid_no`, `member_name`, `product_id`, `product_name`, `price`, `qty`, `amount`, `user`, `dttm`, `active`, `receipt`, `isload`, `isreserve`) VALUES
                                    (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                    $prmIns = array($transaction_id, $rfid_no, $member_name, $product_id, $product_name, $price, $qty, $amount, $user, $dttm, $active, $receipt, $isload, $isreserve);
                                    $db2->insert($insQry, "issisdssssssii", $prmIns);
                                    $rowInserted++;
                                }

                                $row++;
                            }

                            $totalInsertedRecords += $rowInserted;
                        }

                        if ($totalInsertedRecords === 0) {
                            $errorPrompt = "No canteen transaction(s) were uploaded.";
                        } else {
                            $successPrompt = $totalInsertedRecords . " canteen transaction(s) were uploaded.";
                        }
                    }
                } else {
                    $errorPrompt = "Please select file(s) to upload.";
                }
            } catch (Exception $e) {
                globalExceptionHandler($e);
            }
        }
        ?>
        <?php if ($errorPrompt) : ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo $errorPrompt; ?>
            </div>
        <?php endif; ?>
        <?php if ($successPrompt) : ?>
            <div class="alert alert-success mt-3" role="alert">
                <?php echo $successPrompt; ?>
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');

        form.addEventListener('myForm', function(e) {
            e.preventDefault(); // stop form from submitting normally

            // use fetch API to submit the form data
            fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form)
                })
                .then(response => response.text())
                .then(html => alert(html)) // display response
                .catch(error => console.error('Error:', error));
        });
    });
</script>

<?php
$content = ob_get_clean(); // capture the buffer into a variable and clean the buffer
include('../config/master.php');
?>