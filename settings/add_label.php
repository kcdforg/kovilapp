<?php
include('../init.php');
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $display_name = mysqli_real_escape_string($con, $_POST['display_name']);
    $slug = mysqli_real_escape_string($con, $_POST['slug']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $parent_id = intval($_POST['parent_id']);
    $order = intval($_POST['order']);
    
    $sql = "INSERT INTO $tbl_labels (display_name, slug, type, category, parent_id, `order`) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssssii", $display_name, $slug, $type, $category, $parent_id, $order);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: labels.php?success=1&message=Label added successfully!");
    } else {
        header("Location: labels.php?error=1&message=Error adding label: " . mysqli_error($con));
    }
    mysqli_stmt_close($stmt);
    exit;
}

// If not POST, redirect back
header("Location: labels.php");
exit;
?> 