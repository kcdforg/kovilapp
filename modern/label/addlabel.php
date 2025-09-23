<?php
include('../init.php');
check_login();

if (count($_POST) && $_POST['display_name'] != '') {
    $res = add_label($_POST);
    if ($res) {
        echo "<script>alert('Successfully 1 record added'); window.close(); window.opener.location.reload();</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Label</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid p-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-plus-circle"></i> Add Label
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Display Name</label>
                                    <input type="text" class="form-control" name="display_name" placeholder="Display Name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control" name="slug" placeholder="Slug" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="kattalai">Kattalai</option>
                                        <option value="village">Village</option>
                                        <option value="blood_group">Blood Group</option>
                                        <option value="occupation">Occupation</option>
                                        <option value="education">Education</option>
                                        <option value="kootam">Kootam</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-control" name="category" placeholder="Category">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Parent ID</label>
                                    <input type="number" class="form-control" name="parent_id" placeholder="Parent ID" min="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Order</label>
                                    <input type="number" class="form-control" name="order" placeholder="Order" value="0" min="0">
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-4">
                                <button type="button" class="btn btn-secondary" onclick="window.close()">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>