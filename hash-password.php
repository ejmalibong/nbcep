<?php

require_once "config/dbop.php";
require_once "config/method.php";

$msgPrompt = '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Hash Password</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sticky-top-custom {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: #fff;
            padding: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            padding: 20px;
            font-size: 1.5em;
        }

        .log-box {
            height: 200px;
            border: 1px solid #ccc;
            padding: 10px;
            overflow-y: auto;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <form id="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="container">
            <div class="row sticky-top-custom">
                <div class="col-6">
                    <button class="btn btn-primary btn-custom w-100" name="btnHash">Hash All Password</button>
                </div>
                <div class="col-6">
                    <button class="btn btn-secondary btn-custom w-100" name="btnBack">Back to Login</button>
                </div>
            </div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                try {
                    if (isset($_POST['btnHash'])) {
                        $selQry = "SELECT EmployeeId, Password FROM employee";

                        $db1 = new DbOp(1);
                        $res = $db1->select($selQry);

                        $affected = 0;

                        if (!empty($res)) {
                            foreach ($res as $row) {
                                $id = $row['EmployeeId'];
                                $password = $row['Password'];
                                $hash_password = password_hash($password, PASSWORD_DEFAULT);

                                $updQry = "UPDATE employee SET PasswordHash=? WHERE EmployeeId=?";
                                $prm = array($hash_password, $id);
                                $affected += $db1->update($updQry, "si", $prm);
                                echo "Password of user id " . $id . " changed" . "<br/>";
                            }

                            $msgPrompt = $affected . " password changed.";
                        } else {
                            $msgPrompt = "No employee records found.";
                        }
                    } else if (isset($_POST['btnBack'])) {
                        header('Location: index.php');
                        exit;
                    }
                } catch (Exception $e) {
                    globalExceptionHandler($e);
                }
            }
            ?>
            <div class="row">
                <div class="col-12">
                    <div class="log-box" id="logBox">
                        <?php if ($msgPrompt) : ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                <?php echo $msgPrompt; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.bundle.min.js"></script>

    </form>


</body>