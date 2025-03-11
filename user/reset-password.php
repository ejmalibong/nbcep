<?php
require_once '../config/dbop.php';
require_once '../config/method.php';

$errorPrompt = "";
$successPrompt = "";

$db = new DbOp(1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employeeCode = testInput($_POST['txtEmployeeCode']);

    if (isset($_POST['btnBackToLogin'])) {
        // Set passwordresetcode to NULL
        $updQry = "UPDATE employee SET passwordresetcode = NULL WHERE employeeCode = ?";
        $db->update($updQry, "s", [$employeeCode]);

        // Redirect to login
        header("Location: ../index.php");
        exit();
    }

    // $resetCode = testInput($_POST['txtResetCode']);
    $newPassword = testInput($_POST['txtNewPassword']);
    $confirmPassword = testInput($_POST['txtConfirmPassword']);

    if ($newPassword === $confirmPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updQry = "UPDATE employee SET password = ?, passwordhash = ?, passwordresetcode = NULL, isdefaultpassword = 0 WHERE employeeCode = ?";
        $db->update($updQry, "sss", [$newPassword, $hashedPassword, $employeeCode]);

        $successPrompt = "Password changed successfully.";

        // Redirect to login after 2 seconds
        echo "<script>
                    setTimeout(function() {
                        window.location.href = '../index.php';
                    }, 2000);
                </script>";
    } else {
        $errorPrompt = "Passwords do not match.";
    }

    // // Check if the reset code matches
    // $qry = "SELECT passwordresetcode FROM employee WHERE employeeCode = ?";
    // $result = $db->select($qry, "s", [$employeeCode]);

    // if ($result && count($result) > 0 && $result[0]['passwordresetcode'] == $resetCode) {
    //     if ($newPassword === $confirmPassword) {
    //         $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    //         $updQry = "UPDATE employee SET password = ?, passwordhash = ?, passwordresetcode = NULL, isdefaultpassword = 0 WHERE employeeCode = ?";
    //         $db->update($updQry, "sss", [$newPassword, $hashedPassword, $employeeCode]);

    //         $successPrompt = "Password changed successfully.";

    //         // Redirect to login after 2 seconds
    //         echo "<script>
    //             setTimeout(function() {
    //                 window.location.href = '../index.php';
    //             }, 2000);
    //         </script>";
    //     } else {
    //         $errorPrompt = "Passwords do not match.";
    //     }
    // } else {
    //     $errorPrompt = "Invalid reset code.";
    // }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/responsive-tables.css" />
    <link rel="icon" type="image/png" href="../img/favicon.png">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Reset Password</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <input type="hidden" name="txtEmployeeCode" value="<?php echo $_GET['employeeCode'] ?? ''; ?>">
                            <!--
                            <div class="mb-3">
                                <label class="form-label">Reset Code</label>
                                <input type="text" class="form-control" name="txtResetCode" required>
                            </div>
                            -->
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="txtNewPassword" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" name="txtConfirmPassword" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                                <button type="button" class="btn btn-secondary" onclick="resetCodeAndRedirect()">Back to Login</button>
                            </div>
                            <?php if ($errorPrompt) : ?>
                                <div class="alert alert-danger mt-3"><?php echo $errorPrompt; ?></div>
                            <?php endif; ?>
                            <?php if ($successPrompt) : ?>
                                <div class="alert alert-success mt-3"><?php echo $successPrompt; ?></div>
                                <script>
                                    setTimeout(function() {
                                        window.location.href = "../index.php";
                                    }, 2000);
                                </script>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    function resetCodeAndRedirect() {
        let employeeCode = document.querySelector('input[name="txtEmployeeCode"]').value;
        let form = document.createElement("form");
        form.method = "POST";
        form.action = "";

        let input = document.createElement("input");
        input.type = "hidden";
        input.name = "btnBackToLogin";
        input.value = "1";
        form.appendChild(input);

        let empInput = document.createElement("input");
        empInput.type = "hidden";
        empInput.name = "txtEmployeeCode";
        empInput.value = employeeCode;
        form.appendChild(empInput);

        document.body.appendChild(form);
        form.submit();
    }
</script>

</html>