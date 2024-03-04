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
        id INTEGER PRIMARY KEY,
        date TEXT,
        description TEXT,
        amount REAL
    )');

    // Define categories and corresponding keywords
    $categoriesAndKeywords = [
        'Groceries' => ['SAFEWAY', 'REAL CDN SUPERS', 'WALMART', 'COSTCO WHOLESAL'],
        'Utilities' => ['FORTISBC GAS', 'SHAW CABLE', 'ROGERS MOBILE'],
        'Donations' => ['RED CROSS', 'World Vision'],
        'Eating Out' => ['ST JAMES RESTAURAT', 'Subway', 'PUR & SIMPLE RESTAUR', 'MCDONALDS', 'WHITE SPOT RESTAURAN', 'TIM HORTONS'],
        'Health' => ['GATEWAY MSP'],
        'Other' => ['ICBC INS', 'CANADIAN TIRE', '7-ELEVEN', 'O.D.P. FEE', 'MONTHLY ACCOUNT FEE']
    ];

    // Create the buckets table
    $db->exec('CREATE TABLE IF NOT EXISTS buckets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category TEXT,
        description TEXT
    )');

    // Insert unique categories into the buckets table
    foreach ($categoriesAndKeywords as $category => $keywords) {
        $description = implode(', ', $keywords);
        $db->exec('INSERT INTO buckets (category, description) VALUES ("' . $category . '", "' . $description . '")');
    }
}

?>
