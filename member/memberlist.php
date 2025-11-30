<?php
include('../init.php');

// Check if user is logged in
check_login();

// Pagination setup
$per_page = 25;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $per_page;

// Filtering (search, village, kattalai, and table filters)
$where = [];
$params = [];
$param_types = '';

// Existing filters from search form
if (!empty($_GET['search'])) {
    $where[] = "CONVERT(name USING utf8) COLLATE utf8_general_ci LIKE ?";
    $params[] = '%' . $_GET['search'] . '%';
    $param_types .= 's';
}
if (!empty($_GET['village'])) {
    $where[] = "CONVERT(village USING utf8) COLLATE utf8_general_ci = ?";
    $params[] = $_GET['village'];
    $param_types .= 's';
}
if (!empty($_GET['kattalai'])) {
    $where[] = "kattalai = ?";
    $params[] = $_GET['kattalai'];
    $param_types .= 'i';
}

// Table filter parameters
if (!empty($_GET['filter_member_id'])) {
    $where[] = "CONVERT(member_id USING utf8) COLLATE utf8_general_ci LIKE ?";
    $params[] = '%' . $_GET['filter_member_id'] . '%';
    $param_types .= 's';
}
if (!empty($_GET['filter_name'])) {
    $where[] = "CONVERT(name USING utf8) COLLATE utf8_general_ci LIKE ?";
    $params[] = '%' . $_GET['filter_name'] . '%';
    $param_types .= 's';
}
if (!empty($_GET['filter_mobile'])) {
    $where[] = "CONVERT(mobile_no USING utf8) COLLATE utf8_general_ci LIKE ?";
    $params[] = '%' . $_GET['filter_mobile'] . '%';
    $param_types .= 's';
}
if (!empty($_GET['filter_father_name'])) {
    $where[] = "CONVERT(father_name USING utf8) COLLATE utf8_general_ci LIKE ?";
    $params[] = '%' . $_GET['filter_father_name'] . '%';
    $param_types .= 's';
}
if (!empty($_GET['filter_village'])) {
    $where[] = "CONVERT(village USING utf8) COLLATE utf8_general_ci LIKE ?";
    $params[] = '%' . $_GET['filter_village'] . '%';
    $param_types .= 's';
}
if (!empty($_GET['filter_current_address'])) {
    $where[] = "(CONVERT(c_village USING utf8) COLLATE utf8_general_ci LIKE ? OR CONVERT(c_taluk USING utf8) COLLATE utf8_general_ci LIKE ? OR CONVERT(c_district USING utf8) COLLATE utf8_general_ci LIKE ? OR CONVERT(c_state USING utf8) COLLATE utf8_general_ci LIKE ? OR CONVERT(c_pincode USING utf8) COLLATE utf8_general_ci LIKE ?)";
    $search_term = '%' . $_GET['filter_current_address'] . '%';
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term, $search_term]);
    $param_types .= 'sssss';
}
if (!empty($_GET['filter_kattalai'])) {
    // Filter by kattalai ID (now using ID directly)
    $where[] = "kattalai = ?";
    $params[] = $_GET['filter_kattalai'];
    $param_types .= 'i';
}
// Add deleted filter (not as a parameter, directly in SQL)
$where[] = "deleted = 0";

$where_sql = 'WHERE ' . implode(' AND ', $where);

// Get total records for pagination
$stmt = $con->prepare("SELECT COUNT(*) FROM $tbl_family $where_sql");
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$stmt->bind_result($total_records);
$stmt->fetch();
$stmt->close();

// Get paginated records
$sql = "SELECT * FROM $tbl_family $where_sql ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $con->prepare($sql);
if (!empty($params)) {
    $types = $param_types . 'ii';
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

/* Table Header Styling - Multiple selectors for maximum specificity */
.table thead th,
.table-responsive .table thead th,
.card-body .table thead th,
.card-body .table-responsive .table thead th {
    background: linear-gradient(135deg, #5a67d8 0%, #4c51bf 100%) !important;
    background-image: none !important;
    background-color: #5a67d8 !important;
    color: #ffffff !important;
    border-color: #4c51bf !important;
}

/* Additional override for any Bootstrap defaults */
.table > thead > tr > th {
    background: linear-gradient(135deg, #5a67d8 0%, #4c51bf 100%) !important;
    background-image: none !important;
    background-color: #5a67d8 !important;
    color: #ffffff !important;
    border-color: #4c51bf !important;
}

        /* DataTables specific overrides */
        table.dataTable thead th,
        table.dataTable.table-striped > thead > tr > th,
        .dataTables_wrapper .table thead th {
            background: linear-gradient(135deg, #5a67d8 0%, #4c51bf 100%) !important;
            background-image: none !important;
            background-color: #5a67d8 !important;
            color: #ffffff !important;
            border-color: #4c51bf !important;
        }

        /* Column width controls - adjust individual columns by ID */
        
        /* Member ID column */
        #col-member-id {
            width: 100px !important;
            max-width: 100px !important;
            min-width: 100px !important;
        }
        
        /* Name column */
        #col-name,
        .table td:nth-child(2) {
            /* width: 200px !important; */
            /* max-width: 200px !important; */
            /* min-width: 200px !important; */
        }
        
        /* Mobile column */
        #col-mobile,
        .table td:nth-child(3) {
            /* width: 120px !important; */
            /* max-width: 120px !important; */
            /* min-width: 120px !important; */
        }
        
        /* Permanent Address column */
        #col-permanent-address,
        .table td:nth-child(4) {
            /* width: 250px !important; */
            /* max-width: 250px !important; */
            /* min-width: 250px !important; */
        }
        
        /* Current Address column */
        #col-current-address,
        .table td:nth-child(5) {
            /* width: 250px !important; */
            /* max-width: 250px !important; */
            /* min-width: 250px !important; */
        }
        
        /* Kattalai column */
        #col-kattalai,
        .table td:nth-child(6) {
            /* width: 120px !important; */
            /* max-width: 120px !important; */
            /* min-width: 120px !important; */
        }
        
        /* Actions column */
        #col-actions,
        .table td:nth-child(7) {
            /* width: 150px !important; */
            /* max-width: 150px !important; */
            /* min-width: 150px !important; */
        }

/* Filter Row Styling */
.filter-row th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    padding: 8px !important;
    border-color: #764ba2 !important;
}

.filter-row .form-control,
.filter-row .form-select {
    background-color: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #333;
    font-size: 0.875rem;
}

.filter-row .form-control:focus,
.filter-row .form-select:focus {
    background-color: #ffffff;
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.filter-row .form-control::placeholder {
    color: #6c757d;
    font-size: 0.8rem;
}

.filter-row .btn {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* Card Header Styling - Override to ensure it's applied */
.card-header {
    background: linear-gradient(135deg, #5a67d8 0%, #4c51bf 100%) !important;
    color: #ffffff !important;
    border-bottom: 1px solid #4c51bf !important;
    border-radius: 15px 15px 0 0 !important;
    font-weight: 600 !important;
    box-shadow: 0 2px 4px rgba(76, 81, 191, 0.1) !important;
}

.card-header .card-title {
    color: #ffffff !important;
    margin-bottom: 0 !important;
}

.card-header i {
    color: #ffffff !important;
}

.card-header a {
    color: #ffffff !important;
}

.card-header a:hover {
    color: #e9ecef !important;
}

.card-header:hover {
    background: linear-gradient(135deg, #6366f1 0%, #5b21b6 100%) !important;
    transition: all 0.3s ease !important;
}
</style>


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
                <button onclick="printReport()" class="btn btn-success">
                    <i class="bi bi-printer"></i> Print Report
                </button>
                <button onclick="exportExcel()" class="btn btn-info">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>
                <a href="addmember.php" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Add New Member
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<!-- <div class="row mb-4 fluid-with-margins">
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
</div> -->

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
                <form method="GET" id="tableFilterForm">
                    <!-- Preserve existing search parameters -->
                    <?php if (isset($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                    <?php endif; ?>
                    <?php if (isset($_GET['village'])): ?>
                        <input type="hidden" name="village" value="<?php echo htmlspecialchars($_GET['village']); ?>">
                    <?php endif; ?>
                    <?php if (isset($_GET['kattalai'])): ?>
                        <input type="hidden" name="kattalai" value="<?php echo htmlspecialchars($_GET['kattalai']); ?>">
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th id="col-member-id">Member ID</th>
                                <th id="col-name">Name</th>
                                <th id="col-mobile">Mobile</th>
                                <th id="col-father-name">Father's Name</th>
                                <th id="col-village">Village</th>
                                <th id="col-current-address">Current Address</th>
                                <th id="col-kattalai">Kattalai</th>
                                <th id="col-actions">Actions</th>
                            </tr>
                            <!-- Filter Row -->
                            <tr class="filter-row">
                                <th>
                                    <input type="text" class="form-control form-control-sm table-filter" 
                                           name="filter_member_id" 
                                           placeholder="Filter ID..." 
                                           value="<?php echo isset($_GET['filter_member_id']) ? htmlspecialchars($_GET['filter_member_id']) : ''; ?>">
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm table-filter" 
                                           name="filter_name" 
                                           placeholder="Filter Name..." 
                                           value="<?php echo isset($_GET['filter_name']) ? htmlspecialchars($_GET['filter_name']) : ''; ?>">
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm table-filter" 
                                           name="filter_mobile" 
                                           placeholder="Filter Mobile..." 
                                           value="<?php echo isset($_GET['filter_mobile']) ? htmlspecialchars($_GET['filter_mobile']) : ''; ?>">
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm table-filter" 
                                           name="filter_father_name" 
                                           placeholder="Filter Father..." 
                                           value="<?php echo isset($_GET['filter_father_name']) ? htmlspecialchars($_GET['filter_father_name']) : ''; ?>">
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm table-filter" 
                                           name="filter_village" 
                                           placeholder="Filter Village..." 
                                           value="<?php echo isset($_GET['filter_village']) ? htmlspecialchars($_GET['filter_village']) : ''; ?>">
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm table-filter" 
                                           name="filter_current_address" 
                                           placeholder="Filter Address..." 
                                           value="<?php echo isset($_GET['filter_current_address']) ? htmlspecialchars($_GET['filter_current_address']) : ''; ?>">
                                </th>
                                <th>
                                    <select class="form-select form-select-sm table-filter" name="filter_kattalai">
                                        <option value="">All Kattalai</option>
                                        <?php
                                        $kattalais = get_labels_by_type('kattalai');
                                        foreach ($kattalais as $kattalai) {
                                            $selected = (isset($_GET['filter_kattalai']) && $_GET['filter_kattalai'] == $kattalai['id']) ? 'selected' : '';
                                            echo "<option value='{$kattalai['id']}' $selected>{$kattalai['display_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </th>
                                <th>
                                    <button type="button" class="btn btn-sm btn-light" id="clearTableFilters">
                                        <i class="bi bi-x-circle"></i> Clear
                                    </button>
                                </th>
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
                                                    <div class="member-name">
                                                        <a href="viewmember.php?id=<?php echo $family['id']; ?>" class="text-decoration-none">
                                                            <?php echo htmlspecialchars($family['name']); ?>
                                                        </a>
                                                    </div>
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
                                            <?php if (!empty($family['father_name'])): ?>
                                                <?php echo htmlspecialchars($family['father_name']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($family['village'])): ?>
                                                <?php echo htmlspecialchars($family['village']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            // Check if same as permanent
                                            if (isset($family['same_as_permanent']) && $family['same_as_permanent'] == 1) {
                                                // Display permanent address
                                                if (!empty($family['permanent_address'])): ?>
                                                    <small><?php echo htmlspecialchars($family['permanent_address']); ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif;
                                            } else {
                                                // Display current address
                                                if (!empty($family['current_address'])): ?>
                                                    <small><?php echo htmlspecialchars($family['current_address']); ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif;
                                            }
                                            ?>
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
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-member-id="<?php echo $family['id']; ?>"
                                                        data-member-name="<?php echo htmlspecialchars($family['name']); ?>"
                                                        title="Delete">
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
                </form>
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

// Listen for modal show event
document.addEventListener('DOMContentLoaded', function() {
    const deleteModalElement = document.getElementById('deleteModal');
    
    deleteModalElement.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        const button = event.relatedTarget;
        
        // Extract info from data-* attributes
        memberToDelete = button.getAttribute('data-member-id');
        const memberName = button.getAttribute('data-member-name');
        
        // Update the modal's content
        document.getElementById('memberName').textContent = memberName;
        
        // Reset modal content to initial state
        document.getElementById('deleteModalBody').innerHTML = `
            <p>Are you sure you want to delete member <strong>${memberName}</strong>?</p>
            <p class="text-danger mb-0">
                <i class="bi bi-exclamation-circle"></i> 
                This action cannot be undone and will permanently remove the member from the system.
            </p>
        `;
        
        document.getElementById('deleteModalFooter').innerHTML = `
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Cancel
            </button>
            <button type="button" class="btn btn-danger" id="confirmDelete" onclick="handleDelete()">
                <i class="bi bi-trash"></i> Delete Member
            </button>
        `;
    });
});

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

// Table Filter Functionality with Form Submission
document.addEventListener('DOMContentLoaded', function() {
    const tableFilters = document.querySelectorAll('.table-filter');
    const clearButton = document.getElementById('clearTableFilters');
    const filterForm = document.getElementById('tableFilterForm');
    let filterTimeout = null;
    
    // Function to submit the form (triggers page reload with new parameters)
    function submitFilterForm() {
        if (filterForm) {
            filterForm.submit();
        }
    }
    
    // Function to clear table filters only (preserve main search filters)
    function clearTableFilters() {
        // Clear only table filter inputs
        tableFilters.forEach(filter => {
            filter.value = '';
        });
        
        // Submit form to apply cleared filters
        submitFilterForm();
    }
    
    // Debounced form submission
    function debouncedSubmit() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(submitFilterForm, 800); // 800ms delay for typing
    }
    
    // Add event listeners to filter inputs
    tableFilters.forEach(filter => {
        // For text inputs, use debounced submission on input
        if (filter.type === 'text') {
            filter.addEventListener('input', debouncedSubmit);
        }
        
        // For select dropdowns, submit immediately on change
        if (filter.tagName === 'SELECT') {
            filter.addEventListener('change', submitFilterForm);
        }
        
        // Submit on Enter key
        filter.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(filterTimeout); // Cancel debounced submission
                submitFilterForm(); // Submit immediately
            }
        });
    });
    
    // Add event listener to clear button
    if (clearButton) {
        clearButton.addEventListener('click', function(e) {
            e.preventDefault();
            clearTableFilters();
        });
    }
    
    // Add keyboard shortcut (Ctrl+Shift+C to clear table filters)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'C') {
            e.preventDefault();
            clearTableFilters();
        }
    });
    
    // Show loading indicator when form is being submitted
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            // Add a subtle loading indicator
            const submitButton = document.createElement('div');
            submitButton.className = 'position-fixed top-50 start-50 translate-middle';
            submitButton.style.zIndex = '9999';
            submitButton.innerHTML = `
                <div class="bg-primary text-white px-3 py-2 rounded shadow">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        <span>Filtering...</span>
                    </div>
                </div>
            `;
            document.body.appendChild(submitButton);
        });
    }
});

// Print Report
function printReport() {
    const urlParams = new URLSearchParams(window.location.search);
    const printUrl = 'print_report.php?' + urlParams.toString();
    window.open(printUrl, '_blank');
}

// Export to Excel
function exportExcel() {
    const urlParams = new URLSearchParams(window.location.search);
    window.location.href = 'export_excel.php?' + urlParams.toString();
}
</script>

<?php include('../includes/footer.php'); ?> 