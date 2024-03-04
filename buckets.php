<?php include 'inc_header.php'; ?>

<!-- load csv file -->
<?php
$count = $db->querySingle("SELECT count(*) from buckets");

if ($count == 0) {
  $row = 1;
  if (($handle = fopen("2023 02.csv", "r")) !== FALSE) {
    $data = fgetcsv($handle, 1000, ",");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $date = DateTime::createFromFormat('m/d/Y', $data[0]); 
      $formattedDate = $date ? $date->format('Y-m-d') : ''; // adjust date format to yy-mm-dd

      $num = count($data);
      $row++;

      $formattedDate = SQLite3::escapeString($formattedDate);
      $category = SQLite3::escapeString($data[1]);
      $name = SQLite3::escapeString($data[2]);

      if (!empty($name)) {
        $SQLinsert = "INSERT INTO buckets (category, name)";
        $SQLinsert .= " VALUES ";
        $SQLinsert .= " ('$category', '$name')";

        $db->exec($SQLinsert);
        $changes = $db->changes();
      }
    }
  }
}
?>

<!-- display buckets list -->
<div class="container">
  <h2 class="mt-3">List of Buckets</h2>
  <a href="/buckets_add.php" class="btn btn-info ">Add New Bucket</a>
  <a href="/" class="btn btn-primary ">&lt;&lt; BACK</a>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Category</th>
        <th scope="col">Amount</th>
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
      $db->close();
      ?>
    </tbody>
  </table>
</div>

<?php include 'inc_footer.php'; ?>
