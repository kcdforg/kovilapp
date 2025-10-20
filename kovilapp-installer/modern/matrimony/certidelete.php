<?php
$id = $_GET['id'];
$file_name = $_GET['certificate'];
$s = $id . "_certificate";
include('../popupheader.php');
?>
<section class="content-header">
    <h1>
        Delete Certificate
    </h1>
</section>
<section class="content">       
    <div class="box box-default">
        <div class="box-body">
            <label>Are you sure want to delete this certificate?</label>
            <br>
            <br>
            <form action="certifdelete.php" method="POST">
                <input type="hidden" name="certificate" value="<?php echo $file_name ?>">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <button type="submit" >Yes</button>
                <button type="submit" onclick="window.close()">No</button>
            </form>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</section>
<?php
include('../footer.php');
?>