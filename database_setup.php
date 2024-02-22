<?php

// Function to create SQLite tables
function create_tables() {
    // Connect to SQLite database
    $db = new SQLite3('mydatabase.db');

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
