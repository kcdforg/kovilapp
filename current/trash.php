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
$sql = "SELECT * FROM $family WHERE `deleted`=1"; 
$result = mysqli_query($con, $sql);
$family1 = array();
//$i=0;
while($row = mysqli_fetch_array($result))
{	
$fam ['name'] =($row['name']);	
$fam ['mobile_no']  =($row['mobile_no']);
$fam ['w_name'] =($row['w_name']);
$fam ['father_name']=($row['father_name']);
$fam ['mother_name']=($row['mother_name']);
$fam ['permanent_address']=($row['permanent_address']);
$family1[$row['id']]=$fam;
	//$i++;
	//echo count($fam);
}
//var_dump($family1[10]);
$sql1 = "SELECT * from $child "; 
$result1 = mysqli_query($con, $sql1);
while($row = mysqli_fetch_array($result1))
{
$children['c_name']=$row['c_name'];	
$children['c_mobile_no']=$row['c_mobile_no'];	
$children1[$row['father_id']][]=$children;
//$children[$row['father_id']][] ['c_name']=$row['c_name'];	
//$children[$row['father_id']][] ['c_mobile_no']=$row['c_mobile_no'];	
}
//echo count($children);
//var_dump($children1[11]);
?>	
<br>

<div style="margin-left:20px;">	
<?php
$query = "SELECT count(*) AS total FROM  $family"; 
$count = mysqli_query($con, $query); 
$values = mysqli_fetch_assoc($count); 
$num_rows = $values['total']; 
//echo "<b>Families=$num_rows</b>";
?>
</div>
  <div id="list" class="col-md-12">
  <div id="list" class="col-md-2">
			<div class="row1">
	         <h3 class="box-title"><b><center>Trash </center></b></h3>
			 </div>
			 <ul class="sidebar-menu">
        <li>
          <a href="memtrash.php">
            <i class="fa fa-th"></i> <span>Member</span>
          </a>
        </li>
		 <li>
          <a href="mattrash.php">
            <i class="fa fa-th"></i> <span>Matrimony</span>
          </a>
        </li>  
      </ul>

  </div>
    <!-- Content Header (Page header) -->
   
    <!-- Main content -->
		
      <!-- /.row -->
	  </div>
    <!-- /.content --><div style="clear:both"></div>  
<?php
include('footer.php');
?>