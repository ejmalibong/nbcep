<?php

$title = "DTR Uploading";
ob_start(); // start output buffering

require_once "../config/dbop.php";
require_once "../config/dbsql.php";
require_once "../config/header.php";
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$errorPrompt = '';
$successPrompt = '';
$db1 = new DbOp(1);

?>

<div class="container mt-5">
    <form id="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <div class="row g-3 align-items-center input-group mb-2">
            <div>
                <input type="file" class="btn btn-secondary form-control" name="btnBrowse[]" accept=".xls" multiple>
            </div>
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div class="col-auto">
                <input type="submit" class="btn btn-primary form-control" value="Upload to Database" name="btnUpload">
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
                if (isset($_POST['btnUpload']) && !empty($_FILES['btnBrowse']['name'][0])) {
                    $totalInsertedRecords = 0;

                    foreach ($_FILES['btnBrowse']['tmp_name'] as $file) {
                        if ($file) {
                            $spreadsheet = IOFactory::load($file);
                            $sheet = $spreadsheet->getActiveSheet();
                            $row = 7;

                            while ($sheet->getCell('A' . $row)->getValue() != "") {
                                // get data from each cell
                                $employeeId = 0;

                                $employeeCode = $sheet->getCell('A' . $row)->getValue();
                                $employeeName = $sheet->getCell('B' . $row)->getValue();
                                $departmentName = $sheet->getCell('C' . $row)->getValue();
                                $teamName = $sheet->getCell('D' . $row)->getValue();
                                $date = $sheet->getCell('E' . $row)->getValue();
                                $attendanceType = $sheet->getCell('F' . $row)->getValue();
                                $dailySchedule = $sheet->getCell('G' . $row)->getValue();
                                $dayType = $sheet->getCell('H' . $row)->getValue();
                                $timeIn = $sheet->getCell('I' . $row)->getValue();
                                $timeOut = $sheet->getCell('J' . $row)->getValue();
                                $regularHours = $sheet->getCell('K' . $row)->getValue();
                                $tardy = $sheet->getCell('L' . $row)->getValue();
                                $undertime = $sheet->getCell('M' . $row)->getValue();
                                $nd = $sheet->getCell('N' . $row)->getValue();
                                $ot = $sheet->getCell('O' . $row)->getValue();
                                $ndot = $sheet->getCell('P' . $row)->getValue();
                                $leaveType = $sheet->getCell('Q' . $row)->getValue();
                                $leaveQty = $sheet->getCell('R' . $row)->getValue();
                                $leaveStatus = $sheet->getCell('S' . $row)->getValue();
                                $ob = $sheet->getCell('T' . $row)->getValue();
                                $remarks = $sheet->getCell('U' . $row)->getValue();

                                $selQry = "SELECT employeeid FROM `employee` WHERE employeecode=? LIMIT 1";
                                $prmSel = array($employeeCode);
                                $resSel = $db1->select($selQry, "s", $prmSel);

                                $formattedDate = date_format(date_create($date), 'Y-m-d');
                                // Format the time to 24-hour format
                                $timeInFormatted = formatTime($timeIn);
                                $timeOutFormatted = formatTime($timeOut);

                                if (!empty($resSel)) {
                                    foreach ($resSel as $rowSel) {
                                        $employeeId = $rowSel['employeeid'];
                                    }
                                }

                                $insQry = "INSERT INTO `dailytimerecord`(`employeeid`, `employeecode`, `employeename`, `departmentname`, `teamname`, `date`, `attendancetype`, `dailyschedule`, `daytype`, `timein`, `timeout`, `regularhours`, `tardy`, `undertime`, `nd`, `ot`, `ndot`, `leavetype`, `leaveqty`, `leavestatus`, `ob`, `remarks`) VALUES
                                (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                $prmIns = array($employeeId, $employeeCode, $employeeName, $departmentName, $teamName, $formattedDate, $attendanceType, $dailySchedule, $dayType, $timeInFormatted, $timeOutFormatted, $regularHours, $tardy, $undertime, $nd, $ot, $ndot, $leaveType, $leaveQty, $leaveStatus, $ob, $remarks);
                                $db1->insert($insQry, "issssssssssddddddsdsds", $prmIns);

                                $row++;
                            }

                            $totalInsertedRecords += ($row - 7);
                        }
                        $successPrompt = $totalInsertedRecords . " record(s) inserted to database from all files.";
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