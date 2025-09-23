<?php
include('../init.php');
check_login();

include('../includes/header.php');

// Get all labels
$result = get_labels();
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-tags"></i> Label List
                    </h5>
                    <button type="button" class="btn btn-primary" onclick="addlabel()">
                        <i class="bi bi-plus-circle"></i> Add Label
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Display Name</th>
                                    <th>Slug</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Parent ID</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result): ?>
                                    <?php foreach ($result as $k => $row): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($row['display_name']); ?></strong>
                                            </td>
                                            <td>
                                                <code><?php echo htmlspecialchars($row['slug']); ?></code>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo htmlspecialchars($row['type_name']); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                                            <td>
                                                <?php if ($row['parent_id']): ?>
                                                    <span class="badge bg-secondary"><?php echo $row['parent_id']; ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark"><?php echo $row['order']; ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            onclick="updatelabel(<?php echo $row['id']; ?>)">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="deletelabel(<?php echo $row['id']; ?>)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <i class="bi bi-inbox"></i> No labels found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deletelabel(id) {
    url = "dltlabel.php?id=" + id;
    title = "popup";
    var newWindow = window.open(url, title, 'scrollbars=yes, width=800, height=400');
}

function addlabel() {
    url = "addlabel.php";
    title = "popup";
    var newWindow = window.open(url, title, 'scrollbars=yes, width=800, height=400');
}

function updatelabel(id) {
    url = "updatelabel.php?id=" + id;
    title = "popup";
    var newWindow = window.open(url, title, 'scrollbars=yes, width=800, height=400');
}
</script>

<?php include('../includes/footer.php'); ?>


