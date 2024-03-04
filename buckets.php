<?php include 'inc_header.php'; ?>

<?php
// Function to check if the buckets table is empty
function is_buckets_table_empty($db) {
    $count = $db->querySingle("SELECT count(*) FROM buckets");
    return $count == 0;
}

// Function to insert data into the buckets table
function insert_data_into_buckets_table($db, $filePath) {
    if (file_exists($filePath) && ($handle = fopen($filePath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $category = SQLite3::escapeString($data[0]); // Assuming category is in the first column
            $name = SQLite3::escapeString($data[1]); // Assuming name is in the second column
            if (!empty($name)) {
                $sql = "INSERT INTO buckets (category, name) VALUES ('$category', '$name')";
                $db->exec($sql);
            }
        }
        fclose($handle);
    } else {
        echo "The specified CSV file does not exist.";
    }
}

// Check if the buckets table is empty, if so, insert data
if (is_buckets_table_empty($db)) {
    $filePath = "uploads/2023 02.imported.csv"; // Corrected file path
    insert_data_into_buckets_table($db, $filePath);
}
?>

<!-- Display buckets list -->
<div class="container">
    <h2 class="mt-3">List of Buckets</h2>
    <a href="/buckets_add.php" class="btn btn-info">Add New Bucket</a>
    <a href="/" class="btn btn-primary">&lt;&lt; BACK</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Category</th>
                <th scope="col">Name</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $db->query('SELECT * FROM buckets');
            while ($row = $res->fetchArray()) {
                echo "<tr>\n";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['category']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>
                        <a href='buckets_update.php?id={$row['id']}' class='btn btn-warning'>Update</a>
                        <a href='buckets_delete.php?id={$row['id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this bucket?\")'>Delete</a>
                      </td>";
                echo "</tr>\n";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'inc_footer.php'; ?>
