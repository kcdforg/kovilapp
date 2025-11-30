<?php
include('../init.php');
check_login();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (isset($_GET['id']) && isset($_GET['h_image'])) {
    $member_id = (int)$_GET['id'];
    $image_filename = $_GET['h_image'];

    if ($member_id > 0) {
        $image_path = dirname(__DIR__) . "/images/member/" . $image_filename;

        // Check if the file exists and delete it
        if (!empty($image_filename) && file_exists($image_path)) {
            unlink($image_path);
        }
        
        // Update the database to clear the image field
        $sql = "UPDATE $tbl_family SET image = '' WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $member_id);

        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Husband photo deleted successfully.';
        } else {
            $response['message'] = 'Failed to update database.';
        }
        mysqli_stmt_close($stmt);
    } else {
        $response['message'] = 'Invalid member ID.';
    }
} else {
    $response['message'] = 'Required parameters missing.';
}

echo json_encode($response);
?>
