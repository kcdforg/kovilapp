<?php
include('../../init.php');
check_login();

header('Content-Type: application/json');

$group_id = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;

if ($group_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid group ID']);
    exit;
}

if (empty($search)) {
    echo json_encode(['success' => false, 'message' => 'Search term is required']);
    exit;
}

try {
    // Search members in the group
    $result = get_members_by_group($group_id, 1, $per_page, $search);
    
    echo json_encode([
        'success' => true,
        'members' => $result['members'],
        'total' => $result['total'],
        'search' => $search
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error searching members: ' . $e->getMessage()
    ]);
}

