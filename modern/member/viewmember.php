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
</style>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center main-card-header">
                    <h4 class="mb-0 family-name">
                        <i class="bi bi-people"></i> <?php echo htmlspecialchars($row['name']); ?> குடும்பம்
                    </h4>
                    <div class="action-buttons">
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
                        <li class="nav-item" role="presentation">
                            <a class="nav-link <?php echo $horoscope_active; ?>" href="?id=<?php echo $id; ?>&tab=horoscope">
                                <i class="bi bi-stars"></i> Horoscope
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-4" id="memberTabsContent">
                        <div class="tab-pane fade <?php echo $profile_active ? 'show active' : ''; ?>" id="profile" role="tabpanel">
                            <div class="row">
                                <!-- Left Column - Family Members -->
                                <div class="col-lg-7">
                                    <!-- Husband Section -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="bi bi-person"></i> குடும்ப தலைவர்</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 text-center">
                                                    <?php if ($row['image'] && file_exists("../images/member/" . $row['image'])): ?>
                                                        <img src="../images/member/<?php echo htmlspecialchars($row['image']); ?>" class="member-avatar" alt="Husband Photo">
                                                    <?php else: ?>
                                                        <div class="member-avatar-placeholder">
                                                            <div>
                                                                <i class="bi bi-person" style="font-size: 24px;"></i><br>
                                                                No Photo
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadHusbandImageModal">
                                                            <i class="bi bi-upload"></i> Upload
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteHusbandImageModal">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="data-item">
                                                        <div class="data-label">Name:</div>
                                                        <div class="data-value"><?php echo htmlspecialchars($row['name'] ?? '-'); ?></div>
                                                    </div>
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
                                                <div class="col-md-3 text-center">
                                                    <?php if ($row['w_image'] && file_exists("../images/member/" . $row['w_image'])): ?>
                                                        <img src="../images/member/<?php echo htmlspecialchars($row['w_image']); ?>" class="member-avatar" alt="Wife Photo">
                                                    <?php else: ?>
                                                        <div class="member-avatar-placeholder">
                                                            <div>
                                                                <i class="bi bi-person-heart" style="font-size: 24px;"></i><br>
                                                                No Photo
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadWifeImageModal">
                                                            <i class="bi bi-upload"></i> Upload
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteWifeImageModal">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </div>
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
                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-gear"></i> Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="addson(); return false;">
                                                        <i class="bi bi-person-plus"></i> Add Son
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="adddaughter(); return false;">
                                                        <i class="bi bi-person-plus"></i> Add Daughter
                                                    </a></li>
                                                </ul>
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
                                                                <button class="btn btn-sm btn-outline-danger" onclick="childdelete(<?php echo $v['id']; ?>)">
                                                                    <i class="bi bi-trash"></i> Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="p-3">
                                                            <div class="row">
                                                                <div class="col-md-3 text-center">
                                                                    <?php if ($v['c_image'] && file_exists("../images/member/" . $v['c_image'])): ?>
                                                                        <img src="../images/member/<?php echo htmlspecialchars($v['c_image']); ?>" class="member-avatar" alt="Child Photo">
                                                                    <?php else: ?>
                                                                        <div class="member-avatar-placeholder">
                                                                            <div>
                                                                                <i class="bi bi-person" style="font-size: 20px;"></i><br>
                                                                                No Photo
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="mt-2">
                                                                        <button class="btn btn-sm btn-outline-primary" onclick="addchildphoto(<?php echo $v['id']; ?>, <?php echo $v['father_id']; ?>)">
                                                                            <i class="bi bi-upload"></i> Upload
                                                                        </button>
                                                                        <button class="btn btn-sm btn-outline-danger" onclick="cimagedelete(<?php echo $v['id']; ?>, '<?php echo $v['c_image']; ?>')">
                                                                            <i class="bi bi-trash"></i> Delete
                                                                        </button>
                                                                    </div>
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
                                                                                <?php if ($v['fam_id'] == "0"): ?>
                                                                                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="linkfamily(<?php echo $v['id']; ?>)">
                                                                                        <i class="bi bi-link"></i> Link Family
                                                                                    </button>
                                                                                    <?php if ($v['c_marital_status'] == "no" || $v['c_marital_status'] == "No"): ?>
                                                                                        <a href="addmember.php?child_id=<?php echo $v['id']; ?>" class="btn btn-sm btn-outline-success ms-1">
                                                                                            <i class="bi bi-plus"></i> Add Family
                                                                                        </a>
                                                                                    <?php endif; ?>
                                                                                <?php else: ?>
                                                                                    <a href="viewmember.php?id=<?php echo $v['fam_id']; ?>" class="btn btn-sm btn-outline-info ms-2">
                                                                                        <i class="bi bi-eye"></i> View Family
                                                                                    </a>
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
                                            <div class="data-item">
                                                <div class="data-label">Member ID:</div>
                                                <div class="data-value member-id"><?php echo htmlspecialchars($row['member_id'] ?? '-'); ?></div>
                                            </div>
                                            <div class="data-item">
                                                <?php if (empty($row['member_id']) || $row['member_id'] == '0'): ?>
                                                    <form method="POST" class="d-flex justify-content-end">
                                                        <input type="hidden" name="action" value="generate_id">
                                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="bi bi-plus-circle"></i> Generate ID
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn-outline-primary" onclick="printid()">
                                                            <i class="bi bi-printer"></i> Print ID Card
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
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
                                                <div class="data-value"><?php echo htmlspecialchars($row['current_address'] ?? '-'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Family Tree Section -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="bi bi-diagram-3"></i> Family Tree</h6>
                                        </div>
                                        <div class="card-body" style="height: 300px;">
                                            <div class="text-center text-muted py-5">
                                                <i class="bi bi-diagram-3" style="font-size: 48px;"></i>
                                                <p class="mt-2">Family tree visualization coming soon</p>
                                            </div>
                                        </div>
                                    </div>

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
                                            <div class="data-item">
                                                <div class="data-label">Admin Notes:</div>
                                                <div class="data-value"><?php echo htmlspecialchars($row['admin_notes'] ?? '-'); ?></div>
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
<div class="container-fluid mt-4">
    <div class="row">
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
                    
                    <button class="btn btn-success me-2" onclick="addson(); console.log('Add Son button clicked')">
                        <i class="bi bi-person-plus"></i> Add Son
                    </button>
                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addDaughterModal">
                        <i class="bi bi-person-plus"></i> Add Daughter
                    </button>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addHoroscopeModal">
                        <i class="bi bi-stars"></i> Add Horoscope
                    </button>
                </div>
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
            <div class="modal-body">
                <?php
                $upload_dir = "../images/member/";
                $msg = '';
                
                if (isset($_FILES["h_image"])) {
                    if ($_FILES["h_image"]["error"] > 0) {
                        $msg = "Error: " . $_FILES["h_image"]["error"];
                    } else {
                        if ($_FILES["h_image"]["type"] != 'image/jpeg') {
                            $msg = "Image should be in JPEG format";
                        } else {
                            $s = $id . "_husband.jpg";
                            
                            if (file_exists("../images/member/" . $s)) {
                                unlink("../images/member/" . $s);
                            }
                            
                            move_uploaded_file($_FILES["h_image"]["tmp_name"], "../images/member/" . $s);
                            chmod("../images/member/" . $s, 0664);

                            $sql = "UPDATE `$tbl_family` SET `image`=? WHERE `id`=?";
                            $stmt = mysqli_prepare($con, $sql);
                            mysqli_stmt_bind_param($stmt, "si", $s, $id);
                            
                            if (mysqli_stmt_execute($stmt)) {
                                $msg = "Successfully uploaded!";
                            } else {
                                $msg = "Error: " . mysqli_error($con);
                            }
                            mysqli_stmt_close($stmt);
                        }
                    }
                }
                ?>
                
                <?php if ($msg): ?>
                    <div class="alert alert-<?php echo strpos($msg, 'Error') !== false ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
                        <i class="bi bi-<?php echo strpos($msg, 'Error') !== false ? 'exclamation-triangle' : 'check-circle'; ?>"></i> <?php echo $msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (!(is_dir($upload_dir) && is_writable($upload_dir))): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> Upload directory is not writable, or does not exist.
                    </div>
                <?php else: ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="h_image" class="form-label">Select husband image:</label>
                            <input type="file" class="form-control" name="h_image" id="h_image" accept="image/jpeg" required>
                            <div class="form-text">Only JPEG images are allowed.</div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Upload
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

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
            <div class="modal-body">
                <p>Are you sure you want to delete the husband's photo?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="himagedelete.php" method="POST" style="display: inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="h_image" value="<?php echo $row['image']; ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete Photo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

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
            <div class="modal-body">
                <?php
                $upload_dir = "../images/member/";
                $msg = '';
                
                if (isset($_FILES["w_image"])) {
                    if ($_FILES["w_image"]["error"] > 0) {
                        $msg = "Error: " . $_FILES["w_image"]["error"];
                    } else {
                        if ($_FILES["w_image"]["type"] != 'image/jpeg') {
                            $msg = "Image should be in JPEG format";
                        } else {
                            $s = $id . "_wife.jpg";
                            
                            if (file_exists("../images/member/" . $s)) {
                                unlink("../images/member/" . $s);
                            }
                            
                            move_uploaded_file($_FILES["w_image"]["tmp_name"], "../images/member/" . $s);
                            chmod("../images/member/" . $s, 0664);

                            $sql = "UPDATE `$tbl_family` SET `w_image`=? WHERE `id`=?";
                            $stmt = mysqli_prepare($con, $sql);
                            mysqli_stmt_bind_param($stmt, "si", $s, $id);
                            
                            if (mysqli_stmt_execute($stmt)) {
                                $msg = "Successfully uploaded!";
                            } else {
                                $msg = "Error: " . mysqli_error($con);
                            }
                            mysqli_stmt_close($stmt);
                        }
                    }
                }
                ?>
                
                <?php if ($msg): ?>
                    <div class="alert alert-<?php echo strpos($msg, 'Error') !== false ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
                        <i class="bi bi-<?php echo strpos($msg, 'Error') !== false ? 'exclamation-triangle' : 'check-circle'; ?>"></i> <?php echo $msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (!(is_dir($upload_dir) && is_writable($upload_dir))): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> Upload directory is not writable, or does not exist.
                    </div>
                <?php else: ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="w_image" class="form-label">Select wife image:</label>
                            <input type="file" class="form-control" name="w_image" id="w_image" accept="image/jpeg" required>
                            <div class="form-text">Only JPEG images are allowed.</div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Upload
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

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
            <div class="modal-body">
                <p>Are you sure you want to delete the wife's photo?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="wimagedelete.php" method="POST" style="display: inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="w_image" value="<?php echo $row['w_image']; ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete Photo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

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
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="c_name" placeholder="Son's Name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile No</label>
                            <input type="tel" class="form-control" name="c_mobile_no" placeholder="Mobile No" value="-">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="c_dob" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="c_email" placeholder="Email" value="-">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Education</label>
                            <select class="form-select" name="c_qualification">
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
                            <input type="text" class="form-control" name="c_education_details" placeholder="Education Details" value="-">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Occupation</label>
                            <select class="form-select" name="c_occupation">
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
                            <input type="text" class="form-control" name="c_occupation_details" placeholder="Occupation Details" value="-">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Blood Group</label>
                            <select class="form-select" name="c_blood_group">
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
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="c_name" placeholder="Daughter's Name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile No</label>
                            <input type="tel" class="form-control" name="c_mobile_no" placeholder="Mobile No" value="-">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="c_dob" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="c_email" placeholder="Email" value="-">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Education</label>
                            <select class="form-select" name="c_qualification">
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
                            <input type="text" class="form-control" name="c_education_details" placeholder="Education Details" value="-">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Occupation</label>
                            <select class="form-select" name="c_occupation">
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
                            <input type="text" class="form-control" name="c_occupation_details" placeholder="Occupation Details" value="-">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Blood Group</label>
                            <select class="form-select" name="c_blood_group">
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
            <div class="modal-body">
                <?php
                $upload_dir = "../images/member/";
                $msg = '';
                
                if (isset($_FILES["app_front"]) && isset($_FILES["app_back"])) {
                    if ($_FILES["app_front"]["error"] > 0 || $_FILES["app_back"]["error"] > 0) {
                        $msg = "Error: " . $_FILES["app_front"]["error"] . " " . $_FILES["app_back"]["error"];
                    } else {
                        if ($_FILES["app_front"]["type"] != 'image/jpeg' || $_FILES["app_back"]["type"] != 'image/jpeg') {
                            $msg = "Images should be in JPEG format";
                        } else {
                            $s = $id . "_appfront.jpg";
                            $s1 = $id . "_appback.jpg";
                            
                            if (file_exists("../images/member/" . $s)) {
                                unlink("../images/member/" . $s);
                            }
                            if (file_exists("../images/member/" . $s1)) {
                                unlink("../images/member/" . $s1);
                            }
                            
                            move_uploaded_file($_FILES["app_front"]["tmp_name"], "../images/member/" . $s);
                            move_uploaded_file($_FILES["app_back"]["tmp_name"], "../images/member/" . $s1);
                            chmod("../images/member/" . $s, 0664);
                            chmod("../images/member/" . $s1, 0664);

                            $sql = "UPDATE `$tbl_family` SET `app_front`=?, `app_back`=? WHERE `id`=?";
                            $stmt = mysqli_prepare($con, $sql);
                            mysqli_stmt_bind_param($stmt, "ssi", $s, $s1, $id);
                            
                            if (mysqli_stmt_execute($stmt)) {
                                $msg = "Application uploaded successfully!";
                            } else {
                                $msg = "Error: " . mysqli_error($con);
                            }
                            mysqli_stmt_close($stmt);
                        }
                    }
                }
                ?>
                
                <?php if ($msg): ?>
                    <div class="alert alert-<?php echo strpos($msg, 'Error') !== false ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
                        <i class="bi bi-<?php echo strpos($msg, 'Error') !== false ? 'exclamation-triangle' : 'check-circle'; ?>"></i> <?php echo $msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (!(is_dir($upload_dir) && is_writable($upload_dir))): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> Upload directory is not writable, or does not exist.
                    </div>
                <?php else: ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="app_front" class="form-label">Front page of application form:</label>
                            <input type="file" class="form-control" name="app_front" id="app_front" accept="image/jpeg" required>
                            <div class="form-text">Only JPEG images are allowed.</div>
                        </div>
                        <div class="mb-3">
                            <label for="app_back" class="form-label">Back page of application form:</label>
                            <input type="file" class="form-control" name="app_back" id="app_back" accept="image/jpeg" required>
                            <div class="form-text">Only JPEG images are allowed.</div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Upload Application
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

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
                                <input type="tel" class="form-control" name="c_mobile_no" id="editChildMobile" placeholder="Mobile No" value="-">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="c_dob" id="editChildDob" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="c_email" id="editChildEmail" placeholder="Email" value="-">
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
                document.getElementById('editChildMobile').value = data.child.c_mobile_no || '-';
                document.getElementById('editChildDob').value = data.child.c_dob;
                document.getElementById('editChildEmail').value = data.child.c_email || '-';
                document.getElementById('editChildEducation').value = data.child.c_qualification || '-';
                document.getElementById('editChildEducationDetails').value = data.child.c_education_details || '-';
                document.getElementById('editChildOccupation').value = data.child.c_occupation || '-';
                document.getElementById('editChildOccupationDetails').value = data.child.c_occupation_details || '-';
                document.getElementById('editChildBloodGroup').value = data.child.c_blood_group || '-';
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
    url = "applicationview.php?id=" + id;
    title = "popup";
    var newWindow = window.open(url, title, 'scrollbars=yes, width=800, height=600');
}

function printid() {
    url = "printid.php?id=<?php echo $id; ?>";
    title = "popup";
    var newWindow = window.open(url, title, 'scrollbars=yes, width=500, height=400');
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

<?php include('../includes/footer.php'); ?>