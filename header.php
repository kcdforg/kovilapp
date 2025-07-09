<!-- masterpage.html -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .header {
      background-color: #0d6efd;
      color: white;
      padding: 15px 0;
      text-align: center;
      font-size: 1.6rem;
      font-weight: 600;
    }

    .navbar-custom {
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
    }

    .navbar-nav .nav-link {
      color: #0d6efd;
      font-weight: 500;
      margin-right: 15px;
    }

    .navbar-nav .nav-link:hover {
      color: #0a58ca;
    }

    .profile-icon {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-color: #dee2e6;
      display: inline-block;
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">

  <!-- Header -->
  <div class="header">
    National Association Portal
  </div>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="members.php">Members</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">List by</a>
          <li class="nav-item"><a class="nav-link" href="#" title="Trash">
              <i class="bi bi-trash"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" title="Settings">
              <i class="bi bi-gear-fill"></i>
            </a>
          </li>
        </ul>
        <div class="d-flex align-items-center">
          <!-- Right side with profile dropdown -->
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="profileDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              <div class="profile-icon me-2"></div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
              <li><a class="dropdown-item" href="#">My Profile</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item text-danger" href="#">Sign Out</a></li>
            </ul>
          </div>

        </div>
      </div>
    </div>
  </nav>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

