<?php
/**
 * Kovil App - Installer Package Landing Page
 */

// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Check if already installed (config.php would be in parent directory after installation)
$is_installed = file_exists(dirname(__DIR__) . '/config.php') && 
                filesize(dirname(__DIR__) . '/config.php') > 100; // Basic check

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kovil App - Installation Package</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-card { 
            box-shadow: 0 15px 35px rgba(0,0,0,0.3); 
            border: none; 
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header { 
            background: linear-gradient(135deg, #5a7ae0 0%, #3355c4 100%); 
            border: none;
            padding: 2rem;
        }
        .btn-primary { 
            background: linear-gradient(135deg, #5a7ae0 0%, #3355c4 100%); 
            border: none; 
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover { 
            background: linear-gradient(135deg, #4a6ad0 0%, #2345b4 100%); 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-success { 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%); 
            border: none; 
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
        }
        .btn-outline-light {
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #5a7ae0;
        }
        .step-number {
            background: linear-gradient(135deg, #5a7ae0 0%, #3355c4 100%);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
        }
        .hero-section {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Hero Section -->
        <div class="hero-section text-white text-center mb-4">
            <h1 class="display-4 mb-3">
                <i class="bi bi-building"></i> Kovil App
            </h1>
            <p class="lead">Modern Temple Management System</p>
            <p class="mb-0">Complete solution for member management, matrimony services, donations, and subscriptions</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card main-card">
                    <div class="card-header text-white text-center">
                        <h2 class="mb-2">
                            <i class="bi bi-box-seam"></i> 
                            Installation Package
                        </h2>
                        <p class="mb-0">Ready to install Kovil App on your server</p>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if ($is_installed): ?>
                        <!-- Already Installed -->
                        <div class="alert alert-success text-center">
                            <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                            <h4 class="mt-3">Installation Complete!</h4>
                            <p class="mb-3">Kovil App has been successfully installed on your server.</p>
                            <a href="../" class="btn btn-success btn-lg">
                                <i class="bi bi-arrow-right-circle"></i> Access Your Application
                            </a>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-person-circle feature-icon"></i>
                                        <h5>Default Login</h5>
                                        <p class="mb-1"><strong>Username:</strong> admin</p>
                                        <p class="mb-3"><strong>Password:</strong> admin123</p>
                                        <small class="text-danger">⚠️ Change password after login!</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-shield-check feature-icon"></i>
                                        <h5>Security Cleanup</h5>
                                        <p class="mb-3">For security, consider deleting:</p>
                                        <ul class="list-unstyled">
                                            <li>• install.php</li>
                                            <li>• kovil.sql</li>
                                            <li>• check_requirements.php</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php else: ?>
                        <!-- Installation Steps -->
                        <div class="text-center mb-4">
                            <h3>Ready to Install</h3>
                            <p class="text-muted">Follow these simple steps to set up your Kovil App</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 text-center mb-3">
                                <i class="bi bi-gear-fill feature-icon"></i>
                                <h5>Check Requirements</h5>
                                <p>Verify your server meets all system requirements</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <i class="bi bi-download feature-icon"></i>
                                <h5>Install Application</h5>
                                <p>Run the automated installation wizard</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <i class="bi bi-rocket-takeoff feature-icon"></i>
                                <h5>Start Using</h5>
                                <p>Access your new temple management system</p>
                            </div>
                        </div>

                        <!-- Installation Steps -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Installation Steps</h5>
                                
                                <div class="d-flex align-items-center mb-3">
                                    <div class="step-number">1</div>
                                    <div>
                                        <strong>Check System Requirements</strong><br>
                                        <small class="text-muted">Verify PHP version, extensions, and permissions</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-3">
                                    <div class="step-number">2</div>
                                    <div>
                                        <strong>Run Installation</strong><br>
                                        <small class="text-muted">Configure database and install application</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center">
                                    <div class="step-number">3</div>
                                    <div>
                                        <strong>Access Application</strong><br>
                                        <small class="text-muted">Login and start managing your temple</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center">
                            <a href="check_requirements.php" class="btn btn-primary btn-lg me-3">
                                <i class="bi bi-gear-fill"></i> Check Requirements
                            </a>
                            <a href="install.php?install" class="btn btn-success btn-lg">
                                <i class="bi bi-play-fill"></i> Start Installation
                            </a>
                        </div>
                        
                        <?php endif; ?>

                        <!-- Package Information -->
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> Package Info</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li><i class="bi bi-check text-success"></i> Complete Modern Application</li>
                                            <li><i class="bi bi-check text-success"></i> Database Schema Included</li>
                                            <li><i class="bi bi-check text-success"></i> Automated Installation</li>
                                            <li><i class="bi bi-check text-success"></i> Requirements Checker</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="bi bi-list-check"></i> System Requirements</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li><i class="bi bi-dot"></i> PHP 7.4 or higher</li>
                                            <li><i class="bi bi-dot"></i> MySQL 5.7+ / MariaDB 10.2+</li>
                                            <li><i class="bi bi-dot"></i> Apache or Nginx</li>
                                            <li><i class="bi bi-dot"></i> PHP Extensions: mysqli, gd, mbstring</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-star-fill"></i> Key Features</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li><i class="bi bi-people text-primary"></i> Member Management</li>
                                            <li><i class="bi bi-heart text-danger"></i> Matrimony Services</li>
                                            <li><i class="bi bi-cash-coin text-success"></i> Donation Tracking</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li><i class="bi bi-calendar-event text-info"></i> Subscription Management</li>
                                            <li><i class="bi bi-phone text-warning"></i> Responsive Design</li>
                                            <li><i class="bi bi-translate text-secondary"></i> Multi-language Support</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documentation -->
                        <div class="text-center mt-4">
                            <p class="text-muted">
                                Need help? Check the <strong>README.md</strong> file for detailed instructions.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center text-white mt-5 pb-4">
        <p class="mb-0">
            <i class="bi bi-heart-fill text-danger"></i> 
            Made with love for temple communities worldwide
        </p>
    </footer>
</body>
</html>
