<?php
$database = new SQLite3('mydatabase.db');

function generateReport($database, $year) {
    $result = $database->query("
        SELECT category, SUM(amount) AS total
        FROM transactions
        WHERE strftime('%Y', date) = '{$year}'
        GROUP BY category
    ");

    $report = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $report[] = $row;
    }

    // Output report as CSV
    $output = fopen("php://output", 'w');
    fputcsv($output, ['Category', 'Total']);
    foreach ($report as $row) {
        fputcsv($output, $row);
    }
    fclose($output);

    // Optionally, you can return this $report array to create a pie chart with a PHP chart library
    return $report;
}

// Call the function with the year you want to generate a report for
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="report.csv"');
generateReport($database, '2023');

$database->close();
?>
