<?php
include('../init.php');
check_login();

// Get type_id from URL
$type_id = isset($_GET['type_id']) ? (int)$_GET['type_id'] : 0;
$group_id = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;

if ($type_id <= 0) {
    header('Location: index.php');
    exit;
}

$group_type = get_group_type($type_id);
if (!$group_type) {
    header('Location: index.php');
    exit;
}

// Get all groups for this type
$groups = get_groups($type_id, true);
$member_counts = get_group_member_counts($type_id);

// If no group selected, select the first one
if ($group_id <= 0 && !empty($groups)) {
    $group_id = $groups[0]['id'];
}

$selected_group = null;
if ($group_id > 0) {
    $selected_group = get_group($group_id);
}

include('../includes/header.php');
?>

<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Groups</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($group_type['name']); ?></li>
        </ol>
    </nav>
    
    <?php if (empty($groups)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-folder2-open fs-1 text-muted"></i>
            <p class="text-muted mt-2">No groups created for this type yet.</p>
            <a href="../settings/groups.php?type_id=<?php echo $type_id; ?>" class="btn btn-primary">
                <i class="bi bi-gear"></i> Go to Settings to Add Groups
            </a>
        </div>
    </div>
    <?php else: ?>
    <div class="row">
        <!-- Left Sidebar - Groups List -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Groups</h6>
                </div>
                <div class="list-group list-group-flush" style="max-height: 70vh; overflow-y: auto;">
                    <?php foreach ($groups as $group): 
                        $count = $member_counts[$group['id']] ?? 0;
                        $is_active = ($group['id'] == $group_id);
                    ?>
                    <a href="view.php?type_id=<?php echo $type_id; ?>&group_id=<?php echo $group['id']; ?>" 
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo $is_active ? 'active' : ''; ?>">
                        <span><?php echo htmlspecialchars($group['name']); ?></span>
                        <span class="badge <?php echo $is_active ? 'bg-light text-dark' : 'bg-secondary'; ?>"><?php echo $count; ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Right Panel - Members List -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0"><?php echo htmlspecialchars($selected_group['name'] ?? 'Select a Group'); ?></h5>
                            <?php if ($selected_group): ?>
                            <small class="text-muted"><?php echo $member_counts[$group_id] ?? 0; ?> members</small>
                            <?php endif; ?>
                        </div>
                        <?php if ($selected_group): ?>
                        <div>
                            <a href="print.php?group_id=<?php echo $group_id; ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                                <i class="bi bi-printer"></i> Print
                            </a>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-download"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="export.php?group_id=<?php echo $group_id; ?>&format=csv">CSV</a></li>
                                    <li><a class="dropdown-item" href="export.php?group_id=<?php echo $group_id; ?>&format=excel">Excel</a></li>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($selected_group): ?>
                    <!-- Search Bar -->
                    <div class="mb-3">
                        <form method="get" class="row g-2">
                            <input type="hidden" name="type_id" value="<?php echo $type_id; ?>">
                            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search by name, ID, mobile..." 
                                       value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                <?php if (!empty($_GET['search'])): ?>
                                <a href="view.php?type_id=<?php echo $type_id; ?>&group_id=<?php echo $group_id; ?>" class="btn btn-outline-secondary">
                                    Clear
                                </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                    
                    <?php
                    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                    $per_page = get_setting_int('members_per_page', 25);
                    $search = $_GET['search'] ?? '';
                    
                    $result = get_members_by_group($group_id, $page, $per_page, $search);
                    $members = $result['members'];
                    $total = $result['total'];
                    $total_pages = $result['pages'];
                    ?>
                    
                    <?php if (empty($members)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-people fs-1 text-muted"></i>
                        <p class="text-muted mt-2">
                            <?php echo !empty($search) ? 'No members found matching your search.' : 'No members assigned to this group yet.'; ?>
                        </p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Village</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($members as $member): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member['member_id'] ?? '-'); ?></td>
                                    <td>
                                        <a href="../member/viewmember.php?id=<?php echo $member['id']; ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($member['name']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($member['mobile_no'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($member['village'] ?? '-'); ?></td>
                                    <td>
                                        <a href="../member/viewmember.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?type_id=<?php echo $type_id; ?>&group_id=<?php echo $group_id; ?>&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">
                                    Previous
                                </a>
                            </li>
                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?type_id=<?php echo $type_id; ?>&group_id=<?php echo $group_id; ?>&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?type_id=<?php echo $type_id; ?>&group_id=<?php echo $group_id; ?>&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <p class="text-center text-muted">
                        Showing <?php echo count($members); ?> of <?php echo $total; ?> members (Page <?php echo $page; ?> of <?php echo $total_pages; ?>)
                    </p>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <p class="text-muted">Select a group from the left to view members.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>

