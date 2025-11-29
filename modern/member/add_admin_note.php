<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$member_id = $_POST['member_id'] ?? 0;
$note_text = $_POST['note_text'] ?? '';

if (empty($member_id) || empty($note_text)) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

// Get current notes
$sql = "SELECT admin_notes FROM $tbl_family WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $member_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Decode existing notes or create new array
$notes = [];
if (!empty($row['admin_notes'])) {
    $decoded = json_decode($row['admin_notes'], true);
    if (is_array($decoded)) {
        $notes = $decoded;
    }
}

// Add new note
$new_note = [
    'id' => uniqid(),
    'note' => $note_text,
    'added_by' => $_SESSION['username'] ?? 'Admin',
    'added_at' => date('Y-m-d H:i:s')
];

array_unshift($notes, $new_note); // Add to beginning of array

// Save back to database
$notes_json = json_encode($notes);
$update_sql = "UPDATE $tbl_family SET admin_notes = ? WHERE id = ?";
$update_stmt = mysqli_prepare($con, $update_sql);
mysqli_stmt_bind_param($update_stmt, "si", $notes_json, $member_id);

if (mysqli_stmt_execute($update_stmt)) {
    mysqli_stmt_close($update_stmt);
    echo json_encode([
        'success' => true,
        'message' => 'Note added successfully',
        'note' => $new_note
    ]);
} else {
    mysqli_stmt_close($update_stmt);
    echo json_encode(['success' => false, 'message' => 'Failed to add note']);
}
?>


