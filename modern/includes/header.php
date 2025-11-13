<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kovil App - Modern Version</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Select2 Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 2px;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .card-header {
            background: linear-gradient(135deg, #5a67d8 0%, #4c51bf 100%);
            color: #ffffff;
            border-bottom: 1px solid #4c51bf;
            border-radius: 15px 15px 0 0 !important;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(76, 81, 191, 0.1);
        }
        
        .card-header .card-title {
            color: #ffffff;
            margin-bottom: 0;
        }
        
        .card-header i {
            color: #ffffff;
        }
        
        .card-header a {
            color: #ffffff;
        }
        
        .card-header a:hover {
            color: #e9ecef;
        }
        
        .card-header:hover {
            background: linear-gradient(135deg, #6366f1 0%, #5b21b6 100%);
            transition: all 0.3s ease;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(78, 115, 223, 0.4);
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .btn {
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            border: none;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
            border: none;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
            border: none;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead th {
            background: linear-gradient(135deg, #5a67d8 0%, #4c51bf 100%);
            border: none;
            font-weight: 600;
            color: #ffffff;
        }
        

        
        .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .dropdown-item {
            border-radius: 5px;
            margin: 2px 5px;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fc;
            transform: translateX(3px);
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e3e6f0;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .pagination .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: none;
            color: #4e73df;
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
        }
    </style>
    
    <!-- jQuery (needed for some plugins) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="bi bi-house-fill"></i> Kovil App
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $path; ?>/dashboard.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $path; ?>/member/memberlist.php">
                            <i class="bi bi-people-fill"></i> Members
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-filter"></i> List By
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo $path; ?>/member/listby.php?type=village">
                                <i class="bi bi-geo-alt"></i> Village
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo $path; ?>/member/listby.php?type=blood_group">
                                <i class="bi bi-droplet"></i> Blood Group
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo $path; ?>/member/listby.php?type=qualification">
                                <i class="bi bi-mortarboard"></i> Qualification
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo $path; ?>/member/listby.php?type=occupation">
                                <i class="bi bi-briefcase"></i> Occupation
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo $path; ?>/member/listby.php?type=kattalai">
                                <i class="bi bi-diagram-3"></i> Kattalai
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo $path; ?>/member/listby.php?type=pudavai">
                                <i class="bi bi-gift"></i> Pudavai
                            </a></li>
                        </ul>
                    </li>
                    <?php /* Matrimony link hidden - to be revived later
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $path; ?>/matrimony/listhoroscope.php">
                            <i class="bi bi-heart-fill"></i> Matrimony
                        </a>
                    </li>
                    */ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $path; ?>/subscription/list.php">
                            <i class="bi bi-gift"></i> Donations
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['name'] ?? 'Admin'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo $path; ?>/profile.php">
                                <i class="bi bi-person"></i> Profile
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo $path; ?>/user/userlist.php">
                                <i class="bi bi-person-badge"></i> Users
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo $path; ?>/settings/">
                                <i class="bi bi-gear"></i> Settings
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo $path; ?>/trash.php">
                                <i class="bi bi-trash"></i> Trash
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo $path; ?>/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="container-fluid mt-4 flex-fill"> 