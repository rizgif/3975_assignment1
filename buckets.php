<?php
include 'inc_header.php';

// Include SQLite database connection
include 'database_connection.php';

// Function to check if the buckets table is empty
function is_buckets_table_empty($db) {
    $count = $db->querySingle("SELECT count(*) FROM buckets");
    return $count == 0;
}

// Function to retrieve data from the buckets table
function get_buckets_data($db) {
    return $db->query('SELECT id, category, description FROM buckets');
}

// Display buckets list
?>
<div class="container">
    <h2 class="mt-3">List of Buckets</h2>
    <a href="/buckets_add.php" class="btn btn-info">Add New Bucket</a>
    <a href="/" class="btn btn-primary">&lt;&lt; BACK</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Category</th>
                <th scope="col">Description</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = get_buckets_data($db);
            while ($row = $res->fetchArray()) {
                echo "<tr>\n";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['category']}</td>";
                echo "<td>{$row['description']}</td>";
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
