<?php
include('../init.php');
check_login();

$id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Label</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid p-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-triangle-fill"></i> Delete Label
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Are you sure you want to delete this label?</p>
                        <p class="text-danger mb-4">
                            <i class="bi bi-exclamation-circle"></i> 
                            This action cannot be undone and may affect data that references this label.
                        </p>
                        <form action="deletelabel.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="window.close()">No</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Yes, Delete
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