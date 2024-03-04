<?php
// SQLite database file path
$databaseFile = 'mydatabase.db';

// Establish a connection to the SQLite database
$db = new SQLite3($databaseFile);

// Check if the connection was successful
if (!$db) {
    die("Connection to database failed.");
}
?>
