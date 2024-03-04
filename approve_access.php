<?php
$db = new SQLite3('mydatabase.db');

$user_id = $_POST['user_id'];
$isApproved = isset($_POST['isApproved']) ? 1 : 0;

// Update the isApproved value in the database
$db->exec("UPDATE users SET isApproved = $isApproved WHERE id = $user_id");

header('Location: admin.php');
?>