<?php
include('../init.php');
check_login();

if (($_SESSION['username']) != 'admin') {
    echo "<br>" . "<br>" . "<br>" . 'You are not authorized to visit this page!';
    die;
}

$username = $_SESSION['username'];
$id = $_GET['id'];

if (count($_POST) > 0) {
    $res = update_labels($id, $_POST);
    if ($res) {
        echo "<script>alert('Successfully 1 record updated'); window.close(); window.opener.location.reload();</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

$row = get_label($id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Label</title>
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
                            <i class="bi bi-pencil"></i> Update Label
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Display Name</label>
                                    <input type="text" class="form-control" name="display_name" value="<?php echo htmlspecialchars($row['display_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control" name="slug" value="<?php echo htmlspecialchars($row['slug']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="kattalai" <?php echo ($row['type'] == 'kattalai') ? 'selected' : ''; ?>>Kattalai</option>
                                        <option value="village" <?php echo ($row['type'] == 'village') ? 'selected' : ''; ?>>Village</option>
                                        <option value="blood_group" <?php echo ($row['type'] == 'blood_group') ? 'selected' : ''; ?>>Blood Group</option>
                                        <option value="occupation" <?php echo ($row['type'] == 'occupation') ? 'selected' : ''; ?>>Occupation</option>
                                        <option value="education" <?php echo ($row['type'] == 'education') ? 'selected' : ''; ?>>Education</option>
                                        <option value="kootam" <?php echo ($row['type'] == 'kootam') ? 'selected' : ''; ?>>Kootam</option>
                                        <option value="other" <?php echo ($row['type'] == 'other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-control" name="category" value="<?php echo htmlspecialchars($row['category']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Parent ID</label>
                                    <input type="number" class="form-control" name="parent_id" value="<?php echo $row['parent_id']; ?>" min="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Order</label>
                                    <input type="number" class="form-control" name="order" value="<?php echo $row['order']; ?>" min="0">
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-4">
                                <button type="button" class="btn btn-secondary" onclick="window.close()">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update
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