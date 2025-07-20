<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-12 col-md-6">
                <h3>Order Status</h3>
                <p class="text-subtitle text-muted">Track and manage your service requests</p>
            </div>
            <div class="col-12 col-md-6 text-md-end">
                <a href="<?php echo WEBROOT; ?>pages/user/index.php?page=service" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> New Service Request
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <?php
        $orderer_id = $_SESSION["id"];
        $total_orders = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(*) as count FROM tbl_orders WHERE customer_id = '$orderer_id'"))['count'];
        $pending_orders = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(*) as count FROM tbl_orders WHERE customer_id = '$orderer_id' AND order_status = 'Pending'"))['count'];
        $active_orders = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(*) as count FROM tbl_orders WHERE customer_id = '$orderer_id' AND order_status = 'Active'"))['count'];
        $completed_orders = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(*) as count FROM tbl_orders WHERE customer_id = '$orderer_id' AND order_status = 'Completed'"))['count'];
        ?>

        <div class="col-6 col-md-3 mb-3">
            <div class="card stat-card bg-primary bg-opacity-10 border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Total Orders</h6>
                            <h3 class="mb-0"><?php echo $total_orders; ?></h3>
                        </div>
                        <div class="icon-shape bg-primary bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-inbox text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card stat-card bg-warning bg-opacity-10 border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Pending</h6>
                            <h3 class="mb-0"><?php echo $pending_orders; ?></h3>
                        </div>
                        <div class="icon-shape bg-warning bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-hourglass-split text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card stat-card bg-info bg-opacity-10 border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">In Progress</h6>
                            <h3 class="mb-0"><?php echo $active_orders; ?></h3>
                        </div>
                        <div class="icon-shape bg-info bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-arrow-repeat text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card stat-card bg-success bg-opacity-10 border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Completed</h6>
                            <h3 class="mb-0"><?php echo $completed_orders; ?></h3>
                        </div>
                        <div class="icon-shape bg-success bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Recent Service Requests</h5>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-filetype-csv me-2"></i>CSV</a></li>
                    </ul>
                </div>
            </div>

            <?php
            $result = mysqli_query($mysqli, "SELECT go.*, c.first_name, c.last_name, s.service_name, s.service_cost 
                    FROM tbl_orders AS go 
                    JOIN tbl_customers AS c ON go.customer_id = c.user_id 
                    JOIN tbl_services AS s ON go.service_id = s.service_id 
                    WHERE go.customer_id = '$orderer_id'
                    ORDER BY go.order_date DESC");
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $status_class = '';
                    $icon = '';
                    
                    switch($row['order_status']) {
                        case 'Active':
                            $status_class = 'bg-info';
                            $icon = 'bi-arrow-repeat';
                            break;
                        case 'Pending':
                            $status_class = 'bg-warning';
                            $icon = 'bi-hourglass-split';
                            break;
                        case 'Completed':
                            $status_class = 'bg-success';
                            $icon = 'bi-check-circle';
                            break;
                        default:
                            $status_class = 'bg-secondary';
                            $icon = 'bi-question-circle';
                    }
                    ?>
                    <div class="card order-card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-6 col-lg-3 mb-3 mb-md-0">
                                    <div class="d-flex align-items-center">
                                        <div class="order-icon me-3 <?php echo $status_class; ?>">
                                            <i class="bi <?php echo $icon; ?>"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($row['service_name']); ?></h6>
                                            <small class="text-muted">#<?php echo $row['order_refnumber']; ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Order Date</small>
                                    <span class="fw-medium"><?php echo date('M d, Y', strtotime($row['order_date'])); ?></span>
                                </div>
                                <div class="col-6 col-md-2">
                                    <small class="text-muted d-block">Amount</small>
                                    <span class="fw-medium">₱<?php echo number_format($row['service_cost'], 2); ?></span>
                                </div>
                                <div class="col-6 col-md-2">
                                    <small class="text-muted d-block">Status</small>
                                    <span class="badge <?php echo $status_class; ?>"><?php echo $row['order_status']; ?></span>
                                </div>
                                <div class="col-6 col-md-2 text-end">
                                    <a href="index.php?refnumber=<?php echo $row['order_refnumber']; ?>&page=order_detail" 
                                       class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                        </div>
                        <h5>No orders found</h5>
                        <p class="text-muted">You haven\'t placed any service requests yet.</p>
                        <a href="index.php?page=service" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle me-1"></i> Request a Service
                        </a>
                      </div>';
            }
            ?>
        </div>
    </div>
</div>

<style>
/* Order Cards */
.order-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-left: 4px solid transparent;
}

.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
}

.order-card .order-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

/* Status Badges */
.badge {
    padding: 0.4em 0.8em;
    font-weight: 500;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Stats Cards */
.stat-card {
    transition: transform 0.3s ease;
    border-radius: 0.5rem;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.icon-shape {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .order-card .col-6 {
        margin-bottom: 0.5rem;
    }
    
    .order-card .btn {
        width: 100%;
        margin-top: 0.5rem;
    }
}

.icon-shape {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0; /* Add this to prevent the icon from shrinking */
}

/* Add this to ensure the icon itself is properly centered */
.icon-shape i, .order-icon i {
    line-height: 1; /* Removes any extra line height */
    font-size: 1.25rem; /* Adjust size as needed */
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}
</style>

<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>