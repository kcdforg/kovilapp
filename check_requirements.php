<?php
/**
 * Kovil App - System Requirements Checker
 * 
 * This script checks if your system meets the requirements for running Kovil App
 * Run this before installation to ensure compatibility
 */

// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$requirements = [
    'php_version' => '7.4.0',
    'extensions' => ['mysqli', 'gd', 'mbstring', 'json', 'fileinfo'],
    'functions' => ['mysqli_connect', 'imagecreatetruecolor', 'mb_strlen', 'json_encode'],
    'directories' => [
        'modern/images' => 'writable',
        'modern/attachments' => 'writable',
        'modern' => 'readable'
    ]
];

$results = [
    'php_version' => false,
    'extensions' => [],
    'functions' => [],
    'directories' => [],
    'overall' => false
];

// Check PHP version
$current_php = PHP_VERSION;
$results['php_version'] = version_compare($current_php, $requirements['php_version'], '>=');

// Check extensions
foreach ($requirements['extensions'] as $ext) {
    $results['extensions'][$ext] = extension_loaded($ext);
}

// Check functions
foreach ($requirements['functions'] as $func) {
    $results['functions'][$func] = function_exists($func);
}

// Check directories
foreach ($requirements['directories'] as $dir => $permission) {
    $path = __DIR__ . '/' . $dir;
    $exists = file_exists($path);
    $readable = $exists && is_readable($path);
    $writable = $exists && is_writable($path);
    
    $results['directories'][$dir] = [
        'exists' => $exists,
        'readable' => $readable,
        'writable' => $writable,
        'status' => $exists && ($permission === 'readable' ? $readable : $writable)
    ];
}

// Calculate overall status
$overall = $results['php_version'] && 
           !in_array(false, $results['extensions']) && 
           !in_array(false, $results['functions']) &&
           !in_array(false, array_column($results['directories'], 'status'));

$results['overall'] = $overall;

// Web interface
if (php_sapi_name() !== 'cli') {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kovil App - System Requirements Check</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .status-pass { color: #28a745; }
        .status-fail { color: #dc3545; }
        .status-warning { color: #ffc107; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-gear-fill"></i> 
                            Kovil App - System Requirements Check
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <!-- Overall Status -->
                        <div class="alert <?php echo $results['overall'] ? 'alert-success' : 'alert-danger'; ?> mb-4">
                            <h5 class="alert-heading">
                                <i class="bi bi-<?php echo $results['overall'] ? 'check-circle' : 'x-circle'; ?>"></i>
                                Overall Status: <?php echo $results['overall'] ? 'PASSED' : 'FAILED'; ?>
                            </h5>
                            <p class="mb-0">
                                <?php if ($results['overall']): ?>
                                    Your system meets all requirements for Kovil App. You can proceed with installation.
                                <?php else: ?>
                                    Your system does not meet some requirements. Please fix the issues below before installation.
                                <?php endif; ?>
                            </p>
                        </div>

                        <!-- PHP Version -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">PHP Version</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Required:</strong> PHP <?php echo $requirements['php_version']; ?> or higher
                                    </div>
                                    <div class="col-md-6">
                                        <span class="<?php echo $results['php_version'] ? 'status-pass' : 'status-fail'; ?>">
                                            <i class="bi bi-<?php echo $results['php_version'] ? 'check' : 'x'; ?>"></i>
                                            <strong>Current:</strong> PHP <?php echo $current_php; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PHP Extensions -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">PHP Extensions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($requirements['extensions'] as $ext): ?>
                                    <div class="col-md-6 mb-2">
                                        <span class="<?php echo $results['extensions'][$ext] ? 'status-pass' : 'status-fail'; ?>">
                                            <i class="bi bi-<?php echo $results['extensions'][$ext] ? 'check' : 'x'; ?>"></i>
                                            <?php echo $ext; ?>
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- PHP Functions -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">PHP Functions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($requirements['functions'] as $func): ?>
                                    <div class="col-md-6 mb-2">
                                        <span class="<?php echo $results['functions'][$func] ? 'status-pass' : 'status-fail'; ?>">
                                            <i class="bi bi-<?php echo $results['functions'][$func] ? 'check' : 'x'; ?>"></i>
                                            <?php echo $func; ?>()
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Directory Permissions -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Directory Permissions</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach ($requirements['directories'] as $dir => $permission): ?>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <code><?php echo $dir; ?></code>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if ($results['directories'][$dir]['exists']): ?>
                                            <span class="<?php echo $results['directories'][$dir]['status'] ? 'status-pass' : 'status-fail'; ?>">
                                                <i class="bi bi-<?php echo $results['directories'][$dir]['status'] ? 'check' : 'x'; ?>"></i>
                                                <?php echo $permission === 'writable' ? 'Writable' : 'Readable'; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="status-warning">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                Directory not found
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- System Information -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">System Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Operating System:</strong><br>
                                        <?php echo PHP_OS; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Web Server:</strong><br>
                                        <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <strong>PHP SAPI:</strong><br>
                                        <?php echo php_sapi_name(); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Memory Limit:</strong><br>
                                        <?php echo ini_get('memory_limit'); ?>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <strong>Upload Max Size:</strong><br>
                                        <?php echo ini_get('upload_max_filesize'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Post Max Size:</strong><br>
                                        <?php echo ini_get('post_max_size'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center">
                            <?php if ($results['overall']): ?>
                                <a href="install.php?install" class="btn btn-success btn-lg">
                                    <i class="bi bi-play-fill"></i> Proceed to Installation
                                </a>
                            <?php else: ?>
                                <button class="btn btn-danger btn-lg" disabled>
                                    <i class="bi bi-x-circle"></i> Fix Issues Before Installation
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-secondary btn-lg ms-2" onclick="location.reload()">
                                <i class="bi bi-arrow-clockwise"></i> Recheck Requirements
                            </button>
                        </div>

                        <!-- Recommendations -->
                        <?php if (!$results['overall']): ?>
                        <div class="alert alert-info mt-4">
                            <h6 class="alert-heading">Recommendations:</h6>
                            <ul class="mb-0">
                                <?php if (!$results['php_version']): ?>
                                <li>Upgrade PHP to version <?php echo $requirements['php_version']; ?> or higher</li>
                                <?php endif; ?>
                                
                                <?php foreach ($results['extensions'] as $ext => $status): ?>
                                    <?php if (!$status): ?>
                                    <li>Install PHP extension: <?php echo $ext; ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                
                                <?php foreach ($results['directories'] as $dir => $info): ?>
                                    <?php if (!$info['status']): ?>
                                    <li>
                                        <?php if (!$info['exists']): ?>
                                            Create directory: <?php echo $dir; ?>
                                        <?php else: ?>
                                            Set proper permissions for: <?php echo $dir; ?>
                                            <br><code>chmod 777 <?php echo $dir; ?></code>
                                        <?php endif; ?>
                                    </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
} else {
    // CLI output
    echo "\n=== Kovil App - System Requirements Check ===\n\n";
    
    echo "PHP Version: ";
    echo $results['php_version'] ? "✓ PASS" : "✗ FAIL";
    echo " (Required: {$requirements['php_version']}, Current: {$current_php})\n";
    
    echo "\nPHP Extensions:\n";
    foreach ($requirements['extensions'] as $ext) {
        echo "  {$ext}: " . ($results['extensions'][$ext] ? "✓ PASS" : "✗ FAIL") . "\n";
    }
    
    echo "\nPHP Functions:\n";
    foreach ($requirements['functions'] as $func) {
        echo "  {$func}(): " . ($results['functions'][$func] ? "✓ PASS" : "✗ FAIL") . "\n";
    }
    
    echo "\nDirectory Permissions:\n";
    foreach ($requirements['directories'] as $dir => $permission) {
        $status = $results['directories'][$dir];
        echo "  {$dir}: ";
        if (!$status['exists']) {
            echo "⚠ NOT FOUND";
        } else {
            echo $status['status'] ? "✓ PASS" : "✗ FAIL";
        }
        echo "\n";
    }
    
    echo "\nOverall Status: " . ($results['overall'] ? "✓ PASSED" : "✗ FAILED") . "\n";
    
    if ($results['overall']) {
        echo "\nYour system meets all requirements. You can proceed with installation.\n";
    } else {
        echo "\nPlease fix the failed requirements before installation.\n";
    }
    
    echo "\n";
}
?>
