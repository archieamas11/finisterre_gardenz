<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="card-title"><i class="bi bi-list-check"></i> Active Orders Information</h5>
    <div class="buttons d-flex justify-content-center align-items-center">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle me-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bi bi-download"></i>&nbsp; Export
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#"><i class="bi bi-printer"></i> Print</a>
                <a class="dropdown-item" href="#"><i class="bi bi-filetype-csv"></i> CSV</a>
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-customer">+&nbsp;Add New Order</button>
    </div>
</div>

<div class="table-responsive" id="dataTable" role="grid" aria-describedby="dataTable_info">
    <table class="table table-striped" id="table2">
        <?php 
        $result = mysqli_query($mysqli, "SELECT grave.*, customers.first_name, customers.last_name, services.service_name, services.service_cost, points.block FROM tbl_orders AS grave 
        JOIN tbl_customers AS customers ON grave.customer_id = customers.customer_id 
        JOIN tbl_services AS services ON grave.service_id = services.service_id 
        JOIN grave_points AS points ON grave.grave_id = points.grave_id WHERE grave.order_status = 'active' AND grave.payment_status = 'paid'"); 
        ?>
        <thead>
            <tr>
                <th class="col-1">#</th>
                <th class="col-2">REFERENCE NUMBER</th>
                <th class="col-2">CUSTOMER</th>
                <th class="col-2">LOCATION</th>
                <th class="col-2">SERVICE</th>
                <th class="col-2 text-center">STATUS</th>
                <th class="col-1 text-center">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$result) { echo mysqli_error($mysqli); } ?>
            <?php while ($row = mysqli_fetch_array($result)) :
                $order_id = $row['order_id'];
                $order_refnumber = $row['order_refnumber'];
                $customer_name = $row['first_name'] . ' ' . $row['last_name'];
                $service_name = $row['service_name'];      
            ?>
            <tr>
                <td class="col-1"><?php echo $order_id; ?></td>
                <td class="col-2"><?php echo $order_refnumber; ?></td>
                <td class="col-2"><?php echo ucwords($customer_name); ?></td>
                <td class="col-2"><?php echo ucwords('Block ' . $row['block'] . ' - Grave ' . $row['grave_id']); ?></td>
                <td class="col-2"><?php echo ucwords($service_name); ?></td>
                <td class="col-2 text-center">
                    <?php 
                        $status = ($row['order_status']);
                        $badgeClass = '';
                        switch($status) {
                            case 'active':
                            $badgeClass = 'bg-success';
                            break;
                            case 'cancelled':
                            $badgeClass = 'bg-warning';
                            break;
                            case 'pending':
                            $badgeClass = 'bg-danger';
                            break;
                            default:
                            $badgeClass = 'bg-secondary';
                        }
                    ?>
                    <span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($status); ?></span>
                </td>
                <td class="align-middle text-center col-1">
                    <div class="d-flex gap-1">
                        <!-- Accept Button -->
                        <button class="btn btn-success" title="Mark as done" onclick="location.href='function/order_function.php?referencenumber=<?php echo $order_refnumber; ?>&function=complete'">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <!-- Reject Button -->
                        <button class="btn btn-danger" title="Cancel order" onclick="location.href='function/order_function.php?refnumber=<?php echo $order_refnumber; ?>&function=cancel'">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <!-- view details Button -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#view-setup-<?php echo $row['order_id']?>" title="Add Lot Setup Record">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
