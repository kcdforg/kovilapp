<?php
include('../init.php');
check_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subscription/Receipt - Kovil App</title>
    <?php include('../includes/header.php'); ?>
</head>
<body class="d-flex flex-column min-vh-100">
    
    <main class="container-fluid mt-4 flex-fill">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Add Subscription/Receipt</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Member</label>
                                    <select class="form-select select2" name="member_id" required>
                                        <option value="">Select Member</option>
                                        <!-- Populate from family table -->
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Amount</label>
                                    <input type="number" class="form-control" name="amount" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Book No</label>
                                    <select class="form-select" name="book_no" required>
                                        <option value="">Select Book</option>
                                        <!-- Populate from book table -->
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Receipt No</label>
                                    <input type="number" class="form-control" name="receipt_no" required>
                                </div>
                                <div class="col-md-4" id="eventDiv" style="display:none;">
                                    <label class="form-label">Event</label>
                                    <select class="form-select" name="event_id">
                                        <option value="">Select Event</option>
                                        <!-- Populate from event table -->
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Remarks</label>
                                    <input type="text" class="form-control" name="remarks">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Receipt</button>
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