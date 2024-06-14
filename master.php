<?php require('header.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Default Title'; ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="dtrviewer.php">
                <img src="img/nbc.jpg" alt="nbc-logo" width="70" height="35">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="dtrviewer.php">DTR Viewer</a>
                    </li>

                    <?php

                    if ($_SESSION['isHrRecords'] === 1) {
                        echo  '<li class="nav-item">
                        <a class="nav-link active" href="dtruploading.php">DTR Uploading</a>
                        </li>';
                    }

                    ?>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Help
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Contact</a></li>
                            <li><a class="dropdown-item" href="#">Company Manual</a></li>
                            <li><a class="dropdown-item" href="#">Privacy Notice</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">About Employee Portal</a></li>
                        </ul>
                    </li> -->
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown-center">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= htmlspecialchars($_SESSION['employeeName']); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <!-- <li><a class="dropdown-item" href="#">View Information</a></li>
                            <li><a class="dropdown-item" href="#">Change Password</a></li>
                            <li><a class="dropdown-item" href="#">Change Email</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li> -->
                            <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- main content will be added where the master page is included -->
        <?php echo $content ?? ''; ?>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
require_once 'config/footer.php';
?>