<?php
include('../init.php');

// Check if user is logged in
check_login();

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $res = add_user($_POST);
    if ($res) {
        header("Location: userlist.php");
        exit();
    } else {
        $error_message = 'Error: ' . mysqli_error($con);
    }
}

include('../includes/header.php');
?>

<style>
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e3e6f0;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.alert {
    border-radius: 10px;
    border: none;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Add New User</h1>
            <a href="userlist.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>
</div>

<?php if ($error_message): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-plus"></i> User Information
                </h5>
            </div>
            <div class="card-body">
                <form method="post" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   placeholder="Enter username" required>
                            <div class="invalid-feedback">
                                Please provide a username.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Enter email address" required>
                            <div class="invalid-feedback">
                                Please provide a valid email address.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Enter password" required>
                            <div class="invalid-feedback">
                                Please provide a password.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="user">User</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a role.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="profile_id" class="form-label fw-bold">Profile ID</label>
                            <input type="text" class="form-control" id="profile_id" name="profile_id" 
                                   placeholder="Enter profile ID">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="created_by" class="form-label fw-bold">Created By</label>
                            <input type="text" class="form-control" id="created_by" name="created_by" 
                                   value="<?php echo $_SESSION['name'] ?? 'Admin'; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <hr class="my-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="userlist.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Create User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>

<?php include('../includes/footer.php'); ?>