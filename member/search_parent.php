<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

global $con, $tbl_family;

$member_id = $_GET['member_id'] ?? '';
$name = $_GET['name'] ?? '';
$mobile = $_GET['mobile'] ?? '';
$village = $_GET['village'] ?? '';

// Build WHERE clause
$conditions = ['deleted = 0'];
$params = [];
$types = '';

if (!empty($member_id)) {
    $conditions[] = "member_id LIKE ?";
    $params[] = '%' . $member_id . '%';
    $types .= 's';
}

if (!empty($name)) {
    $conditions[] = "(name LIKE ? OR w_name LIKE ?)";
    $params[] = '%' . $name . '%';
    $params[] = '%' . $name . '%';
    $types .= 'ss';
}

if (!empty($mobile)) {
    $conditions[] = "mobile_no LIKE ?";
    $params[] = '%' . $mobile . '%';
    $types .= 's';
}

if (!empty($village)) {
    $conditions[] = "(village LIKE ? OR c_village LIKE ? OR k_village LIKE ?)";
    $params[] = '%' . $village . '%';
    $params[] = '%' . $village . '%';
    $params[] = '%' . $village . '%';
    $types .= 'sss';
}

// Check if at least one search criteria is provided
if (count($conditions) <= 1) {
    echo json_encode(['success' => false, 'message' => 'Please provide at least one search criteria']);
    exit();
}

$whereClause = implode(' AND ', $conditions);
$sql = "SELECT id, member_id, name, father_name, mobile_no, village FROM $tbl_family WHERE $whereClause LIMIT 50";

$stmt = mysqli_prepare($con, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$members = [];
while ($row = mysqli_fetch_assoc($result)) {
    $members[] = [
        'id' => $row['id'],
        'member_id' => $row['member_id'],
        'name' => $row['name'],
        'father_name' => $row['father_name'],
        'mobile_no' => $row['mobile_no'],
        'village' => $row['village']
    ];
}

mysqli_stmt_close($stmt);

echo json_encode([
    'success' => true,
    'members' => $members
]);
?>

