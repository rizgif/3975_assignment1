<?php
$db = new SQLite3('mydatabase.db');

if (isset($_POST['create'])) {
  $category = $_POST['category']; // No need to escape this because it comes from a controlled set
  $description = SQLite3::escapeString($_POST['description']); // Description should still be escaped or parameterized

  // Check if the category is allowed
  if (!in_array($category, ['Entertainment', 'Donations', 'Communication', 'Groceries', 'Car Insurance', 'Other', 'Gas Heating', 'Utilities'])) {
    header('Location: buckets_add.php?error=' . urlencode('Invalid category selected.'));
    exit;
  }

  $stmt = $db->prepare('INSERT INTO buckets (category, description) VALUES (:category, :description)');
  $stmt->bindValue(':category', $category, SQLITE3_TEXT);
  $stmt->bindValue(':description', $description, SQLITE3_TEXT);

  if ($stmt->execute()) {
    header('Location: buckets.php');
    exit;
  } else {
    $error = $db->lastErrorMsg();
    header('Location: buckets_add.php?error=' . urlencode($error));
    exit;
  }
} else {
  header('Location: buckets_add.php');
  exit;
}
?>
