<?php
include('../init.php');

// Check if user is logged in
check_login();

// Pagination setup
$per_page = 25;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $per_page;

// Filtering (search, village, kattalai)
$where = [];
$params = [];
if (!empty($_GET['search'])) {
    $where[] = "name LIKE ?";
    $params[] = '%' . $_GET['search'] . '%';
}
if (!empty($_GET['village'])) {
    $where[] = "village = ?";
    $params[] = $_GET['village'];
}
if (!empty($_GET['kattalai'])) {
    $where[] = "kattalai = ?";
    $params[] = $_GET['kattalai'];
}
$where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// Get total records for pagination
$stmt = $con->prepare("SELECT COUNT(*) FROM $tbl_family $where_sql");
if ($params) $stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$stmt->bind_result($total_records);
$stmt->fetch();
$stmt->close();

// Get paginated records
$sql = "SELECT * FROM $tbl_family $where_sql ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $con->prepare($sql);
if ($params) {
    $types = str_repeat('s', count($params)) . 'ii';
    $bind_params = array_merge($params, [$per_page, $offset]);
    $stmt->bind_param($types, ...$bind_params);
} else {
    $stmt->bind_param('ii', $per_page, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
$families = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

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
/* Fluid Layout with Margins */
.fluid-with-margins {
    margin-left: 2rem;
    margin-right: 2rem;
}

@media (min-width: 1400px) {
    .fluid-with-margins {
        margin-left: 4rem;
        margin-right: 4rem;
    }
}

@media (min-width: 1600px) {
    .fluid-with-margins {
        margin-left: 6rem;
        margin-right: 6rem;
    }
}

@media (max-width: 768px) {
    .fluid-with-margins {
        margin-left: 1rem;
        margin-right: 1rem;
    }
}

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

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    border: none;
    color: #fff !important;
}

.table thead th {
    background: #e9ecef !important;
    background-image: none !important;
    color: #212529 !important;
}
</style>

<!-- Layout Comparison Notice -->
<div class="alert alert-warning alert-dismissible fade show mb-4 fluid-with-margins" role="alert">
    <div class="d-flex align-items-center">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>
            <strong>Fluid Layout with Margins:</strong> This version uses fluid layout with responsive margins for better readability while maintaining full-width behavior. 
            <br><small class="text-muted">Margins increase on larger screens (2rem → 4rem → 6rem) to prevent content from stretching too wide.</small>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<?php if ($success_message): ?>
    <div class="row fluid-with-margins">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="row fluid-with-margins">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row fluid-with-margins">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Member Management</h1>
                <small class="text-muted">
                    <i class="bi bi-arrows-expand"></i> Fluid Layout with Responsive Margins
                </small>
            </div>
            <div class="d-flex gap-2">
                <a href="addmember.php" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Add New Member
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="row mb-4 fluid-with-margins">
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
<div class="row fluid-with-margins">
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
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Permanent Address</th>
                                <th>Current Address</th>
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
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="member-info">
                                                <?php
                                                $name_parts = explode(' ', $family['name']);
                                                $initials = '';
                                                if (count($name_parts) >= 2) {
                                                    $initials = strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
                                                } else {
                                                    $initials = strtoupper(substr($family['name'], 0, 2));
                                                }
                                                $image_path = '';
                                                $image_alt = htmlspecialchars($family['name']);
                                                if (!empty($family['image']) && file_exists("../images/member/" . $family['image'])) {
                                                    $image_path = "../images/member/" . $family['image'];
                                                } elseif (file_exists("../images/default.png")) {
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
                                        <td>
                                            <?php if ($family['mobile_no']): ?>
                                                <?php echo htmlspecialchars($family['mobile_no']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $permanent_address = [];
                                            if ($family['village']) $permanent_address[] = $family['village'];
                                            if ($family['taluk']) $permanent_address[] = $family['taluk'];
                                            if ($family['district']) $permanent_address[] = $family['district'];
                                            if ($family['state']) $permanent_address[] = $family['state'];
                                            if ($family['pincode']) $permanent_address[] = $family['pincode'];
                                            
                                            if (!empty($permanent_address)): ?>
                                                <small><?php echo htmlspecialchars(implode(', ', $permanent_address)); ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $current_address = [];
                                            if ($family['c_village']) $current_address[] = $family['c_village'];
                                            if ($family['c_taluk']) $current_address[] = $family['c_taluk'];
                                            if ($family['c_district']) $current_address[] = $family['c_district'];
                                            if ($family['c_state']) $current_address[] = $family['c_state'];
                                            if ($family['c_pincode']) $current_address[] = $family['c_pincode'];
                                            
                                            if (!empty($current_address)): ?>
                                                <small><?php echo htmlspecialchars(implode(', ', $current_address)); ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
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
                                echo "<tr><td colspan='7' class='text-center text-muted'>No members found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- PHP Pagination Controls -->
                    <?php
                    $total_pages = ceil($total_records / $per_page);
                    if ($total_pages > 1):
                    ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            <li class="page-item<?php if ($page <= 1) echo ' disabled'; ?>">
                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" tabindex="-1">Previous</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item<?php if ($i == $page) echo ' active'; ?>">
                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item<?php if ($page >= $total_pages) echo ' disabled'; ?>">
                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
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
