<?php
$db = new SQLite3('mydatabase.db');

if (isset($_POST['create'])) {
  $category = SQLite3::escapeString($_POST['category']);
  $description = SQLite3::escapeString($_POST['name']);

  $sql = "INSERT INTO buckets (category, description) VALUES ('$category', '$description')";

  if ($db->exec($sql)) {
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
