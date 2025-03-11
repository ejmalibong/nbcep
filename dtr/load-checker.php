<?php

$title = "Canteen Purchases";
ob_start(); // start output buffering

require_once "../config/dbop.php";
require_once "../config/header.php";

$errorPrompt = '';
$successPrompt = '';

$firstDay = date('Y-m-01');
$lastDay = date('Y-m-' . cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')));

?>

<style>
    #cmbSuggestions {
        position: absolute;
        z-index: 1000;
        width: 100%;
        display: none;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        background-color: #fff;
        max-height: 200px;
        overflow-y: auto;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .list-group-item {
        cursor: pointer;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    #txtMember {
        min-width: 100px;
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

        table {
            table-layout: fixed;
            word-wrap: break-word;
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

        th {
            font-weight: normal;
            vertical-align: middle;
        }

        td {
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
            min-width: 50px;
            vertical-align: middle;
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

        /* Label the data */
        td:nth-of-type(1):before {
            content: "#";
        }

        td:nth-of-type(2):before {
            content: "Date";
        }

        td:nth-of-type(3):before {
            content: "Receipt No";
        }

        td:nth-of-type(4):before {
            content: "Product Name";
        }

        td:nth-of-type(5):before {
            content: "Price";
        }

        td:nth-of-type(6):before {
            content: "Qty";
        }

        td:nth-of-type(7):before {
            content: "Amount";
        }
    }
</style>

<form id="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="container-fluid">
        <div class="bg-body p-3 rounded">
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

            <div class="row g-3 align-items-center input-group mb-2">
                <div class="col-auto">
                    <button type="submit" class='btn btn-primary' id="btnGenerate" value="btnGenerate" name="btnGenerate">
                        Generate Purchases
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
                        generateDeduction();
                    } else if (isset($_POST['btnExpExcel'])) {
                        exportToExcel();
                    } else if (isset($_POST['btnExpPdf'])) {
                        exportToPdf();
                    }
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
        </div>
    </div>
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

    //enabling and disabling the search member field
    function toggleDtrOption() {
        const selectedValue = document.querySelector('input[name="dtrOption"]:checked').value
        const inputField = document.getElementById("txtMember");

        if (selectedValue === "option3") {
            inputField.disabled = false;
        } else {
            inputField.disabled = true;
        }
    }
</script>

<?php

function generateDeduction()
{
    $startDate = testInput($_POST["dtpStartDate"]);
    $endDate = testInput($_POST["dtpEndDate"]);

    $db3 = new DbOp(3);

    $rfid = 0;
    $totalAmount = 0;

    $selQry1 = "SELECT rfid_no FROM `members` WHERE emp_no=? LIMIT 1";
    $prmSel1 = array(testInput($_SESSION['employeeCode']));
    $resSel1 = $db3->select($selQry1, "s", $prmSel1);

    if (!empty($resSel1)) {
        foreach ($resSel1 as $rowSel1) {
            $rfid = $rowSel1['rfid_no'];
        }
    }

    $selQry2 = "SELECT SUM(amount) as totalamount FROM `transactions` WHERE rfid_no=? AND dttm BETWEEN ? AND ? AND active=? AND product_name <> ? AND product_name <> ?";
    $prmSel2 = array($rfid, $startDate, $endDate, 2, 'BREAKFAST', 'LUNCH');
    $resSel2 = $db3->select($selQry2, "sssiss", $prmSel2);

    if (!empty($resSel2)) {
        foreach ($resSel2 as $rowSel2) {
            $totalAmount = $rowSel2['totalamount'];
        }
    }

    $selQry = "SELECT * FROM `transactions` WHERE rfid_no=? AND dttm BETWEEN ? AND ? AND active=? AND product_name <> ? AND product_name <> ? ORDER BY dttm desc";
    $prmSel = array($rfid, $startDate, $endDate, 2, 'BREAKFAST', 'LUNCH');
    $resSel = $db3->select($selQry, "sssiss", $prmSel);

    if (!empty($resSel)) {

        echo "<div class='row table-responsive' style='overflow-x: auto;'>";
        echo "<div class='col'>";
        echo "<div class='card'>";
        echo "<div class='card-body' style='overflow-x: auto;'>";
        echo "<div class='card-title' style='font-size:120%'>TOTAL AMOUNT PURCHASED: ₱ " . number_format($totalAmount, 2) . "</div>";
        echo "<table class='table table-sm table-striped table-hover table-mobile-responsive table-mobile-sided caption-top'>";
        echo "<thead>";
        echo "<tr>";

        echo "<th scope='col' style='width: 50px;'>#</th>";
        echo "<th scope='col' style='width: 120px;'>Date</th>";
        echo "<th scope='col' style='width: 100px;'>Receipt No</th>";
        echo "<th scope='col' style='width: 250px;'>Product Name</th>";
        echo "<th scope='col' style='width: 80px;'>Price</th>";
        echo "<th scope='col' style='width: 80px;'>Qty</th>";
        echo "<th scope='col' style='width: 120px;'>Amount</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $rowCount = 1;

        foreach ($resSel as $row) {
            echo "<tr>";

            echo "<td scope='row' data-content='#'>" . $rowCount . "</td>";
            echo "<td>" . $row['dttm'] . "</td>";
            echo "<td>" . $row['receipt'] . "</td>";
            echo "<td>" . $row['product_name'] . "</td>";

            echo "<td>" . number_format($row["price"], 2) . "</td>";
            echo "<td>" . $row["qty"] . "</td>";
            echo "<td>" . number_format($row["amount"], 2) . "</td>";

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

    return;
}

function exportToExcel()
{
    $startDate = testInput($_POST["dtpStartDate"]);
    $endDate = testInput($_POST["dtpEndDate"]);

    $strtDt = date_create($startDate);
    $endDt = date_create($endDate);

    $filename = "Deduction_" . date_format($strtDt, 'Ymd') . "_" . date_format($endDt, 'Ymd');

    $db3 = new DbOp(3);

    $rfid = 0;
    $totalAmount = 0;

    $selQry1 = "SELECT rfid_no FROM `members` WHERE emp_no=? LIMIT 1";
    $prmSel1 = array(testInput($_SESSION['employeeCode']));
    $resSel1 = $db3->select($selQry1, "s", $prmSel1);

    if (!empty($resSel1)) {
        foreach ($resSel1 as $rowSel1) {
            $rfid = $rowSel1['rfid_no'];
        }
    }

    $selQry2 = "SELECT SUM(amount) as totalamount FROM `transactions` WHERE rfid_no=? AND dttm BETWEEN ? AND ? AND active=? AND product_name <> ? AND product_name <> ?";
    $prmSel2 = array($rfid, $startDate, $endDate, 2, 'BREAKFAST', 'LUNCH');
    $resSel2 = $db3->select($selQry2, "sssiss", $prmSel2);

    if (!empty($resSel2)) {
        foreach ($resSel2 as $rowSel2) {
            $totalAmount = $rowSel2['totalamount'];
        }
    }

    $selQry = "SELECT * FROM `transactions` WHERE rfid_no=? AND dttm BETWEEN ? AND ? AND active=? AND product_name <> ? AND product_name <> ? ORDER BY dttm desc";
    $prmSel = array($rfid, $startDate, $endDate, 2, 'BREAKFAST', 'LUNCH');
    $resSel = $db3->select($selQry, "sssiss", $prmSel);

    if (!empty($resSel)) {
        // tell the browser it's going to be a csv file
        header('Content-Type: text/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="' . $filename . '.csv";');

        // clean output buffer
        ob_end_clean();

        $f = fopen('php://output', 'w');

        $headers = array('#', 'Date', 'Receipt No', 'Product Name', 'Price', 'Qty', 'Amount');
        fputcsv($f, $headers);

        $rowCount = 1;

        foreach ($resSel as $row) {
            $price = number_format($row["price"], 2);
            $amount = number_format($row["amount"], 2);

            $rows = [$rowCount, $row['dttm'], $row['receipt'], $row['product_name'], $price, $row['qty'], $amount];
            fputcsv($f, $rows);

            $rowCount++;
        }

        $rowTotal = ['', '', '', '', '', 'TOTAL AMOUNT PURCHASED: ', number_format($totalAmount, 2)];
        fputcsv($f, $rowTotal);

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

    $strtDt = date_create($startDate);
    $endDt = date_create($endDate);

    $db3 = new DbOp(3);

    $rfid = 0;
    $totalAmount = 0;

    $selQry1 = "SELECT rfid_no FROM `members` WHERE emp_no=? LIMIT 1";
    $prmSel1 = array(testInput($_SESSION['employeeCode']));
    $resSel1 = $db3->select($selQry1, "s", $prmSel1);

    if (!empty($resSel1)) {
        foreach ($resSel1 as $rowSel1) {
            $rfid = $rowSel1['rfid_no'];
        }
    }

    $selQry2 = "SELECT SUM(amount) as totalamount FROM `transactions` WHERE rfid_no=? AND dttm BETWEEN ? AND ? AND active=? AND product_name <> ? AND product_name <> ?";
    $prmSel2 = array($rfid, $startDate, $endDate, 2, 'BREAKFAST', 'LUNCH');
    $resSel2 = $db3->select($selQry2, "sssiss", $prmSel2);

    if (!empty($resSel2)) {
        foreach ($resSel2 as $rowSel2) {
            $totalAmount = $rowSel2['totalamount'];
        }
    }

    $selQry = "SELECT * FROM `transactions` WHERE rfid_no=? AND dttm BETWEEN ? AND ? AND active=? AND product_name <> ? AND product_name <> ? ORDER BY dttm desc";
    $prmSel = array($rfid, $startDate, $endDate, 2, 'BREAKFAST', 'LUNCH');
    $resSel = $db3->select($selQry, "sssiss", $prmSel);

    $GLOBALS["periodCovered"] = date_format($strtDt, 'F d, Y') . " - " . date_format($endDt, 'F d, Y');

    // use function args to put title
    class PDF extends FPDF
    {
        // pdf header
        public function Header()
        {
            // use this to put company logo
            // $this->Image('img/nbc.jpg', 5, 10, 25, 10);
            $this->SetFont('Arial', 'B', 10);
            // document title
            $this->Cell(0, 10, 'Canteen Deductions', 0, 0, 'C');
            // line break
            $this->Ln(13);
            // $this->Cell(50, 0);
            $this->Cell(0, 0, $GLOBALS["periodCovered"], 0, 0, 'C');
            $this->Ln(5);
        }

        // pdf footer
        function Footer()
        {
            // position at 1.5 cm from bottom
            $this->SetY(-15);
            // means arial italic font with 8 size
            $this->SetFont('Arial', 'I', 10);
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

        $strtDt = date_create($startDate);
        $endDt = date_create($endDate);
        $filename = "Deduction_" . date_format($strtDt, 'Ymd') . "_" . date_format($endDt, 'Ymd') . '.pdf';

        $pdf = new PDF();
        $pdf->AddPage('L', 'A4'); // Landscape A3
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetMargins(2, 5, 2); // Set margins to avoid cutting content
        $pdf->Ln();

        $lMargin = ($pdf->GetPageWidth() - 255) / 2;
        $pdf->SetLeftMargin($lMargin);

        // Adjusted table header widths to fit page
        $pdf->Cell(45, 8, 'Date', 1, 0, 'C');
        $pdf->Cell(35, 8, 'Receipt No', 1, 0, 'C');
        $pdf->Cell(120, 8, 'Product Name', 1, 0, 'C');
        $pdf->Cell(20, 8, 'Price', 1, 0, 'C');
        $pdf->Cell(15, 8, 'Qty', 1, 0, 'C');
        $pdf->Cell(20, 8, 'Amount', 1, 0, 'C');
        $pdf->Ln();

        foreach ($resSel as $row) {
            $pdf->Cell(45, 8, $row["dttm"], 1, 0, 'C');
            $pdf->Cell(35, 8, $row["receipt"], 1, 0, 'C');
            $pdf->Cell(120, 8, $row["product_name"], 1, 0, 'C');
            $pdf->Cell(20, 8, number_format($row["price"], 2), 1, 0, 'C');
            $pdf->Cell(15, 8, $row["qty"], 1, 0, 'C');
            $pdf->Cell(20, 8, number_format($row["amount"], 2), 1, 0, 'C');
            $pdf->Ln();
        }

        $pdf->Cell(255, 8, "TOTAL AMOUNT PURCHASED : " . number_format($totalAmount, 2), 1, 0, 'R');
        $pdf->Ln();

        // tell the browser it's going to be a csv file
        header('Content-Type: application/pdf');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="' . $filename . '.pdf";');

        // clean output buffer
        ob_end_clean();

        // auto open pdf
        // $pdf->Output();

        // use this to download the pdf instead
        $pdf->Output('D', $filename);
    }
}

?>

<?php
$content = ob_get_clean(); // capture the buffer into a variable and clean the buffer
include('../config/master.php');
?>