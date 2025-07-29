<?php
include('../init.php');
check_login();
include('../includes/header.php');

$id = $_GET['id'];
$error_msg = '';
$success_msg = '';
if (count($_POST) > 0) {
    $res = update_horoscope($id, $_POST);
    if ($res) {
        $success_msg = "Updated Successfully ";
    } else {
        $error_msg = 'Error: ' . mysql_error();
    }
}

$row = get_horoscope($id);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4">Update Horoscope</h2>
            
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
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name'] ?>" required>
                                    <div class="invalid-feedback">Please enter the name.</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="gender" class="form-label">Gender</label>
                                            <?php display_gender("gender", $row['gender']); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="age" class="form-label">Age</label>
                                            <?php display_age("age", $row['age']); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="height" class="form-label">Height</label>
                                            <?php display_height_horo("height", $row['height']); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="weight" class="form-label">Weight</label>
                                            <?php display_weight("weight", $row['weight']); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="blood_group" class="form-label">Blood Group</label>
                                            <?php display_blood_group_list("blood_group", $row['blood_group']); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="colour" class="form-label">Colour</label>
                                            <?php display_colour("colour", $row['colour']); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="marital_status" class="form-label">Marital Status</label>
                                    <?php display_marital_status("marital_status", $row['marital_status']); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mobile_no" class="form-label">Mobile No</label>
                                    <input type="text" class="form-control" id="mobile_no" name="mobile_no" value="<?php echo $row['mobile_no'] ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email'] ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo $row['address'] ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="qualification" class="form-label">Education</label>
                                    <?php display_qualification("qualification", $row['qualification']); ?>
                                </div>

                                <div class="mb-3">
                                    <label for="occupation" class="form-label">Occupation</label>
                                    <?php display_occupation("occupation", $row['occupation']); ?>
                                </div>

                                <div class="mb-3">
                                    <label for="income" class="form-label">Income</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="income" name="income" value="<?php echo $row['income'] ?>">
                                        <span class="input-group-text">per month</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Family Details Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-people"></i> FAMILY DETAILS</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="father_name" class="form-label">Father's Name</label>
                                    <input type="text" class="form-control" id="father_name" name="father_name" value="<?php echo $row['father_name'] ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="f_occupation" class="form-label">Father's Occupation</label>
                                    <input type="text" class="form-control" id="f_occupation" name="f_occupation" value="<?php echo $row['f_occupation'] ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="mother_name" class="form-label">Mother's Name</label>
                                    <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?php echo $row['mother_name'] ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="m_occupation" class="form-label">Mother's Occupation</label>
                                    <input type="text" class="form-control" id="m_occupation" name="m_occupation" value="<?php echo $row['m_occupation'] ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="sibling" class="form-label">Siblings</label>
                                    <input type="text" class="form-control" id="sibling" name="sibling" value="<?php echo $row['sibling'] ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kulam" class="form-label">Kulam</label>
                                    <?php display_kulam_list("kulam", $row['kulam']); ?>
                                </div>

                                <div class="mb-3">
                                    <label for="temple" class="form-label">Temple</label>
                                    <input type="text" class="form-control" id="temple" name="temple" value="<?php echo $row['temple'] ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="m_kulam" class="form-label">Mother's Kulam</label>
                                    <?php display_kulam_list("m_kulam", $row['m_kulam']); ?>
                                </div>

                                <div class="mb-3">
                                    <label for="mm_kulam" class="form-label">Maternal Mother's Kulam</label>
                                    <?php display_kulam_list("mm_kulam", $row['mm_kulam']); ?>
                                </div>

                                <div class="mb-3">
                                    <label for="pm_kulam" class="form-label">Paternal Mother's Kulam</label>
                                    <?php display_kulam_list("pm_kulam", $row['pm_kulam']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Horoscope Details Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-star"></i> HOROSCOPE DETAILS</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Birth Date</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php display_date("birth_date[day]", $row['birth_date']); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?php display_month("birth_date[month]", $row['birth_date']); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?php display_year("birth_date[year]", $row['birth_date']); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Birth Time</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?php display_hour("birth_time[hour]", $row['birth_time']); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php display_minute("birth_time[min]", $row['birth_time']); ?>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-center">
                                            <small class="text-muted">HH:MM</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="birth_place" class="form-label">Birth Place</label>
                                    <input type="text" class="form-control" id="birth_place" name="birth_place" value="<?php echo $row['birth_place'] ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="raasi" class="form-label">Raasi</label>
                                    <?php display_raasi("raasi", $row['raasi']); ?>
                                </div>

                                <div class="mb-3">
                                    <label for="laknam" class="form-label">Laknam</label>
                                    <?php display_raasi("laknam", $row['laknam']); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="star" class="form-label">Star</label>
                                    <?php display_star("star", $row['star']); ?>
                                </div>

                                <div class="mb-3">
                                    <label for="padham" class="form-label">Padham</label>
                                    <?php display_padham("padham", $row['padham']); ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Raghu/Kedhu</label>
                                            <div class="form-check">
                                                <?php display_raghu_kedhu_checkbox('raaghu_kaedhu', $row['raaghu_kaedhu']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sevvai</label>
                                            <div class="form-check">
                                                <?php display_sevvai_checkbox('sevvai', $row['sevvai']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="about_myself" class="form-label">About Myself</label>
                                    <textarea class="form-control" id="about_myself" name="about_myself" rows="4"><?php echo $row['about_myself'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="viewhoroscope.php?id=<?php echo $id ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update
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

<?php include('../includes/footer.php'); ?>	
