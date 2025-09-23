<?php
include('../init.php');
check_login();

include('../includes/header.php');

// Get all labels
$result = get_labels();

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
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-tags"></i> Labels Management
                    </h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLabelModal">
                        <i class="bi bi-plus-circle"></i> Add Label
                    </button>
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
                </div>
            </div>
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