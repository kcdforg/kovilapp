<?php
include('../init.php');
check_login();
include('../includes/header.php');
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

<?php
//var_dump($_POST);

$rec_per_page = 25;
$curr_page = 1;
$offset = ($curr_page - 1) * ($rec_per_page);

$where = '';
if (isset($_POST['filter'])) {
    if ($_POST['reg_no'] != '') {
        $where .= " AND reg_no = " . $_POST['reg_no'];
    }
    if ($_POST['keyword'] != '') {
        $where .= " AND (name = '" . $_POST['keyword'] . "' OR mobile_no = '" . $_POST['keyword'] . "')";
    }
}

$sql_total = "SELECT count(*) as total FROM $tbl_matrimony where `deleted`=0 $where";
$result = mysqli_query($con, $sql_total);
$row = mysqli_fetch_array($result);
$total_records = $row['total'];
//echo $total_records . "<br>";
$total_pages = ceil($total_records / $rec_per_page);
//echo $total_pages;
?> 	

<div class="row fluid-with-margins">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Horoscope List</h2>
                <div class="btn-group" role="group">
                    <a href="addhoroscope.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New
                    </a>
                    <a href="searchhoroscope.php" class="btn btn-info">
                        <i class="bi bi-search"></i> Search
                    </a>
                </div>
            </div>

            <!-- Search Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" class="row g-3">
                        <div class="col-md-4">
                            <label for="reg_no" class="form-label">Registration No</label>
                            <input type="text" name="reg_no" class="form-control" id="reg_no" placeholder="Enter registration number">
                        </div>
                        <div class="col-md-4">
                            <label for="keyword" class="form-label">Name/Mobile No</label>
                            <input type="text" name="keyword" class="form-control" id="keyword" placeholder="Enter name or mobile number">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <input type="hidden" name="filter">
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pagination -->
            <?php if ($total_records > $rec_per_page) { ?>
                <nav aria-label="Page navigation" class="mb-3">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="listhoroscope.php?page=<?php echo max(1, $curr_page - 1) ?>">
                                <i class="bi bi-chevron-left"></i> Previous
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                            <li class="page-item <?php echo ($i == $curr_page) ? 'active' : ''; ?>">
                                <a class="page-link" href="listhoroscope.php?page=<?php echo $i ?>"><?php echo $i; ?></a>
                            </li>
                        <?php } ?>
                        <li class="page-item">
                            <a class="page-link" href="listhoroscope.php?page=<?php echo min($total_pages, $curr_page + 1) ?>">
                                Next <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php } ?>

            <!-- Horoscope List Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul"></i> Horoscope Records (<?php echo $total_records; ?> total)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr> 
                                    <th width="3%">S.No</th>
                                    <th width="8%">Reg No</th>
                                    <th width="15%">Name</th>
                                    <th width="8%">Gender</th>
                                    <th width="8%">Age</th>
                                    <th width="12%">Mobile</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM $tbl_matrimony WHERE `deleted`=0 $where ORDER BY id DESC LIMIT $offset, $rec_per_page";
                                $result = mysqli_query($con, $sql);
                                $i = $offset + 1;
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row['reg_no']; ?></td>
                                        <td>
                                            <strong><?php echo $row['name']; ?></strong>
                                            <?php if ($row['email']) { ?>
                                                <br><small class="text-muted"><?php echo $row['email']; ?></small>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo ($row['gender'] == 'male') ? 'primary' : 'danger'; ?>">
                                                <?php echo ucfirst($row['gender']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $row['age']; ?></td>
                                        <td><?php echo $row['mobile_no']; ?></td>
                                        <td>
                                            <?php if ($row['status'] == 'closed') { ?>
                                                <span class="badge bg-secondary">Closed</span>
                                            <?php } else { ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="viewhoroscope.php?id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="updatehoroscope.php?id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?php if ($row['status'] != 'closed') { ?>
                                                    <a href="closeprofile.php?id=<?php echo $row['id']; ?>" 
                                                       class="btn btn-sm btn-outline-secondary" 
                                                       title="Close Profile"
                                                       onclick="return confirm('Are you sure you want to close this profile?')">
                                                        <i class="bi bi-lock"></i>
                                                    </a>
                                                <?php } ?>
                                                <a href="deletehoroscope.php?id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger" 
                                                   title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                if (mysqli_num_rows($result) == 0) {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                                <p class="mt-2">No horoscope records found</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>

<?php include('../includes/footer.php'); ?>