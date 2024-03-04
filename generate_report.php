<?php
// Include database connection
include 'inc_header.php';

// Function to generate report for a given CSV file
function generateReport($filePath) {
    global $db;

    // Read the CSV file and extract expense data
    $expenses = array();
    if (($handle = fopen($filePath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $category = $data[2]; // Assuming category is in the third column
            $amount = floatval($data[3]); // Assuming amount is in the fourth column
            if (!isset($expenses[$category])) {
                $expenses[$category] = 0;
            }
            $expenses[$category] += $amount;
        }
        fclose($handle);
    }

    // Display breakdown of expenses
    echo '<div class="container">';
    echo '<h3>Breakdown of Expenses for ' . basename($filePath) . '</h3>';
    echo '<table class="table">';
    echo '<thead><tr><th>Category</th><th>Total Amount</th></tr></thead>';
    echo '<tbody>';
    foreach ($expenses as $category => $amount) {
        echo '<tr><td>' . $category . '</td><td>' . number_format($amount, 2) . '</td></tr>';
    }
    echo '</tbody></table>';

    // Generate pie chart visualization
    echo '<canvas id="expenseChart" width="400" height="400"></canvas>';
    echo '</div>';

    // JavaScript for Chart.js pie chart
    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo '<script>';
    echo 'var ctx = document.getElementById("expenseChart").getContext("2d");';
    echo 'var myPieChart = new Chart(ctx, {';
    echo 'type: "pie",';
    echo 'data: {';
    echo 'labels: ' . json_encode(array_keys($expenses)) . ',';
    echo 'datasets: [{';
    echo 'label: "Expense Breakdown",';
    echo 'data: ' . json_encode(array_values($expenses)) . ',';
    echo 'backgroundColor: [';
    echo '"#ff6384", "#36a2eb", "#ffce56", "#4bc0c0", "#9966ff", "#ff8a80", "#b9f6ca", "#80d8ff"';
    echo ']';
    echo '}]';
    echo '}';
    echo '});';
    echo '</script>';
}

// Get list of CSV files in the uploads directory
$files = glob('uploads/*.csv');

// Check if the CSV file is selected from the dropdown menu
if (isset($_POST['file'])) {
    $filePath = $_POST['file'];
    generateReport($filePath);
} else {
    // Display dropdown menu to select CSV file
    echo '<div class="container">';
    echo '<form action="" method="post">';
    echo '<label for="file">Select a CSV file:</label>';
    echo '<select name="file" id="file">';
    foreach ($files as $file) {
        echo '<option value="' . $file . '">' . basename($file) . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" value="Generate Report">';
    echo '</form>';
    echo '</div>';
}

// Include footer
include 'inc_footer.php';
?>
