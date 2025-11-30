<?php
include('../init.php');
check_login();

include('../includes/header.php');

// Filter by type
$filter_type = isset($_GET['type']) ? (int)$_GET['type'] : 0;

// Pagination settings
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
// Validate per_page value
if (!in_array($per_page, [10, 20, 50, 100])) {
    $per_page = 20;
}
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

// Get labels with pagination
global $con, $tbl_labels;

// Build WHERE clause
$where = "type != 0";
if ($filter_type > 0) {
    $where .= " AND type = $filter_type";
}

// Get total count
$count_sql = "SELECT COUNT(*) as total FROM $tbl_labels WHERE $where";
$count_result = mysqli_query($con, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $per_page);

// Get paginated labels
$tps = get_types();
$sql = "SELECT * FROM $tbl_labels WHERE $where ORDER BY type, `order` LIMIT $per_page OFFSET $offset";
$query_result = mysqli_query($con, $sql);

$result = array();
while ($row = mysqli_fetch_array($query_result)) {
    $row['type_name'] = $tps[$row['type']]['display_name'] ?? '';
    $row['type_slug'] = $tps[$row['type']]['slug'] ?? '';
    $result[] = $row;
}

// Handle success/error messages
$success_message = '';
$error_message = '';

if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success_message = $_GET['message'] ?? 'Label operation completed successfully.';
}

if (isset($_GET['error']) && $_GET['error'] == '1') {
    $error_message = $_GET['message'] ?? 'An error occurred.';
}
?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-filter"></i> Filter by Type</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="?per_page=<?php echo $per_page; ?>" class="list-group-item list-group-item-action <?php echo ($filter_type == 0) ? 'active' : ''; ?>">
                        <i class="bi bi-grid"></i> All Types
                        <span class="badge bg-secondary float-end"><?php echo array_sum(array_map(function($t) use ($con, $tbl_labels) {
                            $sql = "SELECT COUNT(*) as cnt FROM $tbl_labels WHERE type = " . $t['id'];
                            $r = mysqli_query($con, $sql);
                            $row = mysqli_fetch_assoc($r);
                            return $row['cnt'];
                        }, $tps)); ?></span>
                    </a>
                    <?php foreach ($tps as $type_id => $type): ?>
                        <?php
                        // Get count for this type
                        $type_count_sql = "SELECT COUNT(*) as cnt FROM $tbl_labels WHERE type = $type_id";
                        $type_count_result = mysqli_query($con, $type_count_sql);
                        $type_count_row = mysqli_fetch_assoc($type_count_result);
                        $type_count = $type_count_row['cnt'];
                        ?>
                        <a href="?type=<?php echo $type_id; ?>&per_page=<?php echo $per_page; ?>" 
                           class="list-group-item list-group-item-action <?php echo ($filter_type == $type_id) ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($type['display_name']); ?>
                            <span class="badge bg-secondary float-end"><?php echo $type_count; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-tags"></i> Labels Management
                        <span class="badge bg-primary ms-2"><?php echo $total_records; ?> Total</span>
                    </h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTypeModal">
                            <i class="bi bi-plus-circle"></i> Add Type
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLabelModal">
                            <i class="bi bi-plus-circle"></i> Add Label
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Pagination at top (above table) -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Records per page selector -->
                        <div class="d-flex align-items-center">
                            <label class="me-2 mb-0">Show:</label>
                            <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href='?page=1&per_page=' + this.value + '&type=<?php echo $filter_type; ?>'">
                                <option value="10" <?php echo ($per_page == 10) ? 'selected' : ''; ?>>10</option>
                                <option value="20" <?php echo ($per_page == 20) ? 'selected' : ''; ?>>20</option>
                                <option value="50" <?php echo ($per_page == 50) ? 'selected' : ''; ?>>50</option>
                                <option value="100" <?php echo ($per_page == 100) ? 'selected' : ''; ?>>100</option>
                            </select>
                            <span class="ms-2 text-muted">records per page</span>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination mb-0">
                                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?>&per_page=<?php echo $per_page; ?>&type=<?php echo $filter_type; ?>">Previous</a>
                                    </li>
                                    <?php
                                    $start_page = max(1, $page - 2);
                                    $end_page = min($total_pages, $page + 2);
                                    
                                    if ($start_page > 1): ?>
                                        <li class="page-item"><a class="page-link" href="?page=1&per_page=<?php echo $per_page; ?>&type=<?php echo $filter_type; ?>">1</a></li>
                                        <?php if ($start_page > 2): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        <?php endif;
                                    endif;
                                    
                                    for ($i = $start_page; $i <= $end_page; $i++): ?>
                                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>&per_page=<?php echo $per_page; ?>&type=<?php echo $filter_type; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor;
                                    
                                    if ($end_page < $total_pages): ?>
                                        <?php if ($end_page < $total_pages - 1): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        <?php endif; ?>
                                        <li class="page-item"><a class="page-link" href="?page=<?php echo $total_pages; ?>&per_page=<?php echo $per_page; ?>&type=<?php echo $filter_type; ?>"><?php echo $total_pages; ?></a></li>
                                    <?php endif; ?>
                                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page + 1; ?>&per_page=<?php echo $per_page; ?>&type=<?php echo $filter_type; ?>">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Display Name</th>
                                    <th>Slug</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Parent ID</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result): ?>
                                    <?php foreach ($result as $k => $row): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($row['display_name']); ?></strong>
                                            </td>
                                            <td>
                                                <code><?php echo htmlspecialchars($row['slug']); ?></code>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo htmlspecialchars($row['type_name']); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                                            <td>
                                                <?php if ($row['parent_id']): ?>
                                                    <span class="badge bg-secondary"><?php echo $row['parent_id']; ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark"><?php echo $row['order']; ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" data-bs-target="#editLabelModal"
                                                            data-label-id="<?php echo $row['id']; ?>"
                                                            data-display-name="<?php echo htmlspecialchars($row['display_name']); ?>"
                                                            data-slug="<?php echo htmlspecialchars($row['slug']); ?>"
                                                            data-type="<?php echo htmlspecialchars($row['type']); ?>"
                                                            data-category="<?php echo htmlspecialchars($row['category']); ?>"
                                                            data-parent-id="<?php echo $row['parent_id']; ?>"
                                                            data-order="<?php echo $row['order']; ?>">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="deleteLabel(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['display_name']); ?>')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <i class="bi bi-inbox"></i> No labels found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination at bottom right -->
                    <?php if ($total_pages > 1): ?>
                        <div class="d-flex justify-content-end mt-3">
                            <nav aria-label="Page navigation">
                                <ul class="pagination mb-0">
                                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?>&per_page=<?php echo $per_page; ?>&type=<?php echo $filter_type; ?>">Previous</a>
                                    </li>
                                    <?php
                                    $start_page = max(1, $page - 2);
                                    $end_page = min($total_pages, $page + 2);
                                    
                                    if ($start_page > 1): ?>
                                        <li class="page-item"><a class="page-link" href="?page=1&per_page=<?php echo $per_page; ?>&type=<?php echo $filter_type; ?>">1</a></li>
                                        <?php if ($start_page > 2): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        <?php endif;
                                    endif;
                                    
                                    for ($i = $start_page; $i <= $end_page; $i++): ?>
                                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>&per_page=<?php echo $per_page; ?>&type=<?php echo $filter_type; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor;
                                    
                                    if ($end_page < $total_pages): ?>
                                        <?php if ($end_page < $total_pages - 1): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        <?php endif; ?>
                                        <li class="page-item"><a class="page-link" href="?page=<?php echo $total_pages; ?>&per_page=<?php echo $per_page; ?>&type=<?php echo $filter_type; ?>"><?php echo $total_pages; ?></a></li>
                                    <?php endif; ?>
                                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page + 1; ?>&per_page=<?php echo $per_page; ?>&type=<?php echo $filter_type; ?>">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Type Modal -->
<div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTypeModalLabel">
                    <i class="bi bi-plus-circle"></i> Add New Type
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="add_type.php" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Type Name</label>
                            <input type="text" class="form-control" name="display_name" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Slug</label>
                            <input type="text" class="form-control" name="slug" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Order</label>
                            <input type="number" class="form-control" name="order" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Add Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Label Modal -->
<div class="modal fade" id="addLabelModal" tabindex="-1" aria-labelledby="addLabelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLabelModalLabel">
                    <i class="bi bi-plus-circle"></i> Add New Label
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="add_label.php" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Display Name</label>
                            <input type="text" class="form-control" name="display_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Slug</label>
                            <input type="text" class="form-control" name="slug" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="type" required>
                                <option value="">Select Type</option>
                                <option value="kattalai">Kattalai</option>
                                <option value="village">Village</option>
                                <option value="blood_group">Blood Group</option>
                                <option value="occupation">Occupation</option>
                                <option value="education">Education</option>
                                <option value="kootam">Kootam</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" name="category">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Parent ID</label>
                            <input type="number" class="form-control" name="parent_id" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Order</label>
                            <input type="number" class="form-control" name="order" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Add Label
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Label Modal -->
<div class="modal fade" id="editLabelModal" tabindex="-1" aria-labelledby="editLabelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLabelModalLabel">
                    <i class="bi bi-pencil"></i> Edit Label
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="update_label.php" method="POST">
                <input type="hidden" name="label_id" id="editLabelId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Display Name</label>
                            <input type="text" class="form-control" name="display_name" id="editDisplayName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Slug</label>
                            <input type="text" class="form-control" name="slug" id="editSlug" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="type" id="editType" required>
                                <option value="">Select Type</option>
                                <option value="kattalai">Kattalai</option>
                                <option value="village">Village</option>
                                <option value="blood_group">Blood Group</option>
                                <option value="occupation">Occupation</option>
                                <option value="education">Education</option>
                                <option value="kootam">Kootam</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" name="category" id="editCategory">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Parent ID</label>
                            <input type="number" class="form-control" name="parent_id" id="editParentId" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Order</label>
                            <input type="number" class="form-control" name="order" id="editOrder" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Label
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteLabelModal" tabindex="-1" aria-labelledby="deleteLabelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteLabelModalLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the label <strong id="deleteLabelName"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    This action cannot be undone and may affect data that references this label.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteLabel" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Delete Label
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Handle edit modal data population
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editLabelModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const labelId = button.getAttribute('data-label-id');
            const displayName = button.getAttribute('data-display-name');
            const slug = button.getAttribute('data-slug');
            const type = button.getAttribute('data-type');
            const category = button.getAttribute('data-category');
            const parentId = button.getAttribute('data-parent-id');
            const order = button.getAttribute('data-order');
            
            document.getElementById('editLabelId').value = labelId;
            document.getElementById('editDisplayName').value = displayName;
            document.getElementById('editSlug').value = slug;
            document.getElementById('editType').value = type;
            document.getElementById('editCategory').value = category;
            document.getElementById('editParentId').value = parentId;
            document.getElementById('editOrder').value = order;
        });
    }
});

function deleteLabel(id, name) {
    document.getElementById('deleteLabelName').textContent = name;
    document.getElementById('confirmDeleteLabel').href = 'delete_label.php?id=' + id;
    new bootstrap.Modal(document.getElementById('deleteLabelModal')).show();
}
</script>

<?php include('../includes/footer.php'); ?> 