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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?> 