<?php
include('../init.php');
check_login();

// Get all active group types
$group_types = get_group_types(true);

include('../includes/header.php');
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-2">
                <i class="bi bi-diagram-3 text-primary"></i> Member Groups
            </h2>
            <p class="text-muted" style="font-size: 1.05rem;">Browse and manage member groups by category</p>
        </div>
    </div>

    <?php if (empty($group_types)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow border">
                <div class="card-body text-center py-5">
                    <i class="bi bi-folder2-open text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-4 mb-2">No group types created yet</h5>
                    <p class="text-muted mb-4" style="font-size: 1rem;">Create group types to organize your members</p>
                    <a href="../settings/groups.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-gear me-2"></i> Go to Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <?php 
        $colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
        $index = 0;
        foreach ($group_types as $type): 
            $group_count = get_group_count($type['id'], true);
            $member_count = get_group_type_total_members($type['id']);
            $color = $colors[$index % count($colors)];
            $index++;
        ?>
        <div class="col-lg-4 col-md-6">
            <a href="view.php?type_id=<?php echo $type['id']; ?>" class="text-decoration-none group-card">
                <div class="card h-100 shadow border-2 border-<?php echo $color; ?>">
                    <div class="card-body p-4">
                        <!-- Title -->
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-diagram-3 text-<?php echo $color; ?> fs-2 me-3"></i>
                            <h4 class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($type['name']); ?></h4>
                        </div>
                        
                        <!-- Description -->
                        <?php if (!empty($type['description'])): ?>
                        <p class="text-secondary mb-4" style="font-size: 1rem; line-height: 1.5;">
                            <?php echo htmlspecialchars($type['description']); ?>
                        </p>
                        <?php else: ?>
                        <p class="text-secondary mb-4" style="font-size: 1rem;">
                            View and manage all groups in this category
                        </p>
                        <?php endif; ?>
                        
                        <!-- Statistics -->
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded-2 border">
                                    <div class="fs-1 fw-bold text-<?php echo $color; ?> mb-1"><?php echo $group_count; ?></div>
                                    <div class="text-muted" style="font-size: 0.95rem;">Groups</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded-2 border">
                                    <div class="fs-1 fw-bold text-<?php echo $color; ?> mb-1"><?php echo $member_count; ?></div>
                                    <div class="text-muted" style="font-size: 0.95rem;">Members</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- View Link -->
                        <div class="mt-3 text-center">
                            <span class="text-<?php echo $color; ?> fw-semibold" style="font-size: 1rem;">
                                View Groups <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.group-card {
    display: block;
    transition: transform 0.2s ease;
}

.group-card:hover {
    transform: translateY(-3px);
}

.group-card .card {
    transition: box-shadow 0.2s ease;
}

.group-card:hover .card {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}

.group-card:hover .bi-arrow-right {
    transform: translateX(3px);
}

.bi-arrow-right {
    transition: transform 0.2s ease;
}
</style>

<?php include('../includes/footer.php'); ?>

