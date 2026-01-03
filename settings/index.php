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
                                    <i class="bi bi-sliders fs-1 text-success mb-3"></i>
                                    <h6 class="card-title">Application Settings</h6>
                                    <p class="card-text text-muted">Configure organization details, display preferences, and module settings</p>
                                    <a href="app_settings.php" class="btn btn-success">
                                        <i class="bi bi-arrow-right"></i> Configure
                                    </a>
                                </div>
                            </div>
                        </div>
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
                                    <i class="bi bi-diagram-3 fs-1 text-info mb-3"></i>
                                    <h6 class="card-title">Member Groups</h6>
                                    <p class="card-text text-muted">Manage group types and groups for organizing members</p>
                                    <a href="groups.php" class="btn btn-info">
                                        <i class="bi bi-arrow-right"></i> Manage Groups
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