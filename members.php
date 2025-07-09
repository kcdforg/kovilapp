<?php
include('header.php');
?>
  <!-- Members Table -->
  <main class="flex-grow-1">
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>Members</h4>
      <button class="btn btn-success">Add Member</button>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-primary">
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Joined Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>John Smith</td>
            <td>john@example.com</td>
            <td><span class="badge bg-success">Active</span></td>
            <td>2023-08-01</td>
            <td>
              <button class="btn btn-sm btn-outline-primary">Edit</button>
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </td>
          </tr>
          <tr>
            <td>Jane Doe</td>
            <td>jane@example.com</td>
            <td><span class="badge bg-warning text-dark">Pending</span></td>
            <td>2023-07-15</td>
            <td>
              <button class="btn btn-sm btn-outline-primary">Edit</button>
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</main>
  <?php
  include('footer.php');
  ?>
