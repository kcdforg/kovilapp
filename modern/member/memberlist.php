<?php
include('../init.php');

// Check if user is logged in
check_login();

// Get all members for DataTables (pagination handled by DataTables)
$where = '';
$families = get_families("ORDER BY id DESC");
$total_records = count($families);

include('../includes/header.php');

// Handle success/error messages
$success_message = '';
$error_message = '';

if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success_message = $_GET['message'] ?? 'Operation completed successfully.';
}

if (isset($_GET['error']) && $_GET['error'] == '1') {
    $error_message = $_GET['message'] ?? 'An error occurred.';
}
?>

<style>
.member-avatar {
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

.member-avatar:hover {
    transform: scale(1.1);
    border-color: #4e73df;
}

.member-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.member-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 2px;
}

.member-spouse {
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

/* Make active page text white in pagination */
.paginate_button.active .page-link {
    color: #fff !important;
}

/* Make active page text white in pagination */
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    color: #fff !important;
    background-color: #0d6efd !important;
}
</style>

<?php if ($success_message): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Member Management</h1>
            <a href="addmember.php" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Add New Member
            </a>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-search"></i> Search & Filter
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search by Name</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
                               placeholder="Enter member name...">
                    </div>
                    <div class="col-md-3">
                        <label for="village" class="form-label">Village</label>
                        <select class="form-select select2" id="village" name="village">
                            <option value="">All Villages</option>
                            <?php
                            $villages = get_location('village');
                            foreach ($villages as $village) {
                                $selected = (isset($_GET['village']) && $_GET['village'] == $village) ? 'selected' : '';
                                echo "<option value='$village' $selected>$village</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="kattalai" class="form-label">Kattalai</label>
                        <select class="form-select select2" id="kattalai" name="kattalai">
                            <option value="">All Kattalai</option>
                            <?php
                            $kattalais = get_labels_by_type('kattalai');
                            foreach ($kattalais as $kattalai) {
                                $selected = (isset($_GET['kattalai']) && $_GET['kattalai'] == $kattalai['id']) ? 'selected' : '';
                                echo "<option value='{$kattalai['id']}' $selected>{$kattalai['display_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Members Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-people-fill"></i> Members List
                    <span class="badge bg-primary ms-2"><?php echo $total_records; ?> Total</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Village</th>
                                <th>Mobile</th>
                                <th>Kattalai</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($families) {
                                foreach ($families as $family) {
                                    $kattalai_name = '';
                                    if ($family['kattalai']) {
                                        $kattalai = get_label($family['kattalai']);
                                        $kattalai_name = $kattalai['display_name'] ?? '';
                                    }
                                    ?>
                                    <tr data-member-id="<?php echo $family['id']; ?>">
                                        <td>
                                            <?php if ($family['member_id']): ?>
                                                <?php echo htmlspecialchars($family['member_id']); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="member-info">
                                                <?php
                                                // Get member initials for default avatar
                                                $name_parts = explode(' ', $family['name']);
                                                $initials = '';
                                                if (count($name_parts) >= 2) {
                                                    $initials = strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
                                                } else {
                                                    $initials = strtoupper(substr($family['name'], 0, 2));
                                                }
                                                
                                                // Determine the image path
                                                $image_path = '';
                                                $image_alt = htmlspecialchars($family['name']);
                                                
                                                // Check if member has a custom image
                                                if (!empty($family['image']) && file_exists("../images/member/" . $family['image'])) {
                                                    $image_path = "../images/member/" . $family['image'];
                                                } 
                                                // Check if there's a default image in modern directory
                                                elseif (file_exists("../images/default.png")) {
                                                    $image_path = "../images/default.png";
                                                }
                                                ?>
                                                
                                                <?php if ($image_path): ?>
                                                    <img src="<?php echo $image_path; ?>" 
                                                         class="member-avatar" 
                                                         alt="<?php echo $image_alt; ?>"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="member-avatar" style="display: none;">
                                                        <?php echo $initials; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="member-avatar">
                                                        <?php echo $initials; ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div>
                                                    <div class="member-name"><?php echo htmlspecialchars($family['name']); ?></div>
                                                    <?php if ($family['w_name']): ?>
                                                        <div class="member-spouse">Spouse: <?php echo htmlspecialchars($family['w_name']); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($family['village']); ?></td>
                                        <td><?php echo htmlspecialchars($family['mobile_no']); ?></td>
                                        <td>
                                            <?php if ($kattalai_name): ?>
                                                <span class="badge bg-info"><?php echo htmlspecialchars($kattalai_name); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="viewmember.php?id=<?php echo $family['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   data-bs-toggle="tooltip" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="updatemember.php?id=<?php echo $family['id']; ?>" 
                                                   class="btn btn-sm btn-outline-secondary" 
                                                   data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="tooltip" title="Delete"
                                                        onclick="deleteMember(<?php echo $family['id']; ?>, '<?php echo htmlspecialchars($family['name']); ?>')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted'>No members found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deleteModalBody">
                <p>Are you sure you want to delete member <strong id="memberName"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    This action cannot be undone and will permanently remove the member from the system.
                </p>
            </div>
            <div class="modal-footer" id="deleteModalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bi bi-trash"></i> Delete Member
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let memberToDelete = null;
let deleteModal = null;

function deleteMember(id, name) {
    memberToDelete = id;
    document.getElementById('memberName').textContent = name;
    
    // Reset modal content
    document.getElementById('deleteModalBody').innerHTML = `
        <p>Are you sure you want to delete member <strong>${name}</strong>?</p>
        <p class="text-danger mb-0">
            <i class="bi bi-exclamation-circle"></i> 
            This action cannot be undone and will permanently remove the member from the system.
        </p>
    `;
    
    document.getElementById('deleteModalFooter').innerHTML = `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cancel
        </button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
            <i class="bi bi-trash"></i> Delete Member
        </button>
    `;
    
    // Re-attach event listener
    document.getElementById('confirmDelete').addEventListener('click', handleDelete);
    
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function handleDelete() {
    if (memberToDelete) {
        const confirmBtn = document.getElementById('confirmDelete');
        const modalBody = document.getElementById('deleteModalBody');
        const modalFooter = document.getElementById('deleteModalFooter');
        
        // Show loading state
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Deleting...';
        confirmBtn.disabled = true;
        
        // Disable close button during deletion
        const closeBtn = document.querySelector('#deleteModal .btn-close');
        if (closeBtn) closeBtn.style.display = 'none';
        
        // Make AJAX call to delete member
        fetch(`deletemember.php?id=${memberToDelete}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    modalBody.innerHTML = `
                        <div class="text-center">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-success">Success!</h5>
                            <p class="mb-0">${data.message}</p>
                        </div>
                    `;
                    
                    modalFooter.innerHTML = `
                        <button type="button" class="btn btn-success" onclick="closeModalAndRefresh()">
                            <i class="bi bi-check-circle"></i> OK
                        </button>
                    `;
                    
                    // Remove the deleted member row from the table
                    const row = document.querySelector(`tr[data-member-id="${memberToDelete}"]`);
                    if (row) {
                        row.remove();
                    }
                    
                    // Update the total count
                    const totalBadge = document.querySelector('.badge.bg-primary');
                    if (totalBadge) {
                        const currentCount = parseInt(totalBadge.textContent.match(/\d+/)[0]);
                        totalBadge.textContent = `${currentCount - 1} Total`;
                    }
                    
                } else {
                    // Show error message
                    modalBody.innerHTML = `
                        <div class="text-center">
                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-danger">Error!</h5>
                            <p class="mb-0">${data.message}</p>
                        </div>
                    `;
                    
                    modalFooter.innerHTML = `
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Close
                        </button>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = `
                    <div class="text-center">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-danger">Error!</h5>
                        <p class="mb-0">An unexpected error occurred. Please try again.</p>
                    </div>
                `;
                
                modalFooter.innerHTML = `
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Close
                    </button>
                `;
            });
    }
}

function closeModalAndRefresh() {
    if (deleteModal) {
        deleteModal.hide();
    }
    // Optionally refresh the page to ensure DataTable is updated
    // window.location.reload();
}

// DataTable is initialized globally in footer.php
// No need to initialize here to avoid conflicts
</script>

<?php include('../includes/footer.php'); ?> 