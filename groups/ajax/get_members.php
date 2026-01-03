<?php
include('../../init.php');
check_login();

header('Content-Type: application/json');

$group_id = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 25;

if ($group_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid group ID']);
    exit;
}

try {
    $result = get_members_by_group($group_id, $page, $per_page, $search);
    
    echo json_encode([
        'success' => true,
        'members' => $result['members'],
        'total' => $result['total'],
        'page' => $page,
        'per_page' => $per_page,
        'total_pages' => ceil($result['total'] / $per_page)
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching members: ' . $e->getMessage()
    ]);
}

