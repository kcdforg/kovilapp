<?php
include('../init.php');
check_login();

$id = $_GET['id'] ?? 0;
$where = '';
$rec_per_page = 20;
$curr_page = 1;
$offset = ($curr_page - 1) * ($rec_per_page);

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'generate_id') {
        $member_id = isset($_POST['id']) ? $_POST['id'] : $id;
        generate_member_id($member_id);
        header("Location: viewmember.php?id=$id");
        exit();
    } elseif ($_POST['action'] == 'update_member_id') {
        $member_id_value = $_POST['member_id_value'] ?? '';
        $member_record_id = $_POST['member_record_id'] ?? $id;
        
        if (!empty($member_id_value)) {
            // Check if member_id already exists
            $check_sql = "SELECT id FROM $tbl_family WHERE member_id = ? AND id != ?";
            $check_stmt = mysqli_prepare($con, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "si", $member_id_value, $member_record_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            
            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                mysqli_stmt_close($check_stmt);
                header("Location: viewmember.php?id=$id&error=duplicate_id");
                exit();
            }
            mysqli_stmt_close($check_stmt);
            
            // Update member_id if unique
            $sql = "UPDATE $tbl_family SET member_id = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "si", $member_id_value, $member_record_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        header("Location: viewmember.php?id=$id");
        exit();
    }
}

$row = get_member($id);

$sql_total = "SELECT count(*) as total FROM $tbl_family where `deleted`=0 $where";
global $con;
$result = mysqli_query($con, $sql_total);
$row1 = mysqli_fetch_assoc($result);
$total_records = $row1['total'];
$total_pages = ceil($total_records / $rec_per_page);

include('../includes/header.php');
?>

<style>
.member-avatar {
    width: 140px;
    height: 110px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.member-avatar-placeholder {
    width: 140px;
    height: 110px;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    color: #6c757d;
    font-size: 12px;
    text-align: center;
    margin:auto;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.card-header {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    font-weight: 600;
    padding: 15px 20px;
}

.list-group-item {
    border: none;
    border-bottom: 1px solid #e9ecef;
    padding: 12px 0;
}

.list-group-item:last-child {
    border-bottom: none;
}

/* Left-aligned data styling with fixed-width labels */
.data-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 8px;
}

.data-label {
    font-weight: 600;
    color: #495057;
    min-width: 175px;
    flex-shrink: 0;
    padding-right: 15px;
}

.data-value {
    color: #212529;
    flex: 1;
}

.btn-action {
    padding: 6px 12px;
    font-size: 0.875rem;
    border-radius: 6px;
    margin: 2px;
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-action:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
}

.child-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 15px;
    background: #f8f9fa;
}

.child-card-header {
    background: #e9ecef;
    padding: 10px 15px;
    border-radius: 8px 8px 0 0;
    font-weight: 600;
}

.nav-tabs .nav-link {
    border-radius: 8px 8px 0 0;
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 12px 20px;
    text-decoration: none;
    display: block;
}

.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    border: none;
}

.nav-tabs {
    border-bottom: 2px solid #4e73df;
}

.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.8em;
    border-radius: 6px;
}

.member-id {
    font-size: 1.5rem;
    font-weight: 700;
    color: #4e73df;
}

.family-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
}

.main-card-header {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
}

@media (max-width: 768px) {
    .action-buttons {
        position: static;
        margin-top: 15px;
    }
}

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

.admin-note-item {
    background: #f8f9fa;
    transition: all 0.2s ease;
}

.admin-note-item:hover {
    background: #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

</style>

<div class="row fluid-with-margins">
        <div class="col-12">
        <?php if (isset($_GET['error']) && $_GET['error'] == 'duplicate_id'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> This Member ID already exists. Please enter a unique Member ID.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center main-card-header">
                    <h4 class="mb-0 family-name">
                        <i class="bi bi-people"></i> <?php echo htmlspecialchars($row['name']); ?> குடும்பம்
                    </h4>
                    <div class="action-buttons">
                        <button class="btn btn-success btn-action" onclick="printMemberDetails()">
                            <i class="bi bi-printer"></i> Print
                        </button>
                        <a href="updatemember.php?id=<?php echo $id; ?>" class="btn btn-primary btn-action">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-action" onclick="deletemember(<?php echo $row['id']; ?>)">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php 
                    $current_tab = $_GET['tab'] ?? 'profile';
                    $profile_active = ($current_tab == 'profile') ? 'active' : '';
                    $horoscope_active = ($current_tab == 'horoscope') ? 'active' : '';
                    ?>
                    
                    <ul class="nav nav-tabs" id="memberTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link <?php echo $profile_active; ?>" href="?id=<?php echo $id; ?>&tab=profile">
                                <i class="bi bi-person"></i> Profile
                            </a>
                        </li>
                        <?php /* Horoscope tab hidden - to be enabled later
                        <li class="nav-item" role="presentation">
                            <a class="nav-link <?php echo $horoscope_active; ?>" href="?id=<?php echo $id; ?>&tab=horoscope">
                                <i class="bi bi-stars"></i> Horoscope
                            </a>
                        </li>
                        */ ?>
                    </ul>
                    
                    <div class="tab-content mt-4" id="memberTabsContent">
                        <div class="tab-pane fade <?php echo $profile_active ? 'show active' : ''; ?>" id="profile" role="tabpanel">
                            <div class="row">
                                <!-- Left Column - Family Members -->
                                <div class="col-lg-7">
                                    <!-- Husband Section -->
                                    <div class="card" id="husband-section">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="bi bi-person"></i> குடும்ப தலைவர்</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 text-center" id="husband-image-container">
                                                    <?php if ($row['image'] && file_exists("../images/member/" . $row['image'])): ?>
                                                        <img src="../images/member/<?php echo htmlspecialchars($row['image']); ?>" class="member-avatar" alt="Husband Photo">
                                                        <div class="mt-2">
                                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteHusbandImageModal">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="member-avatar-placeholder">
                                                            <div>
                                                                <i class="bi bi-person" style="font-size: 24px;"></i><br>
                                                                No Photo
                                                            </div>
                                                        </div>
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadHusbandImageModal">
                                                                <i class="bi bi-upload"></i> Upload Photo
                                                        </button>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="data-item">
                                                        <div class="data-label">Name:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['name'] ?? '-'); ?></div>
                                                    </div>
                                                    <?php if (!empty($row['parent_id']) && $row['parent_id'] > 0): ?>
                                                    <div class="data-item">
                                                        <div class="data-label">Parent Family:</div>
                                                        <div class="data-value">
                                                            <a href="viewmember.php?id=<?php echo $row['parent_id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-arrow-up-circle"></i> View Parent
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="data-item">
                                                        <div class="data-label">Father's Name:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['father_name'] ?? '-'); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Mother's Name:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['mother_name'] ?? '-'); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Education:</div>
                                                        <div class="data-value"><?php echo get_qualification($row['qualification'] ?? ''); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Education Details:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['education_details'] ?? '-'); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Occupation:</div>
                                                        <div class="data-value"><?php echo get_occupation($row['occupation'] ?? ''); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Occupation Details:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['occupation_details'] ?? '-'); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Email:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['email'] ?? '-'); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Mobile:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['mobile_no'] ?? '-'); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Wife Section -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="bi bi-person-heart"></i> குடும்ப தலைவி</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 text-center" id="wife-image-container">
                                                    <?php if ($row['w_image'] && file_exists("../images/member/" . $row['w_image'])): ?>
                                                        <img src="../images/member/<?php echo htmlspecialchars($row['w_image']); ?>" class="member-avatar" alt="Wife Photo">
                                                        <div class="mt-2">
                                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteWifeImageModal">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="member-avatar-placeholder">
                                                            <div>
                                                                <i class="bi bi-person-heart" style="font-size: 24px;"></i><br>
                                                                No Photo
                                                            </div>
                                                        </div>
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadWifeImageModal">
                                                                <i class="bi bi-upload"></i> Upload Photo
                                                        </button>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="data-item">
                                                        <div class="data-label">Name:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['w_name'] ?? '-'); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Education:</div>
                                                        <div class="data-value"><?php echo get_qualification($row['w_qualification'] ?? ''); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Education Details:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['w_education_details'] ?? '-'); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Occupation:</div>
                                                        <div class="data-value"><?php echo get_occupation($row['w_occupation'] ?? ''); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Occupation Details:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['w_occupation_details'] ?? '-'); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Kootam:</div>
                                                        <div class="data-value"><?php echo get_kulam($row['w_kootam'] ?? ''); ?></div>
                                                    </div>
                                                    <div class="data-item">
                                                        <div class="data-label">Temple:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['w_temple'] ?? '-'); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Children Section -->
                                    <?php
                                    $num_rows = count_child($id);
                                    $children = get_children($id);
                                    ?>
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                <i class="bi bi-people-fill"></i> குழந்தைகள் (<?php echo $num_rows; ?>)
                                            </h6>
                                            <div>
                                                <button class="btn btn-success btn-sm me-2" onclick="addson()">
                                                        <i class="bi bi-person-plus"></i> Add Son
                                                </button>
                                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addDaughterModal">
                                                        <i class="bi bi-person-plus"></i> Add Daughter
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <?php if ($children && isset($children[$id])): ?>
                                                <?php foreach ($children[$id] as $k => $v): ?>
                                                    <div class="child-card">
                                                        <div class="child-card-header d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0"><?php echo htmlspecialchars($v['c_name']); ?></h6>
                                                            <div>
                                                                <button class="btn btn-sm btn-outline-primary" onclick="cupdate(<?php echo $v['id']; ?>)">
                                                                    <i class="bi bi-pencil"></i> Edit
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteChildModal" data-child-id="<?php echo $v['id']; ?>" data-child-name="<?php echo htmlspecialchars($v['c_name']); ?>">
                                                                    <i class="bi bi-trash"></i> Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="p-3">
                                                            <div class="row">
                                                                <div class="col-md-3 text-center" id="child-image-container-<?php echo $v['id']; ?>">
                                                                    <?php if ($v['c_image'] && file_exists("../images/member/" . $v['c_image'])): ?>
                                                                        <img src="../images/member/<?php echo htmlspecialchars($v['c_image']); ?>" class="member-avatar" alt="Child Photo">
                                                                        <div class="mt-2">
                                                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteChildImageModal" data-child-id="<?php echo $v['id']; ?>" data-child-image="<?php echo $v['c_image']; ?>">
                                                                                <i class="bi bi-trash"></i> Delete
                                                                            </button>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="member-avatar-placeholder">
                                                                            <div>
                                                                                <i class="bi bi-person" style="font-size: 20px;"></i><br>
                                                                                No Photo
                                                                            </div>
                                                                        </div>
                                                                    <div class="mt-2">
                                                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadChildImageModal" data-child-id="<?php echo $v['id']; ?>" data-father-id="<?php echo $v['father_id']; ?>">
                                                                                <i class="bi bi-upload"></i> Upload Photo
                                                                        </button>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="data-item">
                                                                        <div class="data-label">Name:</div>
                                                                        <div class="data-value"><?php echo htmlspecialchars($v['c_name'] ?? '-'); ?></div>
                                                                    </div>
                                                                    <div class="data-item">
                                                                        <div class="data-label">DOB:</div>
                                                                        <div class="data-value"><?php echo htmlspecialchars($v['c_dob'] ?? '-'); ?></div>
                                                                    </div>
                                                                    <div class="data-item">
                                                                        <div class="data-label">Education:</div>
                                                                        <div class="data-value"><?php echo get_qualification($v['c_qualification'] ?? ''); ?></div>
                                                                    </div>
                                                                    <div class="data-item">
                                                                        <div class="data-label">Occupation:</div>
                                                                        <div class="data-value"><?php echo get_occupation($v['c_occupation'] ?? ''); ?></div>
                                                                    </div>
                                                                    <div class="data-item">
                                                                        <div class="data-label">Mobile:</div>
                                                                        <div class="data-value"><?php echo htmlspecialchars($v['c_mobile_no'] ?? '-'); ?></div>
                                                                    </div>
                                                                    <div class="data-item">
                                                                        <div class="data-label">Marital Status:</div>
                                                                        <div class="data-value">
                                                                            <?php echo htmlspecialchars($v['c_marital_status'] ?? '-'); ?>
                                                                            <?php if ($v['c_gender'] == 'male' || $v['c_gender'] == 'Male'): ?>
                                                                                <?php if (empty($v['fam_id']) || $v['fam_id'] == "0" || $v['fam_id'] == 0): ?>
                                                                                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="linkfamily(<?php echo $v['id']; ?>)">
                                                                                        <i class="bi bi-link"></i> Link Family
                                                                                    </button>
                                                                                    <?php if ($v['c_marital_status'] == "no" || $v['c_marital_status'] == "No"): ?>
                                                                                        <a href="addmember.php?child_id=<?php echo $v['id']; ?>" class="btn btn-sm btn-outline-success ms-1">
                                                                                            <i class="bi bi-plus"></i> Add Family
                                                                                        </a>
                                                                                    <?php endif; ?>
                                                                                <?php elseif (!empty($v['fam_id']) && $v['fam_id'] != "0" && $v['fam_id'] != 0): ?>
                                                                                    <?php
                                                                                    // Check if family exists in family table
                                                                                    $check_fam_sql = "SELECT id FROM $tbl_family WHERE id = ? AND deleted = 0";
                                                                                    $check_fam_stmt = mysqli_prepare($con, $check_fam_sql);
                                                                                    mysqli_stmt_bind_param($check_fam_stmt, "i", $v['fam_id']);
                                                                                    mysqli_stmt_execute($check_fam_stmt);
                                                                                    mysqli_stmt_store_result($check_fam_stmt);
                                                                                    $family_exists = mysqli_stmt_num_rows($check_fam_stmt) > 0;
                                                                                    mysqli_stmt_close($check_fam_stmt);
                                                                                    ?>
                                                                                    <?php if ($family_exists): ?>
                                                                                    <a href="viewmember.php?id=<?php echo $v['fam_id']; ?>" class="btn btn-sm btn-outline-info ms-2">
                                                                                        <i class="bi bi-eye"></i> View Family
                                                                                    </a>
                                                                                        <button class="btn btn-sm btn-outline-danger ms-1" onclick="unlinkFamily(<?php echo $v['id']; ?>)">
                                                                                            <i class="bi bi-link-45deg"></i> Unlink
                                                                                        </button>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="text-center text-muted py-4">
                                                    <i class="bi bi-people" style="font-size: 48px;"></i>
                                                    <p class="mt-2">No children found</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column - Other Details -->
                                <div class="col-lg-5">
                                    <!-- Membership Section -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="bi bi-card-text"></i> Membership</h6>
                                        </div>
                                        <div class="card-body">
                                            <?php if (empty($row['member_id']) || $row['member_id'] == '0'): ?>
                                                <!-- No Member ID - Show input form -->
                                                <div class="data-item align-items-center mb-3">
                                                <div class="data-label">Member ID:</div>
                                                    <div style="width: 50%;">
                                                        <form method="POST" id="memberIdForm">
                                                            <input type="hidden" name="action" value="update_member_id">
                                                            <input type="hidden" name="member_record_id" value="<?php echo $id; ?>">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control" name="member_id_value" 
                                                                       id="memberIdInput" placeholder="Enter Member ID" required>
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="bi bi-save"></i> Save
                                                                </button>
                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="text-center mb-2">
                                                    <small class="text-muted">Enter a unique Member ID or use Auto Generate below</small>
                                                </div>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <form method="POST">
                                                        <input type="hidden" name="action" value="generate_id">
                                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="bi bi-plus-circle"></i> Auto Generate ID
                                                        </button>
                                                    </form>
                                                </div>
                                                <?php else: ?>
                                                <!-- Member ID exists - Show with edit option -->
                                                <div class="data-item">
                                                    <div class="data-label">Member ID:</div>
                                                    <div class="data-value member-id" id="memberIdDisplay">
                                                        <?php echo htmlspecialchars($row['member_id']); ?>
                                                    </div>
                                                    <div id="memberIdEdit" style="display: none; flex: 1;">
                                                        <form method="POST" class="d-flex gap-2">
                                                            <input type="hidden" name="action" value="update_member_id">
                                                            <input type="hidden" name="member_record_id" value="<?php echo $id; ?>">
                                                            <input type="text" class="form-control form-control-sm" name="member_id_value" 
                                                                   value="<?php echo htmlspecialchars($row['member_id']); ?>" 
                                                                   placeholder="Enter Member ID" required>
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="bi bi-check"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-secondary" onclick="cancelEditMemberId()">
                                                                <i class="bi bi-x"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-secondary ms-2" onclick="editMemberId()">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                </div>
                                                <div class="data-item">
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#printIdModal">
                                                            <i class="bi bi-printer"></i> Print ID Card
                                                        </button>
                                                    </div>
                                                    </div>
                                                <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Address Section -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="bi bi-geo-alt"></i> முகவரி</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="data-item">
                                                <div class="data-label">Permanent Address:</div>
                                                <div class="data-value"><?php echo htmlspecialchars($row['permanent_address'] ?? '-'); ?></div>
                                            </div>
                                            <div class="data-item">
                                                <div class="data-label">Current Address:</div>
                                                <div class="data-value">
                                                    <?php 
                                                    if (isset($row['same_as_permanent']) && $row['same_as_permanent'] == 1) {
                                                        echo htmlspecialchars($row['permanent_address'] ?? '-');
                                                    } else {
                                                        echo htmlspecialchars($row['current_address'] ?? '-');
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Family Tree Section -->
                                    <?php
                                    // Get family tree (don't auto-generate, only show if exists)
                                    $ftree_id = getFamilyTreeId($id);
                                    $family_tree = null;
                                    
                                    // Debug: Check what ftree_id we got
                                    // echo "<!-- DEBUG: Family ID: $id, FTree ID: " . ($ftree_id ?? 'NULL') . " -->";
                                    
                                    if ($ftree_id) {
                                        $family_tree = fetchFamilyTree($ftree_id);
                                        // echo "<!-- DEBUG: Tree fetched: " . ($family_tree ? 'YES' : 'NO') . " -->";
                                    }
                                    
                                    // Function to render family tree node recursively
                                    function renderFamilyTreeNode($node, $current_family_id, $level = 0) {
                                        $node_type = $node['type'] ?? 'family';
                                        
                                        if ($node_type == 'child') {
                                            // This is a child node
                                            $fam_id = $node['fam_id'] ?? 0;
                                            $has_family = !empty($fam_id) && $fam_id > 0;
                                            
                                            // If child has a family, just render the family node directly
                                            if ($has_family && !empty($node['children'])) {
                                                foreach ($node['children'] as $child_node) {
                                                    renderFamilyTreeNode($child_node, $current_family_id, $level);
                                                }
                                            } else {
                                                // Child doesn't have a family - show child name from stored data
                                                $child_name = htmlspecialchars($node['c_name'] ?? 'Unknown');
                                                
                                                // Calculate age if DOB exists
                                                if (!empty($node['c_dob']) && $node['c_dob'] != '0000-00-00') {
                                                    $dob = new DateTime($node['c_dob']);
                                                    $now = new DateTime();
                                                    $age = $now->diff($dob)->y;
                                                    $child_name .= ' (' . $age . ')';
                                                }
                                                
                                                echo '<ul class="tree-list">';
                                                echo '<li class="tree-item child-item">';
                                                echo '<div class="tree-content">';
                                                echo '<span class="tree-toggle-empty"></span>';
                                                echo '<span class="tree-text">' . $child_name . '</span>';
                                                echo '</div>';
                                                echo '</li>';
                                                echo '</ul>';
                                            }
                                        } else {
                                            // This is a family node - use stored data
                                            $family_id = $node['id'];
                                            $is_current = ($family_id == $current_family_id);
                                            $highlight_class = $is_current ? 'current-family' : '';
                                            $has_children = !empty($node['children']);
                                            $unique_id = 'tree-node-' . $family_id;
                                            
                                            // Build display name with age from stored data
                                            $husband_name = htmlspecialchars($node['name'] ?? 'Unknown');
                                            if (!empty($node['dob']) && $node['dob'] != '0000-00-00') {
                                                $dob = new DateTime($node['dob']);
                                                $now = new DateTime();
                                                $age = $now->diff($dob)->y;
                                                $husband_name .= ' (' . $age . ')';
                                            }
                                            
                                            $wife_name = '';
                                            if (!empty($node['w_name'])) {
                                                $wife_name = htmlspecialchars($node['w_name']);
                                                if (!empty($node['w_dob']) && $node['w_dob'] != '0000-00-00') {
                                                    $dob = new DateTime($node['w_dob']);
                                                    $now = new DateTime();
                                                    $age = $now->diff($dob)->y;
                                                    $wife_name .= ' (' . $age . ')';
                                                }
                                            }
                                            
                                            echo '<ul class="tree-list">';
                                            echo '<li class="tree-item ' . $highlight_class . '">';
                                            echo '<div class="tree-content">';
                                            
                                            if ($has_children) {
                                                echo '<span class="tree-toggle" onclick="toggleNode(\'' . $unique_id . '\')">▼</span>';
                                            } else {
                                                echo '<span class="tree-toggle-empty"></span>';
                                            }
                                            
                                            echo '<span class="tree-icon">📁</span>';
                                            echo '<a href="viewmember.php?id=' . $family_id . '" class="tree-link">';
                                            echo $husband_name;
                                            if (!empty($wife_name)) {
                                                echo ' & ' . $wife_name;
                                            }
                                            echo '</a>';
                                            echo '</div>';
                                            
                                            // Children
                                            if ($has_children) {
                                                echo '<div class="tree-children" id="' . $unique_id . '">';
                                                foreach ($node['children'] as $child_node) {
                                                    renderFamilyTreeNode($child_node, $current_family_id, $level + 1);
                                                }
                                                echo '</div>';
                                            }
                                            
                                            echo '</li>';
                                            echo '</ul>';
                                        }
                                    }
                                    ?>
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0"><i class="bi bi-diagram-3"></i> Family Tree</h6>
                                            <div>
                                                <button class="btn btn-sm btn-outline-light me-2" onclick="regenerateTree()">
                                                    <i class="bi bi-arrow-clockwise"></i> Regenerate
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteTree()">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                                            <div id="familyTreeContainer" class="family-tree-view">
                                                <?php if ($family_tree): ?>
                                                    <?php renderFamilyTreeNode($family_tree, $id); ?>
                                                <?php else: ?>
                                                    <div class="text-center py-4">
                                                        <i class="bi bi-diagram-3 text-muted" style="font-size: 48px;"></i>
                                                        <p class="text-muted mt-2">No family tree generated yet</p>
                                                        <button class="btn btn-primary btn-sm" onclick="regenerateTree()">
                                                            <i class="bi bi-plus-circle"></i> Generate Family Tree
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <style>
                                    .family-tree-view {
                                        padding: 5px;
                                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                        font-size: 0.85rem;
                                    }
                                    
                                    .tree-list {
                                        list-style: none;
                                        padding-left: 0;
                                        margin: 0;
                                        position: relative;
                                    }
                                    
                                    .tree-list .tree-list {
                                        padding-left: 20px;
                                        margin-top: 2px;
                                    }
                                    
                                    .tree-list .tree-list::before {
                                        content: '';
                                        position: absolute;
                                        left: 8px;
                                        top: 0;
                                        bottom: 0;
                                        width: 1px;
                                        background: #dee2e6;
                                    }
                                    
                                    .tree-item {
                                        position: relative;
                                        padding: 2px 0;
                                    }
                                    
                                    .tree-item::before {
                                        content: '';
                                        position: absolute;
                                        left: -12px;
                                        top: 12px;
                                        width: 12px;
                                        height: 1px;
                                        background: #dee2e6;
                                    }
                                    
                                    .tree-item:first-child::before {
                                        left: -12px;
                                    }
                                    
                                    .tree-content {
                                        display: flex;
                                        align-items: center;
                                        padding: 3px 0;
                                        margin-bottom: 2px;
                                    }
                                    
                                    .tree-content:hover .tree-link {
                                        color: #0a58ca;
                                    }
                                    
                                    .tree-toggle {
                                        display: inline-block;
                                        width: 16px;
                                        font-size: 0.7rem;
                                        cursor: pointer;
                                        user-select: none;
                                        transition: transform 0.2s ease;
                                        color: #6c757d;
                                    }
                                    
                                    .tree-toggle.collapsed {
                                        transform: rotate(-90deg);
                                    }
                                    
                                    .tree-toggle-empty {
                                        display: inline-block;
                                        width: 16px;
                                    }
                                    
                                    .tree-icon {
                                        margin-right: 6px;
                                        font-size: 0.9rem;
                                    }
                                    
                                    .tree-link {
                                        color: #0d6efd;
                                        text-decoration: none;
                                        font-weight: 500;
                                        font-size: 0.8rem;
                                        transition: color 0.15s ease;
                                    }
                                    
                                    .tree-link:hover {
                                        text-decoration: underline;
                                    }
                                    
                                    .tree-text {
                                        color: #495057;
                                        font-size: 0.75rem;
                                        transition: color 0.15s ease;
                                    }
                                    
                                    .tree-item.current-family > .tree-content .tree-link {
                                        color: #198754;
                                        font-weight: 700;
                                    }
                                    
                                    .tree-children {
                                        transition: all 0.2s ease;
                                    }
                                    
                                    .tree-children.collapsed {
                                        display: none;
                                    }
                                    </style>
                                    
                                    <script>
                                    function toggleNode(nodeId) {
                                        const node = document.getElementById(nodeId);
                                        const toggle = event.target;
                                        
                                        if (node) {
                                            node.classList.toggle('collapsed');
                                            toggle.classList.toggle('collapsed');
                                        }
                                    }
                                    
                                    function regenerateTree() {
                                        // Show confirmation modal
                                        const confirmModal = new bootstrap.Modal(document.getElementById('confirmRegenerateModal'));
                                        confirmModal.show();
                                    }
                                    
                                    function confirmRegenerateTree() {
                                        // Hide confirmation modal
                                        const confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmRegenerateModal'));
                                        confirmModal.hide();
                                        
                                        // Show loading in response modal
                                        const responseModal = new bootstrap.Modal(document.getElementById('regenerateResponseModal'));
                                        document.getElementById('regenerateResponseTitle').textContent = 'Processing...';
                                        document.getElementById('regenerateResponseIcon').innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                                        document.getElementById('regenerateResponseMessage').textContent = 'Regenerating family tree, please wait...';
                                        document.getElementById('regenerateResponseDetails').style.display = 'none';
                                        responseModal.show();
                                        
                                        const formData = new FormData();
                                        formData.append('family_id', <?php echo $id; ?>);
                                        
                                        fetch('regenerate_tree.php', {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => {
                                            // Check if response is JSON
                                            const contentType = response.headers.get('content-type');
                                            if (!contentType || !contentType.includes('application/json')) {
                                                return response.text().then(text => {
                                                    throw new Error('Server returned non-JSON response: ' + text.substring(0, 200));
                                                });
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            if (data.success) {
                                                document.getElementById('regenerateResponseTitle').textContent = 'Success';
                                                document.getElementById('regenerateResponseIcon').innerHTML = '<i class="bi bi-check-circle-fill text-success" style="font-size: 48px;"></i>';
                                                document.getElementById('regenerateResponseMessage').textContent = 'Family tree regenerated successfully!';
                                                document.getElementById('regenerateResponseDetails').style.display = 'none';
                                                
                                                // Reload after 2 seconds
                                                setTimeout(() => {
                                                    window.location.reload();
                                                }, 2000);
                                            } else {
                                                document.getElementById('regenerateResponseTitle').textContent = 'Error';
                                                document.getElementById('regenerateResponseIcon').innerHTML = '<i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 48px;"></i>';
                                                document.getElementById('regenerateResponseMessage').textContent = 'Failed to regenerate family tree';
                                                let errorDetails = data.message || 'Unknown error occurred';
                                                if (data.trace) {
                                                    errorDetails += '\n\nStack Trace:\n' + data.trace;
                                                }
                                                document.getElementById('regenerateResponseDetails').innerHTML = '<pre style="white-space: pre-wrap; word-wrap: break-word;">' + errorDetails + '</pre>';
                                                document.getElementById('regenerateResponseDetails').style.display = 'block';
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            document.getElementById('regenerateResponseTitle').textContent = 'Error';
                                            document.getElementById('regenerateResponseIcon').innerHTML = '<i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 48px;"></i>';
                                            document.getElementById('regenerateResponseMessage').textContent = 'Error regenerating family tree';
                                            document.getElementById('regenerateResponseDetails').innerHTML = '<pre style="white-space: pre-wrap; word-wrap: break-word;">Network error: ' + error.message + '</pre>';
                                            document.getElementById('regenerateResponseDetails').style.display = 'block';
                                        });
                                    }
                                    
                                    function deleteTree() {
                                        if (!confirm('Are you sure you want to delete the family tree? This will remove the tree for all related families.')) {
                                            return;
                                        }
                                        
                                        const formData = new FormData();
                                        formData.append('family_id', <?php echo $id; ?>);
                                        
                                        fetch('delete_tree.php', {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                alert('Family tree deleted successfully');
                                                window.location.reload();
                                            } else {
                                                alert('Failed to delete tree: ' + (data.message || 'Unknown error'));
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('Error deleting tree');
                                        });
                                    }
                                    </script>

                                    <!-- Other Details Section -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="bi bi-info-circle"></i> மற்றவை</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="data-item">
                                                <div class="data-label">Family Name:</div>
                                                <div class="data-value"><?php echo htmlspecialchars($row['family_name'] ?? '-'); ?></div>
                                            </div>
                                            <div class="data-item">
                                                <div class="data-label">Remarks:</div>
                                                <div class="data-value"><?php echo htmlspecialchars($row['remarks'] ?? '-'); ?></div>
                                            </div>
                                            <div class="data-item">
                                                <div class="data-label">Created By:</div>
                                                <div class="data-value"><?php echo htmlspecialchars($row['created_by'] ?? '-'); ?></div>
                                            </div>
                                            <div class="data-item">
                                                <div class="data-label">Created Date:</div>
                                                <div class="data-value"><?php echo htmlspecialchars($row['created_date'] ?? '-'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Admin Notes Section -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="bi bi-sticky"></i> Admin Notes</h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- Add Note Form -->
                                            <div class="mb-3">
                                                <textarea class="form-control" id="adminNoteText" rows="3" placeholder="Enter admin note..."></textarea>
                                                <div class="text-end">
                                                    <button class="btn btn-primary btn-sm mt-2" onclick="addAdminNote()">
                                                        <i class="bi bi-plus-circle"></i> Add Note
                                                    </button>
                                            </div>
                                        </div>
                                            
                                            <!-- Display Existing Notes -->
                                            <div id="adminNotesList">
                                                <?php
                                                $notes = [];
                                                if (!empty($row['admin_notes'])) {
                                                    $decoded = json_decode($row['admin_notes'], true);
                                                    if (is_array($decoded)) {
                                                        $notes = $decoded;
                                                    }
                                                }
                                                
                                                if (empty($notes)): ?>
                                                    <div class="text-muted text-center py-3" id="noNotesMessage">
                                                        <i class="bi bi-sticky"></i> No notes yet
                                    </div>
                                                <?php else: ?>
                                                    <?php foreach ($notes as $note): ?>
                                                        <div class="admin-note-item mb-3 p-3 border rounded" id="note-<?php echo htmlspecialchars($note['id'] ?? ''); ?>">
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <small class="text-muted">
                                                                    <i class="bi bi-person"></i> <?php echo htmlspecialchars($note['added_by'] ?? 'Admin'); ?>
                                                                </small>
                                                                <div>
                                                                    <small class="text-muted me-2">
                                                                        <i class="bi bi-clock"></i> <?php echo htmlspecialchars($note['added_at'] ?? ''); ?>
                                                                    </small>
                                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteAdminNote('<?php echo htmlspecialchars($note['id'] ?? ''); ?>')">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div><?php echo nl2br(htmlspecialchars($note['note'] ?? '')); ?></div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Horoscope Tab -->
                        <div class="tab-pane fade <?php echo $horoscope_active ? 'show active' : ''; ?>" id="horoscope" role="tabpanel">
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-stars" style="font-size: 48px;"></i>
                                <p class="mt-2">Horoscope details will be displayed here</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons at Bottom -->
<div class="row fluid-with-margins">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <?php if ($row['app_front'] && $row['app_back']): ?>
                        <button class="btn btn-primary me-2" onclick="applicationview(<?php echo $id; ?>)">
                            <i class="bi bi-file-text"></i> View Application
                        </button>
                    <?php else: ?>
                        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#uploadApplicationModal">
                            <i class="bi bi-upload"></i> Upload Application
                        </button>
                    <?php endif; ?>
                    
                    <?php /* Add Horoscope button hidden - to be enabled later
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addHoroscopeModal">
                        <i class="bi bi-stars"></i> Add Horoscope
                    </button>
                    */ ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Regenerate Tree Modal -->
<div class="modal fade" id="confirmRegenerateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Confirm Regenerate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to regenerate the family tree?</p>
                <p class="text-muted small">This will rebuild the entire family tree structure.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmRegenerateTree()">
                    <i class="bi bi-arrow-clockwise"></i> Regenerate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Regenerate Response Modal -->
<div class="modal fade" id="regenerateResponseModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="regenerateResponseTitle">Processing...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="regenerateResponseIcon" class="mb-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <p id="regenerateResponseMessage">Processing...</p>
                <div id="regenerateResponseDetails" class="alert alert-danger mt-3" style="display: none; text-align: left;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Husband Image Modal -->
<div class="modal fade" id="uploadHusbandImageModal" tabindex="-1" aria-labelledby="uploadHusbandImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadHusbandImageModalLabel">
                    <i class="bi bi-upload"></i> Upload Husband Photo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="uploadHusbandImageBody">
                <form id="uploadHusbandImageForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="h_image" class="form-label">Select husband image:</label>
                        <input type="file" class="form-control" name="h_image" id="h_image" accept="image/jpeg,image/jpg,image/png" required>
                        <div class="form-text">JPEG, JPG or PNG images are allowed.</div>
                        </div>
                </form>
            </div>
            <div class="modal-footer" id="uploadHusbandImageFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="uploadHusbandImageBtn">
                                <i class="bi bi-upload"></i> Upload
                            </button>
                        </div>
            </div>
        </div>
    </div>

<script>
document.getElementById('uploadHusbandImageBtn')?.addEventListener('click', function() {
    const form = document.getElementById('uploadHusbandImageForm');
    const fileInput = document.getElementById('h_image');
    const btn = this;
    const modalBody = document.getElementById('uploadHusbandImageBody');
    const modalFooter = document.getElementById('uploadHusbandImageFooter');
    
    if (!fileInput.files || fileInput.files.length === 0) {
        alert('Please select an image file');
        return;
    }
    
    // Show loading state
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
    btn.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('h_image', fileInput.files[0]);
    formData.append('id', '<?php echo $row['id']; ?>');
    
    // Make AJAX request
    fetch('hupload.php?id=<?php echo $row['id']; ?>', {
        method: 'POST',
        body: formData
    })
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
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="closeUploadHusbandModal">
                    <i class="bi bi-check-circle"></i> OK
                </button>
            `;
            
            // Update the husband image in the DOM
            document.getElementById('closeUploadHusbandModal').addEventListener('click', function() {
                const husbandImageContainer = document.getElementById('husband-image-container');
                if (husbandImageContainer && data.filename) {
                    // Store the new filename for delete functionality
                    window.currentHusbandImage = data.filename;
                    
                    husbandImageContainer.innerHTML = `
                        <img src="../images/member/${data.filename}?t=${new Date().getTime()}" class="member-avatar" alt="Husband Photo">
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteHusbandImageModal">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    `;
                }
            });
        } else {
            // Show error message
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> ${data.message}
            </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            `;
        }
    })
    .catch(error => {
        // Show error message
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Error uploading photo: ${error.message}
        </div>
        `;
        btn.innerHTML = '<i class="bi bi-upload"></i> Upload';
        btn.disabled = false;
    });
});
</script>

<!-- Delete Husband Image Modal -->
<div class="modal fade" id="deleteHusbandImageModal" tabindex="-1" aria-labelledby="deleteHusbandImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteHusbandImageModalLabel">
                    <i class="bi bi-trash"></i> Delete Husband Photo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deleteHusbandImageBody">
                <p>Are you sure you want to delete the husband's photo?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer" id="deleteHusbandImageFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteHusbandImage">
                        <i class="bi bi-trash"></i> Delete Photo
                    </button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize current image filename
window.currentHusbandImage = '<?php echo $row['image']; ?>';

document.getElementById('confirmDeleteHusbandImage')?.addEventListener('click', function() {
    const btn = this;
    const modalBody = document.getElementById('deleteHusbandImageBody');
    const modalFooter = document.getElementById('deleteHusbandImageFooter');
    
    // Show loading state
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
    btn.disabled = true;
    
    // Use the current image filename (updated after upload)
    const imageFilename = window.currentHusbandImage || '<?php echo $row['image']; ?>';
    
    // Create form data
    const formData = new FormData();
    formData.append('image', imageFilename);
    formData.append('id', '<?php echo $row['id']; ?>');
    
    // Make AJAX request
    fetch('hdelete.php?id=<?php echo $row['id']; ?>&h_image=' + encodeURIComponent(imageFilename), {
        method: 'POST',
        body: formData
    })
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
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="closeDeleteHusbandModal">
                    <i class="bi bi-check-circle"></i> OK
                </button>
            `;
            
            // Update the husband image in the DOM to show placeholder
            document.getElementById('closeDeleteHusbandModal').addEventListener('click', function() {
                const husbandImageContainer = document.getElementById('husband-image-container');
                if (husbandImageContainer) {
                    // Clear the current image filename
                    window.currentHusbandImage = '';
                    
                    husbandImageContainer.innerHTML = `
                        <div class="member-avatar-placeholder">
                            <div>
                                <i class="bi bi-person" style="font-size: 24px;"></i><br>
                                No Photo
                            </div>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadHusbandImageModal">
                                <i class="bi bi-upload"></i> Upload Photo
                            </button>
                        </div>
                    `;
                }
            });
        } else {
            // Show error message
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> ${data.message}
                </div>
            `;
            btn.innerHTML = '<i class="bi bi-trash"></i> Delete Photo';
            btn.disabled = false;
        }
    })
    .catch(error => {
        // Show error message
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Error deleting photo: ${error.message}
            </div>
        `;
        btn.innerHTML = '<i class="bi bi-trash"></i> Delete Photo';
        btn.disabled = false;
    });
});
</script>

<!-- Upload Wife Image Modal -->
<div class="modal fade" id="uploadWifeImageModal" tabindex="-1" aria-labelledby="uploadWifeImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadWifeImageModalLabel">
                    <i class="bi bi-upload"></i> Upload Wife Photo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="uploadWifeImageBody">
                <form id="uploadWifeImageForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="w_image" class="form-label">Select wife image:</label>
                        <input type="file" class="form-control" name="w_image" id="w_image" accept="image/jpeg,image/jpg,image/png" required>
                        <div class="form-text">JPEG, JPG or PNG images are allowed.</div>
                        </div>
                </form>
            </div>
            <div class="modal-footer" id="uploadWifeImageFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="uploadWifeImageBtn">
                                <i class="bi bi-upload"></i> Upload
                            </button>
                        </div>
            </div>
        </div>
    </div>

<script>
document.getElementById('uploadWifeImageBtn')?.addEventListener('click', function() {
    const form = document.getElementById('uploadWifeImageForm');
    const fileInput = document.getElementById('w_image');
    const btn = this;
    const modalBody = document.getElementById('uploadWifeImageBody');
    const modalFooter = document.getElementById('uploadWifeImageFooter');
    
    if (!fileInput.files || fileInput.files.length === 0) {
        alert('Please select an image file');
        return;
    }
    
    // Show loading state
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
    btn.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('w_image', fileInput.files[0]);
    formData.append('id', '<?php echo $row['id']; ?>');
    
    // Make AJAX request
    fetch('wupload.php?id=<?php echo $row['id']; ?>', {
        method: 'POST',
        body: formData
    })
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
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="closeUploadWifeModal">
                    <i class="bi bi-check-circle"></i> OK
                </button>
            `;
            
            // Update the wife image in the DOM
            document.getElementById('closeUploadWifeModal').addEventListener('click', function() {
                const wifeImageContainer = document.getElementById('wife-image-container');
                if (wifeImageContainer && data.filename) {
                    // Store the new filename for delete functionality
                    window.currentWifeImage = data.filename;
                    
                    wifeImageContainer.innerHTML = `
                        <img src="../images/member/${data.filename}?t=${new Date().getTime()}" class="member-avatar" alt="Wife Photo">
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteWifeImageModal">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    `;
                }
            });
        } else {
            // Show error message
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> ${data.message}
            </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            `;
        }
    })
    .catch(error => {
        // Show error message
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Error uploading photo: ${error.message}
        </div>
        `;
        btn.innerHTML = '<i class="bi bi-upload"></i> Upload';
        btn.disabled = false;
    });
});
</script>

<!-- Delete Wife Image Modal -->
<div class="modal fade" id="deleteWifeImageModal" tabindex="-1" aria-labelledby="deleteWifeImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteWifeImageModalLabel">
                    <i class="bi bi-trash"></i> Delete Wife Photo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deleteWifeImageBody">
                <p>Are you sure you want to delete the wife's photo?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer" id="deleteWifeImageFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteWifeImage">
                        <i class="bi bi-trash"></i> Delete Photo
                    </button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize current wife image filename
window.currentWifeImage = '<?php echo $row['w_image'] ?? ''; ?>';

document.getElementById('confirmDeleteWifeImage')?.addEventListener('click', function() {
    const btn = this;
    const modalBody = document.getElementById('deleteWifeImageBody');
    const modalFooter = document.getElementById('deleteWifeImageFooter');
    
    // Show loading state
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
    btn.disabled = true;
    
    // Use the current image filename (updated after upload)
    const imageFilename = window.currentWifeImage || '<?php echo $row['w_image'] ?? ''; ?>';
    
    // Create form data
    const formData = new FormData();
    formData.append('image', imageFilename);
    formData.append('id', '<?php echo $row['id']; ?>');
    
    // Make AJAX request
    fetch('wdelete.php?id=<?php echo $row['id']; ?>&w_image=' + encodeURIComponent(imageFilename), {
        method: 'POST',
        body: formData
    })
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
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="closeDeleteWifeModal">
                    <i class="bi bi-check-circle"></i> OK
                </button>
            `;
            
            // Update the wife image in the DOM to show placeholder
            document.getElementById('closeDeleteWifeModal').addEventListener('click', function() {
                const wifeImageContainer = document.getElementById('wife-image-container');
                if (wifeImageContainer) {
                    // Clear the current image filename
                    window.currentWifeImage = '';
                    
                    wifeImageContainer.innerHTML = `
                        <div class="member-avatar-placeholder">
                            <div>
                                <i class="bi bi-person-heart" style="font-size: 24px;"></i><br>
                                No Photo
                            </div>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadWifeImageModal">
                                <i class="bi bi-upload"></i> Upload Photo
                            </button>
                        </div>
                    `;
                }
            });
        } else {
            // Show error message
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> ${data.message}
                </div>
            `;
            btn.innerHTML = '<i class="bi bi-trash"></i> Delete Photo';
            btn.disabled = false;
        }
    })
    .catch(error => {
        // Show error message
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Error deleting photo: ${error.message}
            </div>
        `;
        btn.innerHTML = '<i class="bi bi-trash"></i> Delete Photo';
        btn.disabled = false;
    });
});
</script>

<!-- Add Son Modal -->
<div class="modal fade" id="addSonModal" tabindex="-1" aria-labelledby="addSonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSonModalLabel">
                    <i class="bi bi-person-plus"></i> Add Son
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background-color: #f8f9fa;">
                <?php $father_id = $_GET['id']; ?>
                
                <!-- Alert Container for AJAX responses -->
                <div id="addSonAlertContainer"></div>
                
                <!-- Form Container -->
                <div id="addSonFormContainer">
                    <form id="addSonForm" method="post">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="c_name" placeholder="Son's Name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="c_dob" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile No</label>
                            <input type="tel" class="form-control" name="c_mobile_no" placeholder="Mobile No">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="c_email" placeholder="Email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Education</label>
                            <select class="form-select" name="c_qualification">
                                <option value="">Select Education</option>
                                <option value="10th">10th</option>
                                <option value="12th">12th</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Degree">Degree</option>
                                <option value="PG">PG</option>
                                <option value="PhD">PhD</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Education Details</label>
                            <input type="text" class="form-control" name="c_education_details" placeholder="Education Details">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Occupation</label>
                            <select class="form-select" name="c_occupation">
                                <option value="">Select Occupation</option>
                                <option value="Student">Student</option>
                                <option value="Employee">Employee</option>
                                <option value="Business">Business</option>
                                <option value="Professional">Professional</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Occupation Details</label>
                            <input type="text" class="form-control" name="c_occupation_details" placeholder="Occupation Details">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Blood Group</label>
                            <select class="form-select" name="c_blood_group">
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                    
                        <input type="hidden" name="c_gender" value="male">
                        <input type="hidden" name="c_marital_status" value="No">
                        <input type="hidden" name="father_id" value="<?php echo $father_id; ?>">
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="addSonBtn">
                                <i class="bi bi-save"></i> Add Son
                            </button>
                            <button type="button" class="btn btn-primary d-none" id="addSonLoader" disabled>
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Adding...
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Daughter Modal -->
<div class="modal fade" id="addDaughterModal" tabindex="-1" aria-labelledby="addDaughterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDaughterModalLabel">
                    <i class="bi bi-person-plus"></i> Add Daughter
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background-color: #f8f9fa;">
                <?php $father_id = $_GET['id']; ?>
                
                <!-- Alert Container for AJAX responses -->
                <div id="addDaughterAlertContainer"></div>
                
                <!-- Form Container -->
                <div id="addDaughterFormContainer">
                    <form id="addDaughterForm" method="post">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="c_name" placeholder="Daughter's Name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="c_dob" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile No</label>
                            <input type="tel" class="form-control" name="c_mobile_no" placeholder="Mobile No">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="c_email" placeholder="Email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Education</label>
                            <select class="form-select" name="c_qualification">
                                <option value="">Select Education</option>
                                <option value="10th">10th</option>
                                <option value="12th">12th</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Degree">Degree</option>
                                <option value="PG">PG</option>
                                <option value="PhD">PhD</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Education Details</label>
                            <input type="text" class="form-control" name="c_education_details" placeholder="Education Details">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Occupation</label>
                            <select class="form-select" name="c_occupation">
                                <option value="">Select Occupation</option>
                                <option value="Student">Student</option>
                                <option value="Employee">Employee</option>
                                <option value="Business">Business</option>
                                <option value="Professional">Professional</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Occupation Details</label>
                            <input type="text" class="form-control" name="c_occupation_details" placeholder="Occupation Details">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Blood Group</label>
                            <select class="form-select" name="c_blood_group">
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                    
                        <input type="hidden" name="c_gender" value="female">
                        <input type="hidden" name="c_marital_status" value="No">
                        <input type="hidden" name="father_id" value="<?php echo $father_id; ?>">
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="addDaughterBtn">
                                <i class="bi bi-save"></i> Add Daughter
                            </button>
                            <button type="button" class="btn btn-primary d-none" id="addDaughterLoader" disabled>
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Adding...
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Application Modal -->
<div class="modal fade" id="uploadApplicationModal" tabindex="-1" aria-labelledby="uploadApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadApplicationModalLabel">
                    <i class="bi bi-upload"></i> Upload Application
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="uploadApplicationBody">
                <form id="uploadApplicationForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="app_front" class="form-label">Front page of application form:</label>
                        <input type="file" class="form-control" name="app_front" id="app_front" accept="image/jpeg,image/jpg,image/png" required>
                        <div class="form-text">JPEG, JPG or PNG images are allowed.</div>
                    </div>
                    <div class="mb-3">
                        <label for="app_back" class="form-label">Back page of application form:</label>
                        <input type="file" class="form-control" name="app_back" id="app_back" accept="image/jpeg,image/jpg,image/png" required>
                        <div class="form-text">JPEG, JPG or PNG images are allowed.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="uploadApplicationFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="uploadApplicationBtn">
                    <i class="bi bi-upload"></i> Upload Application
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('uploadApplicationBtn')?.addEventListener('click', function() {
    const form = document.getElementById('uploadApplicationForm');
    const frontInput = document.getElementById('app_front');
    const backInput = document.getElementById('app_back');
    const btn = this;
    const modalBody = document.getElementById('uploadApplicationBody');
    const modalFooter = document.getElementById('uploadApplicationFooter');
    
    if (!frontInput.files || frontInput.files.length === 0) {
        alert('Please select front page image');
        return;
    }
    
    if (!backInput.files || backInput.files.length === 0) {
        alert('Please select back page image');
        return;
    }
    
    // Show loading state
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
    btn.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('app_front', frontInput.files[0]);
    formData.append('app_back', backInput.files[0]);
    formData.append('id', '<?php echo $row['id']; ?>');
    
    // Make AJAX request
    fetch('appupload.php?id=<?php echo $row['id']; ?>', {
        method: 'POST',
        body: formData
    })
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
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="closeUploadApplicationModal">
                    <i class="bi bi-check-circle"></i> OK
                </button>
            `;
            
            // Update the button in the DOM
            document.getElementById('closeUploadApplicationModal').addEventListener('click', function() {
                // Store the new filenames for delete functionality
                if (data.app_front) window.currentAppFront = data.app_front;
                if (data.app_back) window.currentAppBack = data.app_back;
                
                // Find and update the upload button to "View Application"
                const buttonContainer = document.querySelector('.card-body.text-center');
                if (buttonContainer && data.app_front && data.app_back) {
                    const uploadBtn = buttonContainer.querySelector('button[data-bs-target="#uploadApplicationModal"]');
                    if (uploadBtn) {
                        uploadBtn.outerHTML = `
                            <button class="btn btn-primary me-2" onclick="applicationview(<?php echo $id; ?>)">
                                <i class="bi bi-file-text"></i> View Application
                            </button>
                        `;
                    }
                }
            });
        } else {
            // Show error message
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> ${data.message}
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            `;
        }
    })
    .catch(error => {
        // Show error message
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Error uploading application: ${error.message}
            </div>
        `;
        btn.innerHTML = '<i class="bi bi-upload"></i> Upload Application';
        btn.disabled = false;
    });
});
</script>

<!-- View Application Modal -->
<div class="modal fade" id="viewApplicationModal" tabindex="-1" aria-labelledby="viewApplicationModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 90%; max-height: 95vh;">
        <div class="modal-content" style="max-height: 90vh;">
            <div class="modal-header">
                <h5 class="modal-title" id="viewApplicationModalLabel">
                    <i class="bi bi-file-text"></i> View Application
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
            <div class="modal-body" style="max-height: calc(90vh - 120px); overflow-y: auto;">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h6 class="text-center mb-3">Front Page</h6>
                        <?php if ($row['app_front'] && file_exists("../images/member/" . $row['app_front'])): ?>
                            <img src="../images/member/<?php echo htmlspecialchars($row['app_front']); ?>" 
                                 class="img-fluid border rounded shadow-sm" 
                                 alt="Application Front Page"
                                 style="width: 100%; cursor: pointer;"
                                 ondblclick="window.open(this.src, '_blank')">
                            <p class="text-center text-muted small mt-2">
                                <i class="bi bi-zoom-in"></i> Double-click image to view full size
                            </p>
                        <?php else: ?>
                            <div class="alert alert-warning">Front page not available</div>
                <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h6 class="text-center mb-3">Back Page</h6>
                        <?php if ($row['app_back'] && file_exists("../images/member/" . $row['app_back'])): ?>
                            <img src="../images/member/<?php echo htmlspecialchars($row['app_back']); ?>" 
                                 class="img-fluid border rounded shadow-sm" 
                                 alt="Application Back Page"
                                 style="width: 100%; cursor: pointer;"
                                 ondblclick="window.open(this.src, '_blank')">
                            <p class="text-center text-muted small mt-2">
                                <i class="bi bi-zoom-in"></i> Double-click image to view full size
                            </p>
                <?php else: ?>
                            <div class="alert alert-warning">Back page not available</div>
                        <?php endif; ?>
                        </div>
                        </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Close
                </button>
                <?php if ($row['app_front'] && $row['app_back']): ?>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteApplicationModal">
                    <i class="bi bi-trash"></i> Delete Application
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Application Modal -->
<div class="modal fade" id="deleteApplicationModal" tabindex="-1" aria-labelledby="deleteApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteApplicationModalLabel">
                    <i class="bi bi-trash"></i> Delete Application
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deleteApplicationBody">
                <p>Are you sure you want to delete the application images?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    This action cannot be undone and will permanently remove both front and back page images.
                </p>
            </div>
            <div class="modal-footer" id="deleteApplicationFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteApplication">
                    <i class="bi bi-trash"></i> Delete Application
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize current application image filenames
window.currentAppFront = '<?php echo $row['app_front'] ?? ''; ?>';
window.currentAppBack = '<?php echo $row['app_back'] ?? ''; ?>';

document.getElementById('confirmDeleteApplication')?.addEventListener('click', function() {
    const btn = this;
    const modalBody = document.getElementById('deleteApplicationBody');
    const modalFooter = document.getElementById('deleteApplicationFooter');
    
    // Show loading state
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
    btn.disabled = true;
    
    // Use the current image filenames
    const appFront = window.currentAppFront || '<?php echo $row['app_front'] ?? ''; ?>';
    const appBack = window.currentAppBack || '<?php echo $row['app_back'] ?? ''; ?>';
    
    // Create form data
    const formData = new FormData();
    formData.append('app_front', appFront);
    formData.append('app_back', appBack);
    formData.append('id', '<?php echo $row['id']; ?>');
    
    // Make AJAX request
    fetch('appdelete.php?id=<?php echo $row['id']; ?>&app_front=' + encodeURIComponent(appFront) + '&app_back=' + encodeURIComponent(appBack), {
        method: 'POST',
        body: formData
    })
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
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="closeDeleteApplicationModal">
                    <i class="bi bi-check-circle"></i> OK
                </button>
            `;
            
            // Update the button in the DOM to show "Upload Application"
            document.getElementById('closeDeleteApplicationModal').addEventListener('click', function() {
                // Clear the current filenames
                window.currentAppFront = '';
                window.currentAppBack = '';
                
                // Find and update the view button to "Upload Application"
                const buttonContainer = document.querySelector('.card-body.text-center');
                if (buttonContainer) {
                    const viewBtn = buttonContainer.querySelector('button[onclick*="applicationview"]');
                    if (viewBtn) {
                        viewBtn.outerHTML = `
                            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#uploadApplicationModal">
                                <i class="bi bi-upload"></i> Upload Application
                            </button>
                        `;
                    }
                }
                
                // Close the view application modal if it's open
                const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewApplicationModal'));
                if (viewModal) {
                    viewModal.hide();
                }
            });
        } else {
            // Show error message
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> ${data.message}
                </div>
            `;
            btn.innerHTML = '<i class="bi bi-trash"></i> Delete Application';
            btn.disabled = false;
        }
    })
    .catch(error => {
        // Show error message
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Error deleting application: ${error.message}
            </div>
        `;
        btn.innerHTML = '<i class="bi bi-trash"></i> Delete Application';
        btn.disabled = false;
    });
});
</script>

<!-- Upload Child Image Modal -->
<div class="modal fade" id="uploadChildImageModal" tabindex="-1" aria-labelledby="uploadChildImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadChildImageModalLabel">
                    <i class="bi bi-upload"></i> Upload Child Photo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="uploadChildImageBody">
                <form id="uploadChildImageForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="c_image" class="form-label">Select child image:</label>
                        <input type="file" class="form-control" name="c_image" id="c_image" accept="image/jpeg,image/jpg,image/png" required>
                        <div class="form-text">JPEG, JPG or PNG images are allowed.</div>
                        </div>
                    </form>
            </div>
            <div class="modal-footer" id="uploadChildImageFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="uploadChildImageBtn">
                    <i class="bi bi-upload"></i> Upload
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentChildId = null;
let currentFatherId = null;

// When upload modal is shown, capture the child ID and father ID
document.getElementById('uploadChildImageModal')?.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    currentChildId = button.getAttribute('data-child-id');
    currentFatherId = button.getAttribute('data-father-id');
});

document.getElementById('uploadChildImageBtn')?.addEventListener('click', function() {
    const form = document.getElementById('uploadChildImageForm');
    const fileInput = document.getElementById('c_image');
    const btn = this;
    const modalBody = document.getElementById('uploadChildImageBody');
    const modalFooter = document.getElementById('uploadChildImageFooter');
    
    if (!fileInput.files || fileInput.files.length === 0) {
        alert('Please select an image file');
        return;
    }
    
    if (!currentChildId) {
        alert('Child ID not found');
        return;
    }
    
    // Show loading state
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
    btn.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('c_image', fileInput.files[0]);
    formData.append('id', currentChildId);
    formData.append('father_id', currentFatherId);
    
    // Make AJAX request
    fetch('cupload.php?id=' + currentChildId + '&father_id=' + currentFatherId, {
        method: 'POST',
        body: formData
    })
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
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="closeUploadChildModal">
                    <i class="bi bi-check-circle"></i> OK
                </button>
            `;
            
            // Update the child image in the DOM
            document.getElementById('closeUploadChildModal').addEventListener('click', function() {
                const childImageContainer = document.getElementById('child-image-container-' + currentChildId);
                if (childImageContainer && data.filename) {
                    childImageContainer.innerHTML = `
                        <img src="../images/member/${data.filename}?t=${new Date().getTime()}" class="member-avatar" alt="Child Photo">
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteChildImageModal" data-child-id="${currentChildId}" data-child-image="${data.filename}">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    `;
                }
            });
        } else {
            // Show error message
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> ${data.message}
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            `;
        }
    })
    .catch(error => {
        // Show error message
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Error uploading photo: ${error.message}
            </div>
        `;
        btn.innerHTML = '<i class="bi bi-upload"></i> Upload';
        btn.disabled = false;
    });
});
</script>

<!-- Delete Child Image Modal -->
<div class="modal fade" id="deleteChildImageModal" tabindex="-1" aria-labelledby="deleteChildImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteChildImageModalLabel">
                    <i class="bi bi-trash"></i> Delete Child Photo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deleteChildImageBody">
                <p>Are you sure you want to delete this child's photo?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer" id="deleteChildImageFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteChildImage">
                    <i class="bi bi-trash"></i> Delete Photo
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteChildId = null;
let deleteChildImage = null;

// When delete modal is shown, capture the child ID and image filename
document.getElementById('deleteChildImageModal')?.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    deleteChildId = button.getAttribute('data-child-id');
    deleteChildImage = button.getAttribute('data-child-image');
});

document.getElementById('confirmDeleteChildImage')?.addEventListener('click', function() {
    const btn = this;
    const modalBody = document.getElementById('deleteChildImageBody');
    const modalFooter = document.getElementById('deleteChildImageFooter');
    
    if (!deleteChildId) {
        alert('Child ID not found');
        return;
    }
    
    // Show loading state
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
    btn.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('image', deleteChildImage || '');
    formData.append('id', deleteChildId);
    
    // Make AJAX request
    fetch('cdelete.php?id=' + deleteChildId + '&c_image=' + encodeURIComponent(deleteChildImage || ''), {
        method: 'POST',
        body: formData
    })
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
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="closeDeleteChildModal">
                    <i class="bi bi-check-circle"></i> OK
                </button>
            `;
            
            // Update the child image in the DOM to show placeholder
            document.getElementById('closeDeleteChildModal').addEventListener('click', function() {
                const childImageContainer = document.getElementById('child-image-container-' + deleteChildId);
                if (childImageContainer) {
                    childImageContainer.innerHTML = `
                        <div class="member-avatar-placeholder">
                            <div>
                                <i class="bi bi-person" style="font-size: 20px;"></i><br>
                                No Photo
                            </div>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadChildImageModal" data-child-id="${deleteChildId}" data-father-id="<?php echo $id; ?>">
                                <i class="bi bi-upload"></i> Upload Photo
                            </button>
                        </div>
                    `;
                }
            });
        } else {
            // Show error message
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> ${data.message}
                </div>
            `;
            btn.innerHTML = '<i class="bi bi-trash"></i> Delete Photo';
            btn.disabled = false;
        }
    })
    .catch(error => {
        // Show error message
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Error deleting photo: ${error.message}
            </div>
        `;
        btn.innerHTML = '<i class="bi bi-trash"></i> Delete Photo';
        btn.disabled = false;
    });
});
</script>

<!-- Delete Child Modal -->
<div class="modal fade" id="deleteChildModal" tabindex="-1" aria-labelledby="deleteChildModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteChildModalLabel">
                    <i class="bi bi-trash"></i> Delete Child
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deleteChildModalBody">
                <p>Are you sure you want to delete <strong id="deleteChildName"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    This action cannot be undone and will permanently remove the child record.
                </p>
            </div>
            <div class="modal-footer" id="deleteChildModalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteChild">
                    <i class="bi bi-trash"></i> Delete Child
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteChildRecordId = null;

// When delete modal is shown, capture the child ID and name
document.getElementById('deleteChildModal')?.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    deleteChildRecordId = button.getAttribute('data-child-id');
    const childName = button.getAttribute('data-child-name');
    
    // Update the modal content
    document.getElementById('deleteChildName').textContent = childName;
    
    // Reset modal to initial state
    document.getElementById('deleteChildModalBody').innerHTML = `
        <p>Are you sure you want to delete <strong>${childName}</strong>?</p>
        <p class="text-danger mb-0">
            <i class="bi bi-exclamation-circle"></i> 
            This action cannot be undone and will permanently remove the child record.
        </p>
    `;
    
    document.getElementById('deleteChildModalFooter').innerHTML = `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteChild">
            <i class="bi bi-trash"></i> Delete Child
        </button>
    `;
});

// Handle delete confirmation
document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'confirmDeleteChild') {
        const btn = e.target;
        const modalBody = document.getElementById('deleteChildModalBody');
        const modalFooter = document.getElementById('deleteChildModalFooter');
        
        if (!deleteChildRecordId) {
            alert('Child ID not found');
            return;
        }
        
        // Show loading state
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
        btn.disabled = true;
        
        // Make AJAX request
        fetch('childdelete_ajax.php?id=' + deleteChildRecordId, {
            method: 'POST'
        })
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
                    <button type="button" class="btn btn-success" onclick="window.location.reload()">
                        <i class="bi bi-check-circle"></i> OK
                    </button>
                `;
            } else {
                // Show error message
                modalBody.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> ${data.message}
                    </div>
                `;
                btn.innerHTML = '<i class="bi bi-trash"></i> Delete Child';
                btn.disabled = false;
            }
        })
        .catch(error => {
            // Show error message
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> Error deleting child: ${error.message}
                </div>
            `;
            btn.innerHTML = '<i class="bi bi-trash"></i> Delete Child';
            btn.disabled = false;
        });
    }
});
</script>

<!-- Add Horoscope Modal -->
<div class="modal fade" id="addHoroscopeModal" tabindex="-1" aria-labelledby="addHoroscopeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="height: 95vh;">
            <div class="modal-header">
                <h5 class="modal-title" id="addHoroscopeModalLabel">
                    <i class="bi bi-stars"></i> Add Horoscope
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: calc(95vh - 120px); overflow-y: auto;">
                <?php $ref_id = $_GET['id']; ?>
                
                <!-- Alert container for success/error messages -->
                <div id="horoscopeAlertContainer"></div>
                
                <form id="horoscopeForm">
                    <input type="hidden" name="ref_id" value="<?php echo $id; ?>">
                    <!-- Personal Details -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-person"></i> PERSONAL DETAILS</h6>
                        </div>
                        <div class="card-body" style="background-color: #f8f9fa;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Gender</label>
                                                <select class="form-select" name="gender">
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Age</label>
                                                <select class="form-select" name="age">
                                                    <option value="">Select Age</option>
                                                    <?php for($i=18; $i<=60; $i++): ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Height</label>
                                                <select class="form-select" name="height">
                                                    <option value="">Select Height</option>
                                                    <?php for($i=140; $i<=200; $i++): ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?> cm</option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Weight</label>
                                                <select class="form-select" name="weight">
                                                    <option value="">Select Weight</option>
                                                    <?php for($i=40; $i<=120; $i++): ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?> kg</option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Blood Group</label>
                                                <select class="form-select" name="blood_group">
                                                    <option value="">Select Blood Group</option>
                                                    <option value="A+">A+</option>
                                                    <option value="A-">A-</option>
                                                    <option value="B+">B+</option>
                                                    <option value="B-">B-</option>
                                                    <option value="AB+">AB+</option>
                                                    <option value="AB-">AB-</option>
                                                    <option value="O+">O+</option>
                                                    <option value="O-">O-</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Colour</label>
                                                <select class="form-select" name="colour">
                                                    <option value="">Select Colour</option>
                                                    <option value="Fair">Fair</option>
                                                    <option value="Wheatish">Wheatish</option>
                                                    <option value="Dark">Dark</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Marital Status</label>
                                        <select class="form-select" name="marital_status">
                                            <option value="">Select Marital Status</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Asset Details</label>
                                        <input type="text" class="form-control" name="asset_details" placeholder="Asset Details">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Education</label>
                                        <select class="form-select" name="qualification">
                                            <option value="">Select Education</option>
                                            <option value="10th">10th</option>
                                            <option value="12th">12th</option>
                                            <option value="Diploma">Diploma</option>
                                            <option value="Degree">Degree</option>
                                            <option value="PG">PG</option>
                                            <option value="PhD">PhD</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Education Details</label>
                                        <input type="text" class="form-control" name="education_details" placeholder="Education Details">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Occupation</label>
                                        <select class="form-select" name="occupation">
                                            <option value="">Select Occupation</option>
                                            <option value="Student">Student</option>
                                            <option value="Employee">Employee</option>
                                            <option value="Business">Business</option>
                                            <option value="Professional">Professional</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Occupation Details</label>
                                        <input type="text" class="form-control" name="occupation_details" placeholder="Occupation Details">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">College Details</label>
                                        <input type="text" class="form-control" name="college_details" placeholder="College Details">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Income</label>
                                                <input type="text" class="form-control" name="income" placeholder="Income">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">&nbsp;</label>
                                                <div class="form-control-plaintext">per month</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Workplace</label>
                                        <select class="form-select" name="country">
                                            <option value="">Select Workplace</option>
                                            <option value="India">India</option>
                                            <option value="USA">USA</option>
                                            <option value="UK">UK</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" name="address" rows="3" placeholder="Address"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-telephone"></i> CONTACT DETAILS</h6>
                        </div>
                        <div class="card-body" style="background-color: #f8f9fa;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile No</label>
                                        <input type="tel" class="form-control" name="mobile_no" placeholder="Mobile No">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Contact Person</label>
                                        <input type="text" class="form-control" name="contact_person" placeholder="Contact Person">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Relationship</label>
                                        <input type="text" class="form-control" name="relationship" placeholder="Relationship">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Family Details -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-people"></i> FAMILY DETAILS</h6>
                        </div>
                        <div class="card-body" style="background-color: #f8f9fa;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Father's Name</label>
                                        <input type="text" class="form-control" name="father_name" placeholder="Father's Name">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mother's Name</label>
                                        <input type="text" class="form-control" name="mother_name" placeholder="Mother's Name">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Father's Occupation</label>
                                        <input type="text" class="form-control" name="f_occupation" placeholder="Father's Occupation">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mother's Occupation</label>
                                        <input type="text" class="form-control" name="m_occupation" placeholder="Mother's Occupation">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Sibling</label>
                                        <input type="text" class="form-control" name="sibling" placeholder="Sibling">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kulam</label>
                                        <select class="form-select" name="kulam">
                                            <option value="">Select Kulam</option>
                                            <option value="Kulam1">Kulam 1</option>
                                            <option value="Kulam2">Kulam 2</option>
                                            <option value="Kulam3">Kulam 3</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Temple</label>
                                        <input type="text" class="form-control" name="temple" placeholder="Temple">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mother's Kulam</label>
                                        <select class="form-select" name="m_kulam">
                                            <option value="">Select Kulam</option>
                                            <option value="Kulam1">Kulam 1</option>
                                            <option value="Kulam2">Kulam 2</option>
                                            <option value="Kulam3">Kulam 3</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Maternal Mother's Kulam</label>
                                        <select class="form-select" name="mm_kulam">
                                            <option value="">Select Kulam</option>
                                            <option value="Kulam1">Kulam 1</option>
                                            <option value="Kulam2">Kulam 2</option>
                                            <option value="Kulam3">Kulam 3</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Paternal Mother's Kulam</label>
                                        <select class="form-select" name="pm_kulam">
                                            <option value="">Select Kulam</option>
                                            <option value="Kulam1">Kulam 1</option>
                                            <option value="Kulam2">Kulam 2</option>
                                            <option value="Kulam3">Kulam 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Horoscope Details -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-stars"></i> HOROSCOPE DETAILS</h6>
                        </div>
                        <div class="card-body" style="background-color: #f8f9fa;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Birth Date</label>
                                        <div class="row">
                                            <div class="col-3">
                                                <select class="form-select" name="birth_date[day]">
                                                    <option value="">Day</option>
                                                    <?php for($i=1; $i<=31; $i++): ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <select class="form-select" name="birth_date[month]">
                                                    <option value="">Month</option>
                                                    <option value="1">January</option>
                                                    <option value="2">February</option>
                                                    <option value="3">March</option>
                                                    <option value="4">April</option>
                                                    <option value="5">May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <select class="form-select" name="birth_date[year]">
                                                    <option value="">Year</option>
                                                    <?php for($i=1950; $i<=date('Y'); $i++): ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Birth Time</label>
                                        <div class="row align-items-center">
                                            <div class="col-3">
                                                <select class="form-select" name="birth_time_hour">
                                                    <option value="">Hour</option>
                                                    <?php for($i=0; $i<=23; $i++): ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-control-plaintext text-center" style="line-height: 38px; white-space: nowrap;">HH</div>
                                            </div>
                                            <div class="col-3">
                                                <select class="form-select" name="birth_time_min">
                                                    <option value="">Minute</option>
                                                    <?php for($i=0; $i<=59; $i++): ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                            <div class="col-1">
                                                <div class="form-control-plaintext text-center" style="line-height: 38px; white-space: nowrap;">Min</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Birth Place</label>
                                        <input type="text" class="form-control" name="birth_place" placeholder="Birth Place">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Raghu/Kedhu</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="raaghu_kaedhu" value="1">
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Sevvai</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="sevvai" value="1">
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Raasi</label>
                                        <select class="form-select" name="raasi">
                                            <option value="">Select Raasi</option>
                                            <option value="Mesham">Mesham</option>
                                            <option value="Rishabam">Rishabam</option>
                                            <option value="Mithunam">Mithunam</option>
                                            <option value="Kadagam">Kadagam</option>
                                            <option value="Simham">Simham</option>
                                            <option value="Kanni">Kanni</option>
                                            <option value="Thulam">Thulam</option>
                                            <option value="Viruchigam">Viruchigam</option>
                                            <option value="Dhanusu">Dhanusu</option>
                                            <option value="Makaram">Makaram</option>
                                            <option value="Kumbam">Kumbam</option>
                                            <option value="Meenam">Meenam</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Laknam</label>
                                        <select class="form-select" name="laknam">
                                            <option value="">Select Laknam</option>
                                            <option value="Mesham">Mesham</option>
                                            <option value="Rishabam">Rishabam</option>
                                            <option value="Mithunam">Mithunam</option>
                                            <option value="Kadagam">Kadagam</option>
                                            <option value="Simham">Simham</option>
                                            <option value="Kanni">Kanni</option>
                                            <option value="Thulam">Thulam</option>
                                            <option value="Viruchigam">Viruchigam</option>
                                            <option value="Dhanusu">Dhanusu</option>
                                            <option value="Makaram">Makaram</option>
                                            <option value="Kumbam">Kumbam</option>
                                            <option value="Meenam">Meenam</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Star</label>
                                        <select class="form-select" name="star">
                                            <option value="">Select Star</option>
                                            <option value="Ashwini">Ashwini</option>
                                            <option value="Bharani">Bharani</option>
                                            <option value="Krittika">Krittika</option>
                                            <option value="Rohini">Rohini</option>
                                            <option value="Mrigashira">Mrigashira</option>
                                            <option value="Ardra">Ardra</option>
                                            <option value="Punarvasu">Punarvasu</option>
                                            <option value="Pushya">Pushya</option>
                                            <option value="Ashlesha">Ashlesha</option>
                                            <option value="Magha">Magha</option>
                                            <option value="Purva Phalguni">Purva Phalguni</option>
                                            <option value="Uttara Phalguni">Uttara Phalguni</option>
                                            <option value="Hasta">Hasta</option>
                                            <option value="Chitra">Chitra</option>
                                            <option value="Swati">Swati</option>
                                            <option value="Vishakha">Vishakha</option>
                                            <option value="Anuradha">Anuradha</option>
                                            <option value="Jyeshtha">Jyeshtha</option>
                                            <option value="Mula">Mula</option>
                                            <option value="Purva Ashadha">Purva Ashadha</option>
                                            <option value="Uttara Ashadha">Uttara Ashadha</option>
                                            <option value="Shravana">Shravana</option>
                                            <option value="Dhanishta">Dhanishta</option>
                                            <option value="Shatabhisha">Shatabhisha</option>
                                            <option value="Purva Bhadrapada">Purva Bhadrapada</option>
                                            <option value="Uttara Bhadrapada">Uttara Bhadrapada</option>
                                            <option value="Revati">Revati</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Padham</label>
                                        <select class="form-select" name="padham">
                                            <option value="">Select Padham</option>
                                            <option value="1">1st Padham</option>
                                            <option value="2">2nd Padham</option>
                                            <option value="3">3rd Padham</option>
                                            <option value="4">4th Padham</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Referrer Details -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-person-check"></i> REFERRER DETAILS</h6>
                        </div>
                        <div class="card-body" style="background-color: #f8f9fa;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Referrer Name</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Ref Mobile No</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['mobile_no']); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Referrer Address</label>
                                        <textarea class="form-control" rows="2" readonly><?php echo htmlspecialchars($row['permanent_address']); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expectation -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-heart"></i> EXPECTATION</h6>
                        </div>
                        <div class="card-body" style="background-color: #f8f9fa;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Education</label>
                                        <input type="text" class="form-control" name="pp_education" placeholder="Expected Education">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Occupation</label>
                                        <input type="text" class="form-control" name="pp_occupation" placeholder="Expected Occupation">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Work Location</label>
                                        <select class="form-select" name="pp_work_location">
                                            <option value="">Select Work Location</option>
                                            <option value="India">India</option>
                                            <option value="USA">USA</option>
                                            <option value="UK">UK</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Salary</label>
                                                <input type="text" class="form-control" name="pp_salary" placeholder="Expected Salary">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">&nbsp;</label>
                                                <div class="form-control-plaintext">per month</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Asset Details</label>
                                        <input type="text" class="form-control" name="pp_asset_details" placeholder="Expected Asset Details">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Other Expectations</label>
                                        <textarea class="form-control" name="pp_expectation" rows="3" placeholder="Other Expectations"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="ref_id" value="<?php echo $ref_id; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="horoscopeForm" class="btn btn-primary" id="addHoroscopeBtn">
                    <i class="bi bi-save"></i> Add Horoscope
                </button>
                <button type="button" class="btn btn-primary d-none" id="addHoroscopeLoader">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Adding Horoscope...
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Child Modal -->
<div class="modal fade" id="editChildModal" tabindex="-1" aria-labelledby="editChildModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editChildModalLabel">
                    <i class="bi bi-person-gear"></i> Edit Child
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background-color: #f8f9fa;">
                <!-- Alert Container for AJAX responses -->
                <div id="editChildAlertContainer"></div>
                
                <!-- Form Container -->
                <div id="editChildFormContainer">
                    <form id="editChildForm" method="post">
                        <input type="hidden" name="child_id" id="editChildId">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="c_name" id="editChildName" placeholder="Child's Name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile No</label>
                                <input type="tel" class="form-control" name="c_mobile_no" id="editChildMobile" placeholder="Mobile No">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="c_dob" id="editChildDob" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="c_email" id="editChildEmail" placeholder="Email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Education</label>
                                <select class="form-select" name="c_qualification" id="editChildEducation">
                                    <option value="-">Select Education</option>
                                    <option value="10th">10th</option>
                                    <option value="12th">12th</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="Degree">Degree</option>
                                    <option value="PG">PG</option>
                                    <option value="PhD">PhD</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Education Details</label>
                                <input type="text" class="form-control" name="c_education_details" id="editChildEducationDetails" placeholder="Education Details" value="-">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Occupation</label>
                                <select class="form-select" name="c_occupation" id="editChildOccupation">
                                    <option value="-">Select Occupation</option>
                                    <option value="Student">Student</option>
                                    <option value="Employee">Employee</option>
                                    <option value="Business">Business</option>
                                    <option value="Professional">Professional</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Occupation Details</label>
                                <input type="text" class="form-control" name="c_occupation_details" id="editChildOccupationDetails" placeholder="Occupation Details" value="-">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Blood Group</label>
                                <select class="form-select" name="c_blood_group" id="editChildBloodGroup">
                                    <option value="-">Select Blood Group</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Marital Status</label>
                                <select class="form-select" name="c_marital_status" id="editChildMaritalStatus">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>
                        
                        <input type="hidden" name="c_gender" id="editChildGender">
                        
                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="editChildBtn">
                                <i class="bi bi-save"></i> Update Child
                            </button>
                            <button type="button" class="btn btn-primary d-none" id="editChildLoader" disabled>
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Updating...
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deletemember(id) {
    url = "deletemember.php?id=" + id;
    title = "popup";
    var newWindow = window.open(url, title, 'scrollbars=yes, width=600, height=500');
}

function husband() {
    new bootstrap.Modal(document.getElementById('uploadHusbandImageModal')).show();
}

function himagedelete() {
    new bootstrap.Modal(document.getElementById('deleteHusbandImageModal')).show();
}

function wife() {
    new bootstrap.Modal(document.getElementById('uploadWifeImageModal')).show();
}

function wdelete() {
    new bootstrap.Modal(document.getElementById('deleteWifeImageModal')).show();
}

function addchildphoto(id, father_id) {
    url = "cimageupload.php?id=" + id + "&father_id=" + father_id;
    title = "popup";
    var newWindow = window.open(url, title, 'scrollbars=yes, width=700, height=400');
}

function cimagedelete(id, c_image) {
    url = "cimagedelete.php?id=" + id + "&c_image=" + c_image;
    title = "popup";
    var newWindow = window.open(url, title, 'scrollbars=yes, width=700, height=400');
}

function cupdate(id) {
    // Fetch child data and populate modal
    fetch('get_child_data.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate form fields
                document.getElementById('editChildId').value = data.child.id;
                document.getElementById('editChildName').value = data.child.c_name;
                document.getElementById('editChildMobile').value = data.child.c_mobile_no || '';
                document.getElementById('editChildDob').value = data.child.c_dob;
                document.getElementById('editChildEmail').value = data.child.c_email || '';
                document.getElementById('editChildEducation').value = data.child.c_qualification || '';
                document.getElementById('editChildEducationDetails').value = data.child.c_education_details || '';
                document.getElementById('editChildOccupation').value = data.child.c_occupation || '';
                document.getElementById('editChildOccupationDetails').value = data.child.c_occupation_details || '';
                document.getElementById('editChildBloodGroup').value = data.child.c_blood_group || '';
                document.getElementById('editChildMaritalStatus').value = data.child.c_marital_status || 'No';
                document.getElementById('editChildGender').value = data.child.c_gender;
                
                // Show modal
                new bootstrap.Modal(document.getElementById('editChildModal')).show();
            } else {
                alert('Error loading child data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading child data');
        });
}

function childdelete(id) {
    url = "childdelete.php?id=" + id;
    title = "popup";
    var newWindow = window.open(url, title, 'scrollbars=yes, width=500, height=400');
}

function linkfamily(id) {
    url = "linkfamily.php?child_id=" + id;
    title = "popup";
    var newWindow = window.open(url, title, 'scrollbars=yes, width=1050, height=550');
}

function addson() {
    console.log('addson function called');
    const modal = document.getElementById('addSonModal');
    console.log('Modal element:', modal);
    if (modal) {
        try {
            const bootstrapModal = new bootstrap.Modal(modal);
            console.log('Bootstrap modal instance:', bootstrapModal);
            bootstrapModal.show();
        } catch (error) {
            console.error('Bootstrap modal error:', error);
            // Fallback: try to show modal manually
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
    } else {
        console.error('Modal element not found');
    }
}

function adddaughter() {
    new bootstrap.Modal(document.getElementById('addDaughterModal')).show();
}

function addhoro() {
    new bootstrap.Modal(document.getElementById('addHoroscopeModal')).show();
}

function applicupload(id) {
    new bootstrap.Modal(document.getElementById('uploadApplicationModal')).show();
}

function applicationview(id) {
    // Show the view application modal
    const modal = new bootstrap.Modal(document.getElementById('viewApplicationModal'));
    modal.show();
}

function printid() {
    url = "printid.php?id=<?php echo $id; ?>";
    title = "popup";
    var newWindow = window.open(url, title, 'scrollbars=yes, width=500, height=400');
}

function editMemberId() {
    document.getElementById('memberIdDisplay').style.display = 'none';
    document.getElementById('memberIdEdit').style.display = 'flex';
}

function cancelEditMemberId() {
    document.getElementById('memberIdDisplay').style.display = 'block';
    document.getElementById('memberIdEdit').style.display = 'none';
}

// Handle form submissions and page refresh only when data is actually submitted
document.addEventListener('DOMContentLoaded', function() {
    // Debug Bootstrap availability
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    if (typeof bootstrap !== 'undefined') {
        console.log('Bootstrap version:', bootstrap.VERSION);
    }
    
    // Test modal functionality
    const testModal = document.getElementById('addSonModal');
    console.log('Test modal element:', testModal);
    if (testModal) {
        console.log('Modal HTML:', testModal.outerHTML.substring(0, 200) + '...');
    }
    // Track if any form was submitted
    let formSubmitted = false;
    
    // Listen for form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            formSubmitted = true;
        });
    });
    
    // Only refresh page when modal is hidden AND a form was submitted
    const modals = [
        'uploadHusbandImageModal',
        'deleteHusbandImageModal', 
        'uploadWifeImageModal',
        'deleteWifeImageModal',
        'addSonModal',
        'addDaughterModal',
        'uploadApplicationModal',
        'addHoroscopeModal',
        'editChildModal'
    ];
    
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                // Only refresh if a form was actually submitted
                if (formSubmitted) {
                    window.location.reload();
                    formSubmitted = false; // Reset for next time
                }
            });
        }
    });
    
    // Horoscope form AJAX handling
    const horoscopeForm = document.getElementById('horoscopeForm');
    const addHoroscopeBtn = document.getElementById('addHoroscopeBtn');
    const addHoroscopeLoader = document.getElementById('addHoroscopeLoader');
    const alertContainer = document.getElementById('horoscopeAlertContainer');
    
    // Add Son form AJAX handling
    const addSonForm = document.getElementById('addSonForm');
    const addSonBtn = document.getElementById('addSonBtn');
    const addSonLoader = document.getElementById('addSonLoader');
    const addSonAlertContainer = document.getElementById('addSonAlertContainer');
    const addSonFormContainer = document.getElementById('addSonFormContainer');
    
    // Add Daughter form AJAX handling
    const addDaughterForm = document.getElementById('addDaughterForm');
    const addDaughterBtn = document.getElementById('addDaughterBtn');
    const addDaughterLoader = document.getElementById('addDaughterLoader');
    const addDaughterAlertContainer = document.getElementById('addDaughterAlertContainer');
    const addDaughterFormContainer = document.getElementById('addDaughterFormContainer');
    
    // Edit Child form AJAX handling
    const editChildForm = document.getElementById('editChildForm');
    const editChildBtn = document.getElementById('editChildBtn');
    const editChildLoader = document.getElementById('editChildLoader');
    const editChildAlertContainer = document.getElementById('editChildAlertContainer');
    const editChildFormContainer = document.getElementById('editChildFormContainer');
    
    if (horoscopeForm) {
        horoscopeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loader and hide submit button
            addHoroscopeBtn.classList.add('d-none');
            addHoroscopeLoader.classList.remove('d-none');
            
            // Clear previous alerts
            alertContainer.innerHTML = '';
            
            // Get form data
            const formData = new FormData(horoscopeForm);
            
            // Make AJAX request
            fetch('add_horoscope_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Hide loader and show submit button
                addHoroscopeLoader.classList.add('d-none');
                addHoroscopeBtn.classList.remove('d-none');
                
                // Show success or error message
                const alertClass = data.success ? 'alert-success' : 'alert-danger';
                const alertIcon = data.success ? 'bi-check-circle' : 'bi-exclamation-triangle';
                
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="bi ${alertIcon}"></i> ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                alertContainer.innerHTML = alertHtml;
                
                // If successful, optionally close modal or reset form
                if (data.success) {
                    // Reset form after successful submission
                    setTimeout(() => {
                        horoscopeForm.reset();
                    }, 2000);
                }
            })
            .catch(error => {
                // Hide loader and show submit button
                addHoroscopeLoader.classList.add('d-none');
                addHoroscopeBtn.classList.remove('d-none');
                
                // Show error message
                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> Error: Network error occurred. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                alertContainer.innerHTML = alertHtml;
            });
        });
    }
    
    // Add Son form AJAX handling
    if (addSonForm) {
        addSonForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loader and hide submit button
            addSonBtn.classList.add('d-none');
            addSonLoader.classList.remove('d-none');
            
            // Clear previous alerts
            addSonAlertContainer.innerHTML = '';
            
            // Get form data
            const formData = new FormData(addSonForm);
            
            // Make AJAX request
            fetch('add_son_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Hide loader and show submit button
                addSonLoader.classList.add('d-none');
                addSonBtn.classList.remove('d-none');
                
                // Show success or error message
                if (data.success) {
                    // Replace form with success message
                    addSonFormContainer.innerHTML = `
                        <div class="alert alert-success text-center" role="alert">
                            <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                            <h5 class="mt-3">${data.message}</h5>
                            <p class="text-muted">The son has been added successfully.</p>
                        </div>
                    `;
                } else {
                    // Show error message above form
                    addSonAlertContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }
            })
            .catch(error => {
                // Hide loader and show submit button
                addSonLoader.classList.add('d-none');
                addSonBtn.classList.remove('d-none');
                
                // Show error message
                addSonAlertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> Network error: ${error.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
            });
        });
    }
    
    // Add Daughter form AJAX handling
    if (addDaughterForm) {
        addDaughterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loader and hide submit button
            addDaughterBtn.classList.add('d-none');
            addDaughterLoader.classList.remove('d-none');
            
            // Clear previous alerts
            addDaughterAlertContainer.innerHTML = '';
            
            // Get form data
            const formData = new FormData(addDaughterForm);
            
            // Make AJAX request
            fetch('add_daughter_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Hide loader and show submit button
                addDaughterLoader.classList.add('d-none');
                addDaughterBtn.classList.remove('d-none');
                
                // Show success or error message
                if (data.success) {
                    // Replace form with success message
                    addDaughterFormContainer.innerHTML = `
                        <div class="alert alert-success text-center" role="alert">
                            <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                            <h5 class="mt-3">${data.message}</h5>
                            <p class="text-muted">The daughter has been added successfully.</p>
                        </div>
                    `;
                } else {
                    // Show error message above form
                    addDaughterAlertContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }
            })
            .catch(error => {
                // Hide loader and show submit button
                addDaughterLoader.classList.add('d-none');
                addDaughterBtn.classList.remove('d-none');
                
                // Show error message
                addDaughterAlertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> Network error: ${error.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
            });
        });
    }
    
    // Edit Child form AJAX handling
    if (editChildForm) {
        editChildForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loader and hide submit button
            editChildBtn.classList.add('d-none');
            editChildLoader.classList.remove('d-none');
            
            // Clear previous alerts
            editChildAlertContainer.innerHTML = '';
            
            // Get form data
            const formData = new FormData(editChildForm);
            
            // Make AJAX request
            fetch('update_child_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Hide loader and show submit button
                editChildLoader.classList.add('d-none');
                editChildBtn.classList.remove('d-none');
                
                // Show success or error message
                if (data.success) {
                    // Replace form with success message
                    editChildFormContainer.innerHTML = `
                        <div class="alert alert-success text-center" role="alert">
                            <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                            <h5 class="mt-3">${data.message}</h5>
                            <p class="text-muted">The child has been updated successfully.</p>
                        </div>
                    `;
                } else {
                    // Show error message above form
                    editChildAlertContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }
            })
            .catch(error => {
                // Hide loader and show submit button
                editChildLoader.classList.add('d-none');
                editChildBtn.classList.remove('d-none');
                
                // Show error message
                editChildAlertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> Network error: ${error.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
            });
        });
    }
});
</script>

<!-- Print ID Card Modal -->
<div class="modal fade" id="printIdModal" tabindex="-1" aria-labelledby="printIdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printIdModalLabel">
                    <i class="bi bi-card-heading"></i> Member ID Card
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="idCardContent">
                <style>
                    @media print {
                        body * {
                            visibility: hidden;
                        }
                        #idCardContent, #idCardContent * {
                            visibility: visible;
                        }
                        #idCardContent {
                            position: absolute;
                            left: 0;
                            top: 0;
                            width: 100%;
                        }
                        .modal-header, .modal-footer, .btn-close {
                            display: none !important;
                        }
                    }
                    
                    .id-card {
                        border: 3px solid #4e73df;
                        border-radius: 15px;
                        padding: 20px;
                        background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
                        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                    }
                    
                    .id-card-header {
                        text-align: center;
                        border-bottom: 2px solid #4e73df;
                        padding-bottom: 15px;
                        margin-bottom: 20px;
                    }
                    
                    .id-card-header h6 {
                        color: #4e73df;
                        font-weight: 700;
                        margin: 0;
                        font-size: 16px;
                    }
                    
                    .id-card-body {
                        display: flex;
                        gap: 20px;
                    }
                    
                    .id-card-photo {
                        flex-shrink: 0;
                    }
                    
                    .id-card-photo img {
                        width: 120px;
                        height: 140px;
                        object-fit: cover;
                        border: 2px solid #4e73df;
                        border-radius: 8px;
                    }
                    
                    .id-card-details {
                        flex-grow: 1;
                    }
                    
                    .id-detail-row {
                        display: flex;
                        padding: 8px 0;
                        border-bottom: 1px solid #e3e6f0;
                    }
                    
                    .id-detail-row:last-child {
                        border-bottom: none;
                    }
                    
                    .id-detail-label {
                        font-weight: 600;
                        color: #5a5c69;
                        min-width: 100px;
                    }
                    
                    .id-detail-value {
                        color: #2e2f37;
                        font-weight: 500;
                    }
                </style>
                
                <div class="id-card">
                    <div class="id-card-header">
                        <h6><?php echo $org_name; ?></h6>
                    </div>
                    
                    <div class="id-card-body">
                        <div class="id-card-photo">
                            <?php if ($row['image'] && file_exists("../images/member/" . $row['image'])): ?>
                                <img src="../images/member/<?php echo htmlspecialchars($row['image']); ?>" alt="Member Photo">
                            <?php else: ?>
                                <div style="width: 120px; height: 140px; background: #e3e6f0; display: flex; align-items: center; justify-content: center; border: 2px solid #4e73df; border-radius: 8px;">
                                    <i class="bi bi-person" style="font-size: 48px; color: #858796;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="id-card-details">
                            <?php if (!empty($row['member_id'])): ?>
                            <div class="id-detail-row">
                                <div class="id-detail-label">உ எண் :</div>
                                <div class="id-detail-value"><?php echo htmlspecialchars($row['member_id']); ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="id-detail-row">
                                <div class="id-detail-label">பெயர் :</div>
                                <div class="id-detail-value"><?php echo htmlspecialchars($row['name']); ?></div>
                            </div>
                            
                            <?php if (!empty($row['dob'])): ?>
                            <div class="id-detail-row">
                                <div class="id-detail-label">வயது :</div>
                                <div class="id-detail-value">
                                    <?php 
                                    $dob = new DateTime($row['dob']);
                                    $now = new DateTime();
                                    $age = $now->diff($dob)->y;
                                    echo $age;
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($row['village'])): ?>
                            <div class="id-detail-row">
                                <div class="id-detail-label">ஊர் :</div>
                                <div class="id-detail-value"><?php echo htmlspecialchars($row['village']); ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($row['mobile_no'])): ?>
                            <div class="id-detail-row">
                                <div class="id-detail-label">மொபைல் :</div>
                                <div class="id-detail-value"><?php echo htmlspecialchars($row['mobile_no']); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Close
                </button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Unlink Family Modal -->
<div class="modal fade" id="unlinkFamilyModal" tabindex="-1" aria-labelledby="unlinkFamilyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="unlinkFamilyModalLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirm Unlink
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="unlinkChildId">
                
                <div id="unlinkConfirmSection">
                    <p>Are you sure you want to unlink this family?</p>
                    <p class="text-danger mb-0">
                        <i class="bi bi-exclamation-circle"></i> 
                        This will remove the link between the child and the family record.
                    </p>
                </div>
                
                <div id="unlinkSuccessMessage" class="d-none">
                    <div class="alert alert-success">
                        <h6 class="alert-heading"><i class="bi bi-check-circle"></i> Success!</h6>
                        <p class="mb-0">Family unlinked successfully!</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelUnlinkBtn">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmUnlinkBtn" onclick="confirmUnlinkFamily()">
                    <i class="bi bi-link-45deg"></i> Unlink Family
                </button>
                <button type="button" class="btn btn-primary d-none" id="okUnlinkBtn" onclick="closeUnlinkModal()">
                    <i class="bi bi-check-circle"></i> OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Link Family Modal -->
<div class="modal fade" id="linkFamilyModal" tabindex="-1" aria-labelledby="linkFamilyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkFamilyModalLabel">
                    <i class="bi bi-link"></i> Link to Existing Family
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="linkChildId">
                
                <div id="searchSection">
                    <div class="mb-3">
                        <label for="linkMemberId" class="form-label">Enter Member ID</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="linkMemberId" placeholder="Enter Member ID">
                            <button class="btn btn-primary" type="button" onclick="searchMemberById()">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="memberPreview" class="d-none">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Member Details:</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td id="previewName"></td>
                            </tr>
                            <tr>
                                <td><strong>Father's Name:</strong></td>
                                <td id="previewFatherName"></td>
                            </tr>
                            <tr>
                                <td><strong>Mother's Name:</strong></td>
                                <td id="previewMotherName"></td>
                            </tr>
                            <tr>
                                <td><strong>Village:</strong></td>
                                <td id="previewVillage"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div id="memberNotFound" class="alert alert-danger d-none">
                    Member not found with this ID.
                </div>
                
                <div id="linkSuccessMessage" class="d-none">
                    <div class="alert alert-success">
                        <h6 class="alert-heading"><i class="bi bi-check-circle"></i> Success!</h6>
                        <p class="mb-0">Family linked successfully!</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelLinkBtn">Cancel</button>
                <button type="button" class="btn btn-primary d-none" id="confirmLinkBtn" onclick="confirmLinkFamily()">
                    <i class="bi bi-check-circle"></i> Confirm Link
                </button>
                <button type="button" class="btn btn-primary d-none" id="okLinkBtn" onclick="closeLinkModal()">
                    <i class="bi bi-check-circle"></i> OK
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedFamilyId = null;

function linkfamily(childId) {
    document.getElementById('linkChildId').value = childId;
    document.getElementById('linkMemberId').value = '';
    document.getElementById('searchSection').classList.remove('d-none');
    document.getElementById('memberPreview').classList.add('d-none');
    document.getElementById('memberNotFound').classList.add('d-none');
    document.getElementById('confirmLinkBtn').classList.add('d-none');
    selectedFamilyId = null;
    
    new bootstrap.Modal(document.getElementById('linkFamilyModal')).show();
}

function searchMemberById() {
    const memberId = document.getElementById('linkMemberId').value.trim();
    
    if (!memberId) {
        alert('Please enter a Member ID');
        return;
    }
    
    // Search for member
    fetch('search_member_by_id.php?member_id=' + encodeURIComponent(memberId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show member preview and hide search section
                document.getElementById('previewName').textContent = data.member.name || '-';
                document.getElementById('previewFatherName').textContent = data.member.father_name || '-';
                document.getElementById('previewMotherName').textContent = data.member.mother_name || '-';
                document.getElementById('previewVillage').textContent = data.member.village || '-';
                
                document.getElementById('searchSection').classList.add('d-none');
                document.getElementById('memberPreview').classList.remove('d-none');
                document.getElementById('memberNotFound').classList.add('d-none');
                document.getElementById('confirmLinkBtn').classList.remove('d-none');
                
                selectedFamilyId = data.member.id;
            } else {
                document.getElementById('memberPreview').classList.add('d-none');
                document.getElementById('memberNotFound').classList.remove('d-none');
                document.getElementById('confirmLinkBtn').classList.add('d-none');
                selectedFamilyId = null;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error searching for member');
        });
}

function confirmLinkFamily() {
    const childId = document.getElementById('linkChildId').value;
    
    if (!selectedFamilyId) {
        showModalMessage('Please search and select a member first', 'warning');
        return;
    }
    
    // Disable button and show loading
    const confirmBtn = document.getElementById('confirmLinkBtn');
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Linking...';
    
    // Link child to family
    fetch('link_child_to_family.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'child_id=' + childId + '&family_id=' + selectedFamilyId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide all sections and show success message
            document.getElementById('searchSection').classList.add('d-none');
            document.getElementById('memberPreview').classList.add('d-none');
            document.getElementById('memberNotFound').classList.add('d-none');
            document.getElementById('linkSuccessMessage').classList.remove('d-none');
            
            // Hide confirm button and cancel button, show OK button
            document.getElementById('confirmLinkBtn').classList.add('d-none');
            document.getElementById('cancelLinkBtn').classList.add('d-none');
            document.getElementById('okLinkBtn').classList.remove('d-none');
        } else {
            showModalMessage('Error: ' + data.message, 'danger');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="bi bi-check-circle"></i> Confirm Link';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showModalMessage('Error linking family', 'danger');
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="bi bi-check-circle"></i> Confirm Link';
    });
}

function closeLinkModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('linkFamilyModal'));
    modal.hide();
    location.reload();
}

function unlinkFamily(childId) {
    document.getElementById('unlinkChildId').value = childId;
    document.getElementById('unlinkConfirmSection').classList.remove('d-none');
    document.getElementById('unlinkSuccessMessage').classList.add('d-none');
    document.getElementById('cancelUnlinkBtn').classList.remove('d-none');
    document.getElementById('confirmUnlinkBtn').classList.remove('d-none');
    document.getElementById('okUnlinkBtn').classList.add('d-none');
    
    new bootstrap.Modal(document.getElementById('unlinkFamilyModal')).show();
}

function confirmUnlinkFamily() {
    const childId = document.getElementById('unlinkChildId').value;
    const confirmBtn = document.getElementById('confirmUnlinkBtn');
    
    // Disable button and show loading
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Unlinking...';
    
    fetch('unlink_child_from_family.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'child_id=' + childId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide confirm section and show success message
            document.getElementById('unlinkConfirmSection').classList.add('d-none');
            document.getElementById('unlinkSuccessMessage').classList.remove('d-none');
            
            // Hide confirm and cancel buttons, show OK button
            document.getElementById('confirmUnlinkBtn').classList.add('d-none');
            document.getElementById('cancelUnlinkBtn').classList.add('d-none');
            document.getElementById('okUnlinkBtn').classList.remove('d-none');
        } else {
            showErrorToast('Error: ' + data.message);
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="bi bi-link-45deg"></i> Unlink Family';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('Error unlinking family');
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="bi bi-link-45deg"></i> Unlink Family';
    });
}

function closeUnlinkModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('unlinkFamilyModal'));
    modal.hide();
    location.reload();
}

function showModalMessage(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-2`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const modalBody = document.querySelector('#linkFamilyModal .modal-body');
    const existingAlert = modalBody.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    modalBody.insertBefore(alertDiv, modalBody.firstChild);
}

function showSuccessToast(message) {
    const toast = document.createElement('div');
    toast.className = 'position-fixed top-0 end-0 p-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function showErrorToast(message) {
    const toast = document.createElement('div');
    toast.className = 'position-fixed top-0 end-0 p-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header bg-danger text-white">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function addAdminNote() {
    const noteText = document.getElementById('adminNoteText').value.trim();
    
    if (!noteText) {
        showErrorToast('Please enter a note');
        return;
    }
    
    const formData = new FormData();
    formData.append('member_id', <?php echo $id; ?>);
    formData.append('note_text', noteText);
    
    fetch('add_admin_note.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear textarea
            document.getElementById('adminNoteText').value = '';
            
            // Remove "no notes" message if exists
            const noNotesMsg = document.getElementById('noNotesMessage');
            if (noNotesMsg) {
                noNotesMsg.remove();
            }
            
            // Add new note to the list
            const notesList = document.getElementById('adminNotesList');
            const noteHtml = `
                <div class="admin-note-item mb-3 p-3 border rounded" id="note-${data.note.id}">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">
                            <i class="bi bi-person"></i> ${data.note.added_by}
                        </small>
                        <div>
                            <small class="text-muted me-2">
                                <i class="bi bi-clock"></i> ${data.note.added_at}
                            </small>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteAdminNote('${data.note.id}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div>${data.note.note.replace(/\n/g, '<br>')}</div>
                </div>
            `;
            notesList.insertAdjacentHTML('afterbegin', noteHtml);
            
            showSuccessToast('Note added successfully');
        } else {
            showErrorToast(data.message || 'Failed to add note');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('Error adding note');
    });
}

function deleteAdminNote(noteId) {
    if (!confirm('Are you sure you want to delete this note?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('member_id', <?php echo $id; ?>);
    formData.append('note_id', noteId);
    
    fetch('delete_admin_note.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove note from DOM
            const noteElement = document.getElementById('note-' + noteId);
            if (noteElement) {
                noteElement.remove();
            }
            
            // Check if no notes left, show message
            const notesList = document.getElementById('adminNotesList');
            if (notesList.children.length === 0) {
                notesList.innerHTML = '<div class="text-muted text-center py-3" id="noNotesMessage"><i class="bi bi-sticky"></i> No notes yet</div>';
            }
            
            showSuccessToast('Note deleted successfully');
        } else {
            showErrorToast(data.message || 'Failed to delete note');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('Error deleting note');
    });
}

// Print Member Details Function
function printMemberDetails() {
    // Get the print content
    const printContent = document.getElementById('printView').innerHTML;
    
    // Open new window
    const printWindow = window.open('', '', 'width=800,height=600');
    
    // Write content to new window
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title><?php echo htmlspecialchars($row['name']); ?> குடும்பம் - Member Details</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0; 
                    padding: 20px; 
                }
                @media print {
                    @page { margin: 1.5cm; }
                }
            </style>
        </head>
        <body>
            ${printContent}
        </body>
        </html>
    `);
    
    // Close document and trigger print
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}
</script>

<!-- Print-Only View (Hidden on screen) -->
<div id="printView" style="display: none;">
    <div style="max-width: 800px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
        <!-- Header -->
        <div style="text-align: center; border-bottom: 3px solid #333; padding-bottom: 15px; margin-bottom: 25px;">
            <h2 style="margin: 0; color: #333;">குடும்ப விவரம் / Family Details</h2>
            <p style="margin: 5px 0 0 0; color: #666;">அருள்மிகு புது வெங்கரை அம்மன் கோயில்</p>
        </div>

        <!-- Member ID & Family Name -->
        <div style="margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold; width: 20%;">Member ID:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 20%;"><?php echo htmlspecialchars($row['member_id'] ?? '-'); ?></td>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold; width: 23%;">Family Name:</td>
                    <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['family_name'] ?? '-'); ?></td>
                </tr>
            </table>
        </div>

        <!-- Husband Details -->
        <div style="margin-bottom: 20px;">
            <h4 style="background: #333; color: white; padding: 8px; margin: 0 0 10px 0;">கணவர் விவரம் / Husband Details</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold; width: 25%;">Name:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold; width: 25%;">Mobile:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['mobile_no'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">DOB:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['dob'] ?? '-'); ?></td>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Blood Group:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['blood_group'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Education:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo get_qualification($row['qualification'] ?? ''); ?></td>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Occupation:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo get_occupation($row['occupation'] ?? ''); ?></td>
                </tr>
                <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Kootam:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo get_kulam($row['kootam'] ?? ''); ?></td>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Temple:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['temple'] ?? '-'); ?></td>
                </tr>
            </table>
        </div>

        <!-- Wife Details -->
        <div style="margin-bottom: 20px;">
            <h4 style="background: #333; color: white; padding: 8px; margin: 0 0 10px 0;">மனைவி விவரம் / Wife Details</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold; width: 25%;">Name:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['w_name'] ?? '-'); ?></td>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold; width: 25%;">Education:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo get_qualification($row['w_qualification'] ?? ''); ?></td>
                </tr>
                <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Occupation:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo get_occupation($row['w_occupation'] ?? ''); ?></td>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Kootam:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo get_kulam($row['w_kootam'] ?? ''); ?></td>
                </tr>
            </table>
        </div>

        <!-- Children Details -->
        <?php
        $children = get_children($id);
        if ($children && isset($children[$id]) && count($children[$id]) > 0):
        ?>
        <div style="margin-bottom: 20px;">
            <h4 style="background: #333; color: white; padding: 8px; margin: 0 0 10px 0;">குழந்தைகள் விவரம் / Children Details</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f5f5f5;">
                        <th style="padding: 6px; border: 1px solid #ddd; text-align: left;">Name</th>
                        <th style="padding: 6px; border: 1px solid #ddd; text-align: left;">DOB</th>
                        <th style="padding: 6px; border: 1px solid #ddd; text-align: left;">Gender</th>
                        <th style="padding: 6px; border: 1px solid #ddd; text-align: left;">Education</th>
                        <th style="padding: 6px; border: 1px solid #ddd; text-align: left;">Occupation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($children[$id] as $child): ?>
                    <tr>
                        <td style="padding: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($child['c_name']); ?></td>
                        <td style="padding: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($child['c_dob'] ?? '-'); ?></td>
                        <td style="padding: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($child['c_gender'] ?? '-'); ?></td>
                        <td style="padding: 6px; border: 1px solid #ddd;"><?php echo get_qualification($child['c_qualification'] ?? ''); ?></td>
                        <td style="padding: 6px; border: 1px solid #ddd;"><?php echo get_occupation($child['c_occupation'] ?? ''); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Address -->
        <div style="margin-bottom: 20px;">
            <h4 style="background: #333; color: white; padding: 8px; margin: 0 0 10px 0;">முகவரி / Address</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold; width: 25%;">Permanent Address:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['permanent_address'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Current Address:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;">
                        <?php 
                        if (isset($row['same_as_permanent']) && $row['same_as_permanent'] == 1) {
                            echo htmlspecialchars($row['permanent_address'] ?? '-');
                        } else {
                            echo htmlspecialchars($row['current_address'] ?? '-');
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 6px; border: 1px solid #ddd; background: #f5f5f5; font-weight: bold;">Mobile:</td>
                    <td style="padding: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['mobile_no'] ?? '-'); ?></td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; text-align: center; font-size: 12px; color: #666;">
            <p>Printed on: <?php echo date('d-m-Y H:i'); ?></p>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>