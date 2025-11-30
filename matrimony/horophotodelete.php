<?php
$id = $_GET['id'];
$file_name = $_GET['photo'];
$s = $id . "_photo";
include('../popupheader.php');
?>
<section class="content-header">
    <h1>
        Delete profile photo 
    </h1>
</section>
<section class="content">       
    <div class="box box-default">
        <div class="box-body">
            <label>Are you sure want to delete this photo?</label>
            <br>
            <br>
            <form action="photodelete.php" method="POST">
                <input type="hidden" name="photo" value="<?php echo $file_name ?>">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <button type="submit" >Yes</button>
                <button type="submit" onclick="window.close()">No</button>
            </form>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</section>