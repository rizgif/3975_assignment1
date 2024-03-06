<?php
// Function to create SQLite tables
function createTables($db) {
    // Create users table
    $db->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT "user",
        isApproved BOOLEAN NOT NULL DEFAULT FALSE
    )');

    // Check for existing admin account
    $result = $db->query('SELECT COUNT(*) as count FROM users WHERE role = "admin"');
    $row = $result->fetchArray();
    if ($row['count'] == 0) {
        $hashedPassword = password_hash("P@\$\$w0rd", PASSWORD_DEFAULT);
        $db->exec('INSERT INTO users (email, password, role, isApproved) VALUES ("aa@aa.aa", "' . $hashedPassword . '", "admin", 1)');
    }

   // Create transactions table
$db->exec('CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date TEXT NOT NULL,
    description TEXT NOT NULL,
    amount REAL NOT NULL,
    category TEXT NOT NULL
)');


    // Create the buckets table
    $db->exec('CREATE TABLE IF NOT EXISTS buckets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category TEXT NOT NULL,
        description TEXT NOT NULL
    )');
}





?>
