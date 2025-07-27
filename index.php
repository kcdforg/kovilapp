<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kovil App - Version Selector</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .version-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .version-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .version-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .btn-version {
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="text-white mb-5">
                    <i class="bi bi-house-fill"></i> Kovil App
                </h1>
                <h3 class="text-white mb-5">Choose Your Version</h3>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="version-card p-5">
                            <div class="version-icon text-primary">
                                <i class="bi bi-gear-fill"></i>
                            </div>
                            <h4 class="mb-3">Current Version</h4>
                            <p class="text-muted mb-4">
                                Bootstrap 3 - Stable and tested version with all existing features.
                            </p>
                            <a href="current/dashboard.php" class="btn btn-primary btn-version">
                                <i class="bi bi-arrow-right"></i> Launch Current
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="version-card p-5">
                            <div class="version-icon text-success">
                                <i class="bi bi-stars"></i>
                            </div>
                            <h4 class="mb-3">Modern Version</h4>
                            <p class="text-muted mb-4">
                                Bootstrap 5 - New modern interface with enhanced features and design.
                            </p>
                            <a href="modern/dashboard.php" class="btn btn-success btn-version">
                                <i class="bi bi-arrow-right"></i> Launch Modern
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5">
                    <p class="text-white-50">
                        <i class="bi bi-info-circle"></i>
                        Both versions share the same database and functionality.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 