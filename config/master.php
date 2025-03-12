<?php
require_once "header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Default Title'; ?></title>
    <link rel="icon" type="image/png" href="../img/favicon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top bg-body-tertiary bg-dark border-bottom border-body" data-bs-theme="dark">
        <div class=" container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../img/nbc.jpg" alt="nbc-logo" width="70" height="35">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <a class="nav-link active" aria-current="page" href="../dtr/viewer.php">Daily Time Record</a>
                    <a class="nav-link active" aria-current="page" href="../dtr/load-checker.php">Canteen Purchases</a>

                    <?php

                    if ($_SESSION['isAdmin'] === 1) {
                        echo '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Settings
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../dtr/upload.php">Upload DTR</a></li>
                            <li><a class="dropdown-item" href="../dtr/upload-data.php">Upload Data</a></li>
                        </ul>
                    </li>';
                    } else {
                        if ($_SESSION['isHrRecords'] === 1) {
                            echo '<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Settings
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../dtr/upload.php">Upload DTR</a></li>
                        </ul>
                    </li>';
                        }
                    }

                    ?>
                </ul>

                <!-- https://stackoverflow.com/questions/8662535/trigger-php-function-by-clicking-html-link -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown-center">
                        <button class="btn btn-dark activedropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= testInput($_SESSION['employeeName']); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <li><a class="dropdown-item" href="../user/change-password.php">Change Password</a></li>
                            <li><a class="dropdown-item" href="../logout.php">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <!-- main content will be added where the master page is included -->
        <?php echo $content ?? ''; ?>
    </div>

    <!-- included to my reference -->
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php require_once 'footer.php'; ?>