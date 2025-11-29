<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$member_id = $_POST['member_id'] ?? 0;
$note_id = $_POST['note_id'] ?? '';

if (empty($member_id) || empty($note_id)) {
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

// Decode existing notes
$notes = [];
if (!empty($row['admin_notes'])) {
    $decoded = json_decode($row['admin_notes'], true);
    if (is_array($decoded)) {
        $notes = $decoded;
    }
}

// Remove note with matching id
$notes = array_filter($notes, function($note) use ($note_id) {
    return $note['id'] !== $note_id;
});

// Re-index array
$notes = array_values($notes);

// Save back to database
$notes_json = json_encode($notes);
$update_sql = "UPDATE $tbl_family SET admin_notes = ? WHERE id = ?";
$update_stmt = mysqli_prepare($con, $update_sql);
mysqli_stmt_bind_param($update_stmt, "si", $notes_json, $member_id);

if (mysqli_stmt_execute($update_stmt)) {
    mysqli_stmt_close($update_stmt);
    echo json_encode(['success' => true, 'message' => 'Note deleted successfully']);
} else {
    mysqli_stmt_close($update_stmt);
    echo json_encode(['success' => false, 'message' => 'Failed to delete note']);
}
?>


