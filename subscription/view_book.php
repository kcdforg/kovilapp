<?php
include('../init.php');
check_login();

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$book_id) {
    header("Location: list.php");
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'edit_receipt') {
            $receipt_id = intval($_POST['receipt_id']);
            $member_id = intval($_POST['member_id']);
            $amount = floatval($_POST['amount']);
            $payment_date = mysqli_real_escape_string($con, $_POST['payment_date']);
            $address = mysqli_real_escape_string($con, $_POST['address']);
            $remarks = mysqli_real_escape_string($con, $_POST['remarks']);
            
            $sql = "UPDATE $tbl_member_subscriptions SET member_id = ?, amount = ?, payment_date = ?, address = ?, remarks = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "idsssi", $member_id, $amount, $payment_date, $address, $remarks, $receipt_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Receipt updated successfully!";
            } else {
                $error_message = "Error updating receipt: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt);
            
        } elseif ($_POST['action'] == 'add_receipt') {
            $member_id = intval($_POST['member_id']);
            $amount = floatval($_POST['amount']);
            $payment_date = mysqli_real_escape_string($con, $_POST['payment_date']);
            $address = mysqli_real_escape_string($con, $_POST['address']);
            $remarks = mysqli_real_escape_string($con, $_POST['remarks']);
            $receipt_no = intval($_POST['receipt_no']);
            $book_id = intval($_POST['book_id']);
            $event_id = intval($_POST['event_id']);
            
            $sql = "INSERT INTO $tbl_member_subscriptions (member_id, event_id, amount, payment_date, book_id, receipt_no, address, remarks) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "iidsiiss", $member_id, $event_id, $amount, $payment_date, $book_id, $receipt_no, $address, $remarks);
            
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Receipt added successfully!";
            } else {
                $error_message = "Error adding receipt: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Fetch book details
$book = null;
$sql = "SELECT rb.*, se.event_name 
        FROM $tbl_receipt_books rb 
        JOIN $tbl_subscription_events se ON rb.event_id = se.id 
        WHERE rb.id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$book) {
    header("Location: list.php");
    exit;
}

// Fetch all receipts for this book
$sql = "SELECT ms.*, f.name, ms.address, rb.book_no, rb.denomination, rb.book_type
        FROM $tbl_member_subscriptions ms
        JOIN $tbl_family f ON ms.member_id = f.id
        JOIN $tbl_receipt_books rb ON ms.book_id = rb.id
        WHERE ms.book_id = ?
        ORDER BY ms.receipt_no";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
$receipts_result = mysqli_stmt_get_result($stmt);
$receipts = [];
while ($row = mysqli_fetch_assoc($receipts_result)) {
    $receipts[] = $row;
}
mysqli_stmt_close($stmt);

// Create a complete list of all receipt numbers
$all_receipts = [];
$used_receipts = array_column($receipts, 'receipt_no');

// Ensure we have valid start and end receipt numbers
$start_receipt = intval($book['start_receipt_no']);
$end_receipt = intval($book['end_receipt_no']);

for ($receipt_no = $start_receipt; $receipt_no <= $end_receipt; $receipt_no++) {
    $receipt_data = null;
    
    // Find if this receipt number is used
    foreach ($receipts as $receipt) {
        if ($receipt['receipt_no'] == $receipt_no) {
            $receipt_data = $receipt;
            break;
        }
    }
    
    $all_receipts[] = [
        'receipt_no' => $receipt_no,
        'is_used' => $receipt_data !== null,
        'data' => $receipt_data
    ];
}

// Calculate totals
$total_amount = array_sum(array_column($receipts, 'amount'));
$total_receipts = count($receipts);
$available_receipts = $book['end_receipt_no'] - $book['start_receipt_no'] + 1 - $total_receipts;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Book Details - Kovil App</title>
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
                <!-- Book Details Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-journal-text"></i> Receipt Book Details
                        </h5>
                        <a href="event_members.php?event_id=<?php echo $book['event_id']; ?>" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to Event
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Book Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Event:</strong></td>
                                        <td><?php echo htmlspecialchars($book['event_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Book No:</strong></td>
                                        <td><?php echo $book['book_no']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Book Type:</strong></td>
                                        <td>
                                            <span class="badge bg-<?php echo $book['book_type'] == 'fixed' ? 'primary' : 'info'; ?>">
                                                <?php echo ucfirst($book['book_type']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Receipt Range:</strong></td>
                                        <td><?php echo $book['start_receipt_no']; ?> - <?php echo $book['end_receipt_no']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Issued To:</strong></td>
                                        <td><?php echo htmlspecialchars($book['issued_to']); ?></td>
                                    </tr>
                                    <?php if ($book['book_type'] == 'fixed' && !empty($book['denomination'])): ?>
                                    <tr>
                                        <td><strong>Denomination:</strong></td>
                                        <td><?php echo htmlspecialchars($book['denomination']); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Summary</h6>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Total Receipts</h6>
                                                <h3 class="mb-0"><?php echo $total_receipts; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Total Amount</h6>
                                                <h3 class="mb-0">₹<?php echo number_format($total_amount, 2); ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card bg-info text-white">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Available Receipts</h6>
                                                <h3 class="mb-0"><?php echo $available_receipts; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Book Status</h6>
                                                <h3 class="mb-0"><?php echo ucfirst($book['status']); ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Receipts List Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-list-ul"></i> Receipt Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($all_receipts)): ?>
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-3">No receipt numbers available for this book.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Receipt No</th>
                                            <th>Member Name</th>
                                            <th>Address</th>
                                            <th>Amount</th>
                                            <th>Payment Date</th>
                                            <th>Remarks</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($all_receipts as $receipt_item): ?>
                                            <tr class="<?php echo $receipt_item['is_used'] ? '' : 'table-light'; ?>">
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo $receipt_item['receipt_no']; ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($receipt_item['is_used']): ?>
                                                        <strong><?php echo htmlspecialchars($receipt_item['data']['name']); ?></strong>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($receipt_item['is_used']): ?>
                                                        <?php echo htmlspecialchars($receipt_item['data']['address']); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($receipt_item['is_used']): ?>
                                                        <strong>₹<?php echo number_format($receipt_item['data']['amount'], 2); ?></strong>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($receipt_item['is_used']): ?>
                                                        <?php echo date('d M Y', strtotime($receipt_item['data']['payment_date'])); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($receipt_item['is_used']): ?>
                                                        <?php if (!empty($receipt_item['data']['remarks'])): ?>
                                                            <small class="text-muted"><?php echo htmlspecialchars($receipt_item['data']['remarks']); ?></small>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($receipt_item['is_used']): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                data-bs-toggle="modal" data-bs-target="#editReceiptModal"
                                                                data-receipt-id="<?php echo $receipt_item['data']['id']; ?>"
                                                                data-member-id="<?php echo $receipt_item['data']['member_id']; ?>"
                                                                data-member-name="<?php echo htmlspecialchars($receipt_item['data']['name']); ?>"
                                                                data-amount="<?php echo $receipt_item['data']['amount']; ?>"
                                                                data-address="<?php echo htmlspecialchars($receipt_item['data']['address']); ?>"
                                                                data-payment-date="<?php echo $receipt_item['data']['payment_date']; ?>"
                                                                data-remarks="<?php echo htmlspecialchars($receipt_item['data']['remarks']); ?>">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                                data-bs-toggle="modal" data-bs-target="#addReceiptModal"
                                                                data-receipt-no="<?php echo $receipt_item['receipt_no']; ?>">
                                                            <i class="bi bi-plus"></i> Add
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Receipt Modal -->
    <div class="modal fade" id="editReceiptModal" tabindex="-1" aria-labelledby="editReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReceiptModalLabel">
                        <i class="bi bi-pencil"></i> Edit Receipt
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="edit_receipt">
                    <input type="hidden" name="receipt_id" id="editReceiptId">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Member</label>
                                <select class="form-select" name="member_id" id="editMemberId" required>
                                    <option value="">Select Member</option>
                                    <?php 
                                    // Fetch all members for dropdown
                                    $members_sql = "SELECT id, name FROM $tbl_family ORDER BY name";
                                    $members_result = mysqli_query($con, $members_sql);
                                    while ($member = mysqli_fetch_assoc($members_result)): 
                                    ?>
                                        <option value="<?php echo $member['id']; ?>"><?php echo htmlspecialchars($member['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Amount</label>
                                <input type="number" class="form-control" name="amount" id="editAmount" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Payment Date</label>
                                <input type="date" class="form-control" name="payment_date" id="editPaymentDate" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" id="editAddress" placeholder="Enter address">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="remarks" id="editRemarks" rows="3" placeholder="Optional remarks"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Receipt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Receipt Modal -->
    <div class="modal fade" id="addReceiptModal" tabindex="-1" aria-labelledby="addReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addReceiptModalLabel">
                        <i class="bi bi-plus"></i> Add Receipt
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add_receipt">
                    <input type="hidden" name="receipt_no" id="addReceiptNo">
                    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                    <input type="hidden" name="event_id" value="<?php echo $book['event_id']; ?>">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Receipt No</label>
                                <input type="text" class="form-control" id="addReceiptNoDisplay" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Member</label>
                                <select class="form-select" name="member_id" required>
                                    <option value="">Select Member</option>
                                    <?php 
                                    mysqli_data_seek($members_result, 0);
                                    while ($member = mysqli_fetch_assoc($members_result)): 
                                    ?>
                                        <option value="<?php echo $member['id']; ?>"><?php echo htmlspecialchars($member['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Amount</label>
                                <input type="number" class="form-control" name="amount" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Payment Date</label>
                                <input type="date" class="form-control" name="payment_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Enter address">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="remarks" rows="3" placeholder="Optional remarks"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Add Receipt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Handle edit receipt modal data population
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editReceiptModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const receiptId = button.getAttribute('data-receipt-id');
                    const memberId = button.getAttribute('data-member-id');
                    const memberName = button.getAttribute('data-member-name');
                    const amount = button.getAttribute('data-amount');
                    const address = button.getAttribute('data-address');
                    const paymentDate = button.getAttribute('data-payment-date');
                    const remarks = button.getAttribute('data-remarks');
                    
                    // Populate form fields
                    document.getElementById('editReceiptId').value = receiptId;
                    document.getElementById('editMemberId').value = memberId;
                    document.getElementById('editAmount').value = amount;
                    document.getElementById('editAddress').value = address;
                    document.getElementById('editPaymentDate').value = paymentDate;
                    document.getElementById('editRemarks').value = remarks;
                });
            }
            
            // Handle add receipt modal data population
            const addModal = document.getElementById('addReceiptModal');
            if (addModal) {
                addModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const receiptNo = button.getAttribute('data-receipt-no');
                    
                    // Populate receipt number
                    document.getElementById('addReceiptNo').value = receiptNo;
                    document.getElementById('addReceiptNoDisplay').value = receiptNo;
                });
            }
        });
    </script>

    <?php include('../includes/footer.php'); ?>
</body>
</html> 