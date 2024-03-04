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

// Function to import CSV to SQLite for buckets table
function import_csv_to_buckets_table($filePath) {
    $db = new SQLite3('mydatabase.db');

    // Define the list of categories
    $categories = array("Entertainment", "Communication", "Groceries", "Donations", "Car Insurance", "Gas Heating");

    if (($handle = fopen($filePath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Assuming each entry in the CSV file is in the format: date, name, amount, balance
            if (count($data) >= 2) {
                $name = SQLite3::escapeString(trim($data[1])); // Get the name field

                // Generate a random category from the list of categories
                $category = $categories[array_rand($categories)];

                // Insert data into buckets table
                $db->exec("INSERT INTO buckets (category, name) VALUES ('$category', '$name')");
            }
        }
        fclose($handle);
    }

    $db->close();
}


// Call the function to create tables when the script runs
create_tables();

// Call the function to import CSV data into the buckets table
$csvFilePath = 'uploads/2023 02.imported.csv'; // Update with your CSV file path
import_csv_to_buckets_table($csvFilePath);

?>
