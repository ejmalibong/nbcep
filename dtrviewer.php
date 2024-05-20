<?php
$title = "DTR Viewer";
ob_start(); // start output buffering

require "config/dbcon.php";
require "header.php";

$msgPrompt = '';

?>

<div class="container text-center mt-5">
    <form id="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="row g-3 align-items-center input-group mb-2">
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
        </div>
        <div class="row g-3 align-items-center input-group mb-2">
            <div class="col-auto">
                <label for="dtpStartDate" class="col-form-label">Start Date</label>
            </div>
            <div class="col-auto">
                <input type="date" id="dtpStartDate" name="dtpStartDate" class="form-control" value="<?php echo date('2024-04-01'); ?>">
            </div>
            <div class="col-auto">
                <label for="dtpEndDate" class="col-form-label">End Date</label>
            </div>
            <div class="col-auto">
                <input type="date" id="dtpEndDate" name="dtpEndDate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['btnGenerate'])) {
                $startDate = "";
                $endDate = "";

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

                $selQry = "SELECT Date, TimeIn, TimeOut, Hours FROM dbo.tblDailyTimeRecord WHERE EmployeeId=? AND CAST(DATE AS DATE) BETWEEN ? AND ?";
                $params = array($_SESSION['employeeId'], $startDate, $endDate);
                $res = execQuery(2, 1, $selQry, $params);

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
            } else if (isset($_POST['btnExpExcel'])) {
                $curDatetime = getdate(date("U"));
                $nameSuffix = $curDatetime['year'] . $curDatetime['mon'] . $curDatetime['mday'] . "_" . $curDatetime['hours'] . $curDatetime['minutes'] . $curDatetime['seconds'];
                $filename = "Daily Time Record_" . $nameSuffix . ".csv";
            } else if (isset($_POST['btnExpPdf'])) {
                $msgPrompt = "pdf.";
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
    // $selQry = "SELECT Date, TimeIn, TimeOut, Hours FROM dbo.tblDailyTimeRecord WHERE EmployeeId=? AND CAST(DATE AS DATE) BETWEEN ? AND ?";
    // $params = array($_SESSION['employeeId'], $startDate, $endDate);
    // $res = sqlsrv_prepare($connJs, $selQry, $params);

    // if ($res === false) {
    //     echo (FormatErrors(sqlsrv_errors(), true));
    // }

    // if (sqlsrv_execute($res) === false) {
    //     echo (FormatErrors(sqlsrv_errors(), true));
    // }


}

?>


<?php
$content = ob_get_clean(); // capture the buffer into a variable and clean the buffer
include('master.php');
?>