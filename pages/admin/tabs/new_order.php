<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="card-title mb-0"><i class="bi bi-clock-history"></i> Pending Orders</h5>
    <div class="d-flex gap-2 align-items-center">
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-download"></i> Export
            </button>
            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                <li><a class="dropdown-item" href="#" onclick="window.print()"><i class="bi bi-printer"></i> Print</a></li>
                <li><a class="dropdown-item" href="#" id="exportCsv"><i class="bi bi-filetype-csv"></i> CSV</a></li>
            </ul>
        </div>
        <button class="btn btn-primary" >
            <i class="bi bi-plus"></i> 
            Add New Order
            <a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=shop"></a>
        </button>
    </div>
</div>

<div class="table-responsive" id="dataTable" role="grid" aria-describedby="dataTable_info">
    <table class="table table-striped" id="table1">
        <?php 
        $result = mysqli_query($mysqli, "SELECT orders.*, customer.first_name, customer.last_name, services.service_name, services.service_cost, location.block 
        FROM tbl_orders AS orders
        LEFT JOIN tbl_customers AS customer ON orders.customer_id = customer.customer_id 
        LEFT JOIN tbl_services AS services ON orders.service_id = services.service_id 
        LEFT JOIN grave_points AS location ON orders.grave_id = location.grave_id
        WHERE orders.order_status = 'pending' AND orders.payment_status != 'cancelled'"); 
        ?>
        <thead>
            <tr>
                <th class="col-1">#</th>
                <th class="col-2">REFERENCE NUMBER</th>
                <th class="col-2">CUSTOMER</th>
                <th class="col-3">SERVICE</th>
                <th class="col-2">LOCATION</th>
                <th class="col-2 text-center">PAYMENT STATUS</th>
                <th class="col-1 text-center">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_array($result)) :
                $order_id = $row['order_id'];
                $order_refnumber = $row['order_refnumber'];
                $customer_name = $row['first_name'] . ' ' . $row['last_name'];
                $service_name = $row['service_name'];
                $order_date = date('F j, Y', strtotime($row['order_date']));
            ?>
            <tr>
                <td class="col-1"><?php echo $order_id; ?></td>
                <td class="col-2"><?php echo $order_refnumber; ?></td>
                <td class="col-2"><?php echo ucwords($customer_name); ?></td>
                <td class="col-3"><?php echo ucwords($service_name); ?></td>
                <td class="col-2"><?php echo ucwords('Block ' . $row['block'] . ' - Grave ' . $row['grave_id']); ?></td>
                <td class="col-2 text-center">
                    <?php 
                    $status = ($row['payment_status']);
                    $badgeClass = '';
                    switch($status) {
                        case 'paid':
                        $badgeClass = 'bg-success';
                        break;
                        case 'unpaid':
                        $badgeClass = 'bg-warning';
                        break;
                        case 'cancelled':
                        $badgeClass = 'bg-danger';
                        break;
                        default:
                        $badgeClass = 'bg-secondary';
                    }
                    ?>
                    <span class="badge <?php echo $badgeClass; ?>"><?php echo ucwords($row['payment_status']); ?></span>
                </td>
                <td class="align-middle text-center col-1">
                    <div class="d-flex gap-1">
                        <!-- Accept Button -->
                        <button class="btn btn-success" title="Accept Order" onclick="location.href='function/order_function.php?refnumber=<?php echo $order_refnumber; ?>&function=pay'">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <!-- Reject Button -->
                        <button class="btn btn-danger" title="Reject Order" onclick="location.href='function/order_function.php?refnumber=<?php echo $order_refnumber; ?>&function=cancel'">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <!-- view details Button -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#view-setup-<?php echo $row['order_id']?>" title="View Order">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
