<?php include 'inc_header.php'; ?>
<?php
// Include database connection function
include 'database_connection.php';


// Function to parse and insert CSV data into the buckets table
function parseAndInsertCSV($csvFile) {
    // Connect to the database
    $db = getDatabaseConnection();

    // Check if the database connection is successful
    if (!$db) {
        die("Database connection failed.");
    }

    // Keyword-category mapping
    $keywordsAndCategories = array(
        'ST JAMES RESTAURAT' => 'Entertainment',
        'RED CROSS' => 'Donations',
        'GATEWAY' => 'Communication',
        'SAFEWAY' => 'Groceries',
        'Subway' => 'Entertainment',
        'PUR & SIMPLE RESTAUR' => 'Entertainment',
        'REAL CDN SUPERS' => 'Groceries',
        'ICBC' => 'Car Insurance',
        'FORTISBC' => 'Gas Heating',
        'BMO' => 'Other',
        'WALMART' => 'Groceries',
        'COSTCO' => 'Groceries',
        'MCDONALDS' => 'Entertainment',
        'WHITE SPOT RESTAURAN' => 'Entertainment',
        'SHAW CABLE' => 'Utilities',
        'CANADIAN TIRE' => 'Other',
        'World Vision' => 'Donations',
        '7-ELEVEN' => 'Other',
        'TIM HORTONS' => 'Entertainment',
        'ROGERS MOBILE' => 'Communication',
        'O.D.P. FEE' => 'Other',
        'MONTHLY ACCOUNT FEE' => 'Other'
        // Add more keywords and corresponding categories as needed
    );

    // Open the CSV file
    $file = fopen($csvFile, 'r');
    if (!$file) {
        die("Error opening file $csvFile");
    }

    // Prepare the SQL statement to insert data into the buckets table
    $stmt = $db->prepare('INSERT INTO buckets (category, description) VALUES (:category, :description)');
    if (!$stmt) {
        die("Failed to prepare SQL statement.");
    }

    // Bind parameters
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':description', $description);


    // Loop through each line in the CSV file
    while (($line = fgetcsv($file)) !== false) {
        // Parse CSV data
        $description = $line[1];
        $category = getCategoryForDescription($description, $keywordsAndCategories);

        // Execute the prepared statement
        if (!$stmt->execute()) {
            die("Failed to execute SQL statement.");
        }
    }

    // Close the file and database connection
    fclose($file);
    $db->close();
}



// Function to determine category for a description based on keywords
function getCategoryForDescription($description, $keywordsAndCategories) {
    foreach ($keywordsAndCategories as $keyword => $category) {
        if (stripos($description, $keyword) !== false) {
            return $category;
        }
    }
    // If no category found, return 'Other' or handle it as needed
    return 'Other';
}

echo '<div class="container">';
echo '<a href="/buckets_add.php" class="btn btn-info">Add New Bucket</a>';
echo '<a href="/" class="btn btn-primary">&lt;&lt; BACK</a>';
echo '</div>';
?>

<?php
// If bucket is empty, insert sample bucket data
$res = $db->query('SELECT COUNT(*) FROM buckets');
$row = $res->fetchArray();
$res->finalize();
if ($row[0] == 0) {
    parseAndInsertCSV('2023 02.csv');
}
?>




<?php
// Display buckets list
echo '<div class="container">';
echo '<h2 class="mt-3">List of Buckets</h2>';
echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th scope="col">ID</th>';
echo '<th scope="col">Category</th>';
echo '<th scope="col">Description</th>';
echo '<th scope="col"></th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Loop through the results and display each bucket
$res = $db->query('SELECT * FROM buckets');
while ($row = $res->fetchArray()) {
    echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['category'] . '</td>';
    echo '<td>' . $row['description'] . '</td>';
    echo '<td>';
    echo '<a href="buckets_update.php?id=' . $row['id'] . '" class="btn btn-warning">Update</a>';
    echo '<a href="buckets_delete.php?id=' . $row['id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this bucket?\')">Delete</a>';
    echo '</td>';
    echo '</tr>';
}

// Close the database connection
$db->close();

echo '</tbody>';
echo '</table>';
echo '</div>';

?>

<?php include 'inc_footer.php'; ?>