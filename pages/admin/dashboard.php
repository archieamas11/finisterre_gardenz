<?php
// Fetch plots information
$total_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(grave_id) as total_plots FROM grave_points"));
$available_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(status) as status FROM grave_points WHERE status = 'vacant'"));
$reserved_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(status) as status FROM grave_points WHERE status = 'reserved'"));
$occupied_plots = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(status) as status FROM grave_points WHERE status = 'occupied1' OR status = 'occupied2'"));

// Fetch pending orders count
$pending_orders = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(order_id) as `order` FROM tbl_orders WHERE order_status = 'pending'"));

// Fetch recent bookings
$recent_bookings = mysqli_query($mysqli, "SELECT grave.*, services.service_name, services.service_cost, points.block, dead.dead_fullname, customers.first_name, customers.last_name FROM tbl_orders AS grave 
                                                                JOIN tbl_customers AS customers ON grave.customer_id = customers.customer_id 
                                                                JOIN tbl_services AS services ON grave.service_id = services.service_id 
                                                                LEFT JOIN grave_points AS points ON grave.grave_id = points.grave_id 
                                                                LEFT JOIN tbl_deceased AS dead on grave.deceased_id = dead.record_id 
                                                                WHERE grave.order_status = 'complete' LIMIT 5");

$total = $total_plots['total_plots'];
$available_percentage = $total > 0 ? round(($available_plots['status'] / $total) * 100, 1) : 0;
$reserved_percentage = $total > 0 ? round(($reserved_plots['status'] / $total) * 100, 1) : 0;
$occupied_percentage = $total > 0 ? round(($occupied_plots['status'] / $total) * 100, 1) : 0;

$notifications = mysqli_query($mysqli, "SELECT * FROM tbl_notifications WHERE is_read = 0 ORDER BY created_at DESC");
$view_all_notifications = mysqli_query($mysqli, "SELECT * FROM tbl_notifications ORDER BY created_at DESC LIMIT 5");

$profile_result = mysqli_query($mysqli, "SELECT users.*, customers.* FROM tbl_users AS users 
                                                        JOIN tbl_customers AS customers ON users.user_id = customers.user_id WHERE customers.customer_id = '{$_SESSION['customer_id']}'");
$profile_data = mysqli_fetch_assoc($profile_result);
?>

<!-- Load Admin Dashboard Content -->
<div class="page-heading d-flex justify-content-between align-items-center">
    <div class="dashboard-title">
        <h3 class="lh-1">Welcome back, <?php echo ucwords($profile_data['first_name']) ?? 'Admin'; ?>!</h3>
        <p class="text-muted lh-1"><?php echo date('l, F j'); ?></p>
    </div>
    <div class="dashboard-notification">
        <div class="notification-container position-relative">
            <button class="btn btn-light shadow-sm border-0 rounded-circle notification-btn" title="View Notifications"
                data-bs-toggle="modal" data-bs-target="#view-notifications" data-bs-toggle="modal"
                data-bs-target="#view-notifications" onclick="loadNotifications()">
                <i class="iconly-boldNotification text-primary"></i>
            </button>
            <span id="notif-count"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger <?php echo (mysqli_num_rows($notifications) == 0) ? 'd-none' : ''; ?>"
                style="font-size: 0.7rem; width: 25px; height: 20px; display: flex; align-items: center; justify-content: center; padding: 0;">
                <?php echo mysqli_num_rows($notifications); ?>
            </span>
        </div>
    </div>

    <!-- Mark the notificaiton as read when the notification button is clicked -->
    <script>
        function markNotificationsAsRead() {
            fetch('<?php echo WEBROOT; ?>pages/admin/mark_read.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response:', data); // Debug log
                    if (data.success) {
                        // Update notification count to 0 and hide badge
                        const notifCount = document.getElementById('notif-count');
                        notifCount.textContent = '0';
                        notifCount.classList.add('d-none');
                        notifCount.classList.remove('notification-pulse');
                        console.log('Notification count updated to 0'); // Debug log
                    } else {
                        console.error('Error from server:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>

    <!-- Load all unread notifications -->
    <script>
        function loadNotifications() {
            fetch('<?php echo WEBROOT; ?>pages/admin/fetch_notifications.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('notificationList').innerHTML = html;
                    markNotificationsAsRead(); // Optional: mark as read after loading
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                });
        }
    </script>
</div>

<!-- Notifications Modal -->
<div class="modal fade" id="view-notifications" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-4 shadow-sm">
            <div class="modal-body">
                <div class="header-wrapper d-flex justify-content-between mb-3">
                    <div class="header-title">
                        <h5 class="mb-1 fw-bold">Notifications</h5>
                        <p class="mb-0 text-muted" style="font-size: 0.85rem;">View your recent notifications.</p>
                    </div>
                    <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="notification-list" id="notificationList" style="max-width: 100%; max-height: 80vh; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                    <!-- Dynaically load all unread notifications -->
                </div>
            </div>
        </div>
    </div>
</div>



<!-- main dashboard content -->
<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="row mt-4">
                <div class="col-12 col-md-6 col-lg-3 mb-3">
                    <div class="card stat-card bg-primary bg-opacity-10 border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-1">Total Plots</h6>
                                    <h3 class="mb-0"><?php echo $total_plots['total_plots']; ?></h3>
                                </div>
                                <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-25 rounded-circle"
                                    style="width: 60px; height: 60px;">
                                    <i class="iconly-boldChart text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3 mb-3">
                    <div class="card stat-card bg-success bg-opacity-10 border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-1">Available</h6>
                                    <h3 class="mb-0"><?php echo $available_plots['status']; ?></h3>
                                </div>
                                <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-25 rounded-circle"
                                    style="width: 60px; height: 60px;">
                                    <i class="iconly-boldTick-Square text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-3">
                    <div class="card stat-card bg-warning bg-opacity-10 border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-1">Reserved</h6>
                                    <h3 class="mb-0"><?php echo $reserved_plots['status']; ?></h3>
                                </div>
                                <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-25 rounded-circle"
                                    style="width: 60px; height: 60px;">
                                    <i class="iconly-boldTime-Circle text-warning fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-3">
                    <div class="card stat-card bg-danger bg-opacity-10 border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-1">Occupied</h6>
                                    <h3 class="mb-0"><?php echo $occupied_plots['status']; ?></h3>
                                </div>
                                <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-25 rounded-circle"
                                    style="width: 60px; height: 60px;">
                                    <i class="iconly-boldUser text-danger fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings section -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div
                            class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-semibold">Recent Bookings</h5>
                            <a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=order"
                                class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <?php if ($recent_bookings && mysqli_num_rows($recent_bookings) > 0): ?>
                                    <thead class="">
                                        <tr>
                                            <th class="border-0 text-muted text-uppercase fw-medium"
                                                style="font-size: 0.75rem;">Order ID</th>
                                            <th class="border-0 text-muted text-uppercase fw-medium"
                                                style="font-size: 0.75rem;">Customer</th>
                                            <th class="border-0 text-muted text-uppercase fw-medium"
                                                style="font-size: 0.75rem;">Location</th>
                                            <th class="border-0 text-muted text-uppercase fw-medium"
                                                style="font-size: 0.75rem;">Status</th>
                                            <th class="border-0 text-muted text-uppercase fw-medium"
                                                style="font-size: 0.75rem;">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_array($recent_bookings)) :
                                                $order_refnumber = $row['order_refnumber'];
                                                $customer_name = $row['first_name'] . ' ' . $row['last_name'];
                                                $location = ucwords('Block ' . $row['block'] . ' - Grave ' . $row['grave_id']);
                                                $order_date = date('M d, Y', strtotime($row['order_date']));
                                                $status = $row['order_status'];
                                                if ($status == 'Pending') {
                                                    $order_status = '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning">Pending</span>';
                                                } elseif ($status == 'Completed') {
                                                    $order_status = '<span class="badge bg-success bg-opacity-10 text-success border border-success">Completed</span>';
                                                } elseif ($status == 'Active') {
                                                    $order_status = '<span class="badge bg-info bg-opacity-10 text-info border border-info">Active</span>';
                                                } elseif ($status == 'Cancelled') {
                                                    $order_status = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Cancelled</span>';
                                                } else {
                                                    $order_status = '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">' . $status . '</span>';
                                                }
                                            ?>
                                        <tr>
                                            <style>
                                            td {
                                                padding: 20px !important;
                                            }
                                            </style>
                                            <td class="py-3 border-0">
                                                <span class="fw-medium text-dark"
                                                    style="font-size: 0.875rem;">#<?php echo $order_refnumber; ?></span>
                                            </td>
                                            <td class="py-3 border-0">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 32px; height: 32px;">
                                                            <i class="iconly-boldProfile text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <span class="text-dark fw-medium"
                                                        style="font-size: 0.875rem;"><?php echo htmlspecialchars($customer_name); ?></span>
                                                </div>
                                            </td>
                                            <td class="py-3 border-0">
                                                <span class="text-muted"
                                                    style="font-size: 0.8rem;"><?php echo $location; ?></span>
                                            </td>
                                            <td class="py-3 border-0">
                                                <?php
                                                        $status = ($row['order_status']);
                                                        $badgeClass = '';
                                                        switch ($status) {
                                                            case 'complete':
                                                                $badgeClass = 'success';
                                                                break;
                                                            case 'cancelled':
                                                                $badgeClass = 'danger';
                                                                break;
                                                            case 'pending':
                                                                $badgeClass = 'warning';
                                                                break;
                                                            default:
                                                                $badgeClass = 'secondary';
                                                        }
                                                        ?>
                                                <span
                                                    class="bg-opacity-10 text-<?php echo $badgeClass; ?> border border-<?php echo $badgeClass; ?> badge bg-<?php echo $badgeClass; ?>"><?php echo ucfirst($status); ?></span>
                                            </td>
                                            <td class="py-3 border-0">
                                                <span class="text-muted"
                                                    style="font-size: 0.8rem;"><?php echo $order_date; ?></span>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                    <?php else: ?>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5;"></i>
                                                <div class="mt-2">No recent bookings found</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- plot status line chart -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom-0 py-3">
                            <h5 class="mb-0 fw-semibold">Plot Status Overview</h5>
                        </div>
                        <div class="card-body">
                            <!-- Available Status -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted fw-medium" style="font-size: 0.875rem;">Available</span>
                                    <span class="fw-bold text-success"
                                        style="font-size: 0.875rem;"><?php echo $available_percentage; ?>%</span>
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 3px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: <?php echo $available_percentage; ?>%; border-radius: 3px;"
                                        aria-valuenow="<?php echo $available_percentage; ?>" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted mt-1 d-block"
                                    style="font-size: 0.75rem;"><?php echo $available_plots['status']; ?> plots
                                    available</small>
                            </div>

                            <!-- Reserved Status -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted fw-medium" style="font-size: 0.875rem;">Reserved</span>
                                    <span class="fw-bold text-warning"
                                        style="font-size: 0.875rem;"><?php echo $reserved_percentage; ?>%</span>
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 3px;">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: <?php echo $reserved_percentage; ?>%; border-radius: 3px;"
                                        aria-valuenow="<?php echo $reserved_percentage; ?>" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted mt-1 d-block"
                                    style="font-size: 0.75rem;"><?php echo $reserved_plots['status']; ?> plots
                                    reserved</small>
                            </div>

                            <!-- Occupied Status -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted fw-medium" style="font-size: 0.875rem;">Occupied</span>
                                    <span class="fw-bold text-danger"
                                        style="font-size: 0.875rem;"><?php echo $occupied_percentage; ?>%</span>
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 3px;">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                        style="width: <?php echo $occupied_percentage; ?>%; border-radius: 3px;"
                                        aria-valuenow="<?php echo $occupied_percentage; ?>" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted mt-1 d-block"
                                    style="font-size: 0.75rem;"><?php echo $occupied_plots['status']; ?> plots
                                    occupied</small>
                            </div>

                            <!-- Total Summary -->
                            <div class="text-center pt-4 border-top">
                                <h3 class="text-primary mb-1 fw-bold"><?php echo $total; ?></h3>
                                <p class="text-muted mb-0" style="font-size: 0.875rem;">Total Plots</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-semibold">Recent Activity</h5>
                            <a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=activity"
                                class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body p-1">
                            <?php
                                $activity_result = mysqli_query($mysqli, "SELECT act.*, customers.first_name, customers.last_name FROM tbl_activity AS act JOIN tbl_customers AS customers ON act.user_id = customers.user_id ORDER BY act_date DESC LIMIT 6");
                                if ($activity_result && mysqli_num_rows($activity_result) > 0): ?>
                            <div class="activity-list">
                                <div class="list-group list-group-flush">
                                    <?php while ($activity = mysqli_fetch_array($activity_result)): ?>
                                    <div class="list-group-item border-0 py-3 px-4">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="iconly-boldActivity text-success fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <h6 class="mb-0 fw-semibold text-dark" style="font-size: 0.75rem;">
                                                        <?php echo htmlspecialchars($activity['act_title']); ?>
                                                    </h6>
                                                    <small class="text-muted" style="font-size: 0.75rem;">
                                                        <?php echo date('M d, Y', strtotime($activity['act_date'])); ?>
                                                    </small>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                                            style="width: 24px; height: 24px;">
                                                            <i class="iconly-boldProfile text-primary"
                                                                style="font-size: 0.75rem;"></i>
                                                        </div>
                                                        <span class="text-muted fw-medium" style="font-size: 0.8rem;">
                                                            <?php echo htmlspecialchars($activity['first_name']); ?>
                                                        </span>
                                                    </div>
                                                    <span class="mx-2 text-muted" style="font-size: 0.7rem;">•</span>
                                                    <span class="text-muted" style="font-size: 0.75rem;">
                                                        ID: #<?php echo htmlspecialchars($activity['act_id']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-5 px-4">
                                <div class="mb-3">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 60px; height: 60px;">
                                        <i class="iconly-boldActivity text-muted" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <h6 class="text-muted mb-2" style="font-size: 0.875rem;">No recent activity</h6>
                                <p class="text-muted mb-0" style="font-size: 0.75rem;">Activity logs will appear here
                                    when actions are performed.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- recent notifications page -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div
                            class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-semibold">Recent Notifications</h5>
                            <a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=activity"
                                class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body p-6">
                            <div class="notification-list">
                                <?php if (mysqli_num_rows($view_all_notifications) > 0): ?>
                                <div class="list-group list-group-flush mt-3">
                                    <?php while ($all_notifications = mysqli_fetch_assoc($view_all_notifications)): ?>
                                    <a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=order"
                                        class="list-group-item list-group-item-action border-0 py-2 rounded-3 mb-2 <?php echo $notification['is_read'] ? '' : 'bg-light'; ?> text-decoration-none"
                                        style="cursor: pointer;">
                                        <div class="d-flex w-100 justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <div class="me-2">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 30px; height: 30px;">
                                                            <i class="iconly-boldNotification text-primary fs-6"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-semibold text-dark"
                                                            style="font-size: 0.8rem;">
                                                            <?php echo htmlspecialchars($all_notifications['title']); ?>
                                                        </h6>
                                                        <?php if (!$all_notifications['is_read']): ?>
                                                        <span
                                                            class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-2 py-1"
                                                            style="font-size: 0.6rem;">New</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <p class="mb-1 text-muted lh-base" style="font-size: 0.75rem;">
                                                    <?php echo htmlspecialchars($all_notifications['message']); ?></p>
                                                <div class="d-flex align-items-center text-muted">
                                                    <i class="bi bi-clock me-1" style="font-size: 0.7rem;"></i>
                                                    <small
                                                        style="font-size: 0.7rem;"><?php echo date('M d, Y H:i', strtotime($all_notifications['created_at'])); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php endwhile; ?>
                                </div>
                                <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="mb-2">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px;">
                                            <i class="iconly-boldNotification text-muted"
                                                style="font-size: 1.5rem;"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-2" style="font-size: 0.8rem;">No notifications yet</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.75rem;">You're all caught up! Check
                                        back later for updates.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>
</div>