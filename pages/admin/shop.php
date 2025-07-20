<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Cemetery Services</h3>
                <p class="text-subtitle text-muted">Manage and view all available cemetery services</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                href="<?php echo WEBROOT; ?>pages/admin/index.php?page=dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Services</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="parent-container mb-2" id="servicesContainer">    
    <div class="search-filter-section">
        <div class="form-group position-relative has-icon-left">
            <input type="text" class="form-control" id="serviceSearch" onkeyup="filterServices()" placeholder="Search services by name...">
            <div class="form-control-icon">
                <i class="bi bi-search"></i>
            </div>
        </div>
`
        <div class="service-count">
            <span class="text-muted" id="serviceCount">
                <?php 
                    $count_query = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM tbl_services");
                    $count_result = mysqli_fetch_array($count_query);
                    echo $count_result['total'] . ' services';
                ?>
            </span>
        </div>
        
        <div class="add-service-container">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-service-modal">
                <i class="bi bi-plus-circle"></i>
                Add New Service
            </button>
        </div>
    </div>

    <!-- Start of Services Section -->
    <div class="child-container">
        <?php
            $query = mysqli_query($mysqli, "SELECT * FROM tbl_services ORDER BY service_name ASC");
            while ($row = mysqli_fetch_array($query)) {
        ?>
        <div class="service-card mb-0" data-service-name="<?php echo strtolower($row['service_name']); ?>">
            <div class="service-card-body">
                <div class="service-title">
                    <span><?php echo htmlspecialchars($row['service_name']); ?></span>
                    <span
                        class="badge <?php echo ($row['service_availability'] == 'available') ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo ucfirst($row['service_availability']); ?>
                    </span>
                </div>

                <div class="service-price">
                    <h2 class="primary">&#8369; <?php echo number_format($row['service_cost']); ?></h2>
                    <div class="service-completion">
                        <i class="bi bi-clock"></i>
                        <span>Completion Time: <?php echo intval($row['service_completion']); ?>
                            <?php echo intval($row['service_completion']) > 1 ? 'days' : 'day'; ?></span>
                    </div>
                </div>

                <div class="service-footer">
                    <hr>
                    <div class="action-buttons">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edit-service-<?php echo $row["service_id"]?>">
                            <i class="bi bi-pencil btn-icon"></i>
                        </button>

                        <a href="function/shop_function.php?serviceid=<?php echo $row['service_id']; ?>&function=delete"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this service?')">
                            <i class="bi bi-trash btn-icon"></i>
                        </a>

                        <?php if ($row['service_availability'] == 'available') { ?>
                        <a href="function/shop_function.php?serviceid=<?php echo $row['service_id']; ?>&function=unlink"
                            class="btn btn-secondary btn-sm">
                            <i class="bi bi-toggle-on btn-icon"></i>
                            Disable
                        </a>
                        <?php } else { ?>
                        <a href="function/shop_function.php?serviceid=<?php echo $row['service_id']; ?>&function=link"
                            class="btn btn-primary btn-sm">
                            <i class="bi bi-toggle-off btn-icon"></i>
                            Enable
                        </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<!-- Add new service Modal -->
<div class="modal fade" id="add-service-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="function/shop_function.php?function=add" method="POST">
                <div class="modal-body p-5">
                    <div class="header-wrapper d-flex justify-content-between mb-3">
                        <div class="header-title">
                            <h3>Add New Service</h3>
                            <p>Click save record when you're done.</p>
                        </div>
                        <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <!-- Service form fields -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="add-service-name" class="form-label">Service Name</label>
                                <input type="text" class="form-control" id="add-service-name" name="service-name" 
                                       placeholder="Enter service name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="add-service-fee" class="form-label">Service Fee (₱)</label>
                                <input type="number" class="form-control" id="add-service-fee" name="service-fee" 
                                       placeholder="0.00" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="add-service-duration" class="form-label">Completion Time (Days)</label>
                                <input type="number" class="form-control" id="add-service-duration" name="service-duration" 
                                       placeholder="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="add-service-status" class="form-label">Service Status</label>
                                <select class="form-select" id="add-service-status" name="service-status" required>
                                    <option value="">Select status</option>
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Clear</button>
                    <button type="submit" name="btn-submit" id="btn-submit" class="btn btn-primary">Save Record</button>
                </div>
            </form>
        </div>    </div>
</div>

<?php
// Edit modals need to be inside the loop to have access to $row data
$query = mysqli_query($mysqli, "SELECT * FROM tbl_services ORDER BY service_name ASC");
while ($row = mysqli_fetch_array($query)) {
?>
<!-- edit service modal -->
<div class="modal fade" id="edit-service-<?php echo $row["service_id"]?>" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="function/shop_function.php?serviceid=<?php echo $row['service_id'];?>&function=update" method="POST">
                <div class="modal-body p-5">
                    <div class="header-wrapper d-flex justify-content-between mb-3">
                        <div class="header-title">
                            <h3>Edit Service</h3>
                            <p>Click save record when you're done.</p>
                        </div>
                        <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <!-- Service form fields -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="edit-service-name-<?php echo $row['service_id']; ?>" class="form-label">Service Name</label>
                                <input type="text" class="form-control" id="edit-service-name-<?php echo $row['service_id']; ?>" 
                                       name="service-name" value="<?php echo htmlspecialchars($row['service_name']); ?>" 
                                       placeholder="Enter service name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="edit-service-fee-<?php echo $row['service_id']; ?>" class="form-label">Service Fee (₱)</label>
                                <input type="number" class="form-control" id="edit-service-fee-<?php echo $row['service_id']; ?>" 
                                       name="service-fee" value="<?php echo $row['service_cost']; ?>" 
                                       placeholder="0.00" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="edit-service-duration-<?php echo $row['service_id']; ?>" class="form-label">Completion Time (Days)</label>
                                <input type="number" class="form-control" id="edit-service-duration-<?php echo $row['service_id']; ?>" 
                                       name="service-duration" value="<?php echo $row['service_completion']; ?>" 
                                       placeholder="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="edit-service-status-<?php echo $row['service_id']; ?>" class="form-label">Service Status</label>
                                <select class="form-select" id="edit-service-status-<?php echo $row['service_id']; ?>" name="service-status" required>
                                    <option value="">Select status</option>
                                    <option value="available" <?php echo ($row['service_availability'] == 'available') ? 'selected' : ''; ?>>Available</option>
                                    <option value="unavailable" <?php echo ($row['service_availability'] == 'unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Clear</button>
                    <button type="submit" name="btn-submit" id="btn-submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>



<script>
function filterServices() {
    const searchInput = document.getElementById('serviceSearch');
    const filter = searchInput.value.toLowerCase();
    const serviceCards = document.querySelectorAll('.service-card[data-service-name]');
    let visibleCount = 0;

    serviceCards.forEach(function(card) {
        const serviceName = card.getAttribute('data-service-name');
        if (serviceName.indexOf(filter) > -1) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Update service count with better formatting
    const serviceCount = document.getElementById('serviceCount');
    if (filter === '') {
        serviceCount.textContent = serviceCards.length + ' services';
    } else {
        serviceCount.textContent = visibleCount + ' of ' + serviceCards.length + ' services';
    }
    
    // Add visual feedback for search state
    const searchContainer = searchInput.closest('.form-group');
    if (filter && visibleCount === 0) {
        searchContainer.classList.add('no-results');
        if (!document.querySelector('.no-results-message')) {
            const noResultsMsg = document.createElement('div');
            noResultsMsg.className = 'no-results-message text-muted mt-2 text-center';
            noResultsMsg.innerHTML = '<i class="bi bi-search"></i> No services found matching "' + filter + '"';
            document.querySelector('.child-container').appendChild(noResultsMsg);
        }
    } else {
        searchContainer.classList.remove('no-results');
        const existingMsg = document.querySelector('.no-results-message');
        if (existingMsg) {
            existingMsg.remove();
        }
    }
}

// Add keyboard shortcut for search (Ctrl+K or Cmd+K)
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        document.getElementById('serviceSearch').focus();
    }
});

// Clear search on Escape key
document.getElementById('serviceSearch').addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        this.value = '';
        filterServices();
        this.blur();
    }
});

// Add smooth transitions for card display
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card[data-service-name]');
    cards.forEach(function(card, index) {
        card.style.animationDelay = (index * 0.05) + 's';
        card.classList.add('fade-in');
    });
});
</script>