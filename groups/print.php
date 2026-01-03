<?php
include('../init.php');
check_login();

$group_id = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;

if ($group_id <= 0) {
    die('Invalid group ID');
}

$group = get_group($group_id);
if (!$group) {
    die('Group not found');
}

$group_type = get_group_type($group['group_type_id']);

// Get all members (no pagination for print)
$result = get_members_by_group($group_id, 1, 10000, '');
$members = $result['members'];
$total = $result['total'];

// Get organization name from settings
$org_name = get_setting('org_name', 'Organization Name');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($group_type['name'] . ': ' . $group['name']); ?> - Member List</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 14px;
            font-weight: normal;
            color: #666;
        }
        .header .date {
            font-size: 11px;
            color: #888;
            margin-top: 5px;
        }
        .summary {
            margin-bottom: 15px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #888;
            margin-top: 20px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Print</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Close</button>
    </div>
    
    <div class="header">
        <h1><?php echo htmlspecialchars($org_name); ?></h1>
        <h2><?php echo htmlspecialchars($group_type['name']); ?>: <?php echo htmlspecialchars($group['name']); ?> - Member List</h2>
        <div class="date">Generated on: <?php echo date('d-M-Y h:i A'); ?></div>
    </div>
    
    <div class="summary">
        <strong>Total Members:</strong> <?php echo $total; ?>
    </div>
    
    <?php if (empty($members)): ?>
    <p>No members in this group.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Member ID</th>
                <th>Name</th>
                <th>Father's Name</th>
                <th>Mobile</th>
                <th>Village</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $i => $member): ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo htmlspecialchars($member['member_id'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($member['name']); ?></td>
                <td><?php echo htmlspecialchars($member['father_name'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($member['mobile_no'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($member['village'] ?? '-'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    
    <div class="footer">
        Printed from Member Management System
    </div>
</body>
</html>

