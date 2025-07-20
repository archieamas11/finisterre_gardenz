<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Welcome to Your Dashboard</h3>
                <p class="text-subtitle text-muted">View and manage your service orders below</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title"><i data-lucide="user-round-pen"></i> Order Information</h5>
                        <div class="buttons d-flex justify-content-center align">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle me-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-download"></i>&nbsp; Export</button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#"><i class="bi bi-printer"></i> Print</a>
                                    <a class="dropdown-item" href="#"><i class="bi bi-filetype-csv"></i> CSV</a>
                                </div>
                            </div>
                            <a href="<?php echo web_root; ?>pages/user/index.php?page=service" class="btn btn-primary">+&nbsp;Add New Order</a>
                        </div>
                    </div>
                <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                    <table class="table table-striped" id="table1">
                        <?php
                        $orderer_id = $_SESSION["id"];
                        $result = mysqli_query($mysqli, "SELECT go.*, u.user_name, s.service_name 
                                FROM tbl_orders AS go 
                                JOIN tbl_users AS u ON go.customer_id = u.user_id 
                                JOIN tbl_services AS s ON go.service_id = s.service_id 
                                WHERE go.customer_id = '$orderer_id'");
                        ?>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                echo '<tr>';
                                echo '<td>' . $row['order_id'] . '</td>';
                                echo '<td>' . $row['user_name'] . '</td>';
                                echo '<td>' . $row['service_name'] . '</td>';
                                echo '<td>' . $row['order_date'] . '</td>';
                                echo '<td>' . $row['order_total'] . '</td>';
                                if ($row['order_status'] == 'Active') {
                                    echo '<td><span class="badge bg-primary">' . $row['order_status'] . '</span></td>';
                                } elseif ($row['order_status'] == 'Pending') {
                                    echo '<td><span class="badge bg-warning">' . $row['order_status'] . '</span></td>';
                                } elseif ($row['order_status'] == 'Completed') {
                                    echo '<td><span class="badge bg-secondary">' . $row['order_status'] . '</span></td>';
                                }
                                echo '<td>
                                            <a href="index.php?refnumber=' . $row['order_refnumber'] . ' & page=order_detail" class="btn btn-sm btn-outline-primary">More Details</a>
                                        </td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>