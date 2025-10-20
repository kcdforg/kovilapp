<?php
include('../init.php');

// Check if user is logged in
check_login();

// Enable mysqli exception mode

$success_message = '';
$error_message = '';

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
        'ic' => $_POST['ic'] ?? '',
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
    
    $result = add_member($member_data);
    
    if ($result) {
        $success_message = 'Member added successfully!';
        // Redirect to member list after 2 seconds
        header("refresh:2;url=memberlist.php");
    } else {
        $error_message = 'Error adding member. Please try again.';
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
</style>

<div class="row fluid-with-margins">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Add New Member</h1>
            <div class="d-flex gap-2">
                <a href="memberlist.php" class="btn btn-outline-primary">
                    <i class="bi bi-list"></i> View List (Fluid Layout)
                </a>
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
<form method="POST" class="needs-validation" novalidate>
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
                </div>
                
                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label for="qualification" class="col-sm-4 col-form-label">Education</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="qualification" name="qualification">
                                <option value="">Select Education</option>
                                <?php
                                $qualifications = get_labels_by_type('qualification');
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
                    
                    <div class="row mb-3">
                        <label for="email" class="col-sm-4 col-form-label">Email Address</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email">
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="mobile_no" class="col-sm-4 col-form-label">Mobile Number *</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="mobile_no" name="mobile_no" required>
                            <div class="invalid-feedback">Please enter a valid mobile number.</div>
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
                            <input type="text" class="form-control" id="w_name" name="w_name">
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
                    
                    <div class="row mb-3">
                        <label for="village" class="col-sm-4 col-form-label">Village</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="village" name="village">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="taluk" class="col-sm-4 col-form-label">Taluk</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="taluk" name="taluk">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="district" class="col-sm-4 col-form-label">District</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="district" name="district">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="state" class="col-sm-4 col-form-label">State</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="state" name="state">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="country" class="col-sm-4 col-form-label">Country</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="country" name="country" value="India">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="pincode" class="col-sm-4 col-form-label">Pincode</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pincode" name="pincode">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Current Address -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-geo-alt-fill"></i> CURRENT ADDRESS
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label for="current_address" class="col-sm-4 col-form-label">Current Address</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="current_address" name="current_address" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_village" class="col-sm-4 col-form-label">Village</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_village" name="c_village">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_taluk" class="col-sm-4 col-form-label">Taluk</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_taluk" name="c_taluk">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_district" class="col-sm-4 col-form-label">District</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_district" name="c_district">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_state" class="col-sm-4 col-form-label">State</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_state" name="c_state">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_country" class="col-sm-4 col-form-label">Country</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_country" name="c_country" value="India">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="c_pincode" class="col-sm-4 col-form-label">Pincode</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="c_pincode" name="c_pincode">
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
                    
                    <div class="row mb-3">
                        <label for="ic" class="col-sm-4 col-form-label">IC</label>
                        <div class="col-sm-8">
                            <select class="form-select" id="ic" name="ic">
                                <option value="">Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
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

// Initialize Select2
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5'
    });
});
</script>

<?php include('../includes/footer.php'); ?>