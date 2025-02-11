<?php
require_once 'admin/authenticate.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/responsive-tables.css" />
  <!-- display blank favicon - -->
  <link
    rel="icon"
    type="image/x-icon"
    href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=" />
</head>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4 class="text-center">DTR Viewer</h4>
          </div>
          <div class="card-body">
            <form id="myForm" action="" method="POST">
              <div class="mb-3">
                <label for="employeeCode" class="form-label">Employee ID</label>
                <input
                  type="text"
                  class="form-control"
                  id="employeeCode"
                  name="txtEmployeeCode"
                  required />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  name="txtPassword"
                  required />
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary" name="btnlogin">
                  Login
                </button>
              </div>
              <?php if ($errorPrompt) : ?>
                <div class="alert alert-danger mt-3 text-center" role="alert">
                  <?php echo $errorPrompt; ?>
                </div>
              <?php endif; ?>
            </form>
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