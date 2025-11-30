<?php
include('../init.php');
check_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subscription Event - Kovil App</title>
    <?php include('../includes/header.php'); ?>
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
</head>
<body class="d-flex flex-column min-vh-100">
    
    <main class="container-fluid mt-4 flex-fill">
        <div class="row fluid-with-margins">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-calendar-plus"></i> Add Subscription Event</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Event Name</label>
                                    <input type="text" class="form-control" name="event_name" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="open">Open</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Total Amount</label>
                                    <input type="number" class="form-control" name="total_amount" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Remarks</label>
                                    <input type="text" class="form-control" name="remarks">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save Event</button>
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