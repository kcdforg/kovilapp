<?php
include('init.php');

// Check if user is logged in
check_login();

// Handle restore action
if (isset($_GET['action']) && $_GET['action'] == 'restore' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($id > 0) {
        $sql = "UPDATE $tbl_family SET deleted = 0 WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: trash.php?success=restored');
            exit();
        } else {
            mysqli_stmt_close($stmt);
            header('Location: trash.php?error=restore_failed');
            exit();
        }
    }
}

// Handle permanent delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete_permanent' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($id > 0) {
        $sql = "DELETE FROM $tbl_family WHERE id = ? AND deleted = 1";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: trash.php?success=deleted_permanently');
            exit();
        } else {
            mysqli_stmt_close($stmt);
            header('Location: trash.php?error=delete_failed');
            exit();
        }
    }
}

// Pagination setup
$per_page = 25;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $per_page;

// Get deleted members count
$count_sql = "SELECT COUNT(*) as total FROM $tbl_family WHERE deleted = 1";
$count_result = mysqli_query($con, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $per_page);

// Get deleted members
$sql = "SELECT * FROM $tbl_family WHERE deleted = 1 ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $per_page, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$deleted_members = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

include('includes/header.php');
?>

<div class="container-fluid mt-4">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i>
            <?php 
            if ($_GET['success'] == 'restored') {
                echo 'Member restored successfully!';
            } elseif ($_GET['success'] == 'deleted_permanently') {
                echo 'Member deleted permanently!';
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            <?php 
            if ($_GET['error'] == 'restore_failed') {
                echo 'Failed to restore member. Please try again.';
            } elseif ($_GET['error'] == 'delete_failed') {
                echo 'Failed to delete member. Please try again.';
            } elseif ($_GET['error'] == 'invalid_id') {
                echo 'Invalid member ID.';
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-trash"></i> Trash - Deleted Members
                    </h5>
                    <span class="badge bg-danger"><?php echo $total_records; ?> Deleted</span>
                </div>
                <div class="card-body">
                    <?php if (empty($deleted_members)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-trash" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="mt-3 text-muted">Trash is Empty</h5>
                            <p class="text-muted">No deleted members found</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Member ID</th>
                                        <th>Name</th>
                                        <th>Wife Name</th>
                                        <th>Mobile</th>
                                        <th>Father's Name</th>
                                        <th>Mother's Name</th>
                                        <th>Address</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $counter = $offset;
                                    foreach ($deleted_members as $member): 
                                        $counter++;
                                    ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><?php echo htmlspecialchars($member['member_id'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($member['name']); ?></td>
                                        <td><?php echo htmlspecialchars($member['w_name'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($member['mobile_no'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($member['father_name'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($member['mother_name'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($member['permanent_address'] ?? '-'); ?></td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm btn-success" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#restoreModal"
                                                    data-member-id="<?php echo $member['id']; ?>"
                                                    data-member-name="<?php echo htmlspecialchars($member['name']); ?>"
                                                    title="Restore">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#permanentDeleteModal"
                                                    data-member-id="<?php echo $member['id']; ?>"
                                                    data-member-name="<?php echo htmlspecialchars($member['name']); ?>"
                                                    title="Delete Permanently">
                                                <i class="bi bi-trash"></i> Delete Forever
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo ($page - 1); ?>">Previous</a>
                                </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>

                                <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo ($page + 1); ?>">Next</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Restore Confirmation Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="restoreModalLabel">
                    <i class="bi bi-arrow-counterclockwise"></i> Confirm Restore
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="restoreModalBody">
                <p>Are you sure you want to restore member <strong id="restoreMemberName"></strong>?</p>
                <p class="text-muted mb-0">
                    <i class="bi bi-info-circle"></i> 
                    This will restore the member to the active members list.
                </p>
            </div>
            <div class="modal-footer" id="restoreModalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-success" id="confirmRestore">
                    <i class="bi bi-arrow-counterclockwise"></i> Restore Member
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Permanent Delete Confirmation Modal -->
<div class="modal fade" id="permanentDeleteModal" tabindex="-1" aria-labelledby="permanentDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="permanentDeleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirm Permanent Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="permanentDeleteModalBody">
                <p>Are you sure you want to permanently delete member <strong id="deleteMemberName"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    <strong>Warning:</strong> This action cannot be undone and will permanently remove all data.
                </p>
            </div>
            <div class="modal-footer" id="permanentDeleteModalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmPermanentDelete">
                    <i class="bi bi-trash"></i> Delete Forever
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let memberToRestore = null;
let memberToDeletePermanently = null;

// Listen for restore modal show event
document.addEventListener('DOMContentLoaded', function() {
    const restoreModalElement = document.getElementById('restoreModal');
    const permanentDeleteModalElement = document.getElementById('permanentDeleteModal');
    
    restoreModalElement.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        memberToRestore = button.getAttribute('data-member-id');
        const memberName = button.getAttribute('data-member-name');
        document.getElementById('restoreMemberName').textContent = memberName;
    });
    
    permanentDeleteModalElement.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        memberToDeletePermanently = button.getAttribute('data-member-id');
        const memberName = button.getAttribute('data-member-name');
        document.getElementById('deleteMemberName').textContent = memberName;
    });
    
    // Restore member
    document.getElementById('confirmRestore').addEventListener('click', function() {
        if (memberToRestore) {
            window.location.href = `trash.php?action=restore&id=${memberToRestore}`;
        }
    });
    
    // Permanent delete member
    document.getElementById('confirmPermanentDelete').addEventListener('click', function() {
        if (memberToDeletePermanently) {
            window.location.href = `trash.php?action=delete_permanent&id=${memberToDeletePermanently}`;
        }
    });
});
</script>

<?php include('includes/footer.php'); ?>
