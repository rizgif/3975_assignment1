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
    $stmt = $db->prepare('SELECT COUNT(*) as count FROM users WHERE role = ?');
    $stmt->bindValue(1, "admin", SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray();
    if ($row['count'] == 0) {
        $hashedPassword = password_hash("P@\$\$w0rd", PASSWORD_DEFAULT);
        $stmt = $db->prepare('INSERT INTO users (email, password, role, isApproved) VALUES (?, ?, "admin", TRUE)');
        $stmt->bindValue(1, "aa@aa.aa", SQLITE3_TEXT);
        $stmt->bindValue(2, $hashedPassword, SQLITE3_TEXT);
        $stmt->execute();
    }

    // Create transactions table
    $db->exec('CREATE TABLE IF NOT EXISTS transactions (
        id INTEGER PRIMARY KEY,
        date TEXT,
        description TEXT,
        amount REAL
    )');

    // Create filters table
    $db->exec('CREATE TABLE IF NOT EXISTS filters (
        category TEXT,
        keyword TEXT PRIMARY KEY
    )');

    // Add this line to create the buckets table
    $db->exec('CREATE TABLE IF NOT EXISTS buckets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category TEXT,
        description TEXT
    )');
}

?>
