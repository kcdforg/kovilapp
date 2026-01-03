<?php
include('../init.php');
check_login();

// Get type_id from URL (for groups view)
$type_id = isset($_GET['type_id']) ? (int)$_GET['type_id'] : 0;
$group_type = null;

if ($type_id > 0) {
    $group_type = get_group_type($type_id);
    if (!$group_type) {
        // Invalid type_id, redirect to main page
        header('Location: groups.php');
        exit;
    }
}

// Handle form submissions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_group_type':
            $data = [
                'name' => trim($_POST['name']),
                'slug' => trim($_POST['slug']),
                'description' => trim($_POST['description']),
                'is_required' => isset($_POST['is_required']) ? 1 : 0,
                'display_order' => (int)$_POST['display_order']
            ];
            if (add_group_type($data)) {
                $message = 'Group type added successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to add group type. Slug may already exist.';
                $message_type = 'danger';
            }
            break;
            
        case 'update_group_type':
            $id = (int)$_POST['id'];
            $data = [
                'name' => trim($_POST['name']),
                'slug' => trim($_POST['slug']),
                'description' => trim($_POST['description']),
                'is_required' => isset($_POST['is_required']) ? 1 : 0,
                'display_order' => (int)$_POST['display_order']
            ];
            if (update_group_type($id, $data)) {
                $message = 'Group type updated successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to update group type.';
                $message_type = 'danger';
            }
            break;
            
        case 'delete_group_type':
            $id = (int)$_POST['id'];
            $result = delete_group_type($id);
            $message = $result['message'];
            $message_type = $result['success'] ? 'success' : 'danger';
            break;
            
        case 'toggle_group_type_status':
            $id = (int)$_POST['id'];
            toggle_group_type_status($id);
            $message = 'Status updated.';
            $message_type = 'success';
            break;
            
        case 'add_group':
            $data = [
                'group_type_id' => $type_id,
                'name' => trim($_POST['name']),
                'code' => trim($_POST['code']),
                'description' => trim($_POST['description']),
                'display_order' => (int)$_POST['display_order']
            ];
            if (add_group($data)) {
                $message = 'Group added successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to add group.';
                $message_type = 'danger';
            }
            break;
            
        case 'update_group':
            $id = (int)$_POST['id'];
            $data = [
                'name' => trim($_POST['name']),
                'code' => trim($_POST['code']),
                'description' => trim($_POST['description']),
                'display_order' => (int)$_POST['display_order']
            ];
            if (update_group($id, $data)) {
                $message = 'Group updated successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to update group.';
                $message_type = 'danger';
            }
            break;
            
        case 'delete_group':
            $id = (int)$_POST['id'];
            $result = delete_group($id);
            $message = $result['message'];
            $message_type = $result['success'] ? 'success' : 'danger';
            break;
            
        case 'toggle_group_status':
            $id = (int)$_POST['id'];
            toggle_group_status($id);
            $message = 'Status updated.';
            $message_type = 'success';
            break;
    }
    
    // Refresh group type data after updates
    if ($type_id > 0) {
        $group_type = get_group_type($type_id);
    }
}

include('../includes/header.php');
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($type_id > 0 && $group_type): ?>
            <!-- Groups List View -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="groups.php" class="text-decoration-none">
                                <i class="bi bi-arrow-left"></i> Back to Group Types
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Group Type Heading - Center Aligned -->
                    <div class="text-center mb-3">
                        <h3 class="fw-bold mb-2"><?php echo htmlspecialchars($group_type['name']); ?> Groups</h3>
                        <p class="text-muted mb-0">Manage groups under "<?php echo htmlspecialchars($group_type['name']); ?>" type</p>
                    </div>
                    
                    <!-- Add Group Button -->
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal">
                            <i class="bi bi-plus"></i> Add Group
                        </button>
                    </div>
                    
                    <?php 
                    $groups = get_groups($type_id, false);
                    $member_counts = get_group_member_counts($type_id);
                    ?>
                    
                    <?php if (empty($groups)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-folder2-open fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No groups created yet.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal">
                            <i class="bi bi-plus"></i> Add First Group
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Members</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($groups as $i => $group): 
                                    $member_count = $member_counts[$group['id']] ?? 0;
                                ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><?php echo htmlspecialchars($group['name']); ?></td>
                                    <td><?php echo htmlspecialchars($group['code'] ?? '-'); ?></td>
                                    <td><?php echo $member_count; ?></td>
                                    <td>
                                        <?php if ($group['is_active']): ?>
                                        <span class="text-success">Active</span>
                                        <?php else: ?>
                                        <span class="text-muted">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="editGroup(<?php echo htmlspecialchars(json_encode($group)); ?>)">
                                            Edit
                                        </button>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Toggle status?')">
                                            <input type="hidden" name="action" value="toggle_group_status">
                                            <input type="hidden" name="id" value="<?php echo $group['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                <?php echo $group['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                            </button>
                                        </form>
                                        <?php if ($member_count == 0): ?>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Delete this group?')">
                                            <input type="hidden" name="action" value="delete_group">
                                            <input type="hidden" name="id" value="<?php echo $group['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                        <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" disabled 
                                                title="Cannot delete: has <?php echo $member_count; ?> member(s)">Delete</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Add Group Modal -->
            <div class="modal fade" id="addGroupModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post">
                            <input type="hidden" name="action" value="add_group">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Group</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Code</label>
                                    <input type="text" class="form-control" name="code" maxlength="20">
                                    <small class="text-muted">Short code for the group (optional)</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="description">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" class="form-control" name="display_order" value="0">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Edit Group Modal -->
            <div class="modal fade" id="editGroupModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post">
                            <input type="hidden" name="action" value="update_group">
                            <input type="hidden" name="id" id="edit_group_id">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Group</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="edit_group_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Code</label>
                                    <input type="text" class="form-control" name="code" id="edit_group_code" maxlength="20">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="description" id="edit_group_description">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" class="form-control" name="display_order" id="edit_group_display_order">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Group Types List View -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-diagram-3"></i> Manage Member Groups
                        </h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupTypeModal">
                            <i class="bi bi-plus"></i> Add Group Type
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php $group_types = get_group_types(false); ?>
                    
                    <?php if (empty($group_types)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-folder2-open fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No group types created yet.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupTypeModal">
                            <i class="bi bi-plus"></i> Create First Group Type
                        </button>
                    </div>
                    <?php else: ?>
                    <p class="text-muted mb-3">Click on a group type name to manage its groups.</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Groups</th>
                                    <th>Required</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($group_types as $i => $type): 
                                    $group_count = get_group_count($type['id'], false);
                                ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td>
                                        <a href="groups.php?type_id=<?php echo $type['id']; ?>" class="text-decoration-none fw-bold">
                                            <?php echo htmlspecialchars($type['name']); ?>
                                        </a>
                                    </td>
                                    <td><code><?php echo htmlspecialchars($type['slug']); ?></code></td>
                                    <td><?php echo $group_count; ?></td>
                                    <td><?php echo $type['is_required'] ? 'Yes' : 'No'; ?></td>
                                    <td>
                                        <?php if ($type['is_active']): ?>
                                        <span class="text-success">Active</span>
                                        <?php else: ?>
                                        <span class="text-muted">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="editGroupType(<?php echo htmlspecialchars(json_encode($type)); ?>)">
                                            Edit
                                        </button>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Toggle status?')">
                                            <input type="hidden" name="action" value="toggle_group_type_status">
                                            <input type="hidden" name="id" value="<?php echo $type['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                <?php echo $type['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                            </button>
                                        </form>
                                        <?php if ($group_count == 0): ?>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Delete this group type?')">
                                            <input type="hidden" name="action" value="delete_group_type">
                                            <input type="hidden" name="id" value="<?php echo $type['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                        <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" disabled 
                                                title="Cannot delete: has <?php echo $group_count; ?> group(s)">Delete</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Add Group Type Modal -->
            <div class="modal fade" id="addGroupTypeModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post">
                            <input type="hidden" name="action" value="add_group_type">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Group Type</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="add_type_name" required 
                                           onkeyup="generateSlug()">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="slug" id="add_type_slug" required>
                                    <small class="text-muted">Auto-generated from name</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="description">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" name="is_required" id="add_type_required">
                                    <label class="form-check-label" for="add_type_required">Required for all members</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" class="form-control" name="display_order" value="0">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Edit Group Type Modal -->
            <div class="modal fade" id="editGroupTypeModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post">
                            <input type="hidden" name="action" value="update_group_type">
                            <input type="hidden" name="id" id="edit_type_id">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Group Type</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="edit_type_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="slug" id="edit_type_slug" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="description" id="edit_type_description">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" name="is_required" id="edit_type_required">
                                    <label class="form-check-label" for="edit_type_required">Required for all members</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" class="form-control" name="display_order" id="edit_type_display_order">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function generateSlug() {
    const name = document.getElementById('add_type_name').value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    document.getElementById('add_type_slug').value = slug;
}

function editGroupType(type) {
    document.getElementById('edit_type_id').value = type.id;
    document.getElementById('edit_type_name').value = type.name;
    document.getElementById('edit_type_slug').value = type.slug;
    document.getElementById('edit_type_description').value = type.description || '';
    document.getElementById('edit_type_required').checked = type.is_required == 1;
    document.getElementById('edit_type_display_order').value = type.display_order;
    
    new bootstrap.Modal(document.getElementById('editGroupTypeModal')).show();
}

function editGroup(group) {
    document.getElementById('edit_group_id').value = group.id;
    document.getElementById('edit_group_name').value = group.name;
    document.getElementById('edit_group_code').value = group.code || '';
    document.getElementById('edit_group_description').value = group.description || '';
    document.getElementById('edit_group_display_order').value = group.display_order;
    
    new bootstrap.Modal(document.getElementById('editGroupModal')).show();
}
</script>

<?php include('../includes/footer.php'); ?>



