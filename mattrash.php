<?php
include('function.php');
include('header.php');

if (($_SESSION['username'])!='admin')
{
 echo "<br>"."<br>"."<br>".'You are not authorized to visit this page!';
 die;
}
if (!isset($_SESSION['username']))
{
 echo 'You are not logged in<a href="index.php">Visit Login Page</a>';
 die; 
}
$con=connectdb();
mysqli_set_charset($con, 'utf8');
mysqli_select_db($con, "unicode");
//$start_from = ($page-1) * $num_rec_per_page; 
$sql ="SELECT * FROM matrimony WHERE `deleted`=1"; 
$result = mysqli_query($con, $sql);
//$result = mysql_query("SELECT * FROM matrimony");

//$result1 = mysql_query("SELECT * FROM attachments WHERE `type`='photo'");
//$row1 = mysql_fetch_array($result1);
/*$query = "SELECT count(*) AS total FROM $users"; 
$count = mysql_query($query); 
$values = mysql_fetch_assoc($count); 
$num_rows = $values['total']; 
echo "<b>Total no.of users=$num_rows</b>";*/
?> 			
    <!-- Content Header (Page header) -->
    <section class="content-header">
     <div class="container-fluid">
  <h2 class="container text-center">Horoscope List in trash </h2>
  </div>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box table-responsive">
           <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr> 
				 <th width="7%">Photo</th>                
				 <th class="col-sm-2">Personal details</th>
				 <th class="col-sm-2">Horo details</th>
				 <th class="col-sm-1">status</th>
				 <th class="col-sm-2">Address</th>				 
				 <th class="col-sm-2">Other Details</th>				 
				 <th width="11%">Action</th>					
                </tr>		
                </thead>
                <tbody>
               	<?php				
while($row = mysqli_fetch_array($result))
{
?>
		<tr> 
			<td><img src="attachments/<?php echo $row['photo'] ?>" width="100" height="100" class="img-responsive"/></td>      
			<td><?php echo $row['name']."<br>".$row['qualification']."<br>".$row['occupation'] ?></td>
			<td><?php echo $row['raasi']."<br>".$row['star']."<br>".$row['raaghu_kaedhu']."<br>".$row['sevvai'] ?></td>
			<td><?php echo $row['status'] ?></td>
			<td><?php echo $row['mobile_no']."<br>".$row['email']."<br>".$row['address'] ?></td>
			<td><?php echo $row['kulam']."<br>".$row['temple']."<br>".$row['height']."<br>".$row['weight']?></td>
			<td><a href="#" id="a" onclick="deletehoroscope()"> <span class="glyphicon glyphicon-trash"></span></a></td>
		</tr>
		<script>
                function deletehoroscope()
                {
                    url = "dlthoroscope.php?id=<?php echo $row['id']?>";
                    title = "popup";
                    var newWindow = window.open(url, title, 'scrollbars=yes, width=1000 height=500');
                }
            </script> 
						<?php
}
mysqli_close($con);

?>
		
		

		        </tbody>
                </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
<?php
include('footer.php');
?>


