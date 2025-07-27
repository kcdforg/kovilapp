<?php
include('../popupheader.php');
$id = $_GET['id'];

$row = get_member($id);
?>

<style>

    @media (max-width: 1200px) 
    {
         .idview {
            padding-left: 8%;
            padding-right:8%;
        }        
        #idimage.img-responsive   {
            display:block;
            height:140px;
            width:20%;
            float: left;
        }
        #form{
            width:64%;
            float:left;
        }
        #idfield{
            width:56%;
            font-size: 14px;
            text-align: right;
            padding-left: 0px;
            float:left;
        }
        #iddata{
            width:44%;
            font-size: 14px;
            text-align: left;
            float:right;
        }
  
    }

 #idimage.img-responsive   {
        display:block;
        height:140px;
        width:120px;   
              float: left;
    }
    #form{
            width:64%;
            float:left;}
#idfield{
            width:56%;
            font-size: 13px;
            text-align: right;
            padding-left: 0px;
            float:left;
        }
        #iddata{
            width:44%;
            font-size: 13px;
            text-align: left;
            float:left;
        }
    #idlist.list-group-item
    {
        border:0px;
  
    }
   .col-sm-5{
        padding-right:0px;
    }
    .col-sm-7{
        padding-left:0px;
    }

</style>



<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content">
        <div class="idview">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <div class="container-fluid">
                        <h5 class="container text-center"><b> சங்ககிரி கொங்கு வெள்ளாளக் கவுண்டர்கள் சங்கம்</b></h5>
                    </div>
                    <img id="idimage" class="img-responsive" src="../images/<?php echo $row['image'] ?>"  />
                    <div id="form">
                        <ul class="list-group list-group-unbordered">
                            <li id="idlist" class="list-group-item">
                                <div class="row"> <div  id="idfield" class="col-sm-7" > <label> <b> உ எண் : </b> </label> </div>  <div  id="iddata" class="col-sm-5"><?php echo $row['member_id'] ?></div> </div>                       
                            </li>
                            <li id="idlist" class="list-group-item">
                                <div class="row"> <div  id="idfield" class="col-sm-7" > <label> <b>பெயர் :</b> </label> </div>  <div  id="iddata" class="col-sm-5"><?php echo $row['name'] ?> </div></div>                       
                            </li>
                            <li id="idlist" class="list-group-item">
                                <div class="row"> <div  id="idfield" class="col-sm-7" > <label> <b>வயது :</b> </label> </div>  <div  id="iddata" class="col-sm-5"><?php echo $row['age'] ?> </div></div>                       
                            </li>
                            <li id="idlist" class="list-group-item">
                                <div class="row"> <div  id="idfield" class="col-sm-7" > <label> <b>ஊர் :</b> </label> </div>  <div  id="iddata" class="col-sm-5"><?php echo $row['village'] ?> </div></div>                       
                            </li>
                        </ul>
                    </div>     

                </div>
            </div>
        </div>
    </section>
</div>