<?php include('../init.php'); ?>
<?php include('../includes/header.php'); ?>
<div class="container mt-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="bi bi-people"></i> Members Paid for [Event Name]</h5>
      <a href="add.php?event_id=<?php echo isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0; ?>" class="btn btn-primary"><i class="bi bi-plus"></i> Add Payment</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Member</th>
              <th>Amount</th>
              <th>Receipt No</th>
              <th>Date</th>
              <th>Book No</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Example row, replace with PHP loop -->
            <tr>
              <td>John Doe</td>
              <td>₹1,000</td>
              <td>123</td>
              <td>2024-07-29</td>
              <td>5</td>
              <td>
                <a href="#" class="btn btn-sm btn-secondary">View</a>
              </td>
            </tr>
            <!-- ... -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include('../includes/footer.php'); ?> 