<?php
$book_id=$_GET['book_id'];
include('../popupheader.php');
?>
<section class="content-header">
        <h1>
         Delete book
        </h1>
      </section>
	  <section class="content">       
        <div class="box box-default">
		<div class="box-body">
 <label>Are you sure want to delete this book?</label>
 <br>
 <br>
					<form action="deletebook.php" method="POST">
					<input type="hidden" name="book_id" value="<?php echo $book_id ?>"> 
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