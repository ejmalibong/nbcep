<?php

$title = "DTR Viewer";
ob_start(); // start output buffering

require_once "../config/dbop.php";
require_once "../config/header.php";

$errorPrompt = '';
$successPrompt = '';
$db1 = new DbOp(1);

?>

<div class="container mt-5">
    <form id="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="row mb-3">
            <label for="txtNewPassword" class="col-form-label">New Password</label>
            <div class="col-12">
                <input type="password" id="txtNewPassword" name="txtNewPassword" class="form-control" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="txtConfirmPassword" class="col-form-label">Confirm New Password</label>
            <div class="col-12">
                <input type="password" id="txtConfirmPassword" name="txtConfirmPassword" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary form-control" name="btnChangePassword" value="btnChangePassword">Change Password</button>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                $newPassword = testInput($_POST["txtNewPassword"]);
                $confirmPassword = testInput($_POST["txtConfirmPassword"]);

                if (isset($_POST['btnChangePassword'])) {
                    if (empty($newPassword) || is_null($newPassword)) {
                        $errorPrompt = "Please input your new password.";
                    }

                    if (empty($confirmPassword) || is_null($confirmPassword)) {
                        $errorPrompt = "Please input again your new password.";
                    }

                    if (strcmp($newPassword, $confirmPassword) !== 0) {
                        $errorPrompt = "Password do not match.";
                    } else {
                        $affected = 0;
                        $hash_password = password_hash($confirmPassword, PASSWORD_DEFAULT);
                        $updQry = "UPDATE `employee` SET `password`=?, `passwordhash`=?, `isdefaultpassword`=? WHERE `employeeid`=?";
                        $prmUpd = array($confirmPassword, $hash_password, 0, $_SESSION['employeeId']);
                        $affected = $db1->update($updQry, "ssii", $prmUpd);

                        if ($affected > 0) {
                            $successPrompt = "Password changed successfully.";
                        }
                    }
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