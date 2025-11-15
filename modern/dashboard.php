<?php
include('init.php');

// Check if user is logged in
check_login();

// Initialize statistics
$total_members = 0;
$total_users = 0;
$total_horoscopes = 0;
$total_donations = 0;
$total_children = 0;
$total_unmarried = 0;
$unmarried_male = 0;
$unmarried_female = 0;

// Get family count (this should exist)
try {
    $total_members = count_family();
} catch (Exception $e) {
    $total_members = 0;
}

// Get children statistics
try {
    global $con, $tbl_child;
    
    // Total children
    $result = mysqli_query($con, "SELECT COUNT(*) as count FROM $tbl_child");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_children = $row['count'];
    }
    
    // Total unmarried children
    $result = mysqli_query($con, "SELECT COUNT(*) as count FROM $tbl_child WHERE c_marital_status = 'no' OR c_marital_status = 'No'");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_unmarried = $row['count'];
    }
    
    // Unmarried male children
    $result = mysqli_query($con, "SELECT COUNT(*) as count FROM $tbl_child WHERE (c_marital_status = 'no' OR c_marital_status = 'No') AND (c_gender = 'male' OR c_gender = 'Male')");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $unmarried_male = $row['count'];
    }
    
    // Unmarried female children
    $result = mysqli_query($con, "SELECT COUNT(*) as count FROM $tbl_child WHERE (c_marital_status = 'no' OR c_marital_status = 'No') AND (c_gender = 'female' OR c_gender = 'Female')");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $unmarried_female = $row['count'];
    }
} catch (Exception $e) {
    $total_children = 0;
    $total_unmarried = 0;
    $unmarried_male = 0;
    $unmarried_female = 0;
}

// Check if users table exists and get count
try {
    $result = mysqli_query($con, "SHOW TABLES LIKE 'users'");
    if (mysqli_num_rows($result) > 0) {
        $users_result = get_users();
        $total_users = mysqli_num_rows($users_result);
    }
} catch (Exception $e) {
    $total_users = 0;
}

// Check if matrimony table exists and get count
try {
    $result = mysqli_query($con, "SHOW TABLES LIKE 'matrimony'");
    if (mysqli_num_rows($result) > 0) {
        $horoscopes_result = get_horo_list();
        $total_horoscopes = mysqli_num_rows($horoscopes_result);
    }
} catch (Exception $e) {
    $total_horoscopes = 0;
}

// Check if donation table exists and get count
try {
    $result = mysqli_query($con, "SHOW TABLES LIKE 'donation'");
    if (mysqli_num_rows($result) > 0) {
        $donations_array = get_donation();
        $total_donations = count($donations_array);
    }
} catch (Exception $e) {
    $total_donations = 0;
}

include('includes/header.php');
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
</style>

<div class="row fluid-with-margins">
    <div class="col-12">
        <h1 class="h3 mb-4">Dashboard</h1>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4 fluid-with-margins">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?php echo $total_members; ?></div>
                    <div class="stats-label">Total Members</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #198754, #146c43);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?php echo $total_users; ?></div>
                    <div class="stats-label">Total Users</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #dc3545, #b02a37);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?php echo $total_horoscopes; ?></div>
                    <div class="stats-label">Horoscopes</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-heart-fill"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?php echo $total_donations; ?></div>
                    <div class="stats-label">Donations</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-gift-fill"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #6f42c1, #5a32a3);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?php echo $total_children; ?></div>
                    <div class="stats-label">Total Children</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #0dcaf0, #0aa2c0);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?php echo $total_unmarried; ?></div>
                    <div class="stats-label">Total Unmarried</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-person-hearts"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #20c997, #198754);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?php echo $unmarried_male; ?></div>
                    <div class="stats-label">Unmarried Male</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-gender-male"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #fd7e14, #dc6502);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number"><?php echo $unmarried_female; ?></div>
                    <div class="stats-label">Unmarried Female</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-gender-female"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row fluid-with-margins">
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-diagram-3 text-warning"></i> Statistics by Kootam
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Kootam</th>
                                <th class="text-end">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            global $con, $tbl_family, $tbl_labels;
                            
                            // Get kootam statistics
                            $kootam_sql = "SELECT f.w_kootam, l.display_name, COUNT(*) as count 
                                          FROM $tbl_family f 
                                          LEFT JOIN $tbl_labels l ON f.w_kootam = l.id 
                                          WHERE f.deleted = 0 AND f.w_kootam IS NOT NULL AND f.w_kootam != '' AND f.w_kootam != 0
                                          GROUP BY f.w_kootam, l.display_name 
                                          ORDER BY count DESC";
                            $kootam_result = mysqli_query($con, $kootam_sql);
                            
                            $total_kootam = 0;
                            if ($kootam_result && mysqli_num_rows($kootam_result) > 0) {
                                while ($kootam = mysqli_fetch_assoc($kootam_result)) {
                                    $total_kootam += $kootam['count'];
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($kootam['display_name'] ?? 'Unknown') . "</td>";
                                    echo "<td class='text-end'><span class='badge bg-primary'>" . $kootam['count'] . "</span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center text-muted'>No data available</td></tr>";
                            }
                            ?>
                        </tbody>
                        <?php if ($total_kootam > 0): ?>
                        <tfoot>
                            <tr class="table-active fw-bold">
                                <td>Total</td>
                                <td class="text-end"><span class="badge bg-success"><?php echo $total_kootam; ?></span></td>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history text-info"></i> Recent Members
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Village</th>
                                <th>Mobile</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recent_members = get_families("ORDER BY id DESC LIMIT 5");
                            foreach ($recent_members as $member) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($member['name'] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($member['village'] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($member['mobile_no'] ?? '') . "</td>";
                                echo "<td>";
                                echo "<a href='member/viewmember.php?id=" . $member['id'] . "' class='btn btn-sm btn-outline-primary me-1' data-bs-toggle='tooltip' title='View'>";
                                echo "<i class='bi bi-eye'></i>";
                                echo "</a>";
                                echo "<a href='member/updatemember.php?id=" . $member['id'] . "' class='btn btn-sm btn-outline-secondary' data-bs-toggle='tooltip' title='Edit'>";
                                echo "<i class='bi bi-pencil'></i>";
                                echo "</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?> 