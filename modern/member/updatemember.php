<?php
include('../init.php');

// Check if user is logged in
check_login();

$id = $_GET['id'] ?? 0;
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission
    $member_data = array(
        'id' => $id,
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
        'ic' => $_POST['ic'] ?? '',
        'same_as_permanent' => isset($_POST['same_as_permanent']) ? 1 : 0,
        'updated_date' => date('Y-m-d H:i:s')
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
    
    $result = update_family($id, $member_data);
    
    if ($result) {
        $success_message = 'Member updated successfully!';
        // Redirect to member view after 2 seconds
        header("refresh:2;url=viewmember.php?id=$id");
    } else {
        $error_message = 'Error updating member. Please try again.';
    }
}

$row = get_member($id);

// Debug: Check if member data is loaded
if (!$row) {
    $error_message = "Member not found with ID: $id";
    $row = array(); // Initialize empty array to prevent undefined index errors
}

// Ensure all expected fields exist with default values
$row = array_merge([
    'name' => '', 'father_name' => '', 'mother_name' => '', 'dob' => '',
    'blood_group' => '', 'qualification' => '', 'education_details' => '',
    'occupation' => '', 'occupation_details' => '', 'email' => '', 'mobile_no' => '',
    'permanent_address' => '', 'current_address' => '', 'village' => '', 'taluk' => '',
    'district' => '', 'state' => '', 'country' => '', 'pincode' => '',
    'c_village' => '', 'c_taluk' => '', 'c_district' => '', 'c_state' => '',
    'c_country' => '', 'c_pincode' => '', 'kattalai' => '', 'k_village' => '',
    'pudavai' => '', 'ic' => '', 'remarks' => '', 'same_as_permanent' => 0,
    'w_name' => '', 'w_dob' => '', 'w_blood_group' => '', 'w_qualification' => '',
    'w_education_details' => '', 'w_occupation' => '', 'w_occupation_details' => '',
    'w_email' => '', 'w_kootam' => '', 'w_temple' => ''
], $row ?: []);

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

<div class="row fluid-with-margins">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Update Member: <?php echo htmlspecialchars($row['name'] ?? 'Unknown'); ?></h1>
            <div class="d-flex gap-2">
                <a href="viewmember.php?id=<?php echo $id; ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to View
                </a>
                <button type="submit" form="updateMemberForm" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Member
                </button>
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
<form method="POST" class="needs-validation" novalidate id="updateMemberForm">
    <!-- Personal Information -->
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
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($row['name'] ?? ''); ?>" required>
                            <div class="invalid-feedback">Please enter the name.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="father_name" class="col-sm-4 col-form-label">Father's Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="father_name" name="father_name" 
                                   value="<?php echo htmlspecialchars($row['father_name'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="mother_name" class="col-sm-4 col-form-label">Mother's Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mother_name" name="mother_name" 
                                   value="<?php echo htmlspecialchars($row['mother_name'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="qualification" class="col-sm-4 col-form-label">Education</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="qualification" name="qualification">
                                <option value="">Select Education</option>
                                <?php
                                $qualifications = get_labels_by_type('qualification');
                                foreach ($qualifications as $qual) {
                                    $selected = (($row['qualification'] ?? '') == $qual['id']) ? 'selected' : '';
                                    echo "<option value='{$qual['id']}' $selected>{$qual['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="education_details" class="col-sm-4 col-form-label">Education Details</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="education_details" name="education_details" 
                                   value="<?php echo htmlspecialchars($row['education_details'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="email" class="col-sm-4 col-form-label">Email Address</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>">
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label for="mobile_no" class="col-sm-4 col-form-label">Mobile Number *</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="mobile_no" name="mobile_no" 
                                   value="<?php echo htmlspecialchars($row['mobile_no'] ?? ''); ?>" required>
                            <div class="invalid-feedback">Please enter a valid mobile number.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="dob" class="col-sm-4 col-form-label">Date of Birth</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="dob" name="dob" 
                                   value="<?php echo $row['dob'] ?? ''; ?>">
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
                                    $selected = (($row['blood_group'] ?? '') == $bg['id']) ? 'selected' : '';
                                    echo "<option value='{$bg['id']}' $selected>{$bg['display_name']}</option>";
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
                                    $selected = (($row['occupation'] ?? '') == $occ['id']) ? 'selected' : '';
                                    echo "<option value='{$occ['id']}' $selected>{$occ['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="occupation_details" class="col-sm-4 col-form-label">Occupation Details</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="occupation_details" name="occupation_details" 
                                   value="<?php echo htmlspecialchars($row['occupation_details'] ?? ''); ?>">
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
                        <label for="w_name" class="col-sm-4 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="w_name" name="w_name" 
                                   value="<?php echo htmlspecialchars($row['w_name']); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_dob" class="col-sm-4 col-form-label">Date of Birth</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="w_dob" name="w_dob" 
                                   value="<?php echo $row['w_dob']; ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_blood_group" class="col-sm-4 col-form-label">Blood Group</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="w_blood_group" name="w_blood_group">
                                <option value="">Select Blood Group</option>
                                <?php
                                foreach ($blood_groups as $bg) {
                                    $selected = ($row['w_blood_group'] == $bg['id']) ? 'selected' : '';
                                    echo "<option value='{$bg['id']}' $selected>{$bg['display_name']}</option>";
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
                                    $selected = ($row['w_qualification'] == $qual['id']) ? 'selected' : '';
                                    echo "<option value='{$qual['id']}' $selected>{$qual['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_education_details" class="col-sm-4 col-form-label">Education Details</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="w_education_details" name="w_education_details" 
                                   value="<?php echo htmlspecialchars($row['w_education_details']); ?>">
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
                                    $selected = ($row['w_occupation'] == $occ['id']) ? 'selected' : '';
                                    echo "<option value='{$occ['id']}' $selected>{$occ['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_occupation_details" class="col-sm-4 col-form-label">Occupation Details</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="w_occupation_details" name="w_occupation_details" 
                                   value="<?php echo htmlspecialchars($row['w_occupation_details']); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_email" class="col-sm-4 col-form-label">Email</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="w_email" name="w_email" 
                                   value="<?php echo htmlspecialchars($row['w_email']); ?>">
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
                                    $selected = ($row['w_kootam'] == $kootam['id']) ? 'selected' : '';
                                    echo "<option value='{$kootam['id']}' $selected>{$kootam['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="w_temple" class="col-sm-4 col-form-label">Temple</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="w_temple" name="w_temple" 
                                   value="<?php echo htmlspecialchars($row['w_temple']); ?>">
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
                            <textarea class="form-control" id="permanent_address" name="permanent_address" rows="3"><?php echo htmlspecialchars($row['permanent_address'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="village" class="col-sm-4 col-form-label">Village</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="village" name="village" 
                                   value="<?php echo htmlspecialchars($row['village'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="taluk" class="col-sm-4 col-form-label">Taluk</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="taluk" name="taluk" 
                                   value="<?php echo htmlspecialchars($row['taluk'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="district" class="col-sm-4 col-form-label">District</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="district" name="district" 
                                   value="<?php echo htmlspecialchars($row['district'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="state" class="col-sm-4 col-form-label">State</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="state" name="state" 
                                   value="<?php echo htmlspecialchars($row['state'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="country" class="col-sm-4 col-form-label">Country</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="country" name="country" 
                                   value="<?php echo htmlspecialchars($row['country'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="pincode" class="col-sm-4 col-form-label">Pincode</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pincode" name="pincode" 
                                   value="<?php echo htmlspecialchars($row['pincode']); ?>">
                        </div>
                    </div>
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
                        <input class="form-check-input" type="checkbox" id="sameAsPermanent" name="same_as_permanent" value="1" 
                               <?php echo (isset($row['same_as_permanent']) && $row['same_as_permanent'] == 1) ? 'checked' : ''; ?>
                               onchange="toggleCurrentAddress()">
                        <label class="form-check-label text-white" for="sameAsPermanent">
                            Same as Permanent
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="current_address" class="col-sm-4 col-form-label">Current Address</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="current_address" name="current_address" rows="3"><?php echo htmlspecialchars($row['current_address']); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_village" class="col-sm-4 col-form-label">Village</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_village" name="c_village" 
                                   value="<?php echo htmlspecialchars($row['c_village']); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_taluk" class="col-sm-4 col-form-label">Taluk</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_taluk" name="c_taluk" 
                                   value="<?php echo htmlspecialchars($row['c_taluk']); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_district" class="col-sm-4 col-form-label">District</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_district" name="c_district" 
                                   value="<?php echo htmlspecialchars($row['c_district']); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_state" class="col-sm-4 col-form-label">State</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_state" name="c_state" 
                                   value="<?php echo htmlspecialchars($row['c_state']); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_country" class="col-sm-4 col-form-label">Country</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_country" name="c_country" 
                                   value="<?php echo htmlspecialchars($row['c_country']); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_pincode" class="col-sm-4 col-form-label">Pincode</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_pincode" name="c_pincode" 
                                   value="<?php echo htmlspecialchars($row['c_pincode']); ?>">
                        </div>
                    </div>
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
                                    $selected = ($row['kattalai'] == $katt['id']) ? 'selected' : '';
                                    echo "<option value='{$katt['id']}' $selected>{$katt['display_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="k_village" class="col-sm-4 col-form-label">Kattalai Village</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="k_village" name="k_village" 
                                   value="<?php echo htmlspecialchars($row['k_village']); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="pudavai" class="col-sm-4 col-form-label">Pudavai</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pudavai" name="pudavai" 
                                   value="<?php echo htmlspecialchars($row['pudavai']); ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="ic" class="col-sm-4 col-form-label">IC</label>
                        <div class="col-sm-8">
                            <select class="form-select" id="ic" name="ic">
                                <option value="">Select</option>
                                <option value="Yes" <?php echo ($row['ic'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                <option value="No" <?php echo ($row['ic'] == 'No') ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label for="remarks" class="col-sm-4 col-form-label">Remarks</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="remarks" name="remarks" rows="6"><?php echo htmlspecialchars($row['remarks']); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="viewmember.php?id=<?php echo $id; ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Member
                </button>
            </div>
        </div>
    </div>
</form>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
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

// Initialize Select2 and set initial state of current address fields
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5'
    });
    
    // Set initial state of current address fields based on checkbox
    const checkbox = document.getElementById('sameAsPermanent');
    if (checkbox && checkbox.checked) {
        const currentAddressFields = [
            'current_address', 'c_village', 'c_taluk', 'c_district', 
            'c_state', 'c_country', 'c_pincode'
        ];
        currentAddressFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.disabled = true;
                field.style.backgroundColor = '#e9ecef';
            }
        });
    }
});
</script>

<?php include('../includes/footer.php'); ?>	
