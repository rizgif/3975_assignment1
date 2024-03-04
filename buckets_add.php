<?php include("inc_header.php");
$error_message = isset($_GET['error']) ? $_GET['error'] : '';
?>

<h2>Add New Bucket</h2>

<div class="row">
  <div class="col-md-4">
    <?php if (!empty($error_message)) : ?>
      <div class="alert alert-danger">
        <?php echo htmlspecialchars($error_message); ?>
      </div>
    <?php endif; ?>
    <form action="buckets_add_process.php" method="post"> <!-- Update the form action to "buckets_add_process.php" -->


      <div class="form-group">
        <label for="category" class="control-label">Category</label> <!-- Update the label for category -->
        <input type="text" class="form-control" name="category" id="category" required /> <!-- Update the input name and id to "category" -->
      </div>

      <div class="form-group">
        <label for="name" class="control-label">Name</label> <!-- Update the label for name -->
        <input type="text" class="form-control" name="name" id="name" required /> <!-- Update the input name and id to "name" -->
      </div>

      <div class="form-group">
        <a href="/buckets.php" class="btn btn-small btn-primary">&lt;&lt; BACK</a>
        &nbsp;&nbsp;&nbsp;
        <input type="submit" value="Create" name="create" class="btn btn-success" />
      </div>
    </form>
  </div>
</div>

<?php include("inc_footer.php"); ?>
