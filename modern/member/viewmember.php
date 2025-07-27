<?php
include('../init.php');

// Check if user is logged in
check_login();

$id = $_GET['id'] ?? 0;
$where = '';
$rec_per_page = 20;
$curr_page = 1;
$offset = ($curr_page - 1) * ($rec_per_page);

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'generate_id') {
        $member_id = isset($_POST['id']) ? $_POST['id'] : $id;
        generate_member_id($member_id);
    }
}

$row = get_member($id);

$sql_total = "SELECT count(*) as total FROM $tbl_family where `deleted`=0 $where";
global $con;
$result = mysqli_query($con, $sql_total);
$row1 = mysqli_fetch_assoc($result);
$total_records = $row1['total'];
$total_pages = ceil($total_records / $rec_per_page);

include('../includes/header.php');
?>

<style>
.member-avatar-large {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e9ecef;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: 700;
    color: white;
    margin: 0 auto;
    box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
    transition: all 0.3s ease;
}

.member-avatar-medium {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
    background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: 600;
    color: white;
    margin: 0 auto;
    box-shadow: 0 3px 12px rgba(28, 200, 138, 0.3);
    transition: all 0.3s ease;
}

.member-avatar-large:hover,
.member-avatar-medium:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-size: 0.8rem;
    padding: 0.4em 0.8em;
    border-radius: 8px;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><?php echo htmlspecialchars($row['name']); ?> Family</h1>
            <div>
                <a href="memberlist.php" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <a href="updatemember.php?id=<?php echo $id; ?>" class="btn btn-primary me-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <button type="button" class="btn btn-danger" onclick="deleteMember(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['name']); ?>')">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Member Information -->
<div class="row">
    <div class="col-lg-8">
        <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-fill"></i> Personal Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <?php
                        // Get member initials for default avatar
                        $name_parts = explode(' ', $row['name']);
                        $initials = '';
                        if (count($name_parts) >= 2) {
                            $initials = strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
                        } else {
                            $initials = strtoupper(substr($row['name'], 0, 2));
                        }
                        
                        // Determine the image path
                        $image_path = '';
                        $image_alt = htmlspecialchars($row['name']);
                        
                        // Check if member has a custom image
                        if (!empty($row['image']) && file_exists("../images/member/" . $row['image'])) {
                            $image_path = "../images/member/" . $row['image'];
                        } 
                        // Check if there's a default image in modern directory
                        elseif (file_exists("../images/default.png")) {
                            $image_path = "../images/default.png";
                        }
                        ?>
                        
                        <?php if ($image_path): ?>
                            <img src="<?php echo $image_path; ?>" 
                                 class="img-fluid rounded" width="150" height="150" 
                                 alt="<?php echo $image_alt; ?>"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div class="member-avatar-large" style="display: none;">
                                <?php echo $initials; ?>
                            </div>
                        <?php else: ?>
                            <div class="member-avatar-large">
                                <?php echo $initials; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="uploadImage()">Upload</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteImage()">Delete</button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($row['name']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Father's Name</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($row['father_name']); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mother's Name</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($row['mother_name']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <p class="form-control-plaintext"><?php echo $row['dob'] ? date('d M Y', strtotime($row['dob'])) : '-'; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mobile Number</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($row['mobile_no']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($row['email']); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Education</label>
                                <p class="form-control-plaintext"><?php echo get_qualification($row['qualification']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Occupation</label>
                                <p class="form-control-plaintext"><?php echo get_occupation($row['occupation']); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Blood Group</label>
                                <p class="form-control-plaintext"><?php echo get_blood_group($row['blood_group']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Member ID</label>
                                <p class="form-control-plaintext">
                                    <?php if ($row['member_id']): ?>
                                        <span class="badge bg-success"><?php echo htmlspecialchars($row['member_id']); ?></span>
                                    <?php else: ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="generate_id">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            <button type="submit" class="btn btn-sm btn-warning">Generate ID</button>
                                        </form>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-geo-alt-fill"></i> Address Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Permanent Address</h6>
                        <p class="form-control-plaintext"><?php echo nl2br(htmlspecialchars($row['permanent_address'])); ?></p>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Village</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($row['village']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">District</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($row['district']); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Current Address</h6>
                        <p class="form-control-plaintext"><?php echo nl2br(htmlspecialchars($row['current_address'])); ?></p>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Village</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($row['c_village']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">District</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($row['c_district']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Spouse Information -->
        <?php if ($row['w_name']): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-heart-fill"></i> Spouse Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <?php
                        // Get spouse initials for default avatar
                        $spouse_name_parts = explode(' ', $row['w_name']);
                        $spouse_initials = '';
                        if (count($spouse_name_parts) >= 2) {
                            $spouse_initials = strtoupper(substr($spouse_name_parts[0], 0, 1) . substr($spouse_name_parts[1], 0, 1));
                        } else {
                            $spouse_initials = strtoupper(substr($row['w_name'], 0, 2));
                        }
                        
                        // Determine the spouse image path
                        $spouse_image_path = '';
                        $spouse_image_alt = htmlspecialchars($row['w_name']);
                        
                        // Check if spouse has a custom image
                        if (!empty($row['w_image']) && file_exists("../images/member/" . $row['w_image'])) {
                            $spouse_image_path = "../images/member/" . $row['w_image'];
                        } 
                        // Check if there's a default image in modern directory
                        elseif (file_exists("../images/default.png")) {
                            $spouse_image_path = "../images/default.png";
                        }
                        ?>
                        
                        <?php if ($spouse_image_path): ?>
                            <img src="<?php echo $spouse_image_path; ?>" 
                                 class="img-fluid rounded" width="120" height="120" 
                                 alt="<?php echo $spouse_image_alt; ?>"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div class="member-avatar-medium" style="display: none;">
                                <?php echo $spouse_initials; ?>
                            </div>
                        <?php else: ?>
                            <div class="member-avatar-medium">
                                <?php echo $spouse_initials; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="uploadWifeImage()">Upload</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteWifeImage()">Delete</button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($row['w_name']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <p class="form-control-plaintext"><?php echo $row['w_dob'] ? date('d M Y', strtotime($row['w_dob'])) : '-'; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Education</label>
                                <p class="form-control-plaintext"><?php echo get_qualification($row['w_qualification']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Occupation</label>
                                <p class="form-control-plaintext"><?php echo get_occupation($row['w_occupation']); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Blood Group</label>
                                <p class="form-control-plaintext"><?php echo get_blood_group($row['w_blood_group']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kootam</label>
                                <p class="form-control-plaintext"><?php echo get_kulam($row['w_kootam']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning-fill"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="addson.php?id=<?php echo $id; ?>" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus"></i> Add Son
                    </a>
                    <a href="adddaughter.php?id=<?php echo $id; ?>" class="btn btn-outline-success">
                        <i class="bi bi-person-plus"></i> Add Daughter
                    </a>
                    <a href="../matrimony/addhoroscope.php?ref_id=<?php echo $id; ?>" class="btn btn-outline-danger">
                        <i class="bi bi-heart"></i> Add Horoscope
                    </a>
                    <a href="../donation/adddonation.php?member_id=<?php echo $id; ?>" class="btn btn-outline-warning">
                        <i class="bi bi-gift"></i> Add Donation
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Additional Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Kattalai</label>
                    <p class="form-control-plaintext"><?php echo get_kattalai($row['kattalai']); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Kattalai Village</label>
                    <p class="form-control-plaintext"><?php echo htmlspecialchars($row['k_village']); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Pudavai</label>
                    <p class="form-control-plaintext"><?php echo htmlspecialchars($row['pudavai']); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">IC</label>
                    <p class="form-control-plaintext"><?php echo htmlspecialchars($row['ic']); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Remarks</label>
                    <p class="form-control-plaintext"><?php echo nl2br(htmlspecialchars($row['remarks'])); ?></p>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gear"></i> System Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Created By</label>
                    <p class="form-control-plaintext"><?php echo htmlspecialchars($row['created_by']); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Created Date</label>
                    <p class="form-control-plaintext"><?php echo $row['created_date'] ? date('d M Y H:i', strtotime($row['created_date'])) : '-'; ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Last Updated</label>
                    <p class="form-control-plaintext"><?php echo $row['updated_date'] ? date('d M Y H:i', strtotime($row['updated_date'])) : '-'; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deleteModalBody">
                <p>Are you sure you want to delete member <strong id="memberName"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle"></i> 
                    This action cannot be undone and will permanently remove the member from the system.
                </p>
            </div>
            <div class="modal-footer" id="deleteModalFooter">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bi bi-trash"></i> Delete Member
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let memberToDelete = null;
let deleteModal = null;

function deleteMember(id, name) {
    memberToDelete = id;
    document.getElementById('memberName').textContent = name;
    
    // Reset modal content
    document.getElementById('deleteModalBody').innerHTML = `
        <p>Are you sure you want to delete member <strong>${name}</strong>?</p>
        <p class="text-danger mb-0">
            <i class="bi bi-exclamation-circle"></i> 
            This action cannot be undone and will permanently remove the member from the system.
        </p>
    `;
    
    document.getElementById('deleteModalFooter').innerHTML = `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Cancel
        </button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
            <i class="bi bi-trash"></i> Delete Member
        </button>
    `;
    
    // Re-attach event listener
    document.getElementById('confirmDelete').addEventListener('click', handleDelete);
    
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function handleDelete() {
    if (memberToDelete) {
        const confirmBtn = document.getElementById('confirmDelete');
        const modalBody = document.getElementById('deleteModalBody');
        const modalFooter = document.getElementById('deleteModalFooter');
        
        // Show loading state
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Deleting...';
        confirmBtn.disabled = true;
        
        // Disable close button during deletion
        const closeBtn = document.querySelector('#deleteModal .btn-close');
        if (closeBtn) closeBtn.style.display = 'none';
        
        // Make AJAX call to delete member
        fetch(`deletemember.php?id=${memberToDelete}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    modalBody.innerHTML = `
                        <div class="text-center">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-success">Success!</h5>
                            <p class="mb-0">${data.message}</p>
                        </div>
                    `;
                    
                    modalFooter.innerHTML = `
                        <button type="button" class="btn btn-success" onclick="closeModalAndRedirect()">
                            <i class="bi bi-check-circle"></i> OK
                        </button>
                    `;
                    
                } else {
                    // Show error message
                    modalBody.innerHTML = `
                        <div class="text-center">
                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-danger">Error!</h5>
                            <p class="mb-0">${data.message}</p>
                        </div>
                    `;
                    
                    modalFooter.innerHTML = `
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Close
                        </button>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = `
                    <div class="text-center">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-danger">Error!</h5>
                        <p class="mb-0">An unexpected error occurred. Please try again.</p>
                    </div>
                `;
                
                modalFooter.innerHTML = `
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Close
                    </button>
                `;
            });
    }
}

function closeModalAndRedirect() {
    if (deleteModal) {
        deleteModal.hide();
    }
    // Redirect to member list after successful deletion
    window.location.href = 'memberlist.php';
}

function uploadImage() {
    const url = `himageupload.php?id=<?php echo $id; ?>`;
    window.open(url, 'upload', 'width=600,height=400,scrollbars=yes');
}

function deleteImage() {
    if (confirm('Are you sure you want to delete this image?')) {
        window.location.href = `himagedelete.php?id=<?php echo $id; ?>&h_image=<?php echo $row['image']; ?>`;
    }
}

function uploadWifeImage() {
    const url = `wimageupload.php?id=<?php echo $id; ?>`;
    window.open(url, 'upload', 'width=600,height=400,scrollbars=yes');
}

function deleteWifeImage() {
    if (confirm('Are you sure you want to delete this image?')) {
        window.location.href = `wimagedelete.php?id=<?php echo $id; ?>&w_image=<?php echo $row['w_image']; ?>`;
    }
}
</script>

<?php include('../includes/footer.php'); ?>