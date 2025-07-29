<?php include('../init.php'); ?>
<?php include('../includes/header.php'); ?>
<div class="container mt-4">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Subscription Events</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Event Name</th>
              <th>Status</th>
              <th>Total Amount</th>
              <th>Received Amount</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Example row, replace with PHP loop -->
            <tr>
              <td>Annual Subscription 2024</td>
              <td><span class="badge bg-success">Open</span></td>
              <td>₹2,00,000</td>
              <td>₹1,50,000</td>
              <td>
                <a href="event_members.php?event_id=1" class="btn btn-sm btn-primary">View</a>
              </td>
            </tr>
            <tr>
              <td>Special Event - Temple Renovation</td>
              <td><span class="badge bg-secondary">Closed</span></td>
              <td>₹5,00,000</td>
              <td>₹5,10,000</td>
              <td>
                <a href="event_members.php?event_id=2" class="btn btn-sm btn-primary">View</a>
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