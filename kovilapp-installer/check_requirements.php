<?php
/**
 * Kovil App - System Requirements Checker (Packaged Version)
 * 
 * This script checks if your system meets the requirements for running Kovil App
 * Run this before installation to ensure compatibility
 */

// Enable error display for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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
    ],
    'files' => [
        'kovil.sql' => 'readable',
        'modern/index.php' => 'readable'
    ]
];

$results = [
    'php_version' => false,
    'extensions' => [],
    'functions' => [],
    'directories' => [],
    'files' => [],
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

// Check files
foreach ($requirements['files'] as $file => $permission) {
    $path = __DIR__ . '/' . $file;
    $exists = file_exists($path);
    $readable = $exists && is_readable($path);
    
    $results['files'][$file] = [
        'exists' => $exists,
        'readable' => $readable,
        'status' => $exists && $readable
    ];
}

// Calculate overall status
$overall = $results['php_version'] && 
           !in_array(false, $results['extensions']) && 
           !in_array(false, $results['functions']) &&
           !in_array(false, array_column($results['directories'], 'status')) &&
           !in_array(false, array_column($results['files'], 'status'));

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
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .status-pass { color: #28a745; }
        .status-fail { color: #dc3545; }
        .status-warning { color: #ffc107; }
        .check-card { box-shadow: 0 10px 30px rgba(0,0,0,0.3); border: none; }
        .card-header { background: linear-gradient(135deg, #5a7ae0 0%, #3355c4 100%); }
        .btn-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; }
        .btn-danger { background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); border: none; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card check-card">
                    <div class="card-header text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-gear-fill"></i> 
                            Kovil App - System Requirements Check
                        </h3>
                        <small>Packaged Installation Version</small>
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
                                <h5 class="mb-0"><i class="bi bi-code-slash"></i> PHP Version</h5>
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
                                <h5 class="mb-0"><i class="bi bi-puzzle"></i> PHP Extensions</h5>
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

                        <!-- Required Files -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-file-earmark-check"></i> Required Files</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach ($requirements['files'] as $file => $permission): ?>
                                <div class="row mb-2">
                                    <div class="col-md-8">
                                        <code><?php echo $file; ?></code>
                                    </div>
                                    <div class="col-md-4">
                                        <?php if ($results['files'][$file]['exists']): ?>
                                            <span class="<?php echo $results['files'][$file]['status'] ? 'status-pass' : 'status-fail'; ?>">
                                                <i class="bi bi-<?php echo $results['files'][$file]['status'] ? 'check' : 'x'; ?>"></i>
                                                Found
                                            </span>
                                        <?php else: ?>
                                            <span class="status-fail">
                                                <i class="bi bi-x"></i>
                                                Missing
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Directory Permissions -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-folder-check"></i> Directory Permissions</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach ($requirements['directories'] as $dir => $permission): ?>
                                <div class="row mb-2">
                                    <div class="col-md-8">
                                        <code><?php echo $dir; ?></code>
                                    </div>
                                    <div class="col-md-4">
                                        <?php if ($results['directories'][$dir]['exists']): ?>
                                            <span class="<?php echo $results['directories'][$dir]['status'] ? 'status-pass' : 'status-fail'; ?>">
                                                <i class="bi bi-<?php echo $results['directories'][$dir]['status'] ? 'check' : 'x'; ?>"></i>
                                                <?php echo $permission === 'writable' ? 'Writable' : 'Readable'; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="status-warning">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                Not Found
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
                                <h5 class="mb-0"><i class="bi bi-info-circle"></i> System Information</h5>
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
                                <a href="install.php?install" class="btn btn-success btn-lg me-2">
                                    <i class="bi bi-play-fill"></i> Proceed to Installation
                                </a>
                            <?php else: ?>
                                <button class="btn btn-danger btn-lg me-2" disabled>
                                    <i class="bi bi-x-circle"></i> Fix Issues Before Installation
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-secondary btn-lg" onclick="location.reload()">
                                <i class="bi bi-arrow-clockwise"></i> Recheck Requirements
                            </button>
                        </div>

                        <!-- Package Information -->
                        <div class="alert alert-info mt-4">
                            <h6 class="alert-heading"><i class="bi bi-box"></i> Package Information:</h6>
                            <ul class="mb-0">
                                <li><strong>Installation Type:</strong> Packaged Installation</li>
                                <li><strong>Database Schema:</strong> kovil.sql included</li>
                                <li><strong>Application Files:</strong> Complete modern version included</li>
                                <li><strong>Default Credentials:</strong> admin / admin123</li>
                            </ul>
                        </div>

                        <!-- Recommendations -->
                        <?php if (!$results['overall']): ?>
                        <div class="alert alert-warning mt-4">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Recommendations:</h6>
                            <ul class="mb-0">
                                <?php if (!$results['php_version']): ?>
                                <li>Upgrade PHP to version <?php echo $requirements['php_version']; ?> or higher</li>
                                <?php endif; ?>
                                
                                <?php foreach ($results['extensions'] as $ext => $status): ?>
                                    <?php if (!$status): ?>
                                    <li>Install PHP extension: <?php echo $ext; ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                
                                <?php foreach ($results['files'] as $file => $info): ?>
                                    <?php if (!$info['status']): ?>
                                    <li>
                                        <?php if (!$info['exists']): ?>
                                            Missing required file: <?php echo $file; ?>
                                        <?php else: ?>
                                            File not readable: <?php echo $file; ?>
                                        <?php endif; ?>
                                    </li>
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
    echo "\n=== Kovil App - System Requirements Check (Packaged) ===\n\n";
    
    echo "PHP Version: ";
    echo $results['php_version'] ? "✓ PASS" : "✗ FAIL";
    echo " (Required: {$requirements['php_version']}, Current: {$current_php})\n";
    
    echo "\nPHP Extensions:\n";
    foreach ($requirements['extensions'] as $ext) {
        echo "  {$ext}: " . ($results['extensions'][$ext] ? "✓ PASS" : "✗ FAIL") . "\n";
    }
    
    echo "\nRequired Files:\n";
    foreach ($requirements['files'] as $file => $permission) {
        $status = $results['files'][$file];
        echo "  {$file}: ";
        if (!$status['exists']) {
            echo "✗ MISSING";
        } else {
            echo $status['status'] ? "✓ FOUND" : "✗ NOT READABLE";
        }
        echo "\n";
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
        echo "Run: php install.php\n";
    } else {
        echo "\nPlease fix the failed requirements before installation.\n";
    }
    
    echo "\n";
}
?>
