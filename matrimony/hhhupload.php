<?php
include('../popupheader.php');
$id = $_GET['id'];
$s = $id . "_horo" . ".jpg";
if ($_FILES["horo"]["error"] > 0) {
    echo "Return Code: " . $_FILES["horo"]["error"] . "<br />";
} else {
    //echo "images: " . $_FILES["image"]["$s"] . "<br />";
    //echo "Type: " . $_FILES["image"]["type"] . "<br />";
    //echo "Size: " . ($_FILES["image"]["size"] / 1024) . " Kb <br />";
    //echo "Temp file: " . $_FILES["image"]["tmp_name"] . " <br />";
    //var_dump($_FILES["image"]);
    if ($_FILES["horo"]["type"] != 'image/jpeg') {
        echo "Image should be in jpeg format";
        die;
    } else {
        if (file_exists("../attachments/" . $s)) {
            echo $_FILES["horo"]["name"] . " already exists. ";
        } else {
            move_uploaded_file($_FILES["horo"]["tmp_name"], "../attachments/" . $s);
            // echo "Stored in:" . "images/" . $s; 
            ?>
            <div class="callout callout-danger">
                <h4>Upload success!</h4>
            </div>
            <?php
        }
    }
    
//$image=$_FILES["image"]["$s"];
//echo $image;
    
//else 
    //echo "sucessfully connected";
    $sql = "UPDATE `$tbl_matrimony` SET `horo`='$s' where `id`=$id";
    if (!mysql_query($sql, $con)) {
        die('Error: ' . mysql_error());
    }
//echo "Successfully photo Updated";
}
?>