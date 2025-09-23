<?php
include('../init.php');
check_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Receipt Book - Kovil App</title>
    <?php include('../includes/header.php'); ?>
</head>
<body class="d-flex flex-column min-vh-100">
    
    <main class="container-fluid mt-4 flex-fill">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-journal-plus"></i> Add Receipt Book</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Book No</label>
                                    <input type="number" class="form-control" name="book_no" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Start Receipt No</label>
                                    <input type="number" class="form-control" name="start_receipt_no" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">End Receipt No</label>
                                    <input type="number" class="form-control" name="end_receipt_no" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Issued To</label>
                                    <input type="text" class="form-control" name="issued_to">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Denomination</label>
                                    <input type="text" class="form-control" name="denomination">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save Book</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>
</html> 