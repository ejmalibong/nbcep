<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '72000');
ini_set('max_input_time', '72000');

$title = "DTR Uploading";
ob_start(); // start output buffering

require_once "../config/dbop.php";
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
                <input type="submit" class="btn btn-primary form-control" value="Upload DTR" name="btnUpload">
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

                            $rowInserted = 0;

                            $notRegistered = array();

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

                                // exists in employee masterlist
                                if (!empty($resSel)) {
                                    foreach ($resSel as $rowSel) {
                                        $employeeId = $rowSel['employeeid'];

                                        // check if dtr for specific day is exists, if yes, do not insert
                                        $selQry2 = "SELECT count(recordid) as totalcount FROM `dailytimerecord` WHERE employeeid=? AND CAST(date as DATE) =?";
                                        $prmSel2 = array($employeeId, $formattedDate);
                                        $resSel2 = $db1->select($selQry2, "ss", $prmSel2);

                                        // dtr for specific day not exists, insert dtr
                                        if (!empty($resSel2)) {
                                            foreach ($resSel2 as $rowSel2) {
                                                if ($rowSel2['totalcount'] === 0) {
                                                    $insQry = "INSERT INTO `dailytimerecord`(`employeeid`, `employeecode`, `employeename`, `departmentname`, `teamname`, `date`, `attendancetype`, `dailyschedule`, `daytype`, `timein`, `timeout`, `regularhours`, `tardy`, `undertime`, `nd`, `ot`, `ndot`, `leavetype`, `leaveqty`, `leavestatus`, `ob`, `remarks`) VALUES
                                                    (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                    $prmIns = array($employeeId, $employeeCode, $employeeName, $departmentName, $teamName, $formattedDate, $attendanceType, $dailySchedule, $dayType, $timeInFormatted, $timeOutFormatted, $regularHours, $tardy, $undertime, $nd, $ot, $ndot, $leaveType, $leaveQty, $leaveStatus, $ob, $remarks);
                                                    $db1->insert($insQry, "issssssssssddddddsdsds", $prmIns);

                                                    $rowInserted++;
                                                }
                                            }
                                        }
                                    }
                                }
                                // not exists in employee masterlist
                                else {
                                    if (number_format($regularHours, 2) == '0.00') {
                                        $strt = "";
                                        $end = "";
                                    } else {
                                        $strt = is_null($timeIn) ? "" : date('H:i A', strtotime($timeIn));
                                        $end = is_null($timeOut) ? "" : date('H:i A', strtotime($timeOut));
                                    }

                                    $formattedDate = date_format(date_create($date), 'm/d/Y');

                                    $leavetype = is_null($leaveType) ? "" : $leaveType;
                                    $leavestatus = is_null($leaveStatus) ? "" : $leaveStatus;
                                    $remarks = is_null($remarks) ? "" : $remarks;

                                    $notRegistered[] = array(

                                        'Employee Code' => $employeeCode,
                                        'Employee Name' => $employeeName,
                                        'Department' => $departmentName,
                                        'Team' => $teamName,
                                        'Date' => $formattedDate,
                                        'Attendance' => $attendanceType,
                                        'Schedule' => $dailySchedule,
                                        'Day' => $dayType,
                                        'Time In' => $strt,
                                        'Time Out' => $end,
                                        'Reg Hours' => number_format($regularHours, 2),
                                        'Tardy' => number_format($tardy, 2),
                                        'Undertime' => number_format($undertime, 2),
                                        'ND' => number_format($nd, 2),
                                        'OT' => number_format($ot, 2),
                                        'NDOT' => number_format($ndot, 2),
                                        'Leave' => $leavetype,
                                        'Qty' => number_format($leaveQty, 2),
                                        'Status' => $leavestatus,
                                        'OB' => number_format($ob, 2),
                                        'Remarks' => $remarks

                                    );
                                }
                                $row++;
                            }

                            if (!empty($notRegistered)) {
                                $filename = "Employee_NotRegistered_" . date("Ymd_His") . ".csv";
                                $filePath = "../export/" . $filename; // Save to a temporary folder

                                // Ensure the folder exists
                                if (!is_dir("../export")) {
                                    mkdir("../export", 0777, true);
                                }

                                $output = fopen($filePath, "w");
                                fputcsv($output, array_keys($notRegistered[0])); // Headers
                                foreach ($notRegistered as $record) {
                                    fputcsv($output, $record);
                                }
                                fclose($output);

                                // Store the file path for later use
                                $_SESSION['export_file'] = $filePath;
                            }

                            // End the output buffer if not already ended
                            ob_end_flush();

                            $totalInsertedRecords += $rowInserted;
                        }

                        if (!empty($notRegistered)) {
                            if ($totalInsertedRecords === 0) {
                                $errorPrompt = "No record(s) were uploaded.<br/>Please see the DTR of <a href='download.php?file=" . urlencode($filename) . "' target='_blank'>unregistered employees</a>.";
                            } else {
                                $successPrompt = $totalInsertedRecords . " record(s) were uploaded.<br />Please see also the DTR of <a href='download.php?file=" . urlencode($filename) . "' target='_blank'>unregistered employees</a>.";
                            }
                        } else {
                            if ($totalInsertedRecords === 0) {
                                $errorPrompt = "No record(s) were uploaded.";
                            } else {
                                $successPrompt = $totalInsertedRecords . " record(s) were uploaded.";
                            }
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