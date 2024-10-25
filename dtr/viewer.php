<?php

$title = "DTR Viewer";
ob_start(); // start output buffering

require_once "../config/dbop.php";
require_once "../config/dbsql.php";
require_once "../config/header.php";

$errorPrompt = '';
$successPrompt = '';

$firstDay = date('Y-09-01');
$lastDay = date('Y-m-' . cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')));

?>

<style>
    table {
        table-layout: fixed;
        word-wrap: break-word;
    }

    th {
        font-weight: normal;
        vertical-align: middle;
    }

    td {
        min-width: 50px;
        vertical-align: middle;
    }

    @media only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px) {

        /* Force table to not be like tables anymore */
        table,
        thead,
        tbody,
        th,
        td,
        tr {
            display: block;
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        tr {
            border: 1px solid #ccc;
        }

        td {
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
        }

        td:before {
            /* Now like a table header */
            position: absolute;
            /* Top/left values mimic padding */
            top: 6px;
            left: 6px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
        }

        /*Label the data*/
        td:nth-of-type(1):before {
            content: "#";
        }

        td:nth-of-type(2):before {
            content: "Employee Name";
        }

        td:nth-of-type(3):before {
            content: "Department";
        }

        td:nth-of-type(4):before {
            content: "Team";
        }

        td:nth-of-type(5):before {
            content: "Date";
        }

        td:nth-of-type(6):before {
            content: "Attendance";
        }

        td:nth-of-type(7):before {
            content: "Schedule";
        }

        td:nth-of-type(8):before {
            content: "Day";
        }

        td:nth-of-type(9):before {
            content: "Time In";
        }

        td:nth-of-type(10):before {
            content: "Time Out";
        }

        td:nth-of-type(11):before {
            content: "Reg Hours";
        }

        td:nth-of-type(12):before {
            content: "Tardy";
        }

        td:nth-of-type(13):before {
            content: "Undertime";
        }

        td:nth-of-type(14):before {
            content: "ND";
        }

        td:nth-of-type(15):before {
            content: "OT";
        }

        td:nth-of-type(16):before {
            content: "NDOT";
        }

        td:nth-of-type(17):before {
            content: "Leave";
        }

        td:nth-of-type(18):before {
            content: "Qty";
        }

        td:nth-of-type(19):before {
            content: "Status";
        }

        td:nth-of-type(20):before {
            content: "OB";
        }

        td:nth-of-type(21):before {
            content: "Remarks";
        }
    }
</style>

<form id="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="container-fluid">
        <div class="bg-body p-5 rounded">
            <?php
            if ($_SESSION['isApprover'] == 1) { ?>
                <div class="row row-cols-lg-auto g-3 align-items-center mb-2">
                    <div class="col-12">
                        <input class="form-check-input" type="radio" id="rdMyDtr" name="dtrOption" value="option1" <?php if (isset($_POST["dtrOption"]) && $_POST['dtrOption'] == "option1") {
                                                                                                                        echo "checked";
                                                                                                                    } ?> checked>
                        <label class="form-check-label" for="rdMyDtr">
                            My DTR
                        </label>
                    </div>
                    <div class="col-12">
                        <input class="form-check-input" type="radio" id="rdDeptMembers" name="dtrOption" value="option2" <?php if (isset($_POST["dtrOption"]) && $_POST['dtrOption'] == "option2") {
                                                                                                                                echo "checked";
                                                                                                                            } ?>>
                        <label class="form-check-label" for="rdDeptMembers">
                            All Department Members
                        </label>
                    </div>
                    <div class="col-12">
                        <div class="input-group mb-1">
                            <div class="input-group-text">
                                <input class="form-check-input" type="radio" id="rdSearchMember" name="dtrOption" value="option3" <?php if (isset($_POST["dtrOption"]) && $_POST['dtrOption'] == "option3") {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?>>
                                <label class="d-block mx-2" for="rdSearchMember">
                                    Search Member
                                </label>
                            </div>
                            <input type="text" class="form-control form-control">
                        </div>
                    </div>
                </div>
                <div class="row row-cols-lg-auto g-3 align-items-center mb-2">
                    <div class="col-12">
                        <label for="dtpStartDate" class="col-form-label">Start Date</label>
                    </div>
                    <div class="col-12">
                        <input type="date" id="dtpStartDate" name="dtpStartDate" class="form-control" value="<?php echo isset($_POST["dtpStartDate"]) ? $_POST["dtpStartDate"] : $firstDay; ?>">
                    </div>
                    <div class="col-12">
                        <label for="dtpEndDate" class="col-form-label">End Date</label>
                    </div>
                    <div class="col-12">
                        <input type="date" id="dtpEndDate" name="dtpEndDate" class="form-control" value="<?php echo isset($_POST["dtpEndDate"]) ? $_POST["dtpEndDate"] : $lastDay; ?>">
                    </div>
                </div>
            <?php
            }
            ?>

            <div class="row g-3 align-items-center input-group mb-2">
                <div class="col-auto">
                    <button type="submit" class='btn btn-primary' id="btnGenerate" value="btnGenerate" name="btnGenerate">
                        Generate DTR
                    </button>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-secondary" id="btnExpExcel" value="btnExpExcel" name="btnExpExcel">
                        Export to Excel
                    </button>
                </div>
                <div class="col-auto">
                    <button type="submit" class='btn btn-secondary' id="btnExpPdf" value="btnExpPdf" name="btnExpPdf">
                        Export to PDF
                    </button>
                </div>
            </div>

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
        </div>
    </div>

    <!-- <div class="row" style="overflow: auto;">
         -->

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $startDate = testInput($_POST["dtpStartDate"]);
        $endDate = testInput($_POST["dtpEndDate"]);

        if (empty($startDate) || is_null($startDate)) {
            $errorPrompt .= "Start date is empty.<br>";
        }

        if (empty($endDate) || is_null($endDate)) {
            $errorPrompt .= "End date is empty.<br>";
        }

        if (!empty($startDate) && !empty($endDate) && $startDate > $endDate) {
            $errorPrompt .= "Start date is later than end date.<br>";
        }

        if (empty($errorPrompt)) {
            if (isset($_POST['btnGenerate'])) {
                generateDtr();
            } else if (isset($_POST['btnExpExcel'])) {
                exportToExcel();
            } else if (isset($_POST['btnExpPdf'])) {
                exportToPdf();
            }
        }
    }
    ?>
</form>

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

function generateDtr()
{
    $startDate = testInput($_POST["dtpStartDate"]);
    $endDate = testInput($_POST["dtpEndDate"]);

    $db1 = new DbOp(1);

    switch ($_POST["dtrOption"]) {
        case "option1";
            $selQry = "SELECT * FROM `dailytimerecord` WHERE employeeid=? AND CAST(date as DATE) between ? AND ?";
            $prmSel = array($_SESSION['employeeId'], $startDate, $endDate);
            $resSel = $db1->select($selQry, "sss", $prmSel);

            if (!empty($resSel)) {
                echo "<div class='row table-responsive' style='overflow-x: auto;'>";
                echo "<div class='col'>";
                echo "<div class='card'>";
                echo "<div class='card-body' style='overflow-x: auto;'>";
                echo "<table class='table table-striped table-mobile-responsive table-mobile-sided'>";
                echo "<thead>";
                echo "<tr>";

                echo "<th scope='col' style='width: 50px;'>#</th>";
                echo "<th scope='col' style='width: 250px;'>Employee Name</th>";
                echo "<th scope='col' style='width: 200px;'>Department</th>";
                echo "<th scope='col' style='width: 200px;'>Team</th>";
                echo "<th scope='col' style='width: 100px;'>Date</th>";
                echo "<th scope='col' style='width: 150px;'>Attendance</th>";
                echo "<th scope='col' style='width: 200px;'>Schedule</th>";
                echo "<th scope='col' style='width: 150px;'>Day</th>";
                echo "<th scope='col' style='width: 100px;'>Time In</th>";
                echo "<th scope='col' style='width: 100px;'>Time Out</th>";
                echo "<th scope='col' style='width: 80px;'>Reg Hours</th>";
                echo "<th scope='col' style='width: 80px;'>Tardy</th>";
                echo "<th scope='col' style='width: 100px;'>Undertime</th>";
                echo "<th scope='col' style='width: 80px;'>ND</th>";
                echo "<th scope='col' style='width: 80px;'>OT</th>";
                echo "<th scope='col' style='width: 80px;'>NDOT</th>";
                echo "<th scope='col' style='width: 200px;'>Leave</th>";
                echo "<th scope='col' style='width: 80px;'>Qty</th>";
                echo "<th scope='col' style='width: 200px;'>Status</th>";
                echo "<th scope='col' style='width: 80px;'>OB</th>";
                echo "<th scope='col' style='width: 200px;'>Remarks</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                $rowCount = 1;

                foreach ($resSel as $row) {
                    echo "<tr>";

                    echo "<td scope='row' data-content='#'>" . $rowCount . "</td>";
                    echo "<td>" . $row['employeename'] . "</td>";
                    echo "<td>" . $row['departmentname'] . "</td>";
                    echo "<td>" . $row['teamname'] . "</td>";

                    $formattedDate = date_format(date_create($row["date"]), 'm/d/Y');
                    echo "<td>" . $formattedDate . "</td>";

                    echo "<td>" . $row["attendancetype"] . "</td>";
                    echo "<td>" . $row["dailyschedule"] . "</td>";
                    echo "<td>" . $row["daytype"] . "</td>";

                    if (number_format($row["regularhours"], 2) == '0.00') {
                        echo "<td>" . '&nbsp' . "</td>";
                        echo "<td>" . '&nbsp' . "</td>";
                    } else {
                        $strt = is_null($row["timein"]) ? "" : date('h:i A', strtotime($row["timein"]));
                        echo "<td>" . $strt . "</td>";

                        $end = is_null($row["timeout"]) ? " " : date('h:i A', strtotime($row["timeout"]));
                        echo "<td>" . $end . "</td>";
                    }

                    echo "<td>" . number_format($row["regularhours"], 2) . "</td>";
                    echo "<td>" . number_format($row["tardy"], 2) . "</td>";
                    echo "<td>" . number_format($row["undertime"], 2) . "</td>";
                    echo "<td>" . number_format($row["nd"], 2) . "</td>";
                    echo "<td>" . number_format($row["ot"], 2) . "</td>";
                    echo "<td>" . number_format($row["ndot"], 2) . "</td>";

                    $leavetype = is_null($row["leavetype"]) ? '&nbsp' : $row["leavetype"];
                    echo "<td>" . $leavetype . "</td>";

                    echo "<td>" . number_format($row["leaveqty"], 2) . "</td>";

                    $leavestatus = is_null($row["leavestatus"]) ? '&nbsp' : $row["leavestatus"];
                    echo "<td>" . $leavestatus . "</td>";

                    echo "<td>" . number_format($row["ob"], 2) . "</td>";

                    $remarks = is_null($row["remarks"]) ? '&nbsp' : $row["remarks"];
                    echo "<td>" . $remarks . "</td>";

                    echo "</tr>";

                    $rowCount++;
                }

                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-danger mt-3' role='alert'>";
                echo "No records found.";
                echo  "</div>";
            }

            break;

        case "option2";
            $selQry = "SELECT TRIM(departmentname) as departmentname FROM `department` WHERE departmentid = ? AND isactive = 1";
            $prmSel = array($_SESSION['departmentId']);
            $resSel = $db1->select($selQry, "s", $prmSel);

            if (!empty($resSel)) {
                foreach ($resSel as $row) {
                    $selQry2 = "SELECT * FROM `dailytimerecord` WHERE TRIM(departmentname) = ? AND CAST(date as DATE) between ? AND ?";
                    $prmSel2 = array($row['departmentname'], $startDate, $endDate);
                    $resSel2 = $db1->select($selQry2, "sss", $prmSel2);

                    if (!empty($resSel2)) {
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-sm table-hover table-bordered text-center' style='vertical-align: middle'>";
                        echo "<thead>";
                        echo "<tr>";

                        echo "<th>Employee Code</th>";
                        echo "<th>EmployeeName</th>";
                        echo "<th>Date</th>";
                        echo "<th>Attendance</th>";
                        echo "<th>Schedule</th>";
                        echo "<th>Day</th>";
                        echo "<th>Time In</th>";
                        echo "<th>Time Out</th>";
                        echo "<th>Reg Hours</th>";
                        echo "<th>Tardy</th>";
                        echo "<th>Undertime</th>";
                        echo "<th>ND</th>";
                        echo "<th>OT</th>";
                        echo "<th>NDOT</th>";
                        echo "<th>Leave</th>";
                        echo "<th>Qty</th>";
                        echo "<th>Status</th>";
                        echo "<th>OB</th>";
                        echo "<th>Remarks</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        foreach ($resSel2 as $row2) {
                            echo "<tr>";

                            $empCode = is_null($row2["employeecode"]) ? "" : $row2["employeecode"];
                            echo "<td>" . $empCode . "</td>";

                            $empName = is_null($row2["employeename"]) ? "" : $row2["employeename"];
                            echo "<td>" . $empName . "</td>";

                            $formattedDate = date_format(date_create($row2["date"]), 'm/d/Y');
                            echo "<td>" . $formattedDate . "</td>";

                            echo "<td>" . $row2["attendancetype"] . "</td>";
                            echo "<td>" . $row2["dailyschedule"] . "</td>";
                            echo "<td>" . $row2["daytype"] . "</td>";

                            if (number_format($row2["regularhours"], 2) == '0.00') {
                                $strt = is_null($row2["timein"]) ? "" : date('h:i A', strtotime($row2["timein"]));
                                echo "<td>" . "</td>";

                                $end = is_null($row2["timeout"]) ? "" : date('h:i A', strtotime($row2["timeout"]));
                                echo "<td>" . "</td>";
                            } else {
                                $strt = is_null($row2["timein"]) ? "" : date('h:i A', strtotime($row2["timein"]));
                                echo "<td>" . $strt . "</td>";

                                $end = is_null($row2["timeout"]) ? "" : date('h:i A', strtotime($row2["timeout"]));
                                echo "<td>" . $end . "</td>";
                            }

                            echo "<td>" . number_format($row2["regularhours"], 2) . "</td>";
                            echo "<td>" . number_format($row2["tardy"], 2) . "</td>";
                            echo "<td>" . number_format($row2["undertime"], 2) . "</td>";
                            echo "<td>" . number_format($row2["nd"], 2) . "</td>";
                            echo "<td>" . number_format($row2["ot"], 2) . "</td>";
                            echo "<td>" . number_format($row2["ndot"], 2) . "</td>";

                            $leavetype = is_null($row2["leavetype"]) ? "" : $row2["leavetype"];
                            echo "<td>" . $leavetype . "</td>";

                            echo "<td>" . number_format($row2["leaveqty"], 2) . "</td>";

                            $leavestatus = is_null($row2["leavestatus"]) ? "" : $row2["leavestatus"];
                            echo "<td>" . $leavestatus . "</td>";

                            echo "<td>" . number_format($row2["ob"], 2) . "</td>";

                            $remarks = is_null($row2["remarks"]) ? "" : $row2["remarks"];
                            echo "<td>" . $remarks . "</td>";

                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-danger mt-3' role='alert'>";
                        echo "No records found.";
                        echo  "</div>";
                    }
                }
            }

            break;

        case "option3";
            break;

        default:
    }
}

function exportToExcel()
{
    $curDatetime = getdate(date("U"));
    // $nameSuffix = $curDatetime['year'] . $curDatetime['mon'] . $curDatetime['mday'];
    $startDate = testInput($_POST["dtpStartDate"]);
    $endDate = testInput($_POST["dtpEndDate"]);

    $strtDt = date_create($startDate);
    $endDt = date_create($endDate);

    $filename = "DTR_" . date_format($strtDt, 'Ymd') . "_" . date_format($endDt, 'Ymd');

    $db1 = new DbOp(1);

    $selQry = "SELECT * FROM `dailytimerecord` WHERE employeeid=? AND CAST(date as DATE) between ? AND ?";
    $prmSel = array($_SESSION['employeeId'], $startDate, $endDate);
    $resSel = $db1->select($selQry, "sss", $prmSel);

    if ($resSel !== false) {
        // tell the browser it's going to be a csv file
        header('Content-Type: text/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="' . $filename . '.csv";');

        // clean output buffer
        ob_end_clean();

        $f = fopen('php://output', 'w');

        $headers = array('Date', 'Attendance Type', 'Daily Schedule', 'Day Type', 'Time In', 'Time Out', 'Reg Hours', 'Tardy', 'Undertime', 'ND', 'OT', 'NDOT', 'Leave Type', 'Leave Status', 'Leave Qty', 'OB', 'Remarks');
        fputcsv($f, $headers);

        foreach ($resSel as $row) {

            if (number_format($row["regularhours"], 2) == '0.00') {
                $strt = "";
                $end = "";
            } else {
                $strt = is_null($row["timein"]) ? "" : date('H:i A', strtotime($row["timein"]));
                $end = is_null($row["timeout"]) ? "" : date('H:i A', strtotime($row["timeout"]));
            }

            $formattedDate = date_format(date_create($row["date"]), 'm/d/Y');

            $leavetype = is_null($row["leavetype"]) ? "" : $row["leavetype"];
            $leavestatus = is_null($row["leavestatus"]) ? "" : $row["leavestatus"];
            $remarks = is_null($row["remarks"]) ? "" : $row["remarks"];

            // $rows = [htmlspecialchars($row["Date"]->format('m-d-Y')), $strt, $end, number_format($row["Hours"], 2)];
            $rows = [$formattedDate, $row['attendancetype'], $row['dailyschedule'], $row['daytype'], $strt, $end, number_format($row["regularhours"], 2), number_format($row["tardy"], 2), number_format($row["undertime"], 2), number_format($row["nd"], 2), number_format($row["ot"], 2), number_format($row["ndot"], 2), $leavetype, number_format($row["leaveqty"], 2), $leavestatus, number_format($row["ob"], 2), $remarks];
            fputcsv($f, $rows);
        }
        // make php send the generated csv lines to the browser
        fpassthru($f);

        // flush buffer
        ob_flush();

        // use exit to get rid of unexpected output afterward
        exit();
    }
}

function exportToPdf()
{
    require('../config/fpdf186/fpdf.php');

    $startDate = testInput($_POST["dtpStartDate"]);
    $endDate = testInput($_POST["dtpEndDate"]);

    $db1 = new DbOp(1);

    $selQry = "SELECT * FROM `dailytimerecord` WHERE employeeid=? AND CAST(date as DATE) between ? AND ?";
    $prmSel = array($_SESSION['employeeId'], $startDate, $endDate);
    $resSel = $db1->select($selQry, "sss", $prmSel);

    $strtDt = date_create($startDate);
    $endDt = date_create($endDate);

    $GLOBALS["periodCovered"] = date_format($strtDt, 'F d, Y') . " - " . date_format($endDt, 'F d, Y');

    // use function args to put title
    class PDF extends FPDF
    {
        // pdf header
        public function Header()
        {
            // use this to put company logo
            // $this->Image('img/nbc.jpg', 5, 10, 25, 10);
            $this->SetFont('Arial', 'B', 14);
            // move to the right
            // $this->Cell(80, 0);
            // document title
            $this->Cell(416, 10, 'Daily Time Record', 0, 0, 'C');
            // line break
            $this->Ln(13);
            // $this->Cell(50, 0);
            $this->Cell(416, 0, $GLOBALS["periodCovered"], 0, 0, 'C');
            $this->Ln(5);

            // 416 is the sum of cell width of the table below
        }

        // pdf footer
        function Footer()
        {
            // position at 1.5 cm from bottom
            $this->SetY(-15);
            // means arial italic font with 8 size
            $this->SetFont('Arial', 'I', 12);
            // page number
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }

        // Custom MultiCell for wrapping text in a table cell
        function WrapCell($w, $h, $txt, $border = 0, $align = 'C')
        {
            // This calculates how many lines of text need to fit within the given width
            $this->MultiCell($w, $h, $txt, $border, $align);
        }
    }

    if ($resSel !== false) {
        // $curDatetime = getdate(date("U"));
        // $nameSuffix = $curDatetime['year'] . $curDatetime['mon'] . $curDatetime['mday'];
        // $filename = "DTR_" . $nameSuffix . '.pdf';

        $pdf = new PDF();
        $pdf->AddPage('L', 'A3'); // Landscape A3
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetMargins(2, 5, 2); // Set margins to avoid cutting content
        $pdf->Ln();

        // use this to put headers from array with the same column size
        // $headers = array('Date', 'Time In', 'Time Out', 'Hours');
        // foreach ($headers as $head) {
        //     $pdf->Cell(50, 12, $head);
        // }

        // 416 sum of all cell width
        $lMargin = ($pdf->GetPageWidth() - 416) / 2;
        $pdf->SetLeftMargin($lMargin);

        // Adjusted table header widths to fit page
        $pdf->Cell(25, 8, 'Date', 1, 0, 'C');
        $pdf->Cell(25, 8, 'Attendance', 1, 0, 'C');
        $pdf->Cell(50, 8, 'Daily Schedule', 1, 0, 'C');
        $pdf->Cell(50, 8, 'Day Type', 1, 0, 'C');
        $pdf->Cell(25, 8, 'Time In', 1, 0, 'C');
        $pdf->Cell(25, 8, 'Time Out', 1, 0, 'C');
        $pdf->Cell(18, 8, 'Reg Hours', 1, 0, 'C');
        $pdf->Cell(18, 8, 'Tardy', 1, 0, 'C');
        $pdf->Cell(18, 8, 'Undertime', 1, 0, 'C');
        $pdf->Cell(18, 8, 'ND', 1, 0, 'C');
        $pdf->Cell(18, 8, 'OT', 1, 0, 'C');
        $pdf->Cell(18, 8, 'NDOT', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Leave Type', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Leave Status', 1, 0, 'C');
        $pdf->Cell(18, 8, 'OB', 1, 0, 'C');
        $pdf->Cell(30, 8, 'Remarks', 1, 0, 'C');
        $pdf->Ln();

        foreach ($resSel as $row) {
            if (number_format($row["regularhours"], 2) == '0.00') {
                $strt = "";
                $end = "";
            } else {
                $strt = is_null($row["timein"]) ? "" : date('H:i A', strtotime($row["timein"]));
                $end = is_null($row["timeout"]) ? "" : date('H:i A', strtotime($row["timeout"]));
            }

            $formattedDate = date_format(date_create($row["date"]), 'm/d/Y');
            $pdf->Cell(25, 8, htmlspecialchars($formattedDate), 1, 0, 'C');

            $pdf->Cell(25, 8, $row['attendancetype'], 1, 0, 'C');
            $pdf->Cell(50, 8, $row['dailyschedule'], 1, 0, 'C');
            $pdf->Cell(50, 8, $row['daytype'], 1, 0, 'C');

            $pdf->Cell(25, 8, $strt, 1, 0, 'C');
            $pdf->Cell(25, 8, $end, 1, 0, 'C');

            $pdf->Cell(18, 8, number_format($row["regularhours"], 2), 1, 0, 'C');
            $pdf->Cell(18, 8, number_format($row["tardy"], 2), 1, 0, 'C');
            $pdf->Cell(18, 8, number_format($row["undertime"], 2), 1, 0, 'C');
            $pdf->Cell(18, 8, number_format($row["nd"], 2), 1, 0, 'C');
            $pdf->Cell(18, 8, number_format($row["ot"], 2), 1, 0, 'C');
            $pdf->Cell(18, 8, number_format($row["ndot"], 2), 1, 0, 'C');

            $leavetype = is_null($row["leavetype"]) ? "" : $row["leavetype"];
            $pdf->Cell(30, 8,  $leavetype, 1, 0, 'C');

            $leavestatus = is_null($row["leavestatus"]) ? "" : $row["leavestatus"];
            $pdf->Cell(30, 8, $leavestatus, 1, 0, 'C');

            $pdf->Cell(18, 8, number_format($row["ob"], 2), 1, 0, 'C');

            $remarks = is_null($row["remarks"]) ? "" : $row["remarks"];
            $pdf->Cell(30, 8, $remarks, 1, 0, 'C');

            $pdf->Ln();
        }

        ob_end_clean();

        // auto open pdf
        $pdf->Output();

        // use this to download the pdf instead
        // $pdf->Output('D', $filename);
    }
}

?>


<?php
$content = ob_get_clean(); // capture the buffer into a variable and clean the buffer
include('../config/master.php');
?>