<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$member_id = $_GET['member_id'] ?? '';

if (empty($member_id)) {
    echo json_encode(['success' => false, 'message' => 'Member ID is required']);
    exit();
}

global $con, $tbl_family;

$sql = "SELECT id, name, father_name, mother_name, village FROM $tbl_family WHERE member_id = ? AND deleted = 0";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 's', $member_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode([
        'success' => true,
        'member' => [
            'id' => $row['id'],
            'name' => $row['name'],
            'father_name' => $row['father_name'],
            'mother_name' => $row['mother_name'],
            'village' => $row['village']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Member not found']);
}

mysqli_stmt_close($stmt);
?>

