<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$member_id = isset($_GET['member_id']) ? trim($_GET['member_id']) : '';

if (empty($member_id)) {
    echo json_encode(['success' => false, 'message' => 'Member ID is required']);
    exit;
}

global $con, $tbl_family;

// Search by member_id field
$sql = "SELECT id, member_id, name, father_name, current_address, permanent_address, 
               village, district, mobile_no 
        FROM $tbl_family 
        WHERE member_id = ? AND deleted = 0 
        LIMIT 1";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $member_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$member = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($member) {
    echo json_encode([
        'success' => true,
        'member' => [
            'id' => $member['id'],
            'member_id' => $member['member_id'],
            'name' => $member['name'],
            'father_name' => $member['father_name'],
            'current_address' => $member['current_address'] ?: $member['permanent_address'],
            'village' => $member['village'],
            'district' => $member['district'],
            'mobile_no' => $member['mobile_no']
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Member ID not found. Please check and try again.'
    ]);
}

