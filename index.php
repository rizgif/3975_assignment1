<?php
include 'inc_header.php';
session_start();
$db = new SQLite3('mydatabase.db'); // Ensure database connection is available
?>

<div class="container text-center">
  <?php
  if (!isset($_SESSION['email'])) {
    // Before login
  ?>
    <button class="btn btn-primary" onclick="window.location.href = 'login.php'">Login</button>
    <button class="btn btn-success" onclick="window.location.href = 'register.php'">Register</button>
  <?php
  } else {
    // After login
    $result = $db->query('SELECT role, isApproved FROM users WHERE email = "' . $_SESSION['email'] . '"');
    $row = $result->fetchArray();
    $role = $row['role'];
    $isApproved = $row['isApproved']; // Added missing semicolon here
  ?>
    <div class="text-right">
      <h4>Logged in as <?php echo $_SESSION['email']; ?></h4>
      <form action="logout.php" method="post" style="display: inline-block;">
        <button type="submit" class="btn btn-danger">Logout</button>
      </form>
      <?php
      // Show Manage Users button if the user is an admin
      if ($role == 'admin') {
      ?>
        <button class="btn btn-info" onclick="window.location.href = 'admin.php'">Manage Users</button>
      <?php
      }
      // Show Go To Transactions button if the user is an admin or the user is approved
      if ($role == 'admin' || $isApproved) {
      ?>
        <button type="button" class="btn btn-primary" onclick="location.href='transactions.php'">Go To Transactions</button>
        <!-- File Upload Form for Admins or Approved Users -->
        <div class="upload-section mt-4">
          <h5>Upload a CSV File:</h5>
          <form action="upload.php" method="post" enctype="multipart/form-data">
              Select CSV file to upload:
              <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv">
              <input type="submit" value="Upload File" name="submit" class="btn btn-secondary">
          </form>
        </div>
      <?php
      }
      ?>
    </div>
  <?php
  }
  ?>
</div>

<footer class="footer bg-light mt-4">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <hr>
        <div class="text-right">
          <p class="mb-0">Riz Nur Saidy</p>
          <p class="mb-0">Diane Choi</p>
        </div>
      </div>
    </div>
  </div>
</footer>

<?php include 'inc_footer.php'; ?>
