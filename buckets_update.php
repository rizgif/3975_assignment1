<?php
include("inc_header.php");
$db = new SQLite3('mydatabase.db');

if (isset($_POST['update'])) {
  $id = SQLite3::escapeString($_POST['id']);
  $category = SQLite3::escapeString($_POST['category']);
  $description = SQLite3::escapeString($_POST['name']); // Changed 'name' to 'description'

  $sql = "UPDATE buckets SET category = '$category', description = '$description' WHERE id = '$id'"; // Changed 'name' to 'description'

  if ($db->exec($sql)) {
    header('Location: buckets.php?message=Bucket updated successfully'); // Update the redirection URL to 'buckets.php'
    exit;
  } else {
    $error = $db->lastErrorMsg();
    header('Location: buckets.php?error=' . urlencode($error)); // Update the redirection URL to 'buckets.php'
    exit;
  }
} elseif (isset($_GET['id'])) {
  $id = SQLite3::escapeString($_GET['id']);
  $result = $db->querySingle("SELECT * FROM buckets WHERE id = '$id'", true); // Update the table name to 'buckets'
} else {
  header('Location: buckets.php'); // Update the redirection URL to 'buckets.php'
  exit;
}
?>


<h2>Update Bucket</h2>

<form method="post" action="buckets_update.php">
  <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
  <div class="form-group">
    <label for="category">Category:</label>
    <input type="text" class="form-control" id="category" name="category" value="<?php echo $result['category']; ?>">
  </div>
  <div class="form-group">
    <label for="name">Name:</label>
    <input type="text" class="form-control" id="name" name="name" value="<?php echo $result['description']; ?>"> <!-- Changed 'name' to 'description' -->
  </div>

  <button type="submit" class="btn btn-warning" name="update">Update</button>
  <a href="/buckets.php" class="btn btn-small btn-primary">&lt;&lt; BACK</a> <!-- Update the URL to 'buckets.php' -->
</form>

<?php include("inc_footer.php"); ?>
