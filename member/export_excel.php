<?php
/**
 * Export to Excel - Member List
 * 
 * Exports all members to Excel format (CSV/XLS compatible)
 * No external libraries required
 */

require_once('../init.php');
check_login();

// Apply same filters as memberlist.php
$where = [];
$params = [];
$param_types = '';

if (!empty($_GET['search'])) {
    $where[] = "CONVERT(name USING utf8) COLLATE utf8_general_ci LIKE ?";
    $params[] = '%' . $_GET['search'] . '%';
    $param_types .= 's';
}
if (!empty($_GET['village'])) {
    $where[] = "CONVERT(village USING utf8) COLLATE utf8_general_ci = ?";
    $params[] = $_GET['village'];
    $param_types .= 's';
}
if (!empty($_GET['kattalai'])) {
    $where[] = "kattalai = ?";
    $params[] = $_GET['kattalai'];
    $param_types .= 'i';
}
if (!empty($_GET['filter_member_id'])) {
    $where[] = "CONVERT(member_id USING utf8) COLLATE utf8_general_ci LIKE ?";
    $params[] = '%' . $_GET['filter_member_id'] . '%';
    $param_types .= 's';
}
if (!empty($_GET['filter_name'])) {
    $where[] = "CONVERT(name USING utf8) COLLATE utf8_general_ci LIKE ?";
    $params[] = '%' . $_GET['filter_name'] . '%';
    $param_types .= 's';
}
if (!empty($_GET['filter_mobile'])) {
    $where[] = "CONVERT(mobile_no USING utf8) COLLATE utf8_general_ci LIKE ?";
    $params[] = '%' . $_GET['filter_mobile'] . '%';
    $param_types .= 's';
}
if (!empty($_GET['filter_village'])) {
    $where[] = "CONVERT(village USING utf8) COLLATE utf8_general_ci LIKE ?";
    $params[] = '%' . $_GET['filter_village'] . '%';
    $param_types .= 's';
}
if (!empty($_GET['filter_kattalai'])) {
    $where[] = "kattalai = ?";
    $params[] = $_GET['filter_kattalai'];
    $param_types .= 'i';
}

$where[] = "deleted = 0";
$where_sql = 'WHERE ' . implode(' AND ', $where);

// Get all matching records
$sql = "SELECT id, member_id, name, w_name, father_name, mobile_no, email, dob, w_dob, 
        village, taluk, district, state, pincode, 
        permanent_address, current_address,
        c_village, c_taluk, c_district, c_state, c_pincode,
        kattalai, blood_group, w_blood_group, qualification, w_qualification,
        occupation, w_occupation, education_details, occupation_details,
        family_name, remarks, created_date
        FROM $tbl_family $where_sql ORDER BY id ASC";

$stmt = $con->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$members = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Set headers for Excel download
$filename = 'member_report_' . date('Y-m-d_His') . '.xls';
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Output BOM for UTF-8
echo "\xEF\xBB\xBF";

// Start Excel XML
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<?mso-application progid="Excel.Sheet"?>';
echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
echo '<Worksheet ss:Name="Member Report">';
echo '<Table>';

// Header Row
echo '<Row>';
echo '<Cell><Data ss:Type="String">S.No</Data></Cell>';
echo '<Cell><Data ss:Type="String">Member ID</Data></Cell>';
echo '<Cell><Data ss:Type="String">Name</Data></Cell>';
echo '<Cell><Data ss:Type="String">Wife Name</Data></Cell>';
echo '<Cell><Data ss:Type="String">Father Name</Data></Cell>';
echo '<Cell><Data ss:Type="String">Mobile</Data></Cell>';
echo '<Cell><Data ss:Type="String">Email</Data></Cell>';
echo '<Cell><Data ss:Type="String">DOB</Data></Cell>';
echo '<Cell><Data ss:Type="String">Age</Data></Cell>';
echo '<Cell><Data ss:Type="String">Blood Group</Data></Cell>';
echo '<Cell><Data ss:Type="String">Village</Data></Cell>';
echo '<Cell><Data ss:Type="String">Taluk</Data></Cell>';
echo '<Cell><Data ss:Type="String">District</Data></Cell>';
echo '<Cell><Data ss:Type="String">State</Data></Cell>';
echo '<Cell><Data ss:Type="String">Pincode</Data></Cell>';
echo '<Cell><Data ss:Type="String">Permanent Address</Data></Cell>';
echo '<Cell><Data ss:Type="String">Current Address</Data></Cell>';
echo '<Cell><Data ss:Type="String">Kattalai</Data></Cell>';
echo '<Cell><Data ss:Type="String">Qualification</Data></Cell>';
echo '<Cell><Data ss:Type="String">Occupation</Data></Cell>';
echo '<Cell><Data ss:Type="String">Family Name</Data></Cell>';
echo '<Cell><Data ss:Type="String">Remarks</Data></Cell>';
echo '<Cell><Data ss:Type="String">Created Date</Data></Cell>';
echo '</Row>';

// Data Rows
$sno = 1;
foreach ($members as $member) {
    // Calculate age
    $age = '';
    if (!empty($member['dob']) && $member['dob'] != '0000-00-00') {
        $dob = new DateTime($member['dob']);
        $now = new DateTime();
        $age = $now->diff($dob)->y;
    }
    
    // Get kattalai name
    $kattalai_name = '';
    if (!empty($member['kattalai'])) {
        $kattalai_label = get_label($member['kattalai']);
        $kattalai_name = $kattalai_label['display_name'] ?? '';
    }
    
    // Get qualification name
    $qualification_name = '';
    if (!empty($member['qualification'])) {
        $qual = get_label($member['qualification']);
        $qualification_name = $qual['display_name'] ?? '';
    }
    
    // Get occupation name
    $occupation_name = '';
    if (!empty($member['occupation'])) {
        $occ = get_label($member['occupation']);
        $occupation_name = $occ['display_name'] ?? '';
    }
    
    // Determine current address
    $current_address = '';
    if (!empty($member['current_address'])) {
        $current_address = $member['current_address'];
    } else {
        $addr_parts = array_filter([
            $member['c_village'] ?? '',
            $member['c_taluk'] ?? '',
            $member['c_district'] ?? '',
            $member['c_state'] ?? '',
            $member['c_pincode'] ?? ''
        ]);
        $current_address = implode(', ', $addr_parts);
    }
    
    echo '<Row>';
    echo '<Cell><Data ss:Type="Number">' . $sno . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['member_id'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['name'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['w_name'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['father_name'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['mobile_no'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['email'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['dob'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="Number">' . $age . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['blood_group'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['village'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['taluk'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['district'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['state'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['pincode'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['permanent_address'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($current_address) . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($kattalai_name) . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($qualification_name) . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($occupation_name) . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['family_name'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['remarks'] ?? '') . '</Data></Cell>';
    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($member['created_date'] ?? '') . '</Data></Cell>';
    echo '</Row>';
    
    $sno++;
}

echo '</Table>';
echo '</Worksheet>';
echo '</Workbook>';
?>

