<?php
include('../init.php');
check_login();

include('../includes/header.php');
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-gear"></i> Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-tags fs-1 text-primary mb-3"></i>
                                    <h6 class="card-title">Labels Management</h6>
                                    <p class="card-text text-muted">Manage kattalai, villages, and other categorization labels</p>
                                    <a href="labels.php" class="btn btn-primary">
                                        <i class="bi bi-arrow-right"></i> Manage Labels
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-database fs-1 text-success mb-3"></i>
                                    <h6 class="card-title">Database Settings</h6>
                                    <p class="card-text text-muted">Configure database connections and table settings</p>
                                    <a href="database.php" class="btn btn-success">
                                        <i class="bi bi-arrow-right"></i> Database Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-shield-check fs-1 text-warning mb-3"></i>
                                    <h6 class="card-title">Security Settings</h6>
                                    <p class="card-text text-muted">Manage user permissions and security settings</p>
                                    <a href="security.php" class="btn btn-warning">
                                        <i class="bi bi-arrow-right"></i> Security Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-bell fs-1 text-info mb-3"></i>
                                    <h6 class="card-title">Notification Settings</h6>
                                    <p class="card-text text-muted">Configure email and system notifications</p>
                                    <a href="notifications.php" class="btn btn-info">
                                        <i class="bi bi-arrow-right"></i> Notification Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-earmark-text fs-1 text-secondary mb-3"></i>
                                    <h6 class="card-title">Report Settings</h6>
                                    <p class="card-text text-muted">Configure report formats and export settings</p>
                                    <a href="reports.php" class="btn btn-secondary">
                                        <i class="bi bi-arrow-right"></i> Report Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="bi bi-backup fs-1 text-dark mb-3"></i>
                                    <h6 class="card-title">Backup & Restore</h6>
                                    <p class="card-text text-muted">Manage database backups and restore operations</p>
                                    <a href="backup.php" class="btn btn-dark">
                                        <i class="bi bi-arrow-right"></i> Backup Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?> 