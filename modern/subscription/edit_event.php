<?php
include('../init.php');
check_login();

// Check if subscription tables exist
$table_exists = false;
$result = mysqli_query($con, "SHOW TABLES LIKE '$tbl_subscription_events'");
if (mysqli_num_rows($result) > 0) {
    $table_exists = true;
}

$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$event_id) {
    header("Location: list.php");
    exit;
}

// Fetch event details
$event = null;
if ($table_exists) {
    $sql = "SELECT * FROM $tbl_subscription_events WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $event_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $event = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

if (!$event) {
    header("Location: list.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!$table_exists) {
        $error_message = "Subscription tables do not exist. Please run the database setup first.";
    } else {
        $event_name = mysqli_real_escape_string($con, $_POST['event_name']);
        $total_amount = floatval($_POST['total_amount']);
        $status = mysqli_real_escape_string($con, $_POST['status']);
        $date = mysqli_real_escape_string($con, $_POST['date']);
        $remarks = mysqli_real_escape_string($con, $_POST['remarks']);
        
        $sql = "UPDATE $tbl_subscription_events SET event_name = ?, total_amount = ?, status = ?, date = ?, remarks = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sdsssi", $event_name, $total_amount, $status, $date, $remarks, $event_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Event updated successfully!";
            // Refresh event data
            $sql = "SELECT * FROM $tbl_subscription_events WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $event_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $event = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Error updating event: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Kovil App</title>
    <?php include('../includes/header.php'); ?>
</head>
<body class="d-flex flex-column min-vh-100">
    
    <main class="container-fluid mt-4 flex-fill">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil"></i> Edit Subscription Event
                        </h5>
                        <a href="list.php" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to Events
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Event Name</label>
                                    <input type="text" class="form-control" name="event_name" 
                                           value="<?php echo htmlspecialchars($event['event_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date" 
                                           value="<?php echo $event['date']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Total Amount</label>
                                    <input type="number" class="form-control" name="total_amount" step="0.01" min="0" 
                                           value="<?php echo $event['total_amount']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="open" <?php echo $event['status'] == 'open' ? 'selected' : ''; ?>>Open</option>
                                        <option value="closed" <?php echo $event['status'] == 'closed' ? 'selected' : ''; ?>>Closed</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Remarks</label>
                                    <textarea class="form-control" name="remarks" rows="3" 
                                              placeholder="Optional remarks about this event"><?php echo htmlspecialchars($event['remarks']); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Update Event
                                        </button>
                                        <a href="list.php" class="btn btn-secondary">
                                            <i class="bi bi-x-circle"></i> Cancel
                                        </a>
                                    </div>
                                </div>
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