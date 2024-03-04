<?php

// Function to create SQLite tables
function create_tables() {
    // Connect to SQLite database
    $db = new SQLite3('mydatabase.db');

    // Create users table
    $db->exec('CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL
            )');

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
