<?php
include('../init.php');
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $con, $tbl_labels;
    
    $display_name = mysqli_real_escape_string($con, $_POST['display_name']);
    $slug = mysqli_real_escape_string($con, $_POST['slug']);
    $order = (int)$_POST['order'];
    
    // Insert type (type = 0 means it's a type, not a label)
    $sql = "INSERT INTO $tbl_labels (display_name, slug, type, category, parent_id, `order`) 
            VALUES ('$display_name', '$slug', 0, '', 0, $order)";
    
    if (mysqli_query($con, $sql)) {
        header("Location: labels.php?success=1&message=" . urlencode("Type added successfully!"));
    } else {
        header("Location: labels.php?error=1&message=" . urlencode("Error adding type: " . mysqli_error($con)));
    }
    exit();
} else {
    header("Location: labels.php");
    exit();
}
?>

