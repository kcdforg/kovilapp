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
    
    $result = update_family($member_data);
    
    if ($result) {
        $success_message = 'Member updated successfully!';
        // Redirect to member view after 2 seconds
        header("refresh:2;url=viewmember.php?id=$id");
    } else {
        $error_message = 'Error updating member. Please try again.';
    }
}

$row = get_member($id);

include('../includes/header.php');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Update Member: <?php echo htmlspecialchars($row['name']); ?></h1>
            <a href="viewmember.php?id=<?php echo $id; ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to View
            </a>
        </div>
    </div>
</div>

<?php if ($success_message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="POST" class="needs-validation" novalidate>
    <!-- Personal Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-person-fill"></i> Personal Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?php echo htmlspecialchars($row['name']); ?>" required>
                    <div class="invalid-feedback">Please enter the full name.</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="father_name" class="form-label">Father's Name</label>
                    <input type="text" class="form-control" id="father_name" name="father_name" 
                           value="<?php echo htmlspecialchars($row['father_name']); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="mother_name" class="form-label">Mother's Name</label>
                    <input type="text" class="form-control" id="mother_name" name="mother_name" 
                           value="<?php echo htmlspecialchars($row['mother_name']); ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" 
                           value="<?php echo $row['dob']; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="blood_group" class="form-label">Blood Group</label>
                    <select class="form-select select2" id="blood_group" name="blood_group">
                        <option value="">Select Blood Group</option>
                        <?php
                        $blood_groups = get_labels_by_type('blood_group');
                        foreach ($blood_groups as $bg) {
                            $selected = ($row['blood_group'] == $bg['id']) ? 'selected' : '';
                            echo "<option value='{$bg['id']}' $selected>{$bg['display_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="mobile_no" class="form-label">Mobile Number *</label>
                    <input type="tel" class="form-control" id="mobile_no" name="mobile_no" 
                           value="<?php echo htmlspecialchars($row['mobile_no']); ?>" required>
                    <div class="invalid-feedback">Please enter a valid mobile number.</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($row['email']); ?>">
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="qualification" class="form-label">Education</label>
                    <select class="form-select select2" id="qualification" name="qualification">
                        <option value="">Select Education</option>
                        <?php
                        $qualifications = get_labels_by_type('qualification');
                        foreach ($qualifications as $qual) {
                            $selected = ($row['qualification'] == $qual['id']) ? 'selected' : '';
                            echo "<option value='{$qual['id']}' $selected>{$qual['display_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="education_details" class="form-label">Education Details</label>
                    <input type="text" class="form-control" id="education_details" name="education_details" 
                           value="<?php echo htmlspecialchars($row['education_details']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="occupation" class="form-label">Occupation</label>
                    <select class="form-select select2" id="occupation" name="occupation">
                        <option value="">Select Occupation</option>
                        <?php
                        $occupations = get_labels_by_type('occupation');
                        foreach ($occupations as $occ) {
                            $selected = ($row['occupation'] == $occ['id']) ? 'selected' : '';
                            echo "<option value='{$occ['id']}' $selected>{$occ['display_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="occupation_details" class="form-label">Occupation Details</label>
                <input type="text" class="form-control" id="occupation_details" name="occupation_details" 
                       value="<?php echo htmlspecialchars($row['occupation_details']); ?>">
            </div>
        </div>
    </div>

    <!-- Address Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-geo-alt-fill"></i> Address Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary">Permanent Address</h6>
                    <div class="mb-3">
                        <label for="permanent_address" class="form-label">Address</label>
                        <textarea class="form-control" id="permanent_address" name="permanent_address" rows="3"><?php echo htmlspecialchars($row['permanent_address']); ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="village" class="form-label">Village</label>
                            <input type="text" class="form-control" id="village" name="village" 
                                   value="<?php echo htmlspecialchars($row['village']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="taluk" class="form-label">Taluk</label>
                            <input type="text" class="form-control" id="taluk" name="taluk" 
                                   value="<?php echo htmlspecialchars($row['taluk']); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="district" class="form-label">District</label>
                            <input type="text" class="form-control" id="district" name="district" 
                                   value="<?php echo htmlspecialchars($row['district']); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" 
                                   value="<?php echo htmlspecialchars($row['state']); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode" 
                                   value="<?php echo htmlspecialchars($row['pincode']); ?>">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h6 class="text-primary">Current Address</h6>
                    <div class="mb-3">
                        <label for="current_address" class="form-label">Address</label>
                        <textarea class="form-control" id="current_address" name="current_address" rows="3"><?php echo htmlspecialchars($row['current_address']); ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="c_village" class="form-label">Village</label>
                            <input type="text" class="form-control" id="c_village" name="c_village" 
                                   value="<?php echo htmlspecialchars($row['c_village']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="c_taluk" class="form-label">Taluk</label>
                            <input type="text" class="form-control" id="c_taluk" name="c_taluk" 
                                   value="<?php echo htmlspecialchars($row['c_taluk']); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="c_district" class="form-label">District</label>
                            <input type="text" class="form-control" id="c_district" name="c_district" 
                                   value="<?php echo htmlspecialchars($row['c_district']); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="c_state" class="form-label">State</label>
                            <input type="text" class="form-control" id="c_state" name="c_state" 
                                   value="<?php echo htmlspecialchars($row['c_state']); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="c_pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="c_pincode" name="c_pincode" 
                                   value="<?php echo htmlspecialchars($row['c_pincode']); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-info-circle"></i> Additional Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="kattalai" class="form-label">Kattalai</label>
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
                <div class="col-md-4 mb-3">
                    <label for="k_village" class="form-label">Kattalai Village</label>
                    <input type="text" class="form-control" id="k_village" name="k_village" 
                           value="<?php echo htmlspecialchars($row['k_village']); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="pudavai" class="form-label">Pudavai</label>
                    <input type="text" class="form-control" id="pudavai" name="pudavai" 
                           value="<?php echo htmlspecialchars($row['pudavai']); ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ic" class="form-label">IC</label>
                    <select class="form-select" id="ic" name="ic">
                        <option value="">Select</option>
                        <option value="Yes" <?php echo ($row['ic'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No" <?php echo ($row['ic'] == 'No') ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" class="form-control" id="country" name="country" 
                           value="<?php echo htmlspecialchars($row['country'] ?: 'India'); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3"><?php echo htmlspecialchars($row['remarks']); ?></textarea>
            </div>
        </div>
    </div>

    <!-- Wife Information (Optional) -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-heart-fill"></i> Spouse Information (Optional)
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="w_name" class="form-label">Spouse Name</label>
                    <input type="text" class="form-control" id="w_name" name="w_name" 
                           value="<?php echo htmlspecialchars($row['w_name']); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="w_dob" class="form-label">Spouse Date of Birth</label>
                    <input type="date" class="form-control" id="w_dob" name="w_dob" 
                           value="<?php echo $row['w_dob']; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="w_blood_group" class="form-label">Spouse Blood Group</label>
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
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="w_qualification" class="form-label">Spouse Education</label>
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
                <div class="col-md-6 mb-3">
                    <label for="w_occupation" class="form-label">Spouse Occupation</label>
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
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="w_education_details" class="form-label">Spouse Education Details</label>
                    <input type="text" class="form-control" id="w_education_details" name="w_education_details" 
                           value="<?php echo htmlspecialchars($row['w_education_details']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="w_occupation_details" class="form-label">Spouse Occupation Details</label>
                    <input type="text" class="form-control" id="w_occupation_details" name="w_occupation_details" 
                           value="<?php echo htmlspecialchars($row['w_occupation_details']); ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="w_email" class="form-label">Spouse Email</label>
                    <input type="email" class="form-control" id="w_email" name="w_email" 
                           value="<?php echo htmlspecialchars($row['w_email']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="w_kootam" class="form-label">Spouse Kootam</label>
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
            
            <div class="mb-3">
                <label for="w_temple" class="form-label">Spouse Temple</label>
                <input type="text" class="form-control" id="w_temple" name="w_temple" 
                       value="<?php echo htmlspecialchars($row['w_temple']); ?>">
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
