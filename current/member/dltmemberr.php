<?php
$id = $_GET['id'];
include('../popupheader.php');
?>
<section class="content-header">
    <h1>
        Delete member
    </h1>
</section>
<section class="content">       
    <div class="box box-default">
        <div class="box-body">
            <label>Are you sure you want to delete this member?</label>
            <br>
            <br>
            <form action="deletememberr.php" method="POST">
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