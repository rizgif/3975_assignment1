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

// Function to import CSV to SQLite
function import_csv_to_database($filePath) {
    $db = new SQLite3('mydatabase.db');

    if (($handle = fopen($filePath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $date = SQLite3::escapeString($data[0]);
            $description = SQLite3::escapeString(trim($data[1]));
            $debit = SQLite3::escapeString($data[2] === '' ? '0' : $data[2]);
            $credit = SQLite3::escapeString($data[3] === '' ? '0' : $data[3]);
            $balance = SQLite3::escapeString($data[4] === '' ? '0' : $data[4]);

            $db->exec("INSERT INTO transactions (date, description, debit, credit, balance) VALUES ('$date', '$description', $debit, $credit, $balance)");
        }
        fclose($handle);
    }

    $db->close();
}

// Call the function to create tables when the script runs
create_tables();

?>
