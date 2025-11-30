<?php
include('../init.php');
check_login();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $label_id = intval($_GET['id']);
    
    // Get label name for the message
    $sql = "SELECT display_name FROM $tbl_labels WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $label_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $label = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    $label_name = $label ? $label['display_name'] : 'Unknown';
    
    // Delete the label
    $sql = "DELETE FROM $tbl_labels WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $label_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: labels.php?success=1&message=Label '$label_name' deleted successfully!");
    } else {
        header("Location: labels.php?error=1&message=Error deleting label: " . mysqli_error($con));
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: labels.php?error=1&message=Invalid label ID");
}

exit;
?> 