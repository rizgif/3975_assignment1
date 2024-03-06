<?php
// Include database connection function
include 'database_connection.php';

// Function to determine the category based on the description using keyword matching
function determineCategory($description) {
    // Define categories and corresponding keywords
    $categoriesAndKeywords = [
        'Groceries' => ['SAFEWAY', 'REAL CDN SUPERS', 'WALMART', 'COSTCO WHOLESAL'],
        'Utilities' => ['FORTISBC GAS', 'SHAW CABLE', 'ROGERS MOBILE'],
        'Donations' => ['RED CROSS', 'World Vision'],
        'Eating Out' => ['ST JAMES RESTAURAT', 'Subway', 'PUR & SIMPLE RESTAUR', 'MCDONALDS', 'WHITE SPOT RESTAURAN', 'TIM HORTONS'],
        'Health' => ['GATEWAY          MSP'],
        'Other' => ['ICBC             INS', 'CANADIAN TIRE', 'ICBC', '7-ELEVEN', 'O.D.P. FEE', 'MONTHLY ACCOUNT FEE']
    ];

    // Iterate through each category and its corresponding keywords
    foreach ($categoriesAndKeywords as $category => $keywords) {
        // Check if any keyword matches the description
        foreach ($keywords as $keyword) {
            if (stripos($description, $keyword) !== false) {
                return $category; // Return the category if a match is found
            }
        }
    }

    return 'Other'; // Return 'Other' if no match is found
}

// Function to parse and insert CSV data into the database
function parseAndInsertCSV($csvFile) {
    // Connect to the database
    $db = getDatabaseConnection();

    // Check if the database connection is successful
    if (!$db) {
        die("Database connection failed.");
    }

    // Prepare the SQL statement to insert data into the transactions table
    $stmt = $db->prepare('INSERT INTO transactions (date, description, amount, category) VALUES (:date, :description, :amount, :category)');
    if (!$stmt) {
        die("Failed to prepare SQL statement.");
    }

    // Bind parameters
    $stmt->bindParam(':date', $date, SQLITE3_TEXT);
    $stmt->bindParam(':description', $description, SQLITE3_TEXT);
    $stmt->bindParam(':amount', $amount, SQLITE3_FLOAT);
    $stmt->bindParam(':category', $category, SQLITE3_TEXT);

    // Open the CSV file
    $file = fopen($csvFile, 'r');
    if (!$file) {
        die("Error opening file $csvFile");
    }

    // Skip the header line
    fgets($file);

    // Loop through each line in the CSV file
    while (($line = fgetcsv($file)) !== false) {
        // Parse CSV data
        $date = $line[0];
        $description = $line[1];
        $amount = $line[2];
        $category = determineCategory($description); // Determine category based on description

        // Execute the prepared statement
        if (!$stmt->execute()) {
            die("Failed to execute SQL statement.");
        }
    }

    // Close the file and database connection
    fclose($file);
    $db->close();
}
?>
