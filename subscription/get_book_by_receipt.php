<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$receipt_no = isset($_GET['receipt_no']) ? intval($_GET['receipt_no']) : 0;
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

if ($receipt_no <= 0 || $event_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Receipt number and event ID are required']);
    exit;
}

global $con, $tbl_receipt_books, $tbl_member_subscriptions;

// Find which book contains this receipt number
$sql = "SELECT id, book_no, book_type, denomination, start_receipt_no, end_receipt_no 
        FROM $tbl_receipt_books 
        WHERE event_id = ? 
        AND ? BETWEEN start_receipt_no AND end_receipt_no 
        AND status = 'active'
        LIMIT 1";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ii", $event_id, $receipt_no);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($book) {
    // Check if receipt number is already used
    $check_sql = "SELECT id FROM $tbl_member_subscriptions 
                  WHERE book_id = ? AND receipt_no = ?";
    $check_stmt = mysqli_prepare($con, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ii", $book['id'], $receipt_no);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    $is_used = mysqli_num_rows($check_result) > 0;
    mysqli_stmt_close($check_stmt);
    
    if ($is_used) {
        echo json_encode([
            'success' => false,
            'message' => 'Receipt number ' . $receipt_no . ' has already been used in Book ' . $book['book_no']
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'book' => [
                'id' => $book['id'],
                'book_no' => $book['book_no'],
                'book_type' => $book['book_type'],
                'denomination' => $book['denomination'],
                'is_fixed' => $book['book_type'] === 'fixed'
            ]
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Receipt number ' . $receipt_no . ' not found in any active receipt book for this event'
    ]);
}

