<?php
include('../init.php');

// Check if user is logged in
check_login();

$id = $_GET['id'] ?? 0;
$user = get_user($id);

include('../includes/header.php');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">User Details: <?php echo htmlspecialchars($user['username']); ?></h1>
            <div>
                <a href="userupdate.php?id=<?php echo $id; ?>" class="btn btn-primary me-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="userlist.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- User Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-fill"></i> User Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <img src="../images/user/<?php echo $user['u_image'] ?: 'default.png'; ?>" 
                             class="img-fluid rounded" width="150" height="150" 
                             alt="<?php echo htmlspecialchars($user['username']); ?>">
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="uploadImage()">Upload</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteImage()">Delete</button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Username</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($user['username']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($user['full_name']); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mobile Number</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($user['mobile_no']); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Role</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info"><?php echo htmlspecialchars($user['role']); ?></span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    <?php if ($user['status'] == 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- System Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gear"></i> System Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">User ID</label>
                    <p class="form-control-plaintext"><?php echo $user['id']; ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Created Date</label>
                    <p class="form-control-plaintext"><?php echo $user['created_date'] ? date('d M Y H:i', strtotime($user['created_date'])) : '-'; ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Last Updated</label>
                    <p class="form-control-plaintext"><?php echo $user['updated_date'] ? date('d M Y H:i', strtotime($user['updated_date'])) : '-'; ?></p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning-fill"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="userupdate.php?id=<?php echo $id; ?>" class="btn btn-outline-primary">
                        <i class="bi bi-pencil"></i> Edit User
                    </a>
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="deleteUser(<?php echo $id; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">
                        <i class="bi bi-trash"></i> Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(id, username) {
    if (confirm(`Are you sure you want to delete user "${username}"? This action cannot be undone.`)) {
        window.location.href = `userdelete.php?id=${id}`;
    }
}

function uploadImage() {
    const url = `uimageupload.php?id=<?php echo $id; ?>`;
    window.open(url, 'upload', 'width=600,height=400,scrollbars=yes');
}

function deleteImage() {
    if (confirm('Are you sure you want to delete this image?')) {
        window.location.href = `uimagedelete.php?id=<?php echo $id; ?>&u_image=<?php echo $user['u_image']; ?>`;
    }
}
</script>

<?php include('../includes/footer.php'); ?>