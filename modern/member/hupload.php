<?php
include('../popupheader.php');
$id = $_GET['id'];
$s = $id . "_husband" . ".jpg";
if ($_FILES["image"]["error"] > 0) {
    echo "Return Code: " . $_FILES["image"]["error"] . "<br />";
} else {
    //echo "images: " . $_FILES["image"]["$s"] . "<br />";
    //echo "Type: " . $_FILES["image"]["type"] . "<br />";
    //echo "Size: " . ($_FILES["image"]["size"] / 1024) . " Kb <br />";
    //echo "Temp file: " . $_FILES["image"]["tmp_name"] . " <br />";
    //var_dump($_FILES["image"]);
    if ($_FILES["image"]["type"] != 'image/jpeg') {
        echo "Image should be in jpeg format";
        die;
    } else {
        if (file_exists("../images/" . $s)) {
            echo $_FILES["image"]["name"] . " already exists. ";
        } else {
            move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $s);
            // echo "Stored in:" . "images/" . $s; 
            ?>
            <div class="callout callout-danger">
                <h4>Upload success!</h4>
            </div>
            <?php
        }
    }
    
    $sql = "UPDATE `$tbl_family` SET `image`='$s' where `id`=$id";
    if (!mysql_query($sql, $con)) {
        die('Error: ' . mysql_error());
    }
//echo "Successfully photo Updated";
}
include('../footer.php');
?>