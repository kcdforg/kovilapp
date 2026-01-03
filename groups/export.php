<?php
include('../init.php');
check_login();

$group_id = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;
$format = isset($_GET['format']) ? $_GET['format'] : 'csv';

if ($group_id <= 0) {
    die('Invalid group ID');
}

$group = get_group($group_id);
if (!$group) {
    die('Group not found');
}

$group_type = get_group_type($group['group_type_id']);

// Get all members
$result = get_members_by_group($group_id, 1, 10000, '');
$members = $result['members'];

// Generate filename
$filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $group_type['name'] . '_' . $group['name']);
$filename .= '_Members_' . date('Y-m-d');

if ($format === 'excel') {
    // Excel format (simple HTML table that Excel can open)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
    header('Cache-Control: max-age=0');
    
    echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">';
    echo '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>';
    echo '<body>';
    echo '<table border="1">';
    echo '<tr><th>#</th><th>Member ID</th><th>Name</th><th>Father\'s Name</th><th>Mobile</th><th>Village</th></tr>';
    
    foreach ($members as $i => $member) {
        echo '<tr>';
        echo '<td>' . ($i + 1) . '</td>';
        echo '<td>' . htmlspecialchars($member['member_id'] ?? '-') . '</td>';
        echo '<td>' . htmlspecialchars($member['name']) . '</td>';
        echo '<td>' . htmlspecialchars($member['father_name'] ?? '-') . '</td>';
        echo '<td>' . htmlspecialchars($member['mobile_no'] ?? '-') . '</td>';
        echo '<td>' . htmlspecialchars($member['village'] ?? '-') . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</body></html>';
} else {
    // CSV format
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
    header('Cache-Control: max-age=0');
    
    // UTF-8 BOM for Excel compatibility
    echo "\xEF\xBB\xBF";
    
    $output = fopen('php://output', 'w');
    
    // Header row
    fputcsv($output, ['#', 'Member ID', 'Name', 'Father\'s Name', 'Mobile', 'Village']);
    
    // Data rows
    foreach ($members as $i => $member) {
        fputcsv($output, [
            $i + 1,
            $member['member_id'] ?? '-',
            $member['name'],
            $member['father_name'] ?? '-',
            $member['mobile_no'] ?? '-',
            $member['village'] ?? '-'
        ]);
    }
    
    fclose($output);
}

