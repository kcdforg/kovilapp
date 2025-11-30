<?php
include('../init.php');
check_login();

// Check if subscription tables exist
$table_exists = false;
$result = mysqli_query($con, "SHOW TABLES LIKE '$tbl_subscription_events'");
if (mysqli_num_rows($result) > 0) {
    $table_exists = true;
}

// Handle form submission for adding new event
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_name'])) {
    if (!$table_exists) {
        $error_message = "Subscription tables do not exist. Please run the database setup first.";
    } else {
        $event_name = mysqli_real_escape_string($con, $_POST['event_name']);
        $status = mysqli_real_escape_string($con, $_POST['status']);
        $date = mysqli_real_escape_string($con, $_POST['date']);
        $remarks = mysqli_real_escape_string($con, $_POST['remarks']);
        
        $sql = "INSERT INTO $tbl_subscription_events (event_name, status, date, remarks) 
                VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $event_name, $status, $date, $remarks);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Subscription event added successfully!";
        } else {
            $error_message = "Error adding event: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
}

// Handle close event action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'close_event') {
    if (!$table_exists) {
        $error_message = "Subscription tables do not exist. Please run the database setup first.";
    } else {
        $event_id = intval($_POST['event_id']);
        
        $sql = "UPDATE $tbl_subscription_events SET status = 'closed' WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $event_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Event closed successfully!";
        } else {
            $error_message = "Error closing event: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
}

// Handle edit event action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit_event') {
    if (!$table_exists) {
        $error_message = "Subscription tables do not exist. Please run the database setup first.";
    } else {
        $event_id = intval($_POST['event_id']);
        $event_name = mysqli_real_escape_string($con, $_POST['event_name']);
        $status = mysqli_real_escape_string($con, $_POST['status']);
        $date = mysqli_real_escape_string($con, $_POST['date']);
        $remarks = mysqli_real_escape_string($con, $_POST['remarks']);
        
        $sql = "UPDATE $tbl_subscription_events SET event_name = ?, status = ?, date = ?, remarks = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $event_name, $status, $date, $remarks, $event_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Event updated successfully!";
        } else {
            $error_message = "Error updating event: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch all subscription events
$events = [];
if ($table_exists) {
    $sql = "SELECT se.*, 
                   COALESCE(SUM(CASE WHEN rb.book_type = 'fixed' THEN ms.amount ELSE 0 END), 0) as total_fixed_amount,
                   COALESCE(SUM(CASE WHEN rb.book_type = 'generic' THEN ms.amount ELSE 0 END), 0) as total_donations
            FROM $tbl_subscription_events se
            LEFT JOIN $tbl_member_subscriptions ms ON se.id = ms.event_id
            LEFT JOIN $tbl_receipt_books rb ON ms.book_id = rb.id
            GROUP BY se.id
            ORDER BY se.date DESC, se.created_at DESC";
    $result = mysqli_query($con, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Get denomination breakdown for this event
            $event_id = $row['id'];
            $denomination_sql = "SELECT rb.denomination, COUNT(DISTINCT ms.member_id) as member_count
                                FROM $tbl_member_subscriptions ms
                                JOIN $tbl_receipt_books rb ON ms.book_id = rb.id
                                WHERE ms.event_id = ? AND rb.book_type = 'fixed' AND rb.denomination != ''
                                GROUP BY rb.denomination
                                ORDER BY rb.denomination";
            $denomination_stmt = mysqli_prepare($con, $denomination_sql);
            mysqli_stmt_bind_param($denomination_stmt, "i", $event_id);
            mysqli_stmt_execute($denomination_stmt);
            $denomination_result = mysqli_stmt_get_result($denomination_stmt);
            
            $denomination_breakdown = [];
            while ($denom_row = mysqli_fetch_assoc($denomination_result)) {
                $denomination_breakdown[] = $denom_row['denomination'] . ' - ' . $denom_row['member_count'];
            }
            mysqli_stmt_close($denomination_stmt);
            
            $row['denomination_breakdown'] = $denomination_breakdown;
            $events[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Events - Kovil App</title>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-event"></i> Subscription Events
                        </h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            <i class="bi bi-plus-circle"></i> Add Subscription Event
                        </button>
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
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Event Name</th>
                                        <th>Members Paid</th>
                                        <th>Total Fixed Amount</th>
                                        <th>Total Donations</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($events)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                <i class="bi bi-inbox"></i> No subscription events found. Click "Add Subscription Event" to create your first event.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($events as $event): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($event['event_name']); ?></strong>
                                                    <?php if ($event['remarks']): ?>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($event['remarks']); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($event['denomination_breakdown'])): ?>
                                                        <?php foreach ($event['denomination_breakdown'] as $breakdown): ?>
                                                            <span class="badge bg-primary me-1"><?php echo htmlspecialchars($breakdown); ?></span>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">No fixed payments</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($event['total_fixed_amount'] > 0): ?>
                                                        ₹<?php echo number_format($event['total_fixed_amount'], 2); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">₹0.00</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($event['total_donations'] > 0): ?>
                                                        ₹<?php echo number_format($event['total_donations'], 2); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">₹0.00</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($event['total_fixed_amount'] + $event['total_donations'] > 0): ?>
                                                        ₹<?php echo number_format($event['total_fixed_amount'] + $event['total_donations'], 2); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">₹0.00</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $event['status'] == 'open' ? 'success' : 'secondary'; ?>">
                                                        <?php echo ucfirst($event['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($event['date'])); ?></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="event_members.php?event_id=<?php echo $event['id']; ?>" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                        <?php if ($event['status'] == 'open'): ?>
                                                            <a href="#" class="btn btn-sm btn-outline-secondary" 
                                                               data-bs-toggle="modal" data-bs-target="#editEventModal" 
                                                               data-event-id="<?php echo $event['id']; ?>"
                                                               data-event-name="<?php echo htmlspecialchars($event['event_name']); ?>"
                                                               data-event-date="<?php echo $event['date']; ?>"
                                                               data-event-status="<?php echo $event['status']; ?>"
                                                               data-event-remarks="<?php echo htmlspecialchars($event['remarks']); ?>">
                                                                <i class="bi bi-pencil"></i> Edit
                                                            </a>
                                                            <form method="POST" action="" style="display: inline;">
                                                                <input type="hidden" name="action" value="close_event">
                                                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                                <button type="submit" class="btn btn-sm btn-outline-warning" 
                                                                        onclick="return confirm('Are you sure you want to close this event? This will prevent new payments.')">
                                                                    <i class="bi bi-lock"></i> Close
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin' && $event['status'] == 'open'): ?>
                                                            <a href="delete_event.php?id=<?php echo $event['id']; ?>" 
                                                               class="btn btn-sm btn-outline-danger"
                                                               onclick="return confirm('Are you sure you want to delete this event?')">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Subscription Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">
                        <i class="bi bi-calendar-plus"></i> Add Subscription Event
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Event Name</label>
                                <input type="text" class="form-control" name="event_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="remarks" rows="3" placeholder="Optional remarks about this event"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Save Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Subscription Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">
                        <i class="bi bi-pencil"></i> Edit Subscription Event
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="edit_event">
                    <input type="hidden" name="event_id" id="editEventId">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Event Name</label>
                                <input type="text" class="form-control" name="event_name" id="editEventName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" id="editEventDate" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" id="editEventStatus" required>
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="remarks" id="editEventRemarks" rows="3" placeholder="Optional remarks about this event"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Handle edit modal data population
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editEventModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const eventId = button.getAttribute('data-event-id');
                    const eventName = button.getAttribute('data-event-name');
                    const eventDate = button.getAttribute('data-event-date');
                    const eventStatus = button.getAttribute('data-event-status');
                    const eventRemarks = button.getAttribute('data-event-remarks');
                    
                    // Populate form fields
                    document.getElementById('editEventId').value = eventId;
                    document.getElementById('editEventName').value = eventName;
                    document.getElementById('editEventDate').value = eventDate;
                    document.getElementById('editEventStatus').value = eventStatus;
                    document.getElementById('editEventRemarks').value = eventRemarks;
                });
            }
        });
    </script>

    <?php include('../includes/footer.php'); ?>
</body>
</html> 