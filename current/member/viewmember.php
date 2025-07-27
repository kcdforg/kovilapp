<?php
include('../header.php');
$id = $_GET['id'];
$where = '';
$rec_per_page = 20;
$curr_page = 1;
$offset = ($curr_page - 1) * ($rec_per_page);

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'generate_id') {
        $member_id = isset($_POST['id']) ? $_POST['id'] : $id;
        generate_member_id($member_id);
    }
}

$row = get_member($id);

$sql_total = "SELECT count(*) as total FROM $tbl_family where `deleted`=0 $where";
global $con; // ensure connection is available
$result = mysqli_query($con, $sql_total);
$row1 = mysqli_fetch_assoc($result);
$total_records = $row1['total'];
//echo $total_records . "<br>";
$total_pages = ceil($total_records / $rec_per_page);
//echo $total_pages;
?>

<style>
    .nav-tabs-custom>.nav-tabs>li.active>a  {

        background-color:green;
        color:white;
        border-radius:15px 15px 0px 0px;

    }
    .nav-tabs-custom>.nav-tabs>li {
        margin-bottom:-3px;
    }

    .nav-tabs-custom>.nav-tabs>li.active>a:hover {

        background-color:orange;
        color:black;
        border-radius:15px 15px 0px 0px;
    }

    .nav-tabs-custom>.nav-tabs>li>a:hover{
        background-color:orange;
        color:black;
        border-radius:15px 15px 0px 0px;
    }
    .nav-tabs-custom>.nav-tabs>li.active{
        border-top:none;
    }

    .nav-tabs-custom>.nav-tabs {
        border-bottom:2px solid green;
    }

    .table>thead:first-child>tr:first-child>th {
        border-top: 0;
        background: #e4ece7;
    }
    #bb.box-body,th {
        margin-top: 35px;
        width:75%;
        border-radius: 8px;
        padding: 0px;
        margin-left: 150px;
        margin-right: 150px;
        border: 2px solid #d4d4d4;
    }
    tbody#tbody > tr > td {
        border-top: 2px solid #d4d4d4;
    }
    #ph.panel-heading{
        text-align: center;
    }
    #ppd{
        border:0px solid white;
    }
    
    /* Custom styling for action buttons */
    .btn-action {
        padding-left: 8px !important;
        padding-right: 12px !important;
    }
</style>

<div class="col-sm-12" style="background: white">
    <div class="row col-sm-12" style="border-bottom:2px solid #3c8dbc; margin-bottom:10px">   
        <h3 style="padding-left:25px"> <?php echo $row['name'] ?> குடும்பம்
            <div style="float:right;padding-right:20px">
                <a href="updatemember.php?id=<?php echo $id ?>">
                    <button class="btn btn-info pull-right btn-action"><span class="glyphicon glyphicon-edit"></span> Edit</button>
                </a>
                <a href="#" onclick="deletemember(<?php echo $row['id'] ?>)">
                    <button class="btn btn-info pull-right btn-action"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                </a>
            </div>	
        </h3>  </div>	

    <script>
        function deletemember(id)
        {
            url = "deletemember.php?id=" + id;
            title = "popup";
            var newWindow = window.open(url, title, 'scrollbars=yes, width=600, height=500');
        }

    </script> 	   
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#Profile" data-toggle="tab"><b>Profile</b></a></li>
            <li><a href="#Horos" data-toggle="tab"><b>Horoscope</b></a></li>
            <?php /*  <li><a href="#Donation" data-toggle="tab"><b>Donation</b></a></li>    */ ?>    
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="Profile">        

                <div class="col-sm-7">
                    <div class="panel panel-default" >
                        <div class="panel-heading">
                            <b>குடும்ப தலைவர் </b>
                        </div>
                        <div class="panel-body" >
                            <div class="col-sm-3">
                                <img src="../images/member/<?php echo $row['image'] ?>"width="140" height="110"  class="img-responsive"/>
                                <button onclick="husband()">Upload</button>
                                <button onclick="himagedelete()">Delete</button>

                                <script>
                                    function husband()
                                    {
                                        //alert("Do you want to upload husband photo?");
                                        url = "himageupload.php?id=<?php echo $id ?>";
                                        title = "popup";
                                        var newWindow = window.open(url, title, 'scrollbars=yes, width=600, height=400');
                                    }
                                </script>
                                <script>
                                    function himagedelete()
                                    {
                                        url = "himagedelete.php?id=<?php echo $row['id'] ?> &h_image=<?php echo $row['image'] ?>";
                                        title = "popup";
                                        var newWindow = window.open(url, title, 'scrollbars=yes, width=600, height=400');
                                    }
                                </script>
                                <script>
                                    function wife()
                                    {
                                        //alert("Do you want to upload wife photo?");
                                        url = "wimageupload.php?id=<?php echo $id ?>";
                                        title = "popup";
                                        var newWindow = window.open(url, title, 'scrollbars=yes, width=600, height=400');
                                    }
                                </script>
                                <script>
                                    function wdelete()
                                    {
                                        url = "wimagedelete.php?id=<?php echo $row['id'] ?> &w_image=<?php echo $row['w_image'] ?>";
                                        title = "popup";
                                        var newWindow = window.open(url, title, 'scrollbars=yes, width=600, height=400');
                                    }
                                </script>		
                            </div>
                            <div class="col-sm-9">
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b> Name </b> </label> </div>  <div class="col-sm-8" > <?php display("name", $row) ? "name" : " " ?> </div></div>                       
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b> Fathers name </b> </label> </div>  <div class="col-sm-8"  ><?php display("father_name", $row) ? "father_name" : " " ?> </div></div>                       
                                        <div class="clear"></div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b> Mothers name </b> </label> </div>  <div class="col-sm-8"  ><?php display("mother_name", $row) ? "mother_name" : " " ?> </div></div>                       
                                        <div class="clear"></div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b>Education </b> </label> </div>  <div class="col-sm-8"  ><?php echo get_qualification($row['qualification']) ?></div></div>                       
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b>Education Details </b> </label> </div>  <div class="col-sm-8"  ><?php display("education_details", $row) ? "education_details" : " " ?> </div></div>                       
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b>Occupation </b> </label> </div>  <div class="col-sm-8"  ><?php echo get_occupation($row['occupation']) ?></div></div>         
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b>Occupation Details</b> </label> </div>  <div class="col-sm-8"  ><?php display("occupation_details", $row) ? "occupation_details" : " " ?></div></div>                   
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b>E-mail </b> </label> </div>  <div class="col-sm-8"  ><?php display("email", $row) ? "email" : " " ?> </div></div>                       
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b>Mobile no </b> </label> </div>  <div class="col-sm-8"  ><?php display("mobile_no", $row) ? "mobile_no" : " " ?> </div></div>                       
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default" >
                        <div class="panel-heading">
                            <b>குடும்ப தலைவி </b>
                        </div>
                        <div class="panel-body" >
                            <div class="col-sm-3">
                                <img src="../images/member/<?php echo $row['w_image'] ?>" width="140" height="110" class="img-responsive"/>
                                <button onclick="wife()">Upload</button>
                                <button onclick="wdelete()">Delete</button>
                            </div>
                            <div class="col-sm-9">
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b> Name </b> </label> </div>  <div class="col-sm-8"  > <?php display("w_name", $row) ? "w_name" : " " ?></div></div>                       
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"><div class="col-sm-4" > <label> <b> Education </b> </label> </div>  <div class="col-sm-8"  ><?php echo get_qualification($row['w_qualification']) ?></div></div>                       
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"><div class="col-sm-4" > <label> <b> Education Details</b> </label> </div>  <div class="col-sm-8"  ><?php display("w_education_details", $row) ? "w_education_details" : " " ?></div></div>                       
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b> Occupation </b> </label> </div>  <div class="col-sm-8"  ><?php echo get_occupation($row['w_occupation']) ?> </div></div>                       
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b> Occupation Details</b> </label> </div>  <div class="col-sm-8"  ><?php display("w_occupation_details", $row) ? "w_occupation_details" : " " ?> </div></div>                       
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b> Kootam </b> </label> </div>  <div class="col-sm-8"  ><?php echo get_kulam($row['w_kootam']) ?> </div></div>                       
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row"> <div class="col-sm-4" > <label> <b> Temple </b> </label> </div>  <div class="col-sm-8"  ><?php display("w_temple", $row) ? "w_temple" : " " ?> </div></div>                       
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?php
                    $num_rows = count_child($id);
//echo "<b>No of children=$num_rows</b>";
                    ?>
                    <script>
                        function addchildphoto(id, father_id)
                        {
                            //alert("Do you want to upload photo?"+father_id+id);
                            url = "cimageupload.php?id=" + id + "&father_id=" + father_id;
                            title = "popup";
                            var newWindow = window.open(url, title, 'scrollbars=yes, width=700, height=400');
                        }

                        function cimagedelete(id, c_image)
                        {
                            url = "cimagedelete.php?id=" + id + "&c_image=" + c_image;
                            title = "popup";
                            var newWindow = window.open(url, title, 'scrollbars=yes, width=700, height=400');
                        }

                        function cupdate(id)
                        {
                            url = "childupdate.php?id=" + id;
                            title = "popup";
                            var newWindow = window.open(url, title, 'scrollbars=yes, width=1000, height=500');
                        }
                        function childdelete(id)
                        {
                            url = "childdelete.php?id=" + id;
                            title = "popup";
                            var newWindow = window.open(url, title, 'scrollbars=yes, width=500, height=400');
                        }

                        function linkfamily(id)
                        {
                            url = "linkfamily.php?child_id=" + id;
                            title = "popup";
                            var newWindow = window.open(url, title, 'scrollbars=yes,width=1050, height=550');
                        }

                    </script> 
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <b>குழந்தைகள் ( <?php echo $num_rows ?> )</b>
                            <div class="box-tools dropdown pull-right">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    <span class="glyphicon glyphicon-cog"></span></button>
                                <ul class="dropdown-menu" style="border:1px solid gray">
                                    <li><a href="#" onclick="addson()" >Add Son</a></li>
                                    <li><a href="#" onclick="adddaughter()" >Add Daughter</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php
                            $children = get_children($id);
                            //var_dump($children);
                            if ($children != '') {
                                foreach ($children[$id] as $k => $v) {
                                    ?> 

                                    <div id="box" class="box box-primary">
                                        <div class="box-header with-border">
                                            <div class="col-sm-8"> <h3 class="box-title "><?php echo $v['c_name'] ?></h3></div>
                                            <div class="box-tools col-sm-7">    
                                                <button onclick="cupdate(<?php echo $v['id'] ?>)">Edit</button>
                                                <button onclick="childdelete(<?php echo $v['id'] ?>)">Delete</button>	                      
                                            </div>
                                        </div>
                                        <!-- /.box-header -->   
                                        <div class="box-body">
                                            <div class="col-sm-3">
                                                <img src="../images/member/<?php echo $v['c_image'] ?>" width="140" height="110" class="img-responsive"/>
                                                <button onclick="addchildphoto(<?php echo $v['id'] ?>,<?php echo $v['father_id'] ?>)">Upload</button>
                                                <button onclick="cimagedelete(<?php echo $v['id'] ?>, '<?php echo $v['c_image'] ?>')">Delete</button>	
                                            </div>

                                            <div class="col-sm-9">
                                                <ul class="list-group list-group-unbordered">
                                                    <li class="list-group-item">
                                                        <div class="row"> <div class="col-sm-4" > <label> <b> Name </b> </label> </div>  <div class="col-sm-6">  <?php display("c_name", $v) ? "c_name" : " " ?> </div> </div>                       
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row"> <div class="col-sm-4" > <label> <b> DOB </b> </label> </div>  <div class="col-sm-6"> <?php display("c_dob", $v) ? "c_dob" : " " ?> </div></div>                       
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row"> <div class="col-sm-4" > <label> <b> Education </b> </label> </div>  <div class="col-sm-6"  ><?php echo get_qualification($v['c_qualification']) ?> </div></div>                       
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row"> <div class="col-sm-4" > <label> <b> Occupation </b> </label> </div>  <div class="col-sm-6"  > <?php echo get_occupation($v['c_occupation']) ?>  </div></div>                       
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row"> <div class="col-sm-4" > <label> <b>Mobile no </b> </label> </div>  <div class="col-sm-6"  > <?php display("c_mobile_no", $v) ? "c_mobile_no" : " " ?>  </div></div>                       
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row"> <div class="col-sm-4" > <label> <b>Marital Status </b> </label> </div>  <div class="col-sm-7"  > <?php display("c_marital_status", $v) ? "c_marital_status" : " " ?>    
                                                                <?php
                                                                if ($v['c_gender'] == 'male' || $v['c_gender'] == 'Male') {
                                                                    if ($v['fam_id'] == "0") {
                                                                        ?>
                                                                        <button onclick="linkfamily(<?php echo $v['id'] ?>)">Link Family</button>
                                                                        <?php if ($v['c_marital_status'] == "no" || $v['c_marital_status'] == "No") { ?>
                                                                            <a style="color:black" href="addmember.php?child_id=<?php echo $v['id'] ?>"> <button >Add Family </button></a>
                                                                            <?php
                                                                        }
                                                                    } else {
                                                                        ?>
                                                                        <a style="color:black" href="viewmember.php?id=<?php echo $v['fam_id'] ?>"> <button >View Family </button></a>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </div></div>   
                                                    </li>
                                                    <?php $c_id = $children[$id] ?>
                                                </ul>						
                                            </div>
                                        </div>
                                    </div>						
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>  
                </div>

                <!-- Left Column Ends -->
                <div class="col-sm-5">
                    <div class="panel panel-default" >
                        <div class="panel-heading" >
                            <b> Membership</b>
                        </div>
                        <div class="panel-body" style="height:100px;">

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <div class="row"> <div class="col-sm-4 " > <label> <b> Member ID </b> </label> </div> 
                                        <div class="col-sm-8 " style="font-size: 20px;font-weight:bold" ><?php display("member_id", $row) ?>  </div></div>                       
                                </li>
                                <li class="list-group-item">
                                    <div class="row"> <div class="col-sm-4" >  </div> 
                                        <div class="col-sm-8"  >
                                            <?php if ($row['member_id'] == '' || $row['member_id'] == '0') { ?>
                                                <form  class="form-horizontal" method="POST" >
                                                    <input  type="hidden" name ="action" value="generate_id">
                                                    <input  type="hidden" name ="id" value="<?php echo $id ?>">
                                                    <button type="submit" class="btn btn-info pull-right">Generate ID</button>
                                                </form>
                                                <?php
                                            } else {

                                                //   <button class="btn btn-info pull-right" onclick="printid()">Print ID Card</button>
                                            }
                                            ?>
                                            <script>
                                                function printid()
                                                {
                                                    url = "printid.php?id=<?php echo $id ?>";
                                                    title = "popup";
                                                    var newWindow = window.open(url, title, 'scrollbars=yes, width=500px, height=400px');
                                                }
                                            </script>

                                        </div></div>                       
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <b> முகவரி </b>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group list-group-unbordered">
                                <li id="address" class="list-group-item"> 
                                    <b>Permanent address</b> <div> <?php display("permanent_address", $row) ? "permanent_address" : " " ?>  </div>
                                </li>
                                <li id="address" class="list-group-item">
                                    <b>Current address</b> <div><?php display("current_address", $row) ? "current_address" : " " ?> </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-default" >
                        <div class="panel-heading" >
                            <b> Family Tree </b>
                        </div>
                        <div class="panel-body" style="height:300px;">

                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>மற்றவை </b>
                        </div> 
                        <div class="panel-body">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <div class="row"> <div class="col-sm-4" > <label> <b> Family name </b> </label> </div>  <div class="col-sm-8"  ><?php display("family_name", $row) ? "family_name" : " " ?>  </div></div>                       
                                </li>
                                <li class="list-group-item">
                                    <div class="row"> <div class="col-sm-4" > <label> <b> Remarks </b> </label> </div>  <div class="col-sm-8"  ><?php display("remarks", $row) ? "remarks" : " " ?>  </div></div>                       
                                </li>

                                <li class="list-group-item">
                                    <div class="row"> <div class="col-sm-4" > <label> <b> Created by </b> </label> </div>  <div class="col-sm-8"  ><?php display("created_by", $row) ? "created_by" : " " ?></div></div>                       
                                </li>                             
                                <li class="list-group-item">
                                    <div class="row"> <div class="col-sm-4" > <label> <b>Created date </b> </label> </div>  <div class="col-sm-8"  ><?php display("created_date", $row) ? "created_date" : " " ?> </div></div>                       
                                </li>         						                                            					                      
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Admin Notes </b>
                        </div> 
                        <div class="panel-body">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <div class="row"> <div class="col-sm-4" > <label> <b>Admin Notes </b> </label> </div>  <div class="col-sm-8"  ><?php display("admin_notes", $row) ? "admin_notes" : " " ?>  </div></div>                       
                                </li>

                            </ul>
                        </div>
                    </div>

                </div>										
                <!--right column ends -->

                <center> 
                    <div class="col-sm-12 " style="background-color:#1c3a6f">

                        <?php
                        if ($row['app_front'] && $row['app_back'] !== '') {
                            ?>
                            <button onclick = "applicationview(<?php echo $id ?>)" > View application </button>
                        <?php } else { ?>
                            <button onclick="applicupload(<?php echo $id ?>)">Upload application </button>
                        <?php } ?> 

                        <?php // <button onclick="applicationview()">View application</button>  ?>

                        <button onclick="addson()">Add Son</button>
                        <button onclick="adddaughter()">Add Daughter</button>
                        <button onclick="addhoro()">Add horoscope</button>
                        <script>
                            /* function addchild()
                             {
                             url = "addchild.php?id=<?php echo $id ?>";
                             title = "popup";
                             var newWindow = window.open(url, title, 'scrollbars=yes,width=1000 height=500');
                             }*/
                            function applicupload(id)
                            {
                                url = "applicupload.php?id=" + id;
                                title = "popup";
                                var newWindow = window.open(url, title, 'scrollbars=yes, width=500 height=500');
                            }
                            function addson()
                            {
                                url = "addson.php?id=<?php echo $id ?>";
                                title = "popup";
                                var newWindow = window.open(url, title, 'scrollbars=yes,width=1000, height=550');
                            }
                            function adddaughter()
                            {
                                url = "adddaughter.php?id=<?php echo $id ?>";
                                title = "popup";
                                var newWindow = window.open(url, title, 'scrollbars=yes,width=1000, height=550');
                            }
                            function applicationview()
                            {
                                url = "applicview.php?id=<?php echo $id ?>";
                                title = "popup";
                                var newWindow = window.open(url, title, 'scrollbars=yes,width=1000 height=500 ');
                            }

                            function addhoro()
                            {
                                url = "../matrimony/addhoroscope.php?ref_id=<?php echo $id ?>& mbl_no=<?php echo $row['mobile_no'] ?>&ref_name=<?php echo $row['name'] ?>&ref_address=<?php echo $row['village'] ?>";
                                title = "popup";
                                var width = 90 / 100 * screen.width;
                                var newWindow = window.open(url, title, 'scrollbars=yes,width=' + width + ' height=500 ');
                            }

                            function deletehoroscope(id)
                            {
                                url = "../matrimony/dlthoroscope.php?id=" + id;
                                title = "popup";
                                var newWindow = window.open(url, title, 'scrollbars=yes, width=600, height=500');
                            }

                        </script> 
                    </div>
                </center>
            </div>
            <div class="tab-pane" id="Horos"> 
                <div class="col-sm-12" style="margin-top:20px;">
                    <div id="ppd" class="panel panel-default">
                        <div id="ph" class="panel-heading" >
                            <b>Horoscopes uploaded by this member</b>
                        </div>
                        <div  id="bb" class="box-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr> 
                                        <th class="col-sm-1">Photo</th>                
                                        <th class="col-sm-2">Personal details</th>
                                        <th class="col-sm-2">Horo details</th>
                                        <th class="col-sm-1">status</th>
                                        <th class="col-sm-2">Action</th>					
                                    </tr>		
                                </thead>
                                <tbody>
                                    <?php
                                    $horo_list = get_horo_by_member($id);

                                    foreach ($horo_list as $k => $v) {
                                        ?>
                                        <tr> 
                                            <td><img src="../attachments/<?php echo $v['photo'] ?>" class="img-responsive"  width="70"></td>      
                                            <td><?php echo $v['name'] . "<br>" . $v['qualification'] . "<br>" . $v['occupation'] ?></td>
                                            <td> <?php
                                                echo get_raasi($v['raasi']);
                                                echo "<br>";
                                                echo get_star($v['star']);
                                                echo "<br>";
                                                echo ($v['raaghu_kaedhu'] > 0) ? "Yes" : "No";
                                                echo "<br>";
                                                echo ($v['sevvai'] > 0) ? "Yes" : "No";
                                                ?></td>
                                            <td><?php echo $v['status'] ?></td>
                                            <td><a id="a" href="../matrimony/viewhoroscope.php?id=<?php echo $k ?>"><span class="glyphicon glyphicon-eye-open"></span></a><a id="a" href="../matrimony/updatehoroscope.php?id=<?php echo $k ?>"><span class="glyphicon glyphicon-edit"></span></a>  <a href="#" id="a" onclick="deletehoroscope(<?php echo $v['id'] ?>)"> <span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>

                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <?php /*  <script>
              function adddonation(id)
              {
              url = "../donation/adddonation.php?id=" + id;
              title = "popup";
              var newWindow = window.open(url, title, 'scrollbars=yes, width=800, height=500');
              }


              function updatedonation(id)
              {
              url = "../donation/updatedonation.php?id=" + id;
              title = "popup";
              var newWindow = window.open(url, title, 'scrollbars=yes, width=800, height=500');
              }
              </script>
              <style>
              #aa{
              color:white;
              }

              </style>
              <div class="tab-pane" id="Donation">

              <button class="btn btn-info pull-right">    <a id="aa" href="#" onclick="adddonation(<?php echo $row['id'] ?>)" ><span class="glyphicon glyphicon-plus"></span>Add doantion</a></button>

              <div class="panel-heading" >
              <h3> <b> Donations given by  "<?php echo $row['name'] ?>"</b></h3>
              </div><br>
              <div class="col-sm-6 dataTables_paginate paging_simple_numbers pull-right " id="example2_paginate">
              <ul class="pagination pull-right">
              <?php
              if ($total_records > $rec_per_page) {
              ?>
              <li class="paginate_button previous" id="example2_previous">
              <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0">Prev</a>
              </li>
              <?php
              for ($i = 1; $i <= $total_pages; $i++) {
              ?>
              <li class="paginate_button ">
              <a href="viewmember.php?page=<?php echo $i ?>" aria-controls="example2" data-dt-idx="1" tabindex="0"><?php echo $i ?></a>
              </li>
              <?php } ?>
              <li class="paginate_button next" id="example2_next">
              <a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0">Next</a>
              </li>
              <?php } ?>
              </ul></div>
              <br>          <br>
              <div id="bb" class="box-body">
              <table id="example2" class="table table-bordered table-hover">
              <thead>
              <tr>
              <th class="col-sm-1" > Rec No</th>
              <th class="col-sm-1">Amount</th>
              <th class="col-sm-2">Donation Type</th>
              <th class="col-sm-2">Paid on</th>
              <th class="col-sm-2">Received by</th>
              <th  class="col-sm-1">Action</th>
              </tr>
              </thead>
              <tbody id="tbody">
              <?php
              $donation = get_donation($row['member_id'], '');
              //    echo count($donation);

              foreach ($donation as $m => $value) {
              //var_dump($v);
              foreach ($value as $k => $v) {
              ?>
              <tr>
              <td><?php echo $v['receipt_no'] ?></td>
              <td><?php echo $v['amount'] ?></td>
              <td><?php echo $v['type'] ?><?php
              if ($v['type'] == 'subscription') {
              echo '-' . $v['sub_year'];
              }
              ?>
              </td>
              <td><?php echo date('d-m-Y', strtotime($v['paid_on'])) ?></td>
              <td><?php echo $v['receiver_name'] ?></td>
              <td><a id="a" href="#" onclick="updatedonation(<?php echo $v['id'] ?>)" ><span class="glyphicon glyphicon-edit">Edit</span></a>  </td>
              </tr>

              <?php
              }
              }
              ?>
              </tbody>
              </table>
              </div>
              <div class="col-sm-6 dataTables_paginate paging_simple_numbers pull-right " id="example2_paginate">
              <ul class="pagination pull-right">
              <?php
              if ($total_records > $rec_per_page) {
              ?>
              <li class="paginate_button previous" id="example2_previous">
              <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0">Prev</a>
              </li>
              <?php
              for ($i = 1; $i <= $total_pages; $i++) {
              ?>
              <li class="paginate_button ">
              <a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0">1</a>
              </li>
              <?php } ?>
              <li class="paginate_button next" id="example2_next">
              <a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0">Next</a>
              </li>
              <?php } ?>
              </ul></div>
             */ ?>
        </div>
    </div></div></div>
<div style="clear:both"></div>
<?php
include('../footer.php');
?>