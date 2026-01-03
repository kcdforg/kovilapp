<?php
include('../init.php');

// Check if user is logged in
check_login();

// Enable mysqli exception mode

$success_message = '';
$error_message = '';
$child_id = $_GET['child_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission
    $member_data = array(
        'name' => $_POST['name'] ?? '',
        'father_name' => $_POST['father_name'] ?? '',
        'mother_name' => $_POST['mother_name'] ?? '',
        'dob' => $_POST['dob'] ?? '',
        'blood_group' => $_POST['blood_group'] ?? '',
        'qualification' => $_POST['qualification'] ?? '',
        'education_details' => $_POST['education_details'] ?? '',
        'occupation' => $_POST['occupation'] ?? '',
        'occupation_details' => $_POST['occupation_details'] ?? '',
        'email' => $_POST['email'] ?? '',
        'mobile_no' => $_POST['mobile_no'] ?? '',
        'permanent_address' => $_POST['permanent_address'] ?? '',
        'current_address' => $_POST['current_address'] ?? '',
        'village' => $_POST['village'] ?? '',
        'taluk' => $_POST['taluk'] ?? '',
        'district' => $_POST['district'] ?? '',
        'state' => $_POST['state'] ?? '',
        'country' => $_POST['country'] ?? '',
        'pincode' => $_POST['pincode'] ?? '',
        'c_village' => $_POST['c_village'] ?? '',
        'c_taluk' => $_POST['c_taluk'] ?? '',
        'c_district' => $_POST['c_district'] ?? '',
        'c_state' => $_POST['c_state'] ?? '',
        'c_country' => $_POST['c_country'] ?? '',
        'c_pincode' => $_POST['c_pincode'] ?? '',
        'kattalai' => $_POST['kattalai'] ?? '',
        'k_village' => $_POST['k_village'] ?? '',
        'pudavai' => $_POST['pudavai'] ?? '',
        'remarks' => $_POST['remarks'] ?? '',
        'same_as_permanent' => isset($_POST['same_as_permanent']) ? 1 : 0,
        'created_by' => $_SESSION['user_id'] ?? 1,
        'created_date' => date('Y-m-d H:i:s')
    );
    
    // Add wife details if provided
    if (!empty($_POST['w_name'])) {
        $member_data['w_name'] = $_POST['w_name'];
        $member_data['w_dob'] = $_POST['w_dob'] ?? '';
        $member_data['w_blood_group'] = $_POST['w_blood_group'] ?? '';
        $member_data['w_qualification'] = $_POST['w_qualification'] ?? '';
        $member_data['w_education_details'] = $_POST['w_education_details'] ?? '';
        $member_data['w_occupation'] = $_POST['w_occupation'] ?? '';
        $member_data['w_occupation_details'] = $_POST['w_occupation_details'] ?? '';
        $member_data['w_email'] = $_POST['w_email'] ?? '';
        $member_data['w_kootam'] = $_POST['w_kootam'] ?? '';
        $member_data['w_temple'] = $_POST['w_temple'] ?? '';
    }
    
    // Validate required groups before adding member
    $group_types = get_group_types(true);
    $group_validation_error = false;
    
    foreach ($group_types as $type) {
        if ($type['is_required'] == 1) {
            $group_field = 'group_' . $type['id'];
            if (empty($_POST[$group_field])) {
                $error_message = 'Please select ' . htmlspecialchars($type['name']) . ' (required).';
                $group_validation_error = true;
                break;
            }
        }
    }
    
    if (!$group_validation_error) {
        $result = add_member($member_data);
        
        if ($result) {
            $new_family_id = $result; // add_member returns the new family ID
            
            // Assign member to groups
            foreach ($group_types as $type) {
                $group_field = 'group_' . $type['id'];
                if (!empty($_POST[$group_field])) {
                    assign_member_to_group($new_family_id, $type['id'], $_POST[$group_field]);
                }
            }
            
            // If this family is being added for a child, link them
            if (!empty($_POST['child_id'])) {
                $child_id = $_POST['child_id'];
                
                // Get parent family ID from child record
                global $con, $tbl_child, $tbl_family;
                $sql = "SELECT father_id FROM $tbl_child WHERE id = ?";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, 'i', $child_id);
                mysqli_stmt_execute($stmt);
                $result_child = mysqli_stmt_get_result($stmt);
                $child_data = mysqli_fetch_assoc($result_child);
                mysqli_stmt_close($stmt);
                
                if ($child_data) {
                    $parent_family_id = $child_data['father_id'];
                    
                    // Link child to new family
                    $sql = "UPDATE $tbl_child SET fam_id = ? WHERE id = ?";
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_bind_param($stmt, 'ii', $new_family_id, $child_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    
                    // Set parent_id for new family
                    $sql = "UPDATE $tbl_family SET parent_id = ? WHERE id = ?";
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_bind_param($stmt, 'ii', $parent_family_id, $new_family_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    
                    // Regenerate family tree
                    regenerateFamilyTree($parent_family_id);
                }
            }
            
            $success_message = 'Member added successfully!';
            // Redirect to member list after 2 seconds
            header("refresh:2;url=memberlist.php");
        } else {
            $error_message = 'Error adding member. Please try again.';
        }
    }
}

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

/* Remove valid icons from form validation */
.was-validated .form-control:valid,
.was-validated .form-select:valid {
    border-color: #ced4da;
    background-image: none;
    padding-right: 0.75rem;
}
</style>

<div class="row fluid-with-margins">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Add New Member</h1>
            <div class="d-flex gap-2">
                <a href="memberlist.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<?php if ($success_message): ?>
<div class="row fluid-with-margins">
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($error_message): ?>
<div class="row fluid-with-margins">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row fluid-with-margins">
    <div class="col-12">
<form method="POST" class="needs-validation" novalidate id="addMemberForm">
    <?php if ($child_id): ?>
        <input type="hidden" name="child_id" value="<?php echo htmlspecialchars($child_id); ?>">
    <?php endif; ?>
    <!-- Validation Errors Summary -->
    <div id="validationSummary" class="alert alert-danger d-none mb-4" role="alert">
        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Please correct the following errors:</h5>
        <ul id="validationErrorList" class="mb-0"></ul>
    </div>
    
    <!-- Husband Information -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-person-fill"></i> HUSBAND
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label for="name" class="col-sm-4 col-form-label">Name *</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Please enter the name.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="father_name" class="col-sm-4 col-form-label">Father's Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="father_name" name="father_name">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="mother_name" class="col-sm-4 col-form-label">Mother's Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mother_name" name="mother_name">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="qualification" class="col-sm-4 col-form-label">Education</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="qualification" name="qualification">
                                <option value="">Select Education</option>
                                <?php
                                $qualifications = get_labels_by_type('Education');
                                foreach ($qualifications as $qual) {
                                    echo "<option value='{$qual['id']}'>{$qual['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="education_details" class="col-sm-4 col-form-label">Education Details</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="education_details" name="education_details">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="email" class="col-sm-4 col-form-label">Email Address</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email">
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label for="mobile_no" class="col-sm-4 col-form-label">Mobile Number *</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="mobile_no" name="mobile_no" required>
                            <div class="invalid-feedback">Please enter a valid mobile number.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="dob" class="col-sm-4 col-form-label">Date of Birth</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="dob" name="dob">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="blood_group" class="col-sm-4 col-form-label">Blood Group</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="blood_group" name="blood_group">
                                <option value="">Select Blood Group</option>
                                <?php
                                $blood_groups = get_labels_by_type('blood_group');
                                foreach ($blood_groups as $bg) {
                                    echo "<option value='{$bg['id']}'>{$bg['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="occupation" class="col-sm-4 col-form-label">Occupation</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="occupation" name="occupation">
                                <option value="">Select Occupation</option>
                                <?php
                                $occupations = get_labels_by_type('occupation');
                                foreach ($occupations as $occ) {
                                    echo "<option value='{$occ['id']}'>{$occ['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="occupation_details" class="col-sm-4 col-form-label">Occupation Details</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="occupation_details" name="occupation_details">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wife Information -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-heart-fill"></i> WIFE
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label for="w_name" class="col-sm-4 col-form-label">Name *</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="w_name" name="w_name" required>
                            <div class="invalid-feedback">Please enter wife's name.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_dob" class="col-sm-4 col-form-label">Date of Birth</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="w_dob" name="w_dob">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_blood_group" class="col-sm-4 col-form-label">Blood Group</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="w_blood_group" name="w_blood_group">
                                <option value="">Select Blood Group</option>
                                <?php
                                foreach ($blood_groups as $bg) {
                                    echo "<option value='{$bg['id']}'>{$bg['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_qualification" class="col-sm-4 col-form-label">Education</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="w_qualification" name="w_qualification">
                                <option value="">Select Education</option>
                                <?php
                                foreach ($qualifications as $qual) {
                                    echo "<option value='{$qual['id']}'>{$qual['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_education_details" class="col-sm-4 col-form-label">Education Details</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="w_education_details" name="w_education_details">
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label for="w_occupation" class="col-sm-4 col-form-label">Occupation</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="w_occupation" name="w_occupation">
                                <option value="">Select Occupation</option>
                                <?php
                                foreach ($occupations as $occ) {
                                    echo "<option value='{$occ['id']}'>{$occ['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_occupation_details" class="col-sm-4 col-form-label">Occupation Details</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="w_occupation_details" name="w_occupation_details">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_email" class="col-sm-4 col-form-label">Email</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="w_email" name="w_email">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_kootam" class="col-sm-4 col-form-label">Kootam</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="w_kootam" name="w_kootam">
                                <option value="">Select Kootam</option>
                                <?php
                                $kootams = get_labels_by_type('kootam');
                                foreach ($kootams as $kootam) {
                                    echo "<option value='{$kootam['id']}'>{$kootam['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_temple" class="col-sm-4 col-form-label">Temple</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="w_temple" name="w_temple">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Information - Two Column Layout -->
    <div class="row mb-4">
        <!-- Permanent Address -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-house-fill"></i> PERMANENT ADDRESS
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="permanent_address" class="col-sm-4 col-form-label">Permanent Address</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="permanent_address" name="permanent_address" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <!-- Location Search Section -->
                    <div id="permanentLocationSearch" class="row mb-3">
                        <label for="permanentVillageSearch" class="col-sm-4 col-form-label">Search Village</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="permanentVillageSearch" style="width: 100%;">
                                <option value="">Search for a village...</option>
                            </select>
                            <small class="text-muted">Type village, taluk, or district name to search</small>
                        </div>
                    </div>
                    
                    <!-- Add New Location Link -->
                    <div id="permanentAddNewLink" class="row mb-3">
                        <div class="col-sm-8 offset-sm-4">
                            <a href="#" class="text-primary" onclick="openAddLocationModal('permanent'); return false;">
                                <i class="bi bi-plus-circle"></i> Add New Location
                            </a>
                        </div>
                    </div>
                    
                    <!-- Selected Location Display -->
                    <div id="permanentLocationDisplay" style="display: none;">
                        <div class="card bg-light mb-3">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-success">
                                        <i class="bi bi-check-circle-fill"></i> 
                                        <span id="permanentLocationSource">Selected Location</span>
                                    </h6>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="changeLocation('permanent')">
                                            <i class="bi bi-search"></i> Change
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editLocation('permanent')">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                    </div>
                                </div>
                                <div class="row g-2 small">
                                    <div class="col-12">
                                        <span class="fw-bold">Village:</span>
                                        <span id="permanentVillageDisplay" class="ms-2"></span>
                                    </div>
                                    <div class="col-12">
                                        <span class="fw-bold">Taluk:</span>
                                        <span id="permanentTalukDisplay" class="ms-2"></span>
                                    </div>
                                    <div class="col-12">
                                        <span class="fw-bold">District:</span>
                                        <span id="permanentDistrictDisplay" class="ms-2"></span>
                                    </div>
                                    <div class="col-12">
                                        <span class="fw-bold">State:</span>
                                        <span id="permanentStateDisplay" class="ms-2"></span>
                                    </div>
                                    <div class="col-12">
                                        <span class="fw-bold">Country:</span>
                                        <span id="permanentCountryDisplay" class="ms-2"></span>
                                    </div>
                                    <div class="col-12">
                                        <span class="fw-bold">Pincode:</span>
                                        <span id="permanentPincodeDisplay" class="ms-2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden fields for form submission -->
                    <input type="hidden" id="village" name="village">
                    <input type="hidden" id="taluk" name="taluk">
                    <input type="hidden" id="district" name="district">
                    <input type="hidden" id="state" name="state">
                    <input type="hidden" id="country" name="country">
                    <input type="hidden" id="pincode" name="pincode">
                </div>
            </div>
        </div>
        
        <!-- Current Address -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-geo-alt-fill"></i> CURRENT ADDRESS
                    </h5>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="sameAsPermanent" name="same_as_permanent" value="1" onchange="toggleCurrentAddress()">
                        <label class="form-check-label text-white" for="sameAsPermanent">
                            Same as Permanent
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="current_address" class="col-sm-4 col-form-label">Current Address</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="current_address" name="current_address" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <!-- Location Search Section -->
                    <div id="currentLocationSearch" class="row mb-3">
                        <label for="currentVillageSearch" class="col-sm-4 col-form-label">Search Village</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="currentVillageSearch" style="width: 100%;">
                                <option value="">Search for a village...</option>
                            </select>
                            <small class="text-muted">Type village, taluk, or district name to search</small>
                        </div>
                    </div>
                    
                    <!-- Add New Location Link -->
                    <div id="currentAddNewLink" class="row mb-3">
                        <div class="col-sm-8 offset-sm-4">
                            <a href="#" class="text-primary" onclick="openAddLocationModal('current'); return false;">
                                <i class="bi bi-plus-circle"></i> Add New Location
                            </a>
                        </div>
                    </div>
                    
                    <!-- Selected Location Display -->
                    <div id="currentLocationDisplay" style="display: none;">
                        <div class="card bg-light mb-3">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-success">
                                        <i class="bi bi-check-circle-fill"></i> 
                                        <span id="currentLocationSource">Selected Location</span>
                                    </h6>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="changeLocation('current')">
                                            <i class="bi bi-search"></i> Change
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editLocation('current')">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                    </div>
                                </div>
                                <div class="row g-2 small">
                                    <div class="col-12">
                                        <span class="fw-bold">Village:</span>
                                        <span id="currentVillageDisplay" class="ms-2"></span>
                                    </div>
                                    <div class="col-12">
                                        <span class="fw-bold">Taluk:</span>
                                        <span id="currentTalukDisplay" class="ms-2"></span>
                                    </div>
                                    <div class="col-12">
                                        <span class="fw-bold">District:</span>
                                        <span id="currentDistrictDisplay" class="ms-2"></span>
                                    </div>
                                    <div class="col-12">
                                        <span class="fw-bold">State:</span>
                                        <span id="currentStateDisplay" class="ms-2"></span>
                                    </div>
                                    <div class="col-12">
                                        <span class="fw-bold">Country:</span>
                                        <span id="currentCountryDisplay" class="ms-2"></span>
                                    </div>
                                    <div class="col-12">
                                        <span class="fw-bold">Pincode:</span>
                                        <span id="currentPincodeDisplay" class="ms-2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden fields for form submission -->
                    <input type="hidden" id="c_village" name="c_village">
                    <input type="hidden" id="c_taluk" name="c_taluk">
                    <input type="hidden" id="c_district" name="c_district">
                    <input type="hidden" id="c_state" name="c_state">
                    <input type="hidden" id="c_country" name="c_country">
                    <input type="hidden" id="c_pincode" name="c_pincode">
                </div>
            </div>
        </div>
    </div>

    <!-- Other Details -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-info-circle"></i> OTHER DETAILS
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label for="kattalai" class="col-sm-4 col-form-label">Kattalai</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="kattalai" name="kattalai">
                                <option value="">Select Kattalai</option>
                                <?php
                                $kattalais = get_labels_by_type('kattalai');
                                foreach ($kattalais as $katt) {
                                    echo "<option value='{$katt['id']}'>{$katt['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="k_village" class="col-sm-4 col-form-label">Kattalai Village</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="k_village" name="k_village">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="pudavai" class="col-sm-4 col-form-label">Pudavai</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pudavai" name="pudavai">
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label for="remarks" class="col-sm-4 col-form-label">Remarks</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="remarks" name="remarks" rows="6"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Group Assignments -->
    <?php 
    $group_types_form = get_group_types(true);
    if (!empty($group_types_form)) {
    ?>
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0">
                <i class="bi bi-diagram-3"></i> Group Assignments
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <?php 
                    $count = 0;
                    foreach ($group_types_form as $type) {
                        $groups = get_groups($type['id'], true);
                        if (!empty($groups)) {
                            $count++;
                            if ($count % 2 == 1) { // Odd items in left column
                                $is_required = $type['is_required'] == 1;
                    ?>
                    <div class="row mb-3">
                        <label for="group_<?php echo $type['id']; ?>" class="col-sm-4 col-form-label">
                            <?php echo htmlspecialchars($type['name']); ?>
                            <?php if ($is_required): ?>
                            <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <div class="col-sm-8">
                            <select class="form-select" id="group_<?php echo $type['id']; ?>" name="group_<?php echo $type['id']; ?>"<?php echo $is_required ? ' required' : ''; ?>>
                                <option value="">Select <?php echo htmlspecialchars($type['name']); ?></option>
                                <?php foreach ($groups as $group): ?>
                                <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($type['description'])): ?>
                            <small class="text-muted"><?php echo htmlspecialchars($type['description']); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php 
                            }
                        }
                    }
                    ?>
                </div>
                
                <!-- Right Column -->
                <div class="col-md-6">
                    <?php 
                    $count = 0;
                    foreach ($group_types_form as $type) {
                        $groups = get_groups($type['id'], true);
                        if (!empty($groups)) {
                            $count++;
                            if ($count % 2 == 0) { // Even items in right column
                                $is_required = $type['is_required'] == 1;
                    ?>
                    <div class="row mb-3">
                        <label for="group_<?php echo $type['id']; ?>" class="col-sm-4 col-form-label">
                            <?php echo htmlspecialchars($type['name']); ?>
                            <?php if ($is_required): ?>
                            <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <div class="col-sm-8">
                            <select class="form-select" id="group_<?php echo $type['id']; ?>" name="group_<?php echo $type['id']; ?>"<?php echo $is_required ? ' required' : ''; ?>>
                                <option value="">Select <?php echo htmlspecialchars($type['name']); ?></option>
                                <?php foreach ($groups as $group): ?>
                                <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($type['description'])): ?>
                            <small class="text-muted"><?php echo htmlspecialchars($type['description']); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php 
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <!-- Form Actions -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="memberlist.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Add Member
                </button>
            </div>
        </div>
    </div>
</form>
    </div>
</div>

<script>
// Form validation with error summary
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var form = document.getElementById('addMemberForm');
        var validationSummary = document.getElementById('validationSummary');
        var errorList = document.getElementById('validationErrorList');
        
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                
                // Collect all invalid fields
                var invalidFields = form.querySelectorAll(':invalid');
                var errors = [];
                
                invalidFields.forEach(function(field) {
                    var label = form.querySelector('label[for="' + field.id + '"]');
                    var fieldName = label ? label.textContent.replace('*', '').trim() : field.name;
                    
                    if (field.validity.valueMissing) {
                        errors.push(fieldName + ' is required');
                    } else if (field.validity.typeMismatch) {
                        errors.push(fieldName + ' format is invalid');
                    } else if (field.validity.patternMismatch) {
                        errors.push(fieldName + ' format is invalid');
                    }
                });
                
                // Display error summary
                if (errors.length > 0) {
                    errorList.innerHTML = '';
                    errors.forEach(function(error) {
                        var li = document.createElement('li');
                        li.textContent = error;
                        errorList.appendChild(li);
                    });
                    validationSummary.classList.remove('d-none');
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } else {
                validationSummary.classList.add('d-none');
            }
            
            form.classList.add('was-validated');
        }, false);
    }, false);
})();

// Toggle current address fields based on checkbox
function toggleCurrentAddress() {
    const checkbox = document.getElementById('sameAsPermanent');
    const currentAddressFields = [
        'current_address', 'c_village', 'c_taluk', 'c_district', 
        'c_state', 'c_country', 'c_pincode'
    ];
    
    if (checkbox.checked) {
        // Copy permanent address to current address
        document.getElementById('current_address').value = document.getElementById('permanent_address').value;
        document.getElementById('c_village').value = document.getElementById('village').value;
        document.getElementById('c_taluk').value = document.getElementById('taluk').value;
        document.getElementById('c_district').value = document.getElementById('district').value;
        document.getElementById('c_state').value = document.getElementById('state').value;
        document.getElementById('c_country').value = document.getElementById('country').value;
        document.getElementById('c_pincode').value = document.getElementById('pincode').value;
        
        // Disable current address fields
        currentAddressFields.forEach(fieldId => {
            document.getElementById(fieldId).disabled = true;
            document.getElementById(fieldId).style.backgroundColor = '#e9ecef';
        });
    } else {
        // Enable current address fields
        currentAddressFields.forEach(fieldId => {
            document.getElementById(fieldId).disabled = false;
            document.getElementById(fieldId).style.backgroundColor = '';
        });
    }
}

// Initialize Select2
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5'
    });
    
    // Initialize location search dropdowns
    initializeLocationSearch('permanent');
    initializeLocationSearch('current');
});

// Location Management Functions
let currentAddressType = ''; // 'permanent' or 'current'
let currentEditMode = false; // true for edit, false for add new

function initializeLocationSearch(addressType) {
    const searchId = addressType + 'VillageSearch';
    
    $('#' + searchId).select2({
        theme: 'bootstrap-5',
        placeholder: 'Search for a village...',
        allowClear: true,
        ajax: {
            url: 'get_locations.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        minimumInputLength: 2
    }).on('select2:select', function (e) {
        const data = e.params.data;
        selectLocation(addressType, data);
    });
}

function selectLocation(addressType, locationData) {
    const prefix = addressType === 'permanent' ? '' : 'c_';
    
    // Set hidden fields
    document.getElementById(prefix + 'village').value = locationData.village || '';
    document.getElementById(prefix + 'taluk').value = locationData.taluk || '';
    document.getElementById(prefix + 'district').value = locationData.district || '';
    document.getElementById(prefix + 'state').value = locationData.state || '';
    document.getElementById(prefix + 'country').value = locationData.country || '';
    document.getElementById(prefix + 'pincode').value = locationData.pincode || '';
    
    // Update display
    document.getElementById(addressType + 'VillageDisplay').textContent = locationData.village || '-';
    document.getElementById(addressType + 'TalukDisplay').textContent = locationData.taluk || '-';
    document.getElementById(addressType + 'DistrictDisplay').textContent = locationData.district || '-';
    document.getElementById(addressType + 'StateDisplay').textContent = locationData.state || '-';
    document.getElementById(addressType + 'CountryDisplay').textContent = locationData.country || '-';
    document.getElementById(addressType + 'PincodeDisplay').textContent = locationData.pincode || '-';
    
    // Update source indicator
    document.getElementById(addressType + 'LocationSource').innerHTML = 
        '<i class="bi bi-check-circle-fill text-success"></i> Existing Location';
    
    // Show display, hide search
    document.getElementById(addressType + 'LocationSearch').style.display = 'none';
    document.getElementById(addressType + 'AddNewLink').style.display = 'none';
    document.getElementById(addressType + 'LocationDisplay').style.display = 'block';
}

function changeLocation(addressType) {
    // Clear selection
    $('#' + addressType + 'VillageSearch').val(null).trigger('change');
    
    // Show search, hide display
    document.getElementById(addressType + 'LocationSearch').style.display = 'block';
    document.getElementById(addressType + 'AddNewLink').style.display = 'block';
    document.getElementById(addressType + 'LocationDisplay').style.display = 'none';
    
    // Clear hidden fields
    const prefix = addressType === 'permanent' ? '' : 'c_';
    document.getElementById(prefix + 'village').value = '';
    document.getElementById(prefix + 'taluk').value = '';
    document.getElementById(prefix + 'district').value = '';
    document.getElementById(prefix + 'state').value = '';
    document.getElementById(prefix + 'country').value = '';
    document.getElementById(prefix + 'pincode').value = '';
}

function openAddLocationModal(addressType) {
    currentAddressType = addressType;
    currentEditMode = false;
    
    // Reset form
    document.getElementById('locationModalForm').reset();
    document.getElementById('locationModalTitle').textContent = 'Add New Location';
    
    // Show modal
    new bootstrap.Modal(document.getElementById('locationModal')).show();
}

function editLocation(addressType) {
    currentAddressType = addressType;
    currentEditMode = true;
    
    const prefix = addressType === 'permanent' ? '' : 'c_';
    
    // Populate form with current values
    document.getElementById('modalVillage').value = document.getElementById(prefix + 'village').value;
    document.getElementById('modalTaluk').value = document.getElementById(prefix + 'taluk').value;
    document.getElementById('modalDistrict').value = document.getElementById(prefix + 'district').value;
    document.getElementById('modalState').value = document.getElementById(prefix + 'state').value;
    document.getElementById('modalCountry').value = document.getElementById(prefix + 'country').value;
    document.getElementById('modalPincode').value = document.getElementById(prefix + 'pincode').value;
    
    document.getElementById('locationModalTitle').textContent = 'Edit Location Details';
    
    // Show modal
    new bootstrap.Modal(document.getElementById('locationModal')).show();
}

function saveLocationFromModal() {
    const village = document.getElementById('modalVillage').value.trim();
    const taluk = document.getElementById('modalTaluk').value.trim();
    const district = document.getElementById('modalDistrict').value.trim();
    const state = document.getElementById('modalState').value.trim();
    const country = document.getElementById('modalCountry').value.trim();
    const pincode = document.getElementById('modalPincode').value.trim();
    
    // Validate required fields
    if (!village) {
        alert('Village is required');
        return;
    }
    
    const locationData = {
        village: village,
        taluk: taluk,
        district: district,
        state: state,
        country: country,
        pincode: pincode
    };
    
    // Apply to form
    const prefix = currentAddressType === 'permanent' ? '' : 'c_';
    document.getElementById(prefix + 'village').value = village;
    document.getElementById(prefix + 'taluk').value = taluk;
    document.getElementById(prefix + 'district').value = district;
    document.getElementById(prefix + 'state').value = state;
    document.getElementById(prefix + 'country').value = country;
    document.getElementById(prefix + 'pincode').value = pincode;
    
    // Update display
    document.getElementById(currentAddressType + 'VillageDisplay').textContent = village || '-';
    document.getElementById(currentAddressType + 'TalukDisplay').textContent = taluk || '-';
    document.getElementById(currentAddressType + 'DistrictDisplay').textContent = district || '-';
    document.getElementById(currentAddressType + 'StateDisplay').textContent = state || '-';
    document.getElementById(currentAddressType + 'CountryDisplay').textContent = country || '-';
    document.getElementById(currentAddressType + 'PincodeDisplay').textContent = pincode || '-';
    
    // Update source indicator based on mode
    if (currentEditMode) {
        document.getElementById(currentAddressType + 'LocationSource').innerHTML = 
            '<i class="bi bi-pencil text-warning"></i> Modified Location';
    } else {
        document.getElementById(currentAddressType + 'LocationSource').innerHTML = 
            '<i class="bi bi-plus-circle text-primary"></i> New Location';
    }
    
    // Show display, hide search
    document.getElementById(currentAddressType + 'LocationSearch').style.display = 'none';
    document.getElementById(currentAddressType + 'AddNewLink').style.display = 'none';
    document.getElementById(currentAddressType + 'LocationDisplay').style.display = 'block';
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('locationModal')).hide();
}

// Update toggleCurrentAddress function to work with new location system
function toggleCurrentAddress() {
    const checkbox = document.getElementById('sameAsPermanent');
    const currentAddressFields = [
        'current_address', 'c_village', 'c_taluk', 'c_district', 
        'c_state', 'c_country', 'c_pincode'
    ];
    
    if (checkbox.checked) {
        // Copy permanent address to current address
        document.getElementById('current_address').value = document.getElementById('permanent_address').value;
        
        // Copy location values
        document.getElementById('c_village').value = document.getElementById('village').value;
        document.getElementById('c_taluk').value = document.getElementById('taluk').value;
        document.getElementById('c_district').value = document.getElementById('district').value;
        document.getElementById('c_state').value = document.getElementById('state').value;
        document.getElementById('c_country').value = document.getElementById('country').value;
        document.getElementById('c_pincode').value = document.getElementById('pincode').value;
        
        // Copy display values if permanent location is selected
        if (document.getElementById('permanentLocationDisplay').style.display === 'block') {
            document.getElementById('currentVillageDisplay').textContent = document.getElementById('permanentVillageDisplay').textContent;
            document.getElementById('currentTalukDisplay').textContent = document.getElementById('permanentTalukDisplay').textContent;
            document.getElementById('currentDistrictDisplay').textContent = document.getElementById('permanentDistrictDisplay').textContent;
            document.getElementById('currentStateDisplay').textContent = document.getElementById('permanentStateDisplay').textContent;
            document.getElementById('currentCountryDisplay').textContent = document.getElementById('permanentCountryDisplay').textContent;
            document.getElementById('currentPincodeDisplay').textContent = document.getElementById('permanentPincodeDisplay').textContent;
            
            // Show current location display
            document.getElementById('currentLocationSearch').style.display = 'none';
            document.getElementById('currentAddNewLink').style.display = 'none';
            document.getElementById('currentLocationDisplay').style.display = 'block';
            document.getElementById('currentLocationSource').innerHTML = 
                '<i class="bi bi-check-circle-fill text-success"></i> Same as Permanent';
            
            // Hide Change/Edit buttons for current address
            const currentActionButtons = document.querySelector('#currentLocationDisplay .d-flex > div:last-child');
            if (currentActionButtons) {
                currentActionButtons.style.display = 'none';
            }
        }
        
        // Disable current address fields
        document.getElementById('current_address').disabled = true;
        document.getElementById('current_address').style.backgroundColor = '#e9ecef';
        
        // Disable current location controls
        $('#currentVillageSearch').prop('disabled', true);
        document.getElementById('currentLocationSearch').style.display = 'none';
        document.getElementById('currentAddNewLink').style.display = 'none';
        
    } else {
        // Clear current address data
        document.getElementById('current_address').value = '';
        
        // Clear location values
        document.getElementById('c_village').value = '';
        document.getElementById('c_taluk').value = '';
        document.getElementById('c_district').value = '';
        document.getElementById('c_state').value = '';
        document.getElementById('c_country').value = '';
        document.getElementById('c_pincode').value = '';
        
        // Enable current address fields
        document.getElementById('current_address').disabled = false;
        document.getElementById('current_address').style.backgroundColor = '';
        
        // Enable current location controls
        $('#currentVillageSearch').prop('disabled', false);
        
        // Show Change/Edit buttons for current address
        const currentActionButtons = document.querySelector('#currentLocationDisplay .d-flex > div:last-child');
        if (currentActionButtons) {
            currentActionButtons.style.display = 'block';
        }
        
        // Clear and hide current location display, show search
        document.getElementById('currentLocationSearch').style.display = 'block';
        document.getElementById('currentAddNewLink').style.display = 'block';
        document.getElementById('currentLocationDisplay').style.display = 'none';
    }
}
</script>

<!-- Add/Edit Location Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="locationModalTitle">Add New Location</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="locationModalForm">
                    <div class="row mb-3">
                        <label for="modalVillage" class="col-sm-4 col-form-label">Village <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="modalVillage" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="modalTaluk" class="col-sm-4 col-form-label">Taluk</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="modalTaluk">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="modalDistrict" class="col-sm-4 col-form-label">District</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="modalDistrict">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="modalState" class="col-sm-4 col-form-label">State</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="modalState">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="modalCountry" class="col-sm-4 col-form-label">Country</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="modalCountry" value="India">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="modalPincode" class="col-sm-4 col-form-label">Pincode</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="modalPincode">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveLocationFromModal()">
                    <i class="bi bi-check-circle"></i> Save Location
                </button>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>