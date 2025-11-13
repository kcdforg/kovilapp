<?php
include('init.php');

// Check if user is logged in
check_login();

// Initialize statistics
$total_members = 0;
$total_users = 0;
$total_horoscopes = 0;
$total_donations = 0;

// Get family count (this should exist)
try {
    $total_members = count_family();
} catch (Exception $e) {
    $total_members = 0;
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
</div>

<!-- Quick Actions -->
<div class="row mb-4 fluid-with-margins">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning-fill text-warning"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="member/addmember.php" class="btn btn-primary w-100">
                            <i class="bi bi-person-plus"></i> Add Member
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="user/adduser.php" class="btn btn-success w-100">
                            <i class="bi bi-person-badge"></i> Add User
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="matrimony/addhoroscope.php" class="btn btn-danger w-100">
                            <i class="bi bi-heart"></i> Add Horoscope
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="donation/adddonation.php" class="btn btn-warning w-100">
                            <i class="bi bi-gift"></i> Add Donation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row fluid-with-margins">
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
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up text-success"></i> System Status
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Database</span>
                        <span class="text-success">Online</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Server Load</span>
                        <span class="text-warning">Medium</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: 65%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Storage</span>
                        <span class="text-info">75%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: 75%"></div>
                    </div>
                </div>
                
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i>
                    <small>System running smoothly. Last backup: <?php echo date('M d, Y H:i'); ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section (if needed) -->
<div class="row fluid-with-margins">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bar-chart text-primary"></i> Monthly Statistics
                </h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Sample chart data (you can replace with real data from PHP)
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Members',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'New Horoscopes',
                data: [8, 15, 7, 12, 9, 11],
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Monthly Growth'
                }
            }
        }
    });
});
</script>

<?php include('includes/footer.php'); ?> 