<?php
// Function to create SQLite tables
function createTables($db) {
    // Create users table
    $db->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT "user",
        can_login BOOLEAN NOT NULL DEFAULT FALSE,
        can_access_transaction BOOLEAN NOT NULL DEFAULT FALSE,
        can_access_bucket BOOLEAN NOT NULL DEFAULT FALSE,
        can_access_report BOOLEAN NOT NULL DEFAULT FALSE
    )');

    // Check for existing admin account
    $result = $db->query('SELECT COUNT(*) as count FROM users WHERE role = "admin"');
    $row = $result->fetchArray();
    if ($row['count'] == 0) {
        $hashedPassword = password_hash("P@\$\$w0rd", PASSWORD_DEFAULT);
        $db->exec('INSERT INTO users (email, password, role, can_login, can_access_transaction, can_access_bucket, can_access_report) 
        VALUES ("aa@aa.aa", "' . $hashedPassword . '", "admin", 1, 1, 1, 1)');
    }

   // Create transactions table
$db->exec('CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date TEXT NOT NULL,
    description TEXT NOT NULL,
    amount REAL NOT NULL
)');

    // Create the buckets table
    $db->exec('CREATE TABLE IF NOT EXISTS buckets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category TEXT NOT NULL,
        description TEXT NOT NULL
    )');
}

function insertSampleUserData($db) {
    $result = $db->query('SELECT COUNT(*) as count FROM users WHERE role = "user"');
    $row = $result->fetchArray();
    if ($row['count'] == 0) {
        $password1 = password_hash('bbbbbb', PASSWORD_DEFAULT);
        $password2 = password_hash('cccccc', PASSWORD_DEFAULT);
        $password3 = password_hash('dddddd', PASSWORD_DEFAULT);
        $SQL_insert_data = 
        "INSERT INTO users (email, password, role, can_login, can_access_transaction, can_access_bucket, can_access_report) 
            VALUES 
            ('bb@bb.bb', '$password1', 'user', 1, 1, 1, 1),
            ('cc@cc.cc', '$password2', 'user', 0, 0, 0, 0),
            ('dd@dd.dd', '$password3', 'user', 1, 0, 0, 1)";
        $db->exec($SQL_insert_data);
    }
    }
?>
