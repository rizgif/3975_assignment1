<?php

// Function to create SQLite tables
function create_tables() {
    // Connect to SQLite database
    $db = new SQLite3('mydatabase.db');

    // Create users table
    $db->exec('CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                role TEXT NOT NULL DEFAULT "user",
                isApproved BOOLEAN NOT NULL DEFAULT FALSE
            )');

    // Insert admin account
    $result = $db->query('SELECT COUNT(*) as count FROM users WHERE role = "admin"');
    $row = $result->fetchArray();
    if ($row['count'] == 0) {
        // P@$$w0rd
        $hashedPassword = password_hash("P@\$\$w0rd", PASSWORD_DEFAULT);
        $db->exec('INSERT INTO users (email, password, role, isApproved) VALUES ("aa@aa.aa", "' . $hashedPassword . '", "admin", TRUE)');
    }

    // Create transactions table
    $db->exec('CREATE TABLE IF NOT EXISTS transactions (
                id INTEGER PRIMARY KEY,
                date TEXT,
                description TEXT,
                amount REAL
            )');

    // Create buckets table
    $db->exec('CREATE TABLE IF NOT EXISTS buckets (
                id INTEGER PRIMARY KEY,
                category TEXT,
                name TEXT
            )');

    // Close database connection
    $db->close();
}

// Call the function to create tables when the script runs
create_tables();

?>
