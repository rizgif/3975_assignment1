<?php include("inc_header.php");
$error_message = isset($_GET['error']) ? $_GET['error'] : '';
?>

<h2>Add New Transaction</h2>

<div class="row">
  <div class="col-md-4">
    <?php if (!empty($error_message)) : ?>
      <div class="alert alert-danger">
        <?php echo htmlspecialchars($error_message); ?>
      </div>
    <?php endif; ?>
    <form action="transactions_add_process.php" method="post">


      <div class="form-group">
        <label for="date" class="control-label">Date</label>
        <input type="date" class="form-control" name="date" id="date" required />
      </div>

      <div class="form-group">
        <label for="description" class="control-label">Description</label>
        <input type="text" class="form-control" name="description" id="description" required />
      </div>

      <div class="form-group">
        <label for="amount" class="control-label">Amount</label>
        <input type="number" class="form-control" name="amount" id="amount" required />
      </div>

      <div class="form-group">
        <a href="/transactions.php" class="btn btn-small btn-primary">&lt;&lt; BACK</a>
        &nbsp;&nbsp;&nbsp;
        <input type="submit" value="Create" name="create" class="btn btn-success" />
      </div>
    </form>
  </div>
</div>

<?php include("inc_footer.php"); ?>