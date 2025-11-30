<?php
include('../init.php');
check_login();

// Get the type parameter
$type = isset($_GET['type']) ? $_GET['type'] : 'village';
$valid_types = ['village', 'blood_group', 'qualification', 'occupation', 'kattalai', 'pudavai'];

if (!in_array($type, $valid_types)) {
    $type = 'village';
}

// Type configurations
$type_config = [
    'village' => [
        'title' => 'Village',
        'icon' => 'bi-geo-alt',
        'column' => 'village',
        'get_function' => null
    ],
    'blood_group' => [
        'title' => 'Blood Group',
        'icon' => 'bi-droplet',
        'column' => 'blood_group',
        'get_function' => 'get_blood_group'
    ],
    'qualification' => [
        'title' => 'Qualification',
        'icon' => 'bi-mortarboard',
        'column' => 'qualification',
        'get_function' => 'get_qualification'
    ],
    'occupation' => [
        'title' => 'Occupation',
        'icon' => 'bi-briefcase',
        'column' => 'occupation',
        'get_function' => 'get_occupation'
    ],
    'kattalai' => [
        'title' => 'Kattalai',
        'icon' => 'bi-diagram-3',
        'column' => 'kattalai',
        'get_function' => 'get_kattalai'
    ],
    'pudavai' => [
        'title' => 'Pudavai',
        'icon' => 'bi-gift',
        'column' => 'pudavai',
        'get_function' => null
    ]
];

$config = $type_config[$type];
$column = $config['column'];

// Get distinct values with counts
$sql = "SELECT {$column}, COUNT(*) as cnt FROM $tbl_family WHERE deleted = 0 AND {$column} IS NOT NULL AND {$column} != '' GROUP BY {$column} ORDER BY {$column} ASC";
$result = mysqli_query($con, $sql);
$categories = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

// Get selected value
$selected_value = isset($_GET['value']) ? $_GET['value'] : '';

// Pagination
$per_page = 25;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $per_page;

// Get members if a value is selected
$members = [];
$total_records = 0;
$total_pages = 0;

if ($selected_value !== '') {
    // Count total records
    $count_sql = "SELECT COUNT(*) as total FROM $tbl_family WHERE deleted = 0 AND {$column} = ?";
    $count_stmt = mysqli_prepare($con, $count_sql);
    mysqli_stmt_bind_param($count_stmt, 's', $selected_value);
    mysqli_stmt_execute($count_stmt);
    $count_result = mysqli_stmt_get_result($count_stmt);
    $count_row = mysqli_fetch_assoc($count_result);
    $total_records = $count_row['total'];
    $total_pages = ceil($total_records / $per_page);
    mysqli_stmt_close($count_stmt);
    
    // Get members
    $members_sql = "SELECT * FROM $tbl_family WHERE deleted = 0 AND {$column} = ? ORDER BY name ASC LIMIT ? OFFSET ?";
    $members_stmt = mysqli_prepare($con, $members_sql);
    mysqli_stmt_bind_param($members_stmt, 'sii', $selected_value, $per_page, $offset);
    mysqli_stmt_execute($members_stmt);
    $members_result = mysqli_stmt_get_result($members_stmt);
    $members = mysqli_fetch_all($members_result, MYSQLI_ASSOC);
    mysqli_stmt_close($members_stmt);
}

include('../includes/header.php');
?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar with categories -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="<?php echo $config['icon']; ?>"></i> <?php echo $config['title']; ?>
                    </h5>
                </div>
                <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                    <a href="listby.php?type=<?php echo $type; ?>" 
                       class="list-group-item list-group-item-action <?php echo ($selected_value === '') ? 'active' : ''; ?>">
                        <strong>All</strong>
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <?php 
                        $display_value = $cat[$column];
                        if ($config['get_function'] && function_exists($config['get_function'])) {
                            $display_value = $config['get_function']($cat[$column]);
                        }
                        ?>
                        <a href="listby.php?type=<?php echo $type; ?>&value=<?php echo urlencode($cat[$column]); ?>" 
                           class="list-group-item list-group-item-action <?php echo ($selected_value == $cat[$column]) ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($display_value); ?>
                            <span class="badge bg-primary rounded-pill float-end"><?php echo $cat['cnt']; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Main content area -->
        <div class="col-md-9">
            <?php if ($selected_value !== ''): ?>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <?php 
                            $display_selected = $selected_value;
                            if ($config['get_function'] && function_exists($config['get_function'])) {
                                $display_selected = $config['get_function']($selected_value);
                            }
                            echo htmlspecialchars($display_selected); 
                            ?>
                        </h5>
                        <span class="badge bg-primary"><?php echo $total_records; ?> Members</span>
                    </div>
                    <div class="card-body">
                        <?php if (empty($members)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                                <h5 class="mt-3 text-muted">No Members Found</h5>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Member ID</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Village</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $counter = $offset;
                                        foreach ($members as $member): 
                                            $counter++;
                                        ?>
                                        <tr>
                                            <td><?php echo $counter; ?></td>
                                            <td><?php echo htmlspecialchars($member['member_id'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($member['name']); ?></td>
                                            <td><?php echo htmlspecialchars($member['mobile_no'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($member['village'] ?? '-'); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="viewmember.php?id=<?php echo $member['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="updatemember.php?id=<?php echo $member['id']; ?>" 
                                                       class="btn btn-sm btn-outline-secondary" 
                                                       title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
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
                                        <a class="page-link" href="?type=<?php echo $type; ?>&value=<?php echo urlencode($selected_value); ?>&page=<?php echo ($page - 1); ?>">Previous</a>
                                    </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?type=<?php echo $type; ?>&value=<?php echo urlencode($selected_value); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?type=<?php echo $type; ?>&value=<?php echo urlencode($selected_value); ?>&page=<?php echo ($page + 1); ?>">Next</a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="<?php echo $config['icon']; ?>" style="font-size: 4rem; color: #4e73df;"></i>
                        <h4 class="mt-3">Select a <?php echo $config['title']; ?></h4>
                        <p class="text-muted">Choose a <?php echo strtolower($config['title']); ?> from the list to view members</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

