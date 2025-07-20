<?php $refnumber = isset($_GET['refnumber']) ? $_GET['refnumber'] : ''; ?>
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Order Details</h3>
            <p class="text-subtitle text-muted">Summary of your order</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo htmlspecialchars(WEBROOT); ?>pages/user/index.php?page=home">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Order Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Order Summary: #<?php echo htmlspecialchars($refnumber); ?></h5>
            <button id="btnPrint" class="btn btn-outline-primary btn-sm print" onclick="PrintDiv('dataTable')">
                <i class="bi bi-printer"></i> Print <!-- Assuming Bootstrap Icons (bi) are available -->
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                <table class="table table-striped table-bordered table-hover my-0">
                    <?php 
                        // It's better to use prepared statements to prevent SQL injection.
                        // For demonstration, keeping existing mysqli_query structure but ensuring $refnumber is handled.
                        // If $mysqli is not globally available, ensure it's properly initialized.
                        if ($mysqli && !empty($refnumber)) {
                            $stmt = mysqli_prepare($mysqli, "SELECT orders.*, users.user_name, users.user_email, dead.dead_fullname, location.grave_id, location.block, services.service_name, services.service_cost
                            FROM tbl_orders AS orders 
                            JOIN tbl_users AS users ON orders.customer_id = users.user_id 
                            JOIN tbl_deceased AS dead ON orders.deceased_id = dead.record_id
                            JOIN grave_points as location ON orders.grave_id = location.grave_id
                            JOIN tbl_services AS services ON orders.service_id = services.service_id
                            WHERE order_refnumber = ?");
                            mysqli_stmt_bind_param($stmt, "s", $refnumber);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                        } else {
                            $result = false;
                        }
                    ?>
                    <tbody>
                        <?php
                            if ($result && $row = mysqli_fetch_array($result)) {
                                echo '<tr>';
                                echo '<th scope="row" style="width: 200px;">Order Code Number</th><td>' . htmlspecialchars($row['order_refnumber']) . '</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row">Customer Name</th><td>' . htmlspecialchars(ucwords($row['user_name'])) . '</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row">Email</th><td>' . htmlspecialchars($row['user_email']) . '</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row">Service Name</th><td>' . htmlspecialchars($row['service_name']) . '</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row">Grave Location</th><td>Block ' . htmlspecialchars($row['block']) . ' - Grave ' . htmlspecialchars($row['grave_id']) . '</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row">Deceased Name</th><td>' . htmlspecialchars(ucwords($row['dead_fullname'])) . '</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row">Order Total</th><td>₱' . htmlspecialchars(number_format($row['service_cost'], 2)) . '</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row">Instruction:</th><td>' . (empty($row['instruction']) ? 'N/A' : nl2br(htmlspecialchars($row['instruction']))) . '</td>';
                                echo '</tr>';

                                // Payment Status Badge
                                $payment_status_badge = '';
                                $payment_status = isset($row['payment_status']) ? trim($row['payment_status']) : '';
                                $payment_status_lower = strtolower($payment_status);
                                if ($payment_status_lower === 'paid') {
                                    $payment_status_badge = '<span class="badge bg-success">' . htmlspecialchars($payment_status) . '</span>';
                                } elseif ($payment_status_lower === 'unpaid') {
                                    $payment_status_badge = '<span class="badge bg-warning text-dark">' . htmlspecialchars($payment_status) . '</span>';
                                } elseif ($payment_status_lower === 'cancelled') {
                                    $payment_status_badge = '<span class="badge bg-danger">' . htmlspecialchars($payment_status) . '</span>';
                                } elseif (empty($payment_status_lower)) {
                                    $payment_status_badge = '<span class="badge bg-secondary">N/A</span>';
                                } else {
                                    $payment_status_badge = '<span class="badge bg-secondary">' . htmlspecialchars($payment_status) . '</span>';
                                }
                                echo '<tr>';
                                echo '<th scope="row">Payment Status:</th><td>' . $payment_status_badge . '</td>';
                                echo '</tr>';

                                // Order Status Badge
                                $order_status_badge = '';
                                $order_status_lower = strtolower($row['order_status']);
                                if ($order_status_lower == 'completed') {
                                    $order_status_badge = '<span class="badge bg-success">' . htmlspecialchars($row['order_status']) . '</span>';
                                } elseif ($order_status_lower == 'pending') {
                                    $order_status_badge = '<span class="badge bg-warning text-dark">' . htmlspecialchars($row['order_status']) . '</span>';
                                } elseif ($order_status_lower == 'processing') {
                                    $order_status_badge = '<span class="badge bg-info text-dark">' . htmlspecialchars($row['order_status']) . '</span>';
                                } elseif ($order_status_lower == 'cancelled') {
                                    $order_status_badge = '<span class="badge bg-danger">' . htmlspecialchars($row['order_status']) . '</span>';
                                } else {
                                    $order_status_badge = htmlspecialchars($row['order_status']);
                                }
                                echo '<tr>';
                                echo '<th scope="row">Order Status:</th><td>' . $order_status_badge . '</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row">Order Date:</th><td>' . htmlspecialchars(date("F j, Y", strtotime($row['order_date']))) . '</td>';
                                echo '</tr>';
                                if (isset($stmt)) { mysqli_stmt_close($stmt); }
                            } else {
                                echo '<tr><td colspan="2" class="text-center">Order details not found or an error occurred.</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



