<?php
include('../init.php');
check_login();

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

if (!$event_id) {
    header('Location: list.php');
    exit;
}

// Fetch event details
$sql = "SELECT * FROM $tbl_subscription_events WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$event_result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($event_result);
mysqli_stmt_close($stmt);

if (!$event) {
    header("Location: list.php");
    exit;
}

// Check if event is closed
$event_closed = ($event['status'] == 'closed');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add_payment') {
            // Check if event is closed
            if ($event_closed) {
                $error_message = "Cannot add payments to a closed event!";
            } else {
                // Add payment logic
                $member_id = intval($_POST['member_id']);
                $payment_date = mysqli_real_escape_string($con, $_POST['date']);
                $book_id = intval($_POST['book_no']);
                $receipt_no = intval($_POST['receipt_no']);
                $address = mysqli_real_escape_string($con, $_POST['address']);
                $remarks = mysqli_real_escape_string($con, $_POST['remarks']);
                
                // Check if amount is provided (even if field is disabled)
                if (!isset($_POST['amount']) || empty($_POST['amount'])) {
                    $error_message = "Amount is required!";
                } else {
                    $amount = floatval($_POST['amount']);
                    
                    // Check if receipt number already exists for this book
                    $check_sql = "SELECT id FROM $tbl_member_subscriptions WHERE book_id = ? AND receipt_no = ?";
                    $check_stmt = mysqli_prepare($con, $check_sql);
                    mysqli_stmt_bind_param($check_stmt, "ii", $book_id, $receipt_no);
                    mysqli_stmt_execute($check_stmt);
                    $check_result = mysqli_stmt_get_result($check_stmt);
                    
                    if (mysqli_num_rows($check_result) > 0) {
                        $error_message = "Receipt number $receipt_no has already been used for this book!";
                    } else {
                        $sql = "INSERT INTO $tbl_member_subscriptions (member_id, event_id, amount, payment_date, book_id, receipt_no, address, remarks) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($con, $sql);
                        mysqli_stmt_bind_param($stmt, "iidsiiss", $member_id, $event_id, $amount, $payment_date, $book_id, $receipt_no, $address, $remarks);
                        
                        if (mysqli_stmt_execute($stmt)) {
                            $success_message = "Payment added successfully!";
                        } else {
                            $error_message = "Error adding payment: " . mysqli_error($con);
                        }
                        mysqli_stmt_close($stmt);
                    }
                    mysqli_stmt_close($check_stmt);
                }
            }
            
        } elseif ($_POST['action'] == 'add_book') {
            // Add receipt book logic
            $book_no = intval($_POST['book_no']);
            $book_type = mysqli_real_escape_string($con, $_POST['book_type']);
            $issued_to = mysqli_real_escape_string($con, $_POST['issued_to']);
            $start_receipt_no = intval($_POST['start_receipt_no']);
            $end_receipt_no = intval($_POST['end_receipt_no']);
            $denomination = mysqli_real_escape_string($con, $_POST['denomination']);
            
            // Only set denomination if book type is fixed
            if ($book_type !== 'fixed') {
                $denomination = '';
            }
            
            $sql = "INSERT INTO $tbl_receipt_books (event_id, book_no, book_type, issued_to, start_receipt_no, end_receipt_no, denomination) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "iissiis", $event_id, $book_no, $book_type, $issued_to, $start_receipt_no, $end_receipt_no, $denomination);
            
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Receipt book added successfully!";
            } else {
                $error_message = "Error adding receipt book: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt);
        } elseif ($_POST['action'] == 'bulk_add_books') {
            $book_type = mysqli_real_escape_string($con, $_POST['book_type']);
            $denomination = mysqli_real_escape_string($con, $_POST['denomination']);
            $start_book_no = intval($_POST['start_book_no']);
            $end_book_no = intval($_POST['end_book_no']);
            $receipts_per_book = intval($_POST['receipts_per_book']);
            $issued_to = mysqli_real_escape_string($con, $_POST['issued_to']);

            if ($book_type !== 'fixed') {
                $denomination = '';
            }
            
            // Check for existing book numbers to avoid duplicates
            $existing_books_sql = "SELECT book_no FROM $tbl_receipt_books WHERE event_id = ? AND book_no BETWEEN ? AND ?";
            $existing_books_stmt = mysqli_prepare($con, $existing_books_sql);
            mysqli_stmt_bind_param($existing_books_stmt, "iii", $event_id, $start_book_no, $end_book_no);
            mysqli_stmt_execute($existing_books_stmt);
            $existing_books_result = mysqli_stmt_get_result($existing_books_stmt);
            $existing_book_numbers = [];
            while ($row = mysqli_fetch_assoc($existing_books_result)) {
                $existing_book_numbers[] = $row['book_no'];
            }
            mysqli_stmt_close($existing_books_stmt);
            
            $success_count = 0;
            $error_count = 0;
            $skipped_count = 0;
            
            for ($book_no = $start_book_no; $book_no <= $end_book_no; $book_no++) {
                // Skip if book number already exists
                if (in_array($book_no, $existing_book_numbers)) {
                    $skipped_count++;
                    continue;
                }
                
                $start_receipt_no = ($book_no - $start_book_no) * $receipts_per_book + 1;
                $end_receipt_no = $start_receipt_no + $receipts_per_book - 1;
                
                $sql = "INSERT INTO $tbl_receipt_books (event_id, book_no, book_type, issued_to, start_receipt_no, end_receipt_no, denomination) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "iissiis", $event_id, $book_no, $book_type, $issued_to, $start_receipt_no, $end_receipt_no, $denomination);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success_count++;
                } else {
                    $error_count++;
                }
                mysqli_stmt_close($stmt);
            }
            
            if ($error_count == 0 && $skipped_count == 0) {
                $success_message = "Successfully added $success_count receipt books!";
            } elseif ($error_count == 0 && $skipped_count > 0) {
                $success_message = "Successfully added $success_count receipt books! Skipped $skipped_count existing books.";
            } else {
                $error_message = "Added $success_count books successfully, but $error_count failed. Skipped $skipped_count existing books.";
            }
        }
    }
}

// Pagination settings
$items_per_page = 10; // Number of items per page
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Fetch members who paid for this event with pagination (Fixed denomination only)
$denomination_filter = isset($_GET['denomination']) ? $_GET['denomination'] : '';

$count_sql = "SELECT COUNT(*) as total FROM $tbl_member_subscriptions ms 
               JOIN $tbl_family f ON ms.member_id = f.id 
               JOIN $tbl_receipt_books rb ON ms.book_id = rb.id 
               WHERE ms.event_id = ? AND rb.book_type = 'fixed'";
$count_params = [$event_id];

if ($denomination_filter) {
    $count_sql .= " AND rb.denomination = ?";
    $count_params[] = $denomination_filter;
}

$count_stmt = mysqli_prepare($con, $count_sql);
mysqli_stmt_bind_param($count_stmt, str_repeat('s', count($count_params)), ...$count_params);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$total_payments = mysqli_fetch_assoc($count_result)['total'];
mysqli_stmt_close($count_stmt);

$total_pages = ceil($total_payments / $items_per_page);

$sql = "SELECT ms.*, f.name as member_name, rb.book_no, rb.denomination, rb.book_type
        FROM $tbl_member_subscriptions ms 
        JOIN $tbl_family f ON ms.member_id = f.id 
        JOIN $tbl_receipt_books rb ON ms.book_id = rb.id 
        WHERE ms.event_id = ? AND rb.book_type = 'fixed'";
$sql_params = [$event_id];

if ($denomination_filter) {
    $sql .= " AND rb.denomination = ?";
    $sql_params[] = $denomination_filter;
}

$sql .= " ORDER BY ms.payment_date DESC LIMIT ? OFFSET ?";
$sql_params[] = $items_per_page;
$sql_params[] = $offset;

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, str_repeat('s', count($sql_params)), ...$sql_params);
mysqli_stmt_execute($stmt);
$payments_result = mysqli_stmt_get_result($stmt);
$payments = [];
while ($row = mysqli_fetch_assoc($payments_result)) {
    $payments[] = $row;
}
mysqli_stmt_close($stmt);

// Fetch donations (Variable denomination) with pagination
$donations_count_sql = "SELECT COUNT(*) as total FROM $tbl_member_subscriptions ms 
                        JOIN $tbl_family f ON ms.member_id = f.id 
                        JOIN $tbl_receipt_books rb ON ms.book_id = rb.id 
                        WHERE ms.event_id = ? AND rb.book_type = 'generic'";
$donations_count_stmt = mysqli_prepare($con, $donations_count_sql);
mysqli_stmt_bind_param($donations_count_stmt, "i", $event_id);
mysqli_stmt_execute($donations_count_stmt);
$donations_count_result = mysqli_stmt_get_result($donations_count_stmt);
$total_donations = mysqli_fetch_assoc($donations_count_result)['total'];
mysqli_stmt_close($donations_count_stmt);

$donations_current_page = isset($_GET['donations_page']) ? max(1, intval($_GET['donations_page'])) : 1;
$donations_offset = ($donations_current_page - 1) * $items_per_page;
$donations_total_pages = ceil($total_donations / $items_per_page);

$donations_sql = "SELECT ms.*, f.name as member_name, rb.book_no, rb.denomination, rb.book_type
                  FROM $tbl_member_subscriptions ms 
                  JOIN $tbl_family f ON ms.member_id = f.id 
                  JOIN $tbl_receipt_books rb ON ms.book_id = rb.id 
                  WHERE ms.event_id = ? AND rb.book_type = 'generic'
                  ORDER BY ms.payment_date DESC
                  LIMIT ? OFFSET ?";
$donations_stmt = mysqli_prepare($con, $donations_sql);
mysqli_stmt_bind_param($donations_stmt, "iii", $event_id, $items_per_page, $donations_offset);
mysqli_stmt_execute($donations_stmt);
$donations_result = mysqli_stmt_get_result($donations_stmt);
$donations = [];
while ($row = mysqli_fetch_assoc($donations_result)) {
    $donations[] = $row;
}
mysqli_stmt_close($donations_stmt);

// Fetch receipt books for this event with pagination
$books_count_sql = "SELECT COUNT(*) as total FROM $tbl_receipt_books WHERE event_id = ?";
$books_count_stmt = mysqli_prepare($con, $books_count_sql);
mysqli_stmt_bind_param($books_count_stmt, "i", $event_id);
mysqli_stmt_execute($books_count_stmt);
$books_count_result = mysqli_stmt_get_result($books_count_stmt);
$total_books = mysqli_fetch_assoc($books_count_result)['total'];
mysqli_stmt_close($books_count_stmt);

$books_current_page = isset($_GET['books_page']) ? max(1, intval($_GET['books_page'])) : 1;
$books_offset = ($books_current_page - 1) * $items_per_page;
$books_total_pages = ceil($total_books / $items_per_page);

$sql = "SELECT * FROM $tbl_receipt_books WHERE event_id = ? ORDER BY book_no LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "iii", $event_id, $items_per_page, $books_offset);
mysqli_stmt_execute($stmt);
$books_result = mysqli_stmt_get_result($stmt);
$books = [];
while ($row = mysqli_fetch_assoc($books_result)) {
    $books[] = $row;
}
mysqli_stmt_close($stmt);

// Fetch all members for dropdown
$sql = "SELECT id, name FROM $tbl_family ORDER BY name";
$members_result = mysqli_query($con, $sql);
$members = [];
while ($row = mysqli_fetch_assoc($members_result)) {
    $members[] = $row;
}

// Fetch used receipt numbers for each book (for AJAX response)
$used_receipts = [];
$sql = "SELECT book_id, receipt_no FROM $tbl_member_subscriptions WHERE event_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$used_result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($used_result)) {
    if (!isset($used_receipts[$row['book_id']])) {
        $used_receipts[$row['book_id']] = [];
    }
    $used_receipts[$row['book_id']][] = $row['receipt_no'];
}
mysqli_stmt_close($stmt);

// Calculate totals
$total_amount_sql = "SELECT SUM(amount) as total_amount FROM $tbl_member_subscriptions WHERE event_id = ?";
$total_amount_stmt = mysqli_prepare($con, $total_amount_sql);
mysqli_stmt_bind_param($total_amount_stmt, "i", $event_id);
mysqli_stmt_execute($total_amount_stmt);
$total_amount_result = mysqli_stmt_get_result($total_amount_stmt);
$total_collected = mysqli_fetch_assoc($total_amount_result)['total_amount'] ?? 0;
mysqli_stmt_close($total_amount_stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['event_name']); ?> - Kovil App</title>
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
    
    <main class="container-fluid mt-2 flex-fill">
        <div class="row fluid-with-margins">
            <div class="col-12">
                <!-- Event Heading -->
                <div class="text-center mb-3">
                    <h4 class="mb-1">
                        <i class="bi bi-calendar-event"></i> 
                        <?php echo htmlspecialchars($event['event_name']); ?>
                    </h4>
                    <?php if ($event['remarks']): ?>
                        <p class="text-muted mb-0">
                            <strong>Remarks:</strong> <?php echo htmlspecialchars($event['remarks']); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Collected</h6>
                                        <h3 class="mb-0">₹<?php echo number_format($total_collected, 2); ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-cash-coin fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Payments</h6>
                                        <h3 class="mb-0"><?php echo $total_payments; ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-people fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Receipt Books</h6>
                                        <h3 class="mb-0"><?php echo count($books); ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-journal-text fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submenu Navigation -->
                <div class="card mb-4">
                    <div class="card-header">
                        <ul class="nav nav-pills" id="eventSubmenu" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'members') ? 'active' : ''; ?>" 
                                   href="?event_id=<?php echo $event_id; ?>&tab=members<?php echo isset($_GET['page']) ? '&page=' . $_GET['page'] : ''; ?><?php echo isset($_GET['denomination']) ? '&denomination=' . urlencode($_GET['denomination']) : ''; ?>">
                                    <i class="bi bi-people"></i> Members Paid (<?php echo $total_payments; ?>)
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'donations') ? 'active' : ''; ?>" 
                                   href="?event_id=<?php echo $event_id; ?>&tab=donations<?php echo isset($_GET['donations_page']) ? '&donations_page=' . $_GET['donations_page'] : ''; ?>">
                                    <i class="bi bi-gift"></i> Donations (<?php echo $total_donations; ?>)
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'books') ? 'active' : ''; ?>" 
                                   href="?event_id=<?php echo $event_id; ?>&tab=books<?php echo isset($_GET['books_page']) ? '&books_page=' . $_GET['books_page'] : ''; ?>">
                                    <i class="bi bi-journal-text"></i> Receipt Books (<?php echo $total_books; ?>)
                                </a>
                            </li>
                        </ul>
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
                        
                        <?php if ($event_closed): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="bi bi-lock"></i> <strong>This event is closed.</strong> No new payments can be added to this event.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Members Tab Content -->
                        <?php if (!isset($_GET['tab']) || $_GET['tab'] == 'members'): ?>
                            <div id="members" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Members Who Paid (Fixed Denomination)</h6>
                                    <div class="d-flex gap-2">
                                        <!-- Denomination Filter -->
                                        <select class="form-select form-select-sm" style="width: auto;" onchange="filterByDenomination(this.value)">
                                            <option value="">All Denominations</option>
                                            <?php
                                            // Get unique denominations for this event
                                            $denominations_sql = "SELECT DISTINCT rb.denomination 
                                                                 FROM $tbl_member_subscriptions ms 
                                                                 JOIN $tbl_receipt_books rb ON ms.book_id = rb.id 
                                                                 WHERE ms.event_id = ? AND rb.book_type = 'fixed' AND rb.denomination != ''
                                                                 ORDER BY rb.denomination";
                                            $denominations_stmt = mysqli_prepare($con, $denominations_sql);
                                            mysqli_stmt_bind_param($denominations_stmt, "i", $event_id);
                                            mysqli_stmt_execute($denominations_stmt);
                                            $denominations_result = mysqli_stmt_get_result($denominations_stmt);
                                            while ($denomination = mysqli_fetch_assoc($denominations_result)) {
                                                $selected = (isset($_GET['denomination']) && $_GET['denomination'] == $denomination['denomination']) ? 'selected' : '';
                                                echo '<option value="' . htmlspecialchars($denomination['denomination']) . '" ' . $selected . '>' . htmlspecialchars($denomination['denomination']) . '</option>';
                                            }
                                            mysqli_stmt_close($denominations_stmt);
                                            ?>
                                        </select>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentModal" 
                                                <?php echo $event_closed ? 'disabled' : ''; ?>>
                                            <i class="bi bi-plus-circle"></i> Add Payment
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Member ID</th>
                                                <th>Member Name</th>
                                                <th>Denomination</th>
                                                <th>Amount</th>
                                                <th>Payment Date</th>
                                                <th>Book No</th>
                                                <th>Receipt No</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($payments)): ?>
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        <i class="bi bi-inbox"></i> No payments recorded yet
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($payments as $payment): ?>
                                                    <tr>
                                                        <td><?php echo $payment['member_id']; ?></td>
                                                        <td><?php echo htmlspecialchars($payment['member_name']); ?></td>
                                                        <td>
                                                            <span class="badge bg-info">
                                                                <?php echo htmlspecialchars($payment['denomination']); ?>
                                                            </span>
                                                        </td>
                                                        <td>₹<?php echo number_format($payment['amount'], 2); ?></td>
                                                        <td><?php echo date('d M Y', strtotime($payment['payment_date'])); ?></td>
                                                        <td><?php echo $payment['book_no']; ?></td>
                                                        <td><?php echo $payment['receipt_no']; ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $payment['status'] == 'paid' ? 'success' : 'warning'; ?>">
                                                                <?php echo ucfirst($payment['status']); ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination for Members -->
                                <?php if ($total_pages > 1): ?>
                                    <nav aria-label="Members pagination" class="mt-3">
                                        <ul class="pagination justify-content-center">
                                            <!-- Previous button -->
                                            <?php if ($current_page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?event_id=<?php echo $event_id; ?>&tab=members&page=<?php echo $current_page - 1; ?><?php echo isset($_GET['denomination']) ? '&denomination=' . urlencode($_GET['denomination']) : ''; ?>">
                                                        <i class="bi bi-chevron-left"></i> Previous
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link"><i class="bi bi-chevron-left"></i> Previous</span>
                                                </li>
                                            <?php endif; ?>
                                            
                                            <!-- Page numbers -->
                                            <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                                <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?event_id=<?php echo $event_id; ?>&tab=members&page=<?php echo $i; ?><?php echo isset($_GET['denomination']) ? '&denomination=' . urlencode($_GET['denomination']) : ''; ?>">
                                                        <?php echo $i; ?>
                                                    </a>
                                                </li>
                                            <?php endfor; ?>
                                            
                                            <!-- Next button -->
                                            <?php if ($current_page < $total_pages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?event_id=<?php echo $event_id; ?>&tab=members&page=<?php echo $current_page + 1; ?><?php echo isset($_GET['denomination']) ? '&denomination=' . urlencode($_GET['denomination']) : ''; ?>">
                                                        Next <i class="bi bi-chevron-right"></i>
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">Next <i class="bi bi-chevron-right"></i></span>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                    
                                    <!-- Page info -->
                                    <div class="text-center text-muted small">
                                        Showing <?php echo (($current_page - 1) * $items_per_page) + 1; ?> to 
                                        <?php echo min($current_page * $items_per_page, $total_payments); ?> of 
                                        <?php echo $total_payments; ?> payments
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Books Tab Content -->
                        <?php if (isset($_GET['tab']) && $_GET['tab'] == 'books'): ?>
                            <div id="books" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Receipt Books</h6>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addBookModal">
                                            <i class="bi bi-plus-circle"></i> Add Receipt Book
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#bulkAddBooksModal">
                                            <i class="bi bi-collection"></i> Bulk Add Books
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Book No</th>
                                                <th>Book Type</th>
                                                <th>Issued To</th>
                                                <th>Receipt Range</th>
                                                <th>Denomination</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($books)): ?>
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        <i class="bi bi-inbox"></i> No receipt books added yet
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($books as $book): ?>
                                                    <tr>
                                                        <td><?php echo $book['book_no']; ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $book['book_type'] == 'fixed' ? 'primary' : 'info'; ?>">
                                                                <?php echo ucfirst($book['book_type']); ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($book['issued_to']); ?></td>
                                                        <td><?php echo $book['start_receipt_no']; ?> - <?php echo $book['end_receipt_no']; ?></td>
                                                        <td>
                                                            <?php if ($book['book_type'] == 'fixed' && !empty($book['denomination'])): ?>
                                                                <?php echo htmlspecialchars($book['denomination']); ?>
                                                            <?php else: ?>
                                                                <span class="text-muted">N/A</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $book['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                                                <?php echo ucfirst($book['status']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="view_book.php?id=<?php echo $book['id']; ?>" 
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination for Books -->
                                <?php if ($books_total_pages > 1): ?>
                                    <nav aria-label="Books pagination" class="mt-3">
                                        <ul class="pagination justify-content-center">
                                            <!-- Previous button -->
                                            <?php if ($books_current_page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?event_id=<?php echo $event_id; ?>&tab=books&books_page=<?php echo $books_current_page - 1; ?>">
                                                        <i class="bi bi-chevron-left"></i> Previous
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link"><i class="bi bi-chevron-left"></i> Previous</span>
                                                </li>
                                            <?php endif; ?>
                                            
                                            <!-- Page numbers -->
                                            <?php for ($i = max(1, $books_current_page - 2); $i <= min($books_total_pages, $books_current_page + 2); $i++): ?>
                                                <li class="page-item <?php echo $i == $books_current_page ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?event_id=<?php echo $event_id; ?>&tab=books&books_page=<?php echo $i; ?>">
                                                        <?php echo $i; ?>
                                                    </a>
                                                </li>
                                            <?php endfor; ?>
                                            
                                            <!-- Next button -->
                                            <?php if ($books_current_page < $books_total_pages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?event_id=<?php echo $event_id; ?>&tab=books&books_page=<?php echo $books_current_page + 1; ?>">
                                                        Next <i class="bi bi-chevron-right"></i>
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">Next <i class="bi bi-chevron-right"></i></span>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                    
                                    <!-- Page info -->
                                    <div class="text-center text-muted small">
                                        Showing <?php echo (($books_current_page - 1) * $items_per_page) + 1; ?> to 
                                        <?php echo min($books_current_page * $items_per_page, $total_books); ?> of 
                                        <?php echo $total_books; ?> books
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Donations Tab Content -->
                        <?php if (isset($_GET['tab']) && $_GET['tab'] == 'donations'): ?>
                            <div id="donations" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Donations</h6>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentModal" 
                                            <?php echo $event_closed ? 'disabled' : ''; ?>>
                                        <i class="bi bi-plus-circle"></i> Add Donation
                                    </button>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Member ID</th>
                                                <th>Member Name</th>
                                                <th>Amount</th>
                                                <th>Payment Date</th>
                                                <th>Book No</th>
                                                <th>Receipt No</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($donations)): ?>
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        <i class="bi bi-inbox"></i> No donations recorded yet
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($donations as $donation): ?>
                                                    <tr>
                                                        <td><?php echo $donation['member_id']; ?></td>
                                                        <td><?php echo htmlspecialchars($donation['member_name']); ?></td>
                                                        <td>₹<?php echo number_format($donation['amount'], 2); ?></td>
                                                        <td><?php echo date('d M Y', strtotime($donation['payment_date'])); ?></td>
                                                        <td><?php echo $donation['book_no']; ?></td>
                                                        <td><?php echo $donation['receipt_no']; ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $donation['status'] == 'paid' ? 'success' : 'warning'; ?>">
                                                                <?php echo ucfirst($donation['status']); ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination for Donations -->
                                <?php if ($donations_total_pages > 1): ?>
                                    <nav aria-label="Donations pagination" class="mt-3">
                                        <ul class="pagination justify-content-center">
                                            <!-- Previous button -->
                                            <?php if ($donations_current_page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?event_id=<?php echo $event_id; ?>&tab=donations&donations_page=<?php echo $donations_current_page - 1; ?>">
                                                        <i class="bi bi-chevron-left"></i> Previous
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link"><i class="bi bi-chevron-left"></i> Previous</span>
                                                </li>
                                            <?php endif; ?>
                                            
                                            <!-- Page numbers -->
                                            <?php for ($i = max(1, $donations_current_page - 2); $i <= min($donations_total_pages, $donations_current_page + 2); $i++): ?>
                                                <li class="page-item <?php echo $i == $donations_current_page ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?event_id=<?php echo $event_id; ?>&tab=donations&donations_page=<?php echo $i; ?>">
                                                        <?php echo $i; ?>
                                                    </a>
                                                </li>
                                            <?php endfor; ?>
                                            
                                            <!-- Next button -->
                                            <?php if ($donations_current_page < $donations_total_pages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?event_id=<?php echo $event_id; ?>&tab=donations&donations_page=<?php echo $donations_current_page + 1; ?>">
                                                        Next <i class="bi bi-chevron-right"></i>
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">Next <i class="bi bi-chevron-right"></i></span>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                    
                                    <!-- Page info -->
                                    <div class="text-center text-muted small">
                                        Showing <?php echo (($donations_current_page - 1) * $items_per_page) + 1; ?> to 
                                        <?php echo min($donations_current_page * $items_per_page, $total_donations); ?> of 
                                        <?php echo $total_donations; ?> donations
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Used receipt numbers data from PHP
        const usedReceipts = <?php echo json_encode($used_receipts); ?>;
        
        // Handle book type selection for Add Receipt Book modal
        document.addEventListener('DOMContentLoaded', function() {
            // Use event delegation for the book type selection
            document.addEventListener('change', function(event) {
                if (event.target.id === 'bookType') {
                    const denominationDiv = document.getElementById('denominationDiv');
                    const denominationInput = denominationDiv.querySelector('input[name="denomination"]');
                    
                    console.log('Book type changed to:', event.target.value); // Debug
                    
                    if (event.target.value === 'fixed') {
                        denominationDiv.style.display = 'block';
                        denominationInput.required = true;
                        console.log('Showing denomination field'); // Debug
                    } else if (event.target.value === 'generic') {
                        denominationDiv.style.display = 'none';
                        denominationInput.required = false;
                        denominationInput.value = '';
                        console.log('Hiding denomination field'); // Debug
                    } else {
                        denominationDiv.style.display = 'none';
                        denominationInput.required = false;
                        denominationInput.value = '';
                        console.log('Hiding denomination field (default)'); // Debug
                    }
                }
            });
            
            // Handle modal shown event to ensure proper initialization
            const addBookModal = document.getElementById('addBookModal');
            if (addBookModal) {
                addBookModal.addEventListener('shown.bs.modal', function() {
                    console.log('Add Book Modal shown'); // Debug
                    const bookTypeSelect = document.getElementById('bookType');
                    const denominationDiv = document.getElementById('denominationDiv');
                    
                    if (bookTypeSelect && denominationDiv) {
                        // Reset to default state when modal opens
                        bookTypeSelect.value = '';
                        denominationDiv.style.display = 'none';
                        console.log('Modal initialized'); // Debug
                    }
                });
            }
            
            // Handle book selection in Add Payment modal
            const paymentBookNo = document.getElementById('paymentBookNo');
            const paymentAmount = document.getElementById('paymentAmount');
            const paymentReceiptNo = document.getElementById('paymentReceiptNo');
            
            if (paymentBookNo && paymentAmount && paymentReceiptNo) {
                paymentBookNo.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const bookType = selectedOption.getAttribute('data-book-type');
                    const denomination = selectedOption.getAttribute('data-denomination');
                    
                    console.log('Book selected:', bookType, denomination); // Debug
                    
                    if (bookType === 'fixed' && denomination) {
                        // Auto-populate and make readonly for fixed denomination books
                        paymentAmount.value = denomination;
                        paymentAmount.readOnly = true;
                        paymentAmount.style.backgroundColor = '#f8f9fa';
                        paymentAmount.style.color = '#6c757d';
                        console.log('Amount auto-populated:', denomination); // Debug
                    } else {
                        // Enable amount field for generic books
                        paymentAmount.value = '';
                        paymentAmount.readOnly = false;
                        paymentAmount.style.backgroundColor = '';
                        paymentAmount.style.color = '';
                        console.log('Amount field enabled for variable amounts'); // Debug
                    }
                    
                    // Populate receipt number dropdown based on selected book
                    const receiptNoOptions = paymentReceiptNo.options;
                    receiptNoOptions.length = 1; // Clear existing options
                    receiptNoOptions[0].text = "Select Receipt No";
                    
                    if (this.value) {
                        const bookId = this.value;
                        const startReceiptNo = parseInt(selectedOption.getAttribute('data-start-receipt-no'));
                        const endReceiptNo = parseInt(selectedOption.getAttribute('data-end-receipt-no'));
                        const usedReceiptsForBook = usedReceipts[bookId] || [];
                        
                        console.log('Book selected:', bookId, 'Range:', startReceiptNo, '-', endReceiptNo, 'Used:', usedReceiptsForBook);
                        
                        // Generate available receipt numbers
                        for (let i = startReceiptNo; i <= endReceiptNo; i++) {
                            if (!usedReceiptsForBook.includes(i)) {
                                const option = document.createElement('option');
                                option.value = i;
                                option.text = i;
                                paymentReceiptNo.appendChild(option);
                            }
                        }
                        
                        // Check if any receipts are available
                        if (paymentReceiptNo.options.length === 1) {
                            const option = document.createElement('option');
                            option.value = '';
                            option.text = 'No available receipts';
                            option.disabled = true;
                            paymentReceiptNo.appendChild(option);
                        }
                    } else {
                        receiptNoOptions[0].text = "Select Book First";
                    }
                });
            }
            
            // Handle bulk book type selection
            const bulkBookType = document.getElementById('bulkBookType');
            const bulkDenominationDiv = document.getElementById('bulkDenominationDiv');
            
            if (bulkBookType && bulkDenominationDiv) {
                bulkBookType.addEventListener('change', function() {
                    const denominationInput = bulkDenominationDiv.querySelector('input[name="denomination"]');
                    
                    if (this.value === 'fixed') {
                        bulkDenominationDiv.style.display = 'block';
                        denominationInput.required = true;
                    } else if (this.value === 'generic') {
                        bulkDenominationDiv.style.display = 'none';
                        denominationInput.required = false;
                        denominationInput.value = '';
                    } else {
                        bulkDenominationDiv.style.display = 'none';
                        denominationInput.required = false;
                        denominationInput.value = '';
                    }
                });
            }
        });
    </script>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">
                        <i class="bi bi-plus"></i> Add Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add_payment">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Member</label>
                                <select class="form-select select2" name="member_id" required>
                                    <option value="">Select Member</option>
                                    <?php foreach ($members as $member): ?>
                                        <option value="<?php echo $member['id']; ?>">
                                            <?php echo htmlspecialchars($member['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Book No</label>
                                <select class="form-select" name="book_no" id="paymentBookNo" required>
                                    <option value="">Select Book</option>
                                    <?php foreach ($books as $book): ?>
                                        <option value="<?php echo $book['id']; ?>" 
                                                data-book-type="<?php echo $book['book_type']; ?>"
                                                data-denomination="<?php echo htmlspecialchars($book['denomination']); ?>"
                                                data-start-receipt-no="<?php echo $book['start_receipt_no']; ?>"
                                                data-end-receipt-no="<?php echo $book['end_receipt_no']; ?>">
                                            Book <?php echo $book['book_no']; ?> (<?php echo $book['start_receipt_no']; ?>-<?php echo $book['end_receipt_no']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Receipt No</label>
                                <select class="form-select" name="receipt_no" id="paymentReceiptNo" required>
                                    <option value="">Select Book First</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Enter address as written on receipt">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="remarks" rows="3" placeholder="Optional remarks"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Amount</label>
                                <input type="number" class="form-control" name="amount" id="paymentAmount" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Receipt Book Modal -->
    <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookModalLabel">
                        <i class="bi bi-journal-plus"></i> Add Receipt Book
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add_book">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Book No</label>
                                <input type="number" class="form-control" name="book_no" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Start Receipt No</label>
                                <input type="number" class="form-control" name="start_receipt_no" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Receipt No</label>
                                <input type="number" class="form-control" name="end_receipt_no" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Issued To</label>
                                <input type="text" class="form-control" name="issued_to" placeholder="Person responsible for this book">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Book Type</label>
                                <select class="form-select" name="book_type" id="bookType" required>
                                    <option value="">Select Book Type</option>
                                    <option value="fixed">Fixed Denomination</option>
                                    <option value="generic">Generic (Variable Amounts)</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="denominationDiv" style="display: none;">
                                <label class="form-label">Fixed Denomination Amount</label>
                                <input type="text" class="form-control" name="denomination" placeholder="e.g., ₹500.00" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Save Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Add Receipt Books Modal -->
    <div class="modal fade" id="bulkAddBooksModal" tabindex="-1" aria-labelledby="bulkAddBooksModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkAddBooksModalLabel">
                        <i class="bi bi-collection"></i> Bulk Add Receipt Books
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="bulk_add_books">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Book Type</label>
                                <select class="form-select" name="book_type" id="bulkBookType" required>
                                    <option value="">Select Book Type</option>
                                    <option value="fixed">Fixed Denomination</option>
                                    <option value="generic">Generic (Variable Amounts)</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="bulkDenominationDiv" style="display: none;">
                                <label class="form-label">Fixed Denomination Amount</label>
                                <input type="text" class="form-control" name="denomination" placeholder="e.g., ₹500.00" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Start Book No</label>
                                <input type="number" class="form-control" name="start_book_no" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Book No</label>
                                <input type="number" class="form-control" name="end_book_no" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Receipts Per Book</label>
                                <input type="number" class="form-control" name="receipts_per_book" min="1" value="100" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Issued To</label>
                                <input type="text" class="form-control" name="issued_to" placeholder="Person responsible for these books">
                            </div>
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> 
                                    <strong>Example:</strong> If you enter Start Book No: 1, End Book No: 5, and Receipts Per Book: 100, 
                                    it will create 5 books with receipt ranges: Book 1 (1-100), Book 2 (101-200), Book 3 (201-300), etc.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-save"></i> Add Books
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html> 