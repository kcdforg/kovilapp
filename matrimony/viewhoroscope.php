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
$username = $_SESSION['username'];
$id = $_GET['id'];
$row = get_horoscope($id);
$row1 = get_attachments($id);
$row2 = get_member($row['ref_id']);
global $kattam;
$kattam = array();
$kattam = get_kattam($id);
$rasi = $kattam['rasi'];
$amsam = $kattam['amsam'];
?>

<div class="row fluid-with-margins">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Profile Details</h2>
                <div class="btn-group" role="group">
                    <button onclick="print()" class="btn btn-info">
                        <i class="bi bi-printer"></i> Print
                    </button>
                    <a href="updatehoroscope.php?id=<?php echo $row['id'] ?>" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <?php if ($row['status'] != 'closed') { ?>
                        <button onclick="closeprofile()" class="btn btn-secondary">
                            <i class="bi bi-lock"></i> Close Profile
                        </button>
                    <?php } else { ?>
                        <a href="viewhoroscope.php?id=<?php echo $row['id'] ?>&action=reopen" class="btn btn-success">
                            <i class="bi bi-unlock"></i> Re-Open
                        </a>
                    <?php } ?>
                    <button onclick="deletehoroscope(<?php echo $row['id'] ?>)" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
            </div>

            <div class="row">
                <!-- Profile Photo and Basic Info -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-circle"></i> Profile Photo
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img class="img-fluid rounded-circle mb-3" 
                                 src="../images/horo/<?php echo $row['photo'] ?: 'default.jpg' ?>" 
                                 alt="Profile Photo" 
                                 style="width: 200px; height: 200px; object-fit: cover;">
                            
                            <h4 class="mb-3"><?php echo $row['name']; ?></h4>
                            
                            <div class="btn-group-vertical w-100" role="group">
                                <button onclick="uploadphoto()" class="btn btn-outline-primary mb-2">
                                    <i class="bi bi-upload"></i> Upload Photo
                                </button>
                                <button onclick="deletephoto()" class="btn btn-outline-danger">
                                    <i class="bi bi-trash"></i> Delete Photo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person"></i> Personal Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Registration No:</strong></td>
                                            <td><?php echo $row['reg_no']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gender:</strong></td>
                                            <td>
                                                <span class="badge bg-<?php echo ($row['gender'] == 'male') ? 'primary' : 'danger'; ?>">
                                                    <?php echo ucfirst($row['gender']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Age:</strong></td>
                                            <td><?php echo $row['age']; ?> years</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Height:</strong></td>
                                            <td><?php echo $row['height']; ?> cm</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Weight:</strong></td>
                                            <td><?php echo $row['weight']; ?> kg</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Blood Group:</strong></td>
                                            <td><?php echo $row['blood_group']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Colour:</strong></td>
                                            <td><?php echo $row['colour']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Marital Status:</strong></td>
                                            <td>
                                                <span class="badge bg-<?php echo ($row['marital_status'] == 'No') ? 'success' : 'warning'; ?>">
                                                    <?php echo ($row['marital_status'] == 'No') ? 'Unmarried' : 'Married'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Mobile:</strong></td>
                                            <td><?php echo $row['mobile_no']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td><?php echo $row['email']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Address:</strong></td>
                                            <td><?php echo $row['address']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Education:</strong></td>
                                            <td><?php echo $row['qualification']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Occupation:</strong></td>
                                            <td><?php echo $row['occupation']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Income:</strong></td>
                                            <td><?php echo $row['income']; ?> per month</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge bg-<?php echo ($row['status'] == 'closed') ? 'secondary' : 'success'; ?>">
                                                    <?php echo ucfirst($row['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Details -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-people"></i> Family Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Father's Name:</strong></td>
                                            <td><?php echo $row['father_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Father's Occupation:</strong></td>
                                            <td><?php echo $row['f_occupation']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mother's Name:</strong></td>
                                            <td><?php echo $row['mother_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mother's Occupation:</strong></td>
                                            <td><?php echo $row['m_occupation']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Siblings:</strong></td>
                                            <td><?php echo $row['sibling']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Kulam:</strong></td>
                                            <td><?php echo get_kulam($row['kulam']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Temple:</strong></td>
                                            <td><?php echo $row['temple']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mother's Kulam:</strong></td>
                                            <td><?php echo get_kulam($row['m_kulam']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Maternal Mother's Kulam:</strong></td>
                                            <td><?php echo get_kulam($row['mm_kulam']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Paternal Mother's Kulam:</strong></td>
                                            <td><?php echo get_kulam($row['pm_kulam']); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horoscope Details -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-star"></i> Horoscope Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Birth Date:</strong></td>
                                            <td><?php echo $row['birth_date']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Birth Time:</strong></td>
                                            <td><?php echo $row['birth_time']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Birth Place:</strong></td>
                                            <td><?php echo $row['birth_place']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Raasi:</strong></td>
                                            <td><?php echo get_raasi($row['raasi']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Laknam:</strong></td>
                                            <td><?php echo get_lagnam($row['laknam']); ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Star:</strong></td>
                                            <td><?php echo get_star($row['star']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Padham:</strong></td>
                                            <td><?php echo $row['padham']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Raghu/Kedhu:</strong></td>
                                            <td>
                                                <span class="badge bg-<?php echo ($row['raaghu_kaedhu'] > 0) ? 'danger' : 'success'; ?>">
                                                    <?php echo ($row['raaghu_kaedhu'] > 0) ? 'Yes' : 'No'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sevvai:</strong></td>
                                            <td>
                                                <span class="badge bg-<?php echo ($row['sevvai'] > 0) ? 'danger' : 'success'; ?>">
                                                    <?php echo ($row['sevvai'] > 0) ? 'Yes' : 'No'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>About Myself:</strong></td>
                                            <td><?php echo $row['about_myself']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referrer Details -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-badge"></i> Referrer Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Referrer Name:</strong></td>
                                            <td><?php echo $row2['name']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Referrer Mobile:</strong></td>
                                            <td><?php echo $row2['mobile_no']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Referrer Address:</strong></td>
                                            <td><?php echo $row2['permanent_address']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<script>
    function print() {
        url = "printhoro.php?id=<?php echo $id ?>";
        title = "Horoscope list";
        var newWindow = window.open(url, title, 'scrollbars=yes, width=1000px, height=650px');
    }
    
    function closeprofile() {
        url = "closeprofile.php?id=<?php echo $id ?>";
        title = "Close Horoscope ";
        var newWindow = window.open(url, title, 'scrollbars=yes, width=800px, height=400px');
    }
    
    function deletehoroscope(rowid) {
        if (confirm('Are you sure you want to delete this horoscope?')) {
            url = "deletehoroscope.php?id=" + rowid;
            title = "popup";
            var newWindow = window.open(url, title, 'scrollbars=yes, width=500, height=400');
        }
    }
    
    function uploadphoto() {
        url = "ppupload.php?id=<?php echo $id ?>";
        title = "popup";
        var newWindow = window.open(url, title, 'scrollbars=yes, width=500, height=400');
    }
    
    function deletephoto() {
        if (confirm('Are you sure you want to delete this photo?')) {
            url = "ppdelete.php?id=<?php echo $row['id'] ?>&photo=<?php echo $row['photo'] ?>";
            title = "popup";
            var newWindow = window.open(url, title, 'scrollbars=yes, width=500, height=400');
        }
    }
</script>

<?php include('../includes/footer.php'); ?>