<?php
session_start();

include 'inc_header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Connect to SQLite database
  $db = new SQLite3('mydatabase.db');

  // Get email and password from form
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Query the database for the user
  $stmt = $db->prepare('SELECT * FROM users WHERE email=:email');
  $stmt->bindValue(':email', $email);
  $result = $stmt->execute();

  // Check if the user exists and the password is correct
  if ($result && $row = $result->fetchArray()) {
    // Verify the hashed password
    if (password_verify($password, $row['password'])) {
      // User authenticated
      $_SESSION['email'] = $email;
      header('Location: index.php');
      exit;
    } else {
      // Invalid credentials
      $login_err = "Invalid email or password";
    }
  } else {
    // Invalid credentials
    $login_err = "Invalid email or password";
  }

  // Close the database connection
  $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font: 14px sans-serif;
    }

    .wrapper {
      width: 360px;
      padding: 20px;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <h2>Login</h2>
    <p></p>

    <?php if (!empty($login_err)) : ?>
      <div class="alert alert-danger"><?php echo $login_err; ?></div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Login">
      </div>
      <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
    </form>
  </div>
</body>

</html>

<?php include 'inc_footer.php'; ?>