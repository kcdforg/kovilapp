<?php
include('../init.php');

// Check if user is logged in
check_login();

$users = get_users();

include('../includes/header.php');
?>

<style>
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: 600;
    color: #6c757d;
    transition: all 0.3s ease;
}

.user-avatar:hover {
    transform: scale(1.1);
    border-color: #4e73df;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 2px;
}

.user-role {
    font-size: 0.8rem;
    color: #6c757d;
}

.table tbody tr:hover {
    background-color: #f8f9fc;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-group .btn {
    border-radius: 6px;
    margin: 0 2px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">User Management</h1>
            <a href="adduser.php" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Add New User
            </a>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-people-fill"></i> Users List
                    <span class="badge bg-primary ms-2"><?php echo $users ? mysqli_num_rows($users) : 0; ?> Total</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Profile ID</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($users && mysqli_num_rows($users) > 0) {
                                while ($user = mysqli_fetch_assoc($users)) {
                                    // Get user initials for default avatar
                                    $name_parts = explode(' ', $user['username']);
                                    $initials = '';
                                    if (count($name_parts) >= 2) {
                                        $initials = strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
                                    } else {
                                        $initials = strtoupper(substr($user['username'], 0, 2));
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <?php echo $initials; ?>
                                                </div>
                                                <div>
                                                    <div class="user-name"><?php echo htmlspecialchars($user['username']); ?></div>
                                                    <div class="user-role"><?php echo htmlspecialchars($user['role']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($user['role']); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($user['profile_id']): ?>
                                                <span class="badge bg-success"><?php echo htmlspecialchars($user['profile_id']); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Not Assigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="userview.php?id=<?php echo $user['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   data-bs-toggle="tooltip" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="userupdate.php?id=<?php echo $user['id']; ?>" 
                                                   class="btn btn-sm btn-outline-secondary" 
                                                   data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="tooltip" title="Delete"
                                                        onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(id, name) {
    if (confirm(`Are you sure you want to delete user "${name}"? This action cannot be undone.`)) {
        window.open(`usrdelete.php?id=${id}`, 'popup', 'scrollbars=yes, width=1000, height=500');
    }
}
</script>

<?php include('../includes/footer.php'); ?>


