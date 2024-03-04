<?php
include 'inc_header.php';
session_start();
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
    $isApproved = $row['isApproved']
  ?>
    <div class="text-right">
      <h4>Logged in with <?php echo $_SESSION['email']; ?></h4>
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
      ?>
      <?php
      // Show Go To Transactions button if the user is an admin or the user is approved
      if ($role == 'admin' || $isApproved) {
      ?>
        <button type="button" class="btn btn-primary" onclick="location.href='transactions.php'">Go To Transactions</button>
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