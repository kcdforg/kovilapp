<?php
include('../init.php');
check_login();
include('../popupheader.php');
if (!isset($_GET['ref_id'])) {
    echo "Horoscope can be uploaded only from Member Page";
    exit();
}

$error_msg = '';
$success_msg = '';
$ref_id = $_GET['ref_id'];
//var_dump($_POST);
if (count($_POST) && $_POST['name'] != '') {
    $res = add_horoscope($_POST);
    if ($res) {
        $success_msg = "Added Successfully ";
    } else {
        $error_msg = 'Error: ' . mysql_error();
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4">Add Horoscope</h2>
            
            <?php if ($success_msg) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?php echo $success_msg ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php } ?>

            <?php if ($error_msg) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error_msg ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php } ?>
            
            <form method="post" class="needs-validation" novalidate>
                <!-- Personal Details Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-person"></i> PERSONAL DETAILS</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback">Please enter the name.</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="gender" class="form-label">Gender</label>
                                            <?php display_gender("gender"); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="age" class="form-label">Age</label>
                                            <?php display_age("age"); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="height" class="form-label">Height</label>
                                            <?php display_height_horo("height"); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="weight" class="form-label">Weight</label>
                                            <?php display_weight("weight"); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="blood_group" class="form-label">Blood Group</label>
                                            <?php display_blood_group_list("blood_group"); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="colour" class="form-label">Colour</label>
                                            <?php display_colour("colour"); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="marital_status" class="form-label">Marital Status</label>
                                    <?php display_marital_status("marital_status"); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mobile_no" class="form-label">Mobile No</label>
                                    <input type="text" class="form-control" id="mobile_no" name="mobile_no">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="ref_name" class="form-label">Ref Name</label>
                                            <input type="text" class="form-control" id="ref_name" name="ref_name" value="<?php echo $_GET['ref_name'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="ref_mobile" class="form-label">Ref Mobile No</label>
                                            <input type="text" class="form-control" id="ref_mobile" name="ref_mobile" value="<?php echo $_GET['mbl_no'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="ref_address" class="form-label">Referrer Address</label>
                                    <textarea class="form-control" id="ref_address" name="ref_address" rows="2" readonly><?php echo $_GET['ref_address'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expectation Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-heart"></i> EXPECTATION</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pp_education" class="form-label">Education</label>
                                    <input type="text" class="form-control" id="pp_education" name="pp_education">
                                </div>

                                <div class="mb-3">
                                    <label for="pp_occupation" class="form-label">Occupation</label>
                                    <input type="text" class="form-control" id="pp_occupation" name="pp_occupation">
                                </div>

                                <div class="mb-3">
                                    <label for="pp_work_location" class="form-label">Work Location</label>
                                    <?php display_workplace("pp_work_location"); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pp_salary" class="form-label">Salary</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="pp_salary" name="pp_salary">
                                        <span class="input-group-text">per month</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="pp_asset_details" class="form-label">Asset Details</label>
                                    <input type="text" class="form-control" id="pp_asset_details" name="pp_asset_details">
                                </div>

                                <div class="mb-3">
                                    <label for="pp_expectation" class="form-label">Other Expectations</label>
                                    <textarea class="form-control" id="pp_expectation" name="pp_expectation" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" onclick="window.close()" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Form validation script -->
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
</script>

<?php
include('../includes/footer.php');
?>