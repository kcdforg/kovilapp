<?php
include('init.php');

// Check if user is logged in
check_login();

$user_id = $_SESSION['ID'] ?? 0;
$user = get_user($user_id);

include('includes/header.php');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">User Profile</h1>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-circle"></i> Profile Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['username'] ?? ''); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['name'] ?? ''); ?></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['role'] ?? 'User'); ?></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Created Date</label>
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['created_date'] ?? ''); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Last Login</label>
                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user['last_login'] ?? 'Never'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-shield-check"></i> Account Status
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Account Status</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Login Status</span>
                        <span class="badge bg-info">Online</span>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>Welcome back!</strong> You're currently logged in to the modern version of Kovil App.
                </div>
                
                <div class="d-grid gap-2">
                    <a href="user/userupdate.php?id=<?php echo $user_id; ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </a>
                    <a href="logout.php" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?> 