<?php
/**
 * Print Report - HTML Print-Friendly Member List
 * 
 * Displays all members in a clean, print-optimized format
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
$sql = "SELECT id, member_id, name, w_name, father_name, mobile_no, dob, w_dob, 
        village, taluk, district, state, pincode, 
        c_village, c_taluk, c_district, c_state, c_pincode,
        kattalai, blood_group, w_blood_group, qualification, occupation
        FROM $tbl_family $where_sql ORDER BY id ASC";

$stmt = $con->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$members = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$total_members = count($members);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Report - <?php echo date('d-m-Y'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12pt;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24pt;
        }
        
        .header .subtitle {
            margin: 5px 0;
            color: #666;
            font-size: 14pt;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background: #4e73df;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10pt;
            font-weight: bold;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10pt;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10pt;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .no-print {
            margin-bottom: 20px;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            
            body {
                margin: 0;
            }
            
            @page {
                margin: 1cm;
            }
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            thead {
                display: table-header-group;
            }
            
            th {
                background: #333 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4e73df; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14pt;">
            🖨️ Print Report
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14pt; margin-left: 10px;">
            ❌ Close
        </button>
    </div>

    <div class="header">
        <h1>அருள்மிகு புது வெங்கரை அம்மன் கோயில்</h1>
        <div class="subtitle">Member Directory Report</div>
        <div class="subtitle">Generated on: <?php echo date('d-m-Y H:i:s'); ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">S.No</th>
                <th style="width: 12%;">Member ID</th>
                <th style="width: 25%;">Name</th>
                <th style="width: 15%;">Mobile</th>
                <th style="width: 8%;">Age</th>
                <th style="width: 17%;">Village</th>
                <th style="width: 18%;">Current Address</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sno = 1;
            foreach ($members as $member): 
                // Calculate age
                $age = '';
                if (!empty($member['dob']) && $member['dob'] != '0000-00-00') {
                    $dob = new DateTime($member['dob']);
                    $now = new DateTime();
                    $age = $now->diff($dob)->y;
                }
                
                // Build full name with wife
                $full_name = htmlspecialchars($member['name']);
                if (!empty($member['w_name'])) {
                    $full_name .= '<br><small style="color: #666;">& ' . htmlspecialchars($member['w_name']) . '</small>';
                }
                
                // Current address
                $current_address = htmlspecialchars($member['c_village'] ?? $member['village'] ?? '-');
            ?>
            <tr>
                <td><?php echo $sno; ?></td>
                <td><?php echo htmlspecialchars($member['member_id'] ?? '-'); ?></td>
                <td><?php echo $full_name; ?></td>
                <td><?php echo htmlspecialchars($member['mobile_no'] ?? '-'); ?></td>
                <td><?php echo $age; ?></td>
                <td><?php echo htmlspecialchars($member['village'] ?? '-'); ?></td>
                <td><?php echo $current_address; ?></td>
            </tr>
            <?php 
            $sno++;
            endforeach; 
            ?>
        </tbody>
    </table>

    <div class="footer">
        End of Report - Total <?php echo $total_members; ?> members listed<br>
        Generated by Kovil App on <?php echo date('d-m-Y H:i:s'); ?>
    </div>
</body>
</html>

