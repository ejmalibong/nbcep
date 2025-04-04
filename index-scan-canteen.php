<?php
require_once 'admin/authenticate-scan-canteen.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link rel="icon" type="image/png" href="img/favicon.png">
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/responsive-tables.css" />
</head>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            <h4>Employee Portal</h4>
          </div>
          <div class="card-body">
            <form id="myForm" action="" method="POST">
              <div class="mb-3">
                <label for="employeeCode" class="form-label">Scan/Tap your ID</label>
                <input type="text" class="form-control" id="employeeCode" name="txtEmployeeCode" required autofocus />
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary" name="btnlogin">Login</button>
              </div>
            </form>

            <?php if ($errorPrompt) : ?>
              <div class="alert alert-danger mt-3" role="alert">
                <?php echo $errorPrompt; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
require_once 'config/footer.php';
?>