<?php

$title = "DTR Viewer";
ob_start(); // start output buffering

require_once "config/dbop.php";
require_once "config/dbsql.php";
require_once "header.php";

$msgPrompt = '';

?>

<div class="container text-center mt-5">
    <form id="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <!-- <div class="row g-3 align-items-center input-group mb-2">
            <div class="col-auto form-check form-check-inline">
                <input class="form-check-input" type="radio" name="dtrOption" id="rdMyDtr" value="option1" checked>
                <label class="form-check-label" for="rdMyDtr">
                    My DTR
                </label>
            </div>
            <div class="col-auto form-check form-check-inline">
                <input class="form-check-input" type="radio" name="dtrOption" id="rdDeptMembers" value="option2">
                <label class="form-check-label" for="rdDeptMembers">
                    All Department Members
                </label>
            </div>
            <div class="col-auto form-check-inline">
                <div class="input-group mb-1">
                    <div class="input-group-text">
                        <input class="form-check-input" type="radio" value="option3" id="rdSearchMember" name="dtrOption">
                        <label class="d-block mx-2" for="rdSearchMember">
                            Search Member
                        </label>
                    </div>
                    <input type="text" class="form-control form-control">
                </div>
            </div>
        </div> -->
        <div class="row g-3 align-items-center input-group mb-2">
            <div class="col-auto">
                <label for="dtpStartDate" class="col-form-label">Start Date</label>
            </div>
            <div class="col-auto">
                <input type="date" id="dtpStartDate" name="dtpStartDate" class="form-control">
            </div>
            <div class="col-auto">
                <label for="dtpEndDate" class="col-form-label">End Date</label>
            </div>
            <div class="col-auto">
                <input type="date" id="dtpEndDate" name="dtpEndDate" class="form-control">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary form-control" name="btnGenerate" value="btnGenerate">Generate DTR</button>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary form-control" name="btnExpExcel" value="btnExpExcel">Export to Excel</button>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary form-control" name="btnExpPdf" value="btnExpPdf">Export to PDF</button>
            </div>
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $startDate = testInput($_POST["dtpStartDate"]);
            $endDate = testInput($_POST["dtpEndDate"]);

            if (empty($startDate) or is_null($startDate)) {
                $msgPrompt = "Start date is empty.";
            }

            if (empty($endDate) or is_null($endDate)) {
                $msgPrompt = "End date is empty.";
            }

            if ($startDate > $endDate) {
                $msgPrompt = "Start date is later than to end date.";
            }

            if (empty($msgPrompt)) {
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
        <?php if ($msgPrompt) : ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo $msgPrompt; ?>
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

function generateDtr()
{
    $startDate = testInput($_POST["dtpStartDate"]);
    $endDate = testInput($_POST["dtpEndDate"]);

    $dbsql1 = new DbSql(2);

    $selQry = "SELECT Date, TimeIn, TimeOut, Hours FROM dbo.tblDailyTimeRecord WHERE EmployeeId=? AND CAST(DATE AS DATE) BETWEEN ? AND ?";
    $prm = array($_SESSION['employeeId'], $startDate, $endDate);
    $res = $dbsql1->rdQuery(2, $selQry, $prm);

    echo "<div class='container'>";
    echo "<div class='row-fluid'>";

    echo "<div class='col-xs-6'>";
    echo "<div class='table-responsive'>";

    echo "<table class='table table-hover table-inverse'>";

    echo "<tr>";
    echo "<th>DATE</th>";
    echo "<th>TIME IN</th>";
    echo "<th>TIME OUT</th>";
    echo "<th>HOURS</th>";
    echo "</tr>";

    if ($res !== false) {
        foreach ($res as $row) {
            echo "<tr>";
            echo "<td>" . $row["Date"]->format('m-d-Y') . "</td>";

            $strt = is_null($row["TimeIn"]) ? "" : $row["TimeIn"]->format('m-d-Y H:i:s');
            $end = is_null($row["TimeOut"]) ? "" : $row["TimeOut"]->format('m-d-Y H:i:s');

            echo "<td>" . $strt . "</td>";
            echo "<td>" . $end . "</td>";
            echo "<td>" . number_format($row["Hours"], 2) . "</td>";
            echo "</tr>";
        }
    }

    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

function exportToExcel()
{
    $curDatetime = getdate(date("U"));
    $nameSuffix = $curDatetime['year'] . $curDatetime['mon'] . $curDatetime['mday'];
    $filename = "DTR_" . $nameSuffix;

    $startDate = testInput($_POST["dtpStartDate"]);
    $endDate = testInput($_POST["dtpEndDate"]);

    $dbsql1 = new DbSql(2);

    $selQry = "SELECT Date, TimeIn, TimeOut, Hours FROM dbo.tblDailyTimeRecord WHERE EmployeeId=? AND CAST(DATE AS DATE) BETWEEN ? AND ?";
    $prm = array($_SESSION['employeeId'], $startDate, $endDate);
    $res = $dbsql1->rdQuery(2, $selQry, $prm);

    if ($res !== false) {
        // tell the browser it's going to be a csv file
        header('Content-Type: text/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="' . $filename . '.csv";');

        // clean output buffer
        ob_end_clean();

        $f = fopen('php://output', 'w');

        $headers = array('Date', 'TimeIn', 'TimeOut', 'Hours');
        fputcsv($f, $headers);

        foreach ($res as $row) {
            $strt = is_null($row["TimeIn"]) ? "" : htmlspecialchars($row["TimeIn"]->format('m-d-Y H:i:s'));
            $end = is_null($row["TimeOut"]) ? "" : htmlspecialchars($row["TimeOut"]->format('m-d-Y H:i:s'));

            $rows = [htmlspecialchars($row["Date"]->format('m-d-Y')), $strt,  $end, number_format($row["Hours"], 2)];
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
    require('config/fpdf186/fpdf.php');

    $startDate = testInput($_POST["dtpStartDate"]);
    $endDate = testInput($_POST["dtpEndDate"]);

    $dbsql1 = new DbSql(2);

    $selQry = "SELECT Date, TimeIn, TimeOut, Hours FROM dbo.tblDailyTimeRecord WHERE EmployeeId=? AND CAST(DATE AS DATE) BETWEEN ? AND ?";
    $prm = array($_SESSION['employeeId'], $startDate, $endDate);
    $res = $dbsql1->rdQuery(2, $selQry, $prm);

    $strtDt = date_create($startDate);
    $endDt = date_create($endDate);

    $GLOBALS["periodCovered"] = date_format($strtDt, 'F d, Y') . " - " . date_format($endDt, 'F d, Y');

    //use function args to put title
    class PDF extends FPDF
    {
        // pdf header
        public function Header()
        {
            // use this to put company logo
            // $this->Image('img/nbc.jpg', 5, 10, 25, 10);
            $this->SetFont('Arial', 'B', 13);
            // move to the right
            $this->Cell(50, 0);
            // document title
            $this->Cell(80, 10, 'Daily Time Record', 0, 0, 'C');
            // line break
            $this->Ln(13);
            $this->Cell(50, 0);
            $this->Cell(80, 0, $GLOBALS["periodCovered"], 0, 0, 'C');
            $this->Ln(5);
        }

        // pdf footer
        function Footer()
        {
            // position at 1.5 cm from bottom
            $this->SetY(-15);
            // means arial italic font with 8 size
            $this->SetFont('Arial', 'I', 8);
            // page number
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }

    if ($res !== false) {
        $curDatetime = getdate(date("U"));
        $nameSuffix = $curDatetime['year'] . $curDatetime['mon'] . $curDatetime['mday'];
        $filename = "DTR_" . $nameSuffix . '.pdf';

        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Ln();

        // use this to put headers from array with the same column size
        // $headers = array('Date', 'Time In', 'Time Out', 'Hours');
        // foreach ($headers as $head) {
        //     $pdf->Cell(50, 12, $head);
        // }

        // table header
        $pdf->Cell(40, 10, 'Date', 1, 0, 'C');
        $pdf->Cell(50, 10, 'Time In', 1, 0, 'C');
        $pdf->Cell(50, 10, 'Time Out', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Hours', 1, 0, 'C');
        $pdf->Ln();

        foreach ($res as $row) {
            $strt = is_null($row["TimeIn"]) ? "" : htmlspecialchars($row["TimeIn"]->format('m-d-Y H:i:s'));
            $end = is_null($row["TimeOut"]) ? "" : htmlspecialchars($row["TimeOut"]->format('m-d-Y H:i:s'));

            $pdf->Cell(40, 12, htmlspecialchars($row["Date"]->format('m-d-Y')), 1, 0, 'C');
            $pdf->Cell(50, 12, $strt, 1, 0, 'C');
            $pdf->Cell(50, 12, $end, 1, 0, 'C');
            $pdf->Cell(40, 12, number_format($row["Hours"], 2), 1, 0, 'C');
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
include('master.php');
?>