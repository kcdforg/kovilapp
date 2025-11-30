<?php
include('../init.php');
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $label_id = intval($_POST['label_id']);
    $display_name = mysqli_real_escape_string($con, $_POST['display_name']);
    $slug = mysqli_real_escape_string($con, $_POST['slug']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $parent_id = intval($_POST['parent_id']);
    $order = intval($_POST['order']);
    
    $sql = "UPDATE $tbl_labels SET display_name = ?, slug = ?, type = ?, category = ?, parent_id = ?, `order` = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssssiii", $display_name, $slug, $type, $category, $parent_id, $order, $label_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: labels.php?success=1&message=Label updated successfully!");
    } else {
        header("Location: labels.php?error=1&message=Error updating label: " . mysqli_error($con));
    }
    mysqli_stmt_close($stmt);
    exit;
}

// If not POST, redirect back
header("Location: labels.php");
exit;
?> 