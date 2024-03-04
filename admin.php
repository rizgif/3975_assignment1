<?php
include 'inc_header.php';
$result = $db->query('SELECT * FROM users WHERE role = "user"');
?>

<div class="container">
  <h2 class="text-center">Grant users access</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Email</th>
        <th>Role</th>
        <th>Access</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Loop through the users
      while ($row = $result->fetchArray()) {
      ?>
        <tr>
          <td><?php echo $row['email']; ?></td>
          <td><?php echo $row['role']; ?></td>
          <td>
            <form action="approve_access.php" method="post">
              <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
              <input type="checkbox" name="isApproved" value="1" <?php echo ($row['isApproved'] == 1) ? 'checked' : ''; ?> onchange="this.form.submit()">
            </form>
          </td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>

  <button class="btn btn-primary" onclick="window.location.href = 'index.php'">Back</button>
  <?php include 'inc_footer.php'; ?>