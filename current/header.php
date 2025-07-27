<?php
include_once('init.php');
check_login();
//var_dump($_SERVER);
//echo $path;
//var_dump($_SESSION);
?>
<style>
    #con.container
    {
        width:100%;
        margin:0px;
        padding:0px;
    }
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
    }
    /* IE 6 doesn't support max-height
     * we use height instead, but this forces the menu to always be this tall
     */
    * html .ui-autocomplete {
        height: 100px;
    }

    .ui-menu .ui-menu-item {
        margin: 0;
        cursor: pointer;
        padding:7px;
        padding-left:15px;
        margin-left:0px;
        background: #f0f0f3;
        border-bottom:1px solid lightgray;
    }
    .ui-state-hover,
    .ui-widget-content .ui-state-hover,
    .ui-widget-header .ui-state-hover,
    .ui-state-focus,
    .ui-widget-content .ui-state-focus,
    .ui-widget-header .ui-state-focus,
    .ui-button:hover,
    .ui-button:focus {
        border: 0px solid #cccccc;
        background: #5f89c0;
        font-weight: normal;
        color: #f0f0f3;
        font-weight:bold;
    }
    #body{
        min-height: 90%;
    }
</style>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>kakkaveri</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php echo $path ?>/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $path ?>/plugins/select2/select2.min.css">
        <link rel="stylesheet" href="<?php echo $path ?>/plugins/jQueryUI/jquery-ui.css">
        <script src="<?php echo $path ?>/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="<?php echo $path ?>/plugins/jQueryUI/jquery-ui.min.js"></script>
        <script src="<?php echo $path ?>/plugins/select2/select2.min.js"></script>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo $path ?>/plugins/daterangepicker/daterangepicker.css">
        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="<?php echo $path ?>/plugins/datepicker/datepicker3.css">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo $path ?>/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo $path ?>/dist/css/skins/_all-skins.min.css">
        <link rel="stylesheet" href="<?php echo $path ?>/bootstrap/css/custom.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
    <body id="body" class="hold-transition skin-blue layout-top-nav">
        <div class="wrapper">
            <header class="main-header">
                <nav class="navbar navbar-default navbar-fixed-top">

                    <div  id="con" class="container">
                        <div class="navbar-header" style="text-align: center;width:100%">
                            <h2 style="margin:0px;padding:12px;text-align:center;width:100%;color:white;border-bottom:1px solid #7da793; text-shadow: 2px 2px #3900ff;;" ><b style="font-family: 'Cinzel Decorative';">KAKAVERI ANNAMAR TRUST</b></h2>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="container">
                        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo $path ?>/dashboard.php">Dashboard<span class="sr-only">(current)</span></a></li>
                                <?php
                                if (($_SESSION['username']) == 'admin') {
                                    ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">User<span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">

                                            <li><a href='<?php echo $path ?>/user/userlist.php'><span>User List</span></a></li>
                                            <li><a href='<?php echo $path ?>/user/adduser.php'><span>Add User</span></a></li> 
                                        </ul>
                                    </li>
                                    <?php
                                }
                                ?>		

                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Member<span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?php echo $path ?>/member/listmember.php">Member List</a></li>
                                        <li><a href="<?php echo $path ?>/member/addmember.php">Add Member</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">List by<span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?php echo $path ?>/member/owise.php">Occupation</a></li>
                                        <li><a href="<?php echo $path ?>/member/qwise.php"><span>Qualification</span></a></li>
                                        <li><a href="<?php echo $path ?>/member/vwise.php"><span>Village</span></a></li>
                                        <li><a href="<?php echo $path ?>/member/pwise.php"><span>Pudavai</span></a></li>
                                        <li><a href="<?php echo $path ?>/member/bwise.php"><span>Blood group</span></a></li>
                                        <li><a href="<?php echo $path ?>/member/kattwise.php"><span>Kattalai</span></a></li>  
                                    </ul>
                                </li>
                                <?php /*     <li>
                                  <a href="<?php echo $path ?>/donation/eventlist.php">Donation </a>
                                  </li>
                                 */ ?>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Matrimony<span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?php echo $path ?>/matrimony/listhoroscope.php">Horoscope List</a></li>
                                        <li><a href="<?php echo $path ?>/matrimony/searchhoroscope.php">Search Horoscope</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Settings<span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="<?php echo $path ?>/label/viewlabel.php">Label List</a></li>
                                    </ul>
                                </li>
                                <?php
                                if (($_SESSION['username']) == 'admin') {
                                    ?>
                                    <!-- Remove the trash icon from the main nav -->
                                    <?php
                                }
                                ?>	
                            </ul>
                        </div>
                        <!-- Navbar Right Menu -->
                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">
                                <!-- Messages: style can be found in dropdown.less--
                                <!-- User Account Menu -->
                                <li class="dropdown user user-menu">
                                    <!-- Menu Toggle Button -->
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <!-- The user image in the navbar-->
                                        <?php
                                        $user_image = !empty($_SESSION['u_image']) ? 'user/' . $_SESSION['u_image'] : 'default.png';
                                        ?>
                                        <img src="<?php echo $path ?>/images/<?php echo $user_image ?>"  class="user-image" alt="User Image">
                                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                        <span class="hidden-xs"><?php echo $_SESSION['username'] ?></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <!-- The user image in the menu -->
                                        <li class="user-header">
                                        <img src="<?php echo $path ?>/images/<?php echo $user_image ?>" class="img-circle" alt="User Image">
                                            <p><?php echo $_SESSION['username'] ?> </p>    
                                        </li>
                                        <!-- Menu Footer: links one by one -->
                                        <li>
                                            <a href="user/userview.php?id=" class="btn btn-default btn-flat btn-block" style="text-align:left; padding: 8px 16px;">
                                                <i class="fa fa-user" style="margin-right:8px;"></i> My Profile
                                            </a>
                                        </li>
                                        <li>
                                            <a href="logout.php" class="btn btn-default btn-flat btn-block" style="text-align:left; padding: 8px 16px;">
                                                <i class="fa fa-sign-out" style="margin-right:8px;"></i> Sign out
                                            </a>
                                        </li>
                                        <?php if (($_SESSION['username']) == 'admin') { ?>
                                        <li>
                                            <a href="<?php echo $path ?>/trash.php" class="btn btn-default btn-flat btn-block" style="text-align:left; padding: 8px 16px;">
                                                <i class="fa fa-trash" style="margin-right:8px;"></i> Trash
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- /.navbar-custom-menu -->
                    </div>
                    <!-- /.container-fluid -->
                </nav>
            </header>
            <div class="content-wrapper" style="padding-top:55px;background-color: white;">