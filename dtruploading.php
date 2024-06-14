<?php

$title = "DTR Uploading";
ob_start(); // start output buffering

require_once "config/dbop.php";
require_once "config/dbsql.php";
require_once "header.php";

$msgPrompt = '';

?>

<div class="container text-center mt-5">
    <form id="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="row g-3 align-items-center input-group mb-2">
            <div class="col-auto">
                <button type="submit" class="btn btn-primary form-control" name="btnUpload" value="btnUpload">Upload DTR</button>
            </div>
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // $startDate = testInput($_POST["dtpStartDate"]);
            // $endDate = testInput($_POST["dtpEndDate"]);

            // if (empty($startDate) or is_null($startDate)) {
            //     $msgPrompt = "Start date is empty.";
            // }

            // if (empty($endDate) or is_null($endDate)) {
            //     $msgPrompt = "End date is empty.";
            // }

            // if ($startDate > $endDate) {
            //     $msgPrompt = "Start date is later than to end date.";
            // }

            // if (empty($msgPrompt)) {
            //     if (isset($_POST['btnGenerate'])) {
            //         generateDtr();
            //     } else if (isset($_POST['btnExpExcel'])) {
            //         exportToExcel();
            //     } else if (isset($_POST['btnExpPdf'])) {
            //         exportToPdf();
            //     }
            // }
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
$content = ob_get_clean(); // capture the buffer into a variable and clean the buffer
include('master.php');
?>