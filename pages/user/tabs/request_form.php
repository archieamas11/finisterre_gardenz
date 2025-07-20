<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Service Request Form</h3>
                <p class="text-subtitle text-muted">Please complete the service request details below</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=map">Services</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Service Form</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <?php $dead_information = mysqli_query($mysqli, "SELECT dead.* FROM tbl_deceased AS dead
                                                    JOIN grave_points as location ON dead.grave_id = location.grave_id
                                                    WHERE dead.customer_id = ".$_SESSION['customer_id']); ?>

    <!-- request form section -->
    <div class="card">
        <div class="card-body">
            <?php $product_id = $_GET['service_id'];     
            function generateUniqueOrderCode($mysqli) {
                $numbers = '0123456789';
                $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            
                do {
                    $orderCode = 'ORD-';
            
                    // Add 4 random letters
                    for ($i = 0; $i < 4; $i++) {
                        $orderCode .= $letters[rand(0, strlen($letters) - 1)];
                    }
            
                    $orderCode .= '-';
            
                    // Add 4 random numbers
                    for ($i = 0; $i < 4; $i++) {
                        $orderCode .= $numbers[rand(0, strlen($numbers) - 1)];
                    }
         
                    // Check if this code already exists in the database
                    $result = mysqli_query($mysqli, "SELECT COUNT(*) FROM tbl_orders WHERE order_refnumber = '$orderCode'");
                    $exists = mysqli_fetch_array($result)[0];
            
                } while ($exists > 0); // Keep generating until a unique code is found
            
                return $orderCode;
            }
            $order_code_generated = generateUniqueOrderCode($mysqli); 
            ?>

            <div class="row mx-3 py-2">
                <div class="col-md-4 mb-4">
                    <?php $service_query = mysqli_query($mysqli, "SELECT * FROM tbl_services WHERE service_id = $product_id");
                        while ($service = mysqli_fetch_array($service_query)) {
                            $product_name = $service['service_name'];
                            $product_cost = $service['service_cost'];
                        ?>
                        <h4 class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Service Info</span>
                        </h4>
                        <div class="mb-3">
                            <label for="service">Service</label>
                            <input type="text" class="form-control" id="service" name="service" value="<?php echo $service['service_name']; ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="service_cost">Service Cost</label>
                            <input type="text" class="form-control" id="service_cost" name="service_cost" value="<?php echo $service['service_cost']; ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted"><small>Note: Please proceed to the management office to pay the service you have requested</small></p>
                        </div>
                    <?php 
                } ?>
                </div>

                <!-- grave info section -->
                <div class="col-md-8">
                    <form action="function/user_function.php" method="POST">
                        <input type="hidden" name="service_id" value="<?php echo $product_id; ?>">
                        <div class="mb-3">
                            <label for="deceasedId">Deceased <span class="text-danger">*</span></label>
                            <select name="deceasedId" id="deceasedId" class="form-select customer-site" required>
                                <option value="" hidden>Select a deceased</option>
                                <?php while ($dead = mysqli_fetch_assoc($dead_information)): ?>
                                    <option value="<?php echo $dead['record_id']; ?>">
                                        <?php echo ucwords($dead['dead_fullname']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="instruction" class="form-label label mt-2">Instruction</label>
                            <textarea class="form-control" id="instruction" name="instruction" rows="4" placeholder="Type your instruction here..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="cc-name">Order number</label>
                                <input type="text" class="form-control" id="cc-name" name="orderNumber" value="<?php echo $order_code_generated; ?>" readonly>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-lg btn-block" name="btn-submit-checkout" value="Submit">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>