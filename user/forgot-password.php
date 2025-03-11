<?php
require_once '../config/dbop.php';
require_once '../config/method.php';
require_once '../config/mail.php';

$errorPrompt = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new DbOp(1);
    $employeeCode = testInput($_POST['txtEmployeeCode']);
    $userEmailAddress = testInput($_POST['txtEmailAddress']);

    // Fetch employee data
    $qry = "SELECT emailaddress FROM employee WHERE employeecode = ?";
    $result = $db->select($qry, "s", [$employeeCode]);

    if ($result && count($result) > 0) {
        $registeredEmailAddress = $result[0]['emailaddress'];

        if ($userEmailAddress === $registeredEmailAddress) {
            header("Location: reset-password.php?employeeCode=$employeeCode");
            exit();

            // Password recovery via sending of email temporary disabled

            // // Generate reset code
            // $resetCode = generateResetCode();

            // // Store the reset code in the database
            // $updQry = "UPDATE employee SET passwordresetcode = ? WHERE employeecode = ?";
            // $db->update($updQry, "ss", [$resetCode, $employeeCode]);

            // // Send email - disabled temporary
            // $subject = "Password Reset Code";
            // $message = "Your password reset code is: $resetCode";
            // if (sendEmail($userEmailAddress, $subject, $message)) {
            //     header("Location: reset-password.php?employeeCode=$employeeCode");
            //     exit();
            // } else {
            //     $errorPrompt = "Failed to send email. Please try again.";
            // }
        } else {
            $errorPrompt = "The entered email does not match our records.";
        }
    } else {
        $errorPrompt = "Employee not found.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Forgot Password</title>
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
                        <h4>Forgot Password</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="employeeCode" class="form-label">Employee ID</label>
                                <input type="text" class="form-control" name="txtEmployeeCode" id="employeeCode" required>
                            </div>
                            <div class="mb-3">
                                <label for="emailAddress" class="form-label">Registered Personal Email Address</label>
                                <input type="email" class="form-control" name="txtEmailAddress" id="emailAddress" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                                <a href="../index.php" class="btn btn-secondary">Back to Login</a>
                            </div>
                            <?php if ($errorPrompt) : ?>
                                <div class="alert alert-danger mt-3"><?php echo $errorPrompt; ?></div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
require_once '../config/footer.php';
?>