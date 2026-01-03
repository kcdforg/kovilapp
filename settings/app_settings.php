<?php
include('../init.php');
check_login();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $group = $_POST['group'] ?? '';
    $updated = 0;
    
    foreach ($_POST as $key => $value) {
        if ($key !== 'save_settings' && $key !== 'group') {
            if (update_setting($key, $value)) {
                $updated++;
            }
        }
    }
    
    $success_message = "Settings updated successfully! ($updated settings saved)";
}

// Get all settings grouped
$general_settings = get_settings_by_group('general');
$display_settings = get_settings_by_group('display');
$modules_settings = get_settings_by_group('modules');
$member_id_settings = get_settings_by_group('member_id');
$receipt_settings = get_settings_by_group('receipt');

// Get active tab
$active_tab = $_GET['tab'] ?? 'general';

include('../includes/header.php');
?>

<style>
.settings-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.settings-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}
.nav-pills .nav-link {
    color: #495057;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: all 0.3s ease;
}
.nav-pills .nav-link:hover {
    background-color: #e9ecef;
}
.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.nav-pills .nav-link i {
    width: 20px;
}
.form-label {
    font-weight: 500;
    color: #495057;
}
.form-text {
    font-size: 0.8rem;
}
.settings-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}
.settings-section h6 {
    color: #667eea;
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
    margin-bottom: 20px;
}
.form-switch .form-check-input {
    width: 3em;
    height: 1.5em;
}
</style>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Settings</a></li>
                    <li class="breadcrumb-item active">Application Settings</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <?php if (isset($success_message)): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card settings-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-sliders"></i> Application Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Sidebar Navigation -->
                        <div class="col-md-3">
                            <nav class="nav nav-pills flex-column" id="settingsTabs" role="tablist">
                                <a class="nav-link <?php echo $active_tab === 'general' ? 'active' : ''; ?>" 
                                   id="general-tab" data-bs-toggle="pill" href="#general" role="tab">
                                    <i class="bi bi-building me-2"></i> General
                                </a>
                                <a class="nav-link <?php echo $active_tab === 'display' ? 'active' : ''; ?>" 
                                   id="display-tab" data-bs-toggle="pill" href="#display" role="tab">
                                    <i class="bi bi-display me-2"></i> Display
                                </a>
                                <a class="nav-link <?php echo $active_tab === 'modules' ? 'active' : ''; ?>" 
                                   id="modules-tab" data-bs-toggle="pill" href="#modules" role="tab">
                                    <i class="bi bi-grid-3x3-gap me-2"></i> Modules
                                </a>
                                <a class="nav-link <?php echo $active_tab === 'member_id' ? 'active' : ''; ?>" 
                                   id="member_id-tab" data-bs-toggle="pill" href="#member_id" role="tab">
                                    <i class="bi bi-person-badge me-2"></i> Member ID
                                </a>
                                <a class="nav-link <?php echo $active_tab === 'receipt' ? 'active' : ''; ?>" 
                                   id="receipt-tab" data-bs-toggle="pill" href="#receipt" role="tab">
                                    <i class="bi bi-receipt me-2"></i> Receipt
                                </a>
                            </nav>
                        </div>
                        
                        <!-- Tab Content -->
                        <div class="col-md-9">
                            <div class="tab-content" id="settingsTabContent">
                                
                                <!-- General Settings -->
                                <div class="tab-pane fade <?php echo $active_tab === 'general' ? 'show active' : ''; ?>" 
                                     id="general" role="tabpanel">
                                    <form method="POST">
                                        <input type="hidden" name="group" value="general">
                                        
                                        <div class="settings-section">
                                            <h6><i class="bi bi-building me-2"></i>Organization Details</h6>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Organization Name (Tamil)</label>
                                                    <input type="text" class="form-control" name="org_name" 
                                                           value="<?php echo htmlspecialchars($general_settings['org_name'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Organization Name (English)</label>
                                                    <input type="text" class="form-control" name="org_name_english" 
                                                           value="<?php echo htmlspecialchars($general_settings['org_name_english'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Address</label>
                                                <textarea class="form-control" name="org_address" rows="2"><?php echo htmlspecialchars($general_settings['org_address'] ?? ''); ?></textarea>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Phone Number</label>
                                                    <input type="text" class="form-control" name="org_phone" 
                                                           value="<?php echo htmlspecialchars($general_settings['org_phone'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="org_email" 
                                                           value="<?php echo htmlspecialchars($general_settings['org_email'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Logo Filename</label>
                                                    <input type="text" class="form-control" name="org_logo" 
                                                           value="<?php echo htmlspecialchars($general_settings['org_logo'] ?? ''); ?>">
                                                    <div class="form-text">Place logo file in images/ folder</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-end">
                                            <button type="submit" name="save_settings" class="btn btn-primary">
                                                <i class="bi bi-check-lg me-1"></i> Save General Settings
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Display Settings -->
                                <div class="tab-pane fade <?php echo $active_tab === 'display' ? 'show active' : ''; ?>" 
                                     id="display" role="tabpanel">
                                    <form method="POST">
                                        <input type="hidden" name="group" value="display">
                                        
                                        <div class="settings-section">
                                            <h6><i class="bi bi-display me-2"></i>Display Preferences</h6>
                                            
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Members Per Page</label>
                                                    <input type="number" class="form-control" name="members_per_page" 
                                                           value="<?php echo htmlspecialchars($display_settings['members_per_page'] ?? '25'); ?>"
                                                           min="10" max="100">
                                                    <div class="form-text">Number of records to show per page</div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Date Format</label>
                                                    <select class="form-select" name="date_format">
                                                        <option value="d-m-Y" <?php echo ($display_settings['date_format'] ?? '') === 'd-m-Y' ? 'selected' : ''; ?>>DD-MM-YYYY</option>
                                                        <option value="m-d-Y" <?php echo ($display_settings['date_format'] ?? '') === 'm-d-Y' ? 'selected' : ''; ?>>MM-DD-YYYY</option>
                                                        <option value="Y-m-d" <?php echo ($display_settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : ''; ?>>YYYY-MM-DD</option>
                                                        <option value="d/m/Y" <?php echo ($display_settings['date_format'] ?? '') === 'd/m/Y' ? 'selected' : ''; ?>>DD/MM/YYYY</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Currency Symbol</label>
                                                    <input type="text" class="form-control" name="currency_symbol" 
                                                           value="<?php echo htmlspecialchars($display_settings['currency_symbol'] ?? '₹'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-end">
                                            <button type="submit" name="save_settings" class="btn btn-primary">
                                                <i class="bi bi-check-lg me-1"></i> Save Display Settings
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Modules Settings -->
                                <div class="tab-pane fade <?php echo $active_tab === 'modules' ? 'show active' : ''; ?>" 
                                     id="modules" role="tabpanel">
                                    <form method="POST">
                                        <input type="hidden" name="group" value="modules">
                                        
                                        <div class="settings-section">
                                            <h6><i class="bi bi-grid-3x3-gap me-2"></i>Module Settings</h6>
                                            <p class="text-muted mb-4">Enable or disable application modules</p>
                                            
                                            <div class="row">
                                                <div class="col-md-4 mb-4">
                                                    <div class="card h-100">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <h6 class="mb-1"><i class="bi bi-heart text-danger me-2"></i>Matrimony</h6>
                                                                    <small class="text-muted">Marriage matching module</small>
                                                                </div>
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" name="enable_matrimony" value="1"
                                                                           <?php echo ($modules_settings['enable_matrimony'] ?? '0') === '1' ? 'checked' : ''; ?>>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-4">
                                                    <div class="card h-100">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <h6 class="mb-1"><i class="bi bi-calendar-check text-success me-2"></i>Subscription</h6>
                                                                    <small class="text-muted">Event subscription module</small>
                                                                </div>
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" name="enable_subscription" value="1"
                                                                           <?php echo ($modules_settings['enable_subscription'] ?? '0') === '1' ? 'checked' : ''; ?>>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-4">
                                                    <div class="card h-100">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <h6 class="mb-1"><i class="bi bi-gift text-primary me-2"></i>Donation</h6>
                                                                    <small class="text-muted">Donation tracking module</small>
                                                                </div>
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" name="enable_donation" value="1"
                                                                           <?php echo ($modules_settings['enable_donation'] ?? '0') === '1' ? 'checked' : ''; ?>>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden fields for unchecked checkboxes -->
                                        <input type="hidden" name="enable_matrimony" value="<?php echo ($modules_settings['enable_matrimony'] ?? '0') === '1' ? '1' : '0'; ?>">
                                        <input type="hidden" name="enable_subscription" value="<?php echo ($modules_settings['enable_subscription'] ?? '0') === '1' ? '1' : '0'; ?>">
                                        <input type="hidden" name="enable_donation" value="<?php echo ($modules_settings['enable_donation'] ?? '0') === '1' ? '1' : '0'; ?>">
                                        
                                        <div class="text-end">
                                            <button type="submit" name="save_settings" class="btn btn-primary">
                                                <i class="bi bi-check-lg me-1"></i> Save Module Settings
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Member ID Settings -->
                                <div class="tab-pane fade <?php echo $active_tab === 'member_id' ? 'show active' : ''; ?>" 
                                     id="member_id" role="tabpanel">
                                    <form method="POST">
                                        <input type="hidden" name="group" value="member_id">
                                        
                                        <div class="settings-section">
                                            <h6><i class="bi bi-person-badge me-2"></i>Member ID Generation</h6>
                                            
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Auto Generate</label>
                                                    <select class="form-select" name="auto_generate">
                                                        <option value="1" <?php echo ($member_id_settings['auto_generate'] ?? '1') === '1' ? 'selected' : ''; ?>>Yes</option>
                                                        <option value="0" <?php echo ($member_id_settings['auto_generate'] ?? '1') === '0' ? 'selected' : ''; ?>>No</option>
                                                    </select>
                                                    <div class="form-text">Automatically generate member IDs</div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Prefix</label>
                                                    <input type="text" class="form-control" name="prefix" 
                                                           value="<?php echo htmlspecialchars($member_id_settings['prefix'] ?? ''); ?>"
                                                           placeholder="e.g., MEM">
                                                    <div class="form-text">Optional prefix for member IDs</div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Number Padding</label>
                                                    <input type="number" class="form-control" name="padding" 
                                                           value="<?php echo htmlspecialchars($member_id_settings['padding'] ?? '3'); ?>"
                                                           min="1" max="10">
                                                    <div class="form-text">Digits padding (3 = 001, 4 = 0001)</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-end">
                                            <button type="submit" name="save_settings" class="btn btn-primary">
                                                <i class="bi bi-check-lg me-1"></i> Save Member ID Settings
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Receipt Settings -->
                                <div class="tab-pane fade <?php echo $active_tab === 'receipt' ? 'show active' : ''; ?>" 
                                     id="receipt" role="tabpanel">
                                    <form method="POST">
                                        <input type="hidden" name="group" value="receipt">
                                        
                                        <div class="settings-section">
                                            <h6><i class="bi bi-receipt me-2"></i>Receipt Settings</h6>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Receipt Prefix</label>
                                                    <input type="text" class="form-control" name="prefix" 
                                                           value="<?php echo htmlspecialchars($receipt_settings['prefix'] ?? 'RCP'); ?>"
                                                           placeholder="e.g., RCP">
                                                    <div class="form-text">Prefix for receipt numbers</div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Starting Number</label>
                                                    <input type="number" class="form-control" name="start_number" 
                                                           value="<?php echo htmlspecialchars($receipt_settings['start_number'] ?? '1'); ?>"
                                                           min="1">
                                                    <div class="form-text">Starting receipt number</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-end">
                                            <button type="submit" name="save_settings" class="btn btn-primary">
                                                <i class="bi bi-check-lg me-1"></i> Save Receipt Settings
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Handle checkbox toggle for modules
document.querySelectorAll('.form-check-input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        // Find the corresponding hidden input and update its value
        const hiddenInput = document.querySelector(`input[type="hidden"][name="${this.name}"]`);
        if (hiddenInput) {
            hiddenInput.value = this.checked ? '1' : '0';
        }
    });
});

// Fix for checkbox submission - ensure value is sent correctly
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        this.querySelectorAll('.form-check-input[type="checkbox"]').forEach(checkbox => {
            const hiddenInput = this.querySelector(`input[type="hidden"][name="${checkbox.name}"]`);
            if (hiddenInput) {
                hiddenInput.value = checkbox.checked ? '1' : '0';
                checkbox.disabled = true; // Disable checkbox so only hidden value is sent
            }
        });
    });
});
</script>

<?php include('../includes/footer.php'); ?>

