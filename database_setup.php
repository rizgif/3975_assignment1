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

    // Define categories and corresponding keywords
    $categoriesAndKeywords = [
        'Groceries' => ['SAFEWAY', 'REAL CDN SUPERS', 'WALMART', 'COSTCO WHOLESAL',],
        'Utilities' => ['FORTISBC GAS', 'SHAW CABLE', 'ROGERS MOBILE'],
        'Donations' => ['RED CROSS', 'World Vision'],
        'Eating Out' => ['ST JAMES RESTAURAT', 'Subway', 'PUR & SIMPLE RESTAUR', 'MCDONALDS', 'WHITE SPOT RESTAURAN', 'TIM HORTONS'],
        'Health' => ['GATEWAY          MSP',],
        'Other' => ['ICBC             INS', 'CANADIAN TIRE', 'ICBC', '7-ELEVEN', 'O.D.P. FEE', 'MONTHLY ACCOUNT FEE']
    ];

    // Create the filters table
    $db->exec('CREATE TABLE IF NOT EXISTS filters (
        category TEXT,
        keyword TEXT
    )');

    // Insert data into the filters table
    foreach ($categoriesAndKeywords as $category => $keywords) {
        foreach ($keywords as $keyword) {
            $db->exec("INSERT INTO filters (category, keyword) VALUES ('$category', '$keyword')");
        }
    }

    // Close database connection
    $db->close();
}

// Function to import CSV to SQLite for buckets table
function import_csv_to_buckets_table($filePath) {
    if ($filePath !== null && file_exists($filePath)) { // Check if file path is not null and file exists
        // Connect to SQLite database
        $db = new SQLite3('mydatabase.db');

        // Define the list of categories
        $categories = array("Entertainment", "Communication", "Groceries", "Donations", "Car Insurance", "Gas Heating");

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Assuming each entry in the CSV file is in the format: name
                if (count($data) >= 1) {
                    $name = SQLite3::escapeString(trim($data[0])); // Get the name field

                    // Generate a random category from the list of categories
                    $category = $categories[array_rand($categories)];

                    // Insert data into buckets table
                    $db->exec("INSERT INTO buckets (category, description) VALUES ('$category', '$name')");
                }
            }
            fclose($handle);
        } else {
            // Handle file open error
            echo "Error opening file.";
        }

        // Close database connection
        $db->close();
    } else {
        // Handle the case where the file path is null or the file doesn't exist
        echo "File path is invalid or file does not exist.";
    }
}

// Call the function to create tables when the script runs
create_tables();

// Check if file upload was successful
if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] == UPLOAD_ERR_OK) {
    // Path to the uploaded CSV file
    $uploadedFile = $_FILES['uploaded_file']['tmp_name'];

    // Call the function to import CSV to buckets table
    import_csv_to_buckets_table($uploadedFile);
} else {
    // Handle file upload error or missing file
    echo "File upload failed or file not found.";
}
?>
