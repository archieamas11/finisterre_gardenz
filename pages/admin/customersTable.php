<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="card-title"><i data-lucide="user-round-pen"></i> Customers Information</h5>
    <div class="buttons d-flex justify-content-center align">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle me-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-download"></i>&nbsp; Export</button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#"><i class="bi bi-printer"></i> Print</a>
                <a class="dropdown-item" href="#"><i class="bi bi-filetype-csv"></i> CSV</a>
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-customer">+&nbsp;Add Customer</button>
    </div>
</div>

<!-- customers table -->
<div class="table-responsive">
    <table class="table table-striped" id="table1">
        <?php $result = mysqli_query($mysqli, "SELECT customers.*, users.user_type, users.user_status 
            FROM tbl_customers AS customers
            LEFT JOIN tbl_users AS users ON customers.user_id = users.user_id
            WHERE (users.user_type = 'user' AND users.user_status != 'archived') 
            OR users.user_id IS NULL
            "); 
        ?>
            <thead>
                <th class="col-1">#</th>
                <th class="col-3">CUSTOMER NAME</th>
                <th class="col-4">ADDRESS</th>
                <th class="col-4">CONTACT INFORMATION</th>
                <th class="col-1 text-center">ACTION</th>
            </thead>
            <tbody>
            <?php if (!$result) { echo mysqli_error($mysqli); } ?>
                <?php while ($row = mysqli_fetch_array($result)) : 
                    $fullName = str_replace(',', '</br>', $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']);
                    $exist = mysqli_query($mysqli, "SELECT CASE WHEN l.grave_id IS NOT NULL THEN 'TRUE' ELSE NULL END AS exist
                    FROM tbl_lot l
                    JOIN tbl_customers c ON c.customer_id = l.customer_id
                    WHERE c.customer_id = " . $row['customer_id']);
                    $exist = mysqli_fetch_assoc($exist);

                    if (isset($exist['exist']) && $exist['exist'] == 'TRUE') {
                        $disabled = 'disabled';
                        $disabled_edit = '';
                        $btn = 'secondary';
                        $btn_edit = 'success';
                    } else {
                        $disabled = '';
                        $disabled_edit = 'disabled';
                        $btn = 'primary';
                        $btn_edit = 'secondary';
                    }
                    ?>
                    <tr>
                        <td class="col-1"><?php echo $row['customer_id']; ?></td>
                        <td class="col-3"><?php echo ucwords($fullName); ?></td>
                        <td class="col-4"><?php echo $row['address'] ? ucwords($row['address']) : '<span class="badge bg-light-danger">N/A</span>'; ?></td>
                        <td class="col-4">
                            <i class="bi bi-telephone"></i> <?php echo $row['contact_number'] ? $row['contact_number'] : '<span class="badge bg-light-danger">N/A</span>';?><br>
                            <i class="bi bi-envelope"></i> <?php echo $row['email'] ? $row['email'] : '<span class="badge bg-light-danger">N/A</span>'; ?>
                        </td>
                        <td class="align-middle text-center col-1">
                            <div class="d-flex gap-1">
                                <!-- add lot setup record button -->
                                <button class="btn btn-<?php echo $btn ?>" onclick="validateCustomerData(<?php echo $row['customer_id']?>)" <?php echo $disabled ?> title="Add Lot Setup Record">
                                    <i class="bi bi-house-add"></i>
                                </button>

                                <!-- edit information button -->
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit-customer-<?php echo $row["customer_id"]?>" title="Edit Customer Information">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <!-- view customers information button -->
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#view-customer-<?php echo $row["customer_id"]?>" title="View Customer Information">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
        </table>
    </div>

<!-- Add Customer Modal -->
<div class="modal fade" id="add-customer" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form action="function/customers/addCustomer.php" method="post">
                <div class="modal-body p-5">
                    <!-- this is header -->
                    <div class="header-wrapper d-flex justify-content-between mb-3">
                        <div class="header-title">
                            <h3>Add Customer Record</h2>
                            <p>Click save record when you're done.</p>
                        </div>
                        <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- start of modal body -->
                    <div class="row mb-2">
                        <div class="col-md-3 col-sm-6">
                            <label for="first-name">First name <i class="text-danger">*</i></label>
                            <input type="text" name="first-name" id="first-name" class="form-control" placeholder="e.g. Juan" required>
                        </div>

                        <!-- middle name -->
                        <div class="col-md-3 col-sm-6">
                            <label for="middle-name">Middle name </label>
                            <input type="text" name="middle-name" id="middle-name" class="form-control" placeholder="e.g Dela">
                        </div>

                        <!-- last name input -->
                        <div class="col-md-3 col-sm-6">
                            <label for="family-name">Last name <i class="text-danger">*</i></label>
                            <input type="text" name="family-name" id="family-name" class="form-control" placeholder="e.g Cruz" required>
                        </div>

                        <!-- nickanme input -->
                        <div class="col-md-3 col-sm-6">
                            <label for="nickname">NIckaname </label>
                            <input type="text" name="nickname" id="nickname" class="form-control" placeholder="Enter your nickname">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <!-- address -->
                        <div class="col-md-12 col-sm-12">
                            <label for="home-address">Home Address <i class="text-danger">*</i></label>
                            <input type="text" name="home-address" id="home-address" class="form-control" placeholder="House No./Unit/Purok/Subdivision/Village - Brgy. - City - Province" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <!-- contact number -->
                        <div class="col-md-3 col-sm-6">
                            <label for="contact">Contact Number<i class="text-danger">*</i></label>
                            <input type="text" name="contact" id="contact" class="form-control" pattern="(\+?\d{2}?\s?\d{3}\s?\d{3}\s?\d{4})|([0]\d{3}\s?\d{3}\s?\d{4})" maxlength="11" placeholder="09XXxxxxxxx" required>
                        </div>

                        <!-- email address -->
                        <div class="col-md-3 col-sm-6">
                            <label for="email">Email <i class="text-danger">*</i></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address" required>
                        </div>

                        <!-- your birthdate -->
                        <div class="col-md-3 col-sm-6">
                            <label for="bday">Birth Date <i class="text-danger">*</i></label>
                            <input type="date" name="bday" id="bday" class="form-control" required>
                        </div>

                        <!-- gender -->
                        <div class="col-md-3 col-sm-6">
                            <label for="gender">Gender <i class="text-danger">*</i></label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="" hidden>Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <!-- religion -->
                        <div class="col-md-3 col-sm-6">
                            <label for="religion">Religion <i class="text-danger">*</i></label>
                            <input type="text" name="religion" id="religion" class="form-control" placeholder="Enter religion" required>
                        </div>

                        <!-- citizenship -->
                        <div class="col-md-3 col-sm-6">
                            <label for="citizenship">Citizenship <i class="text-danger">*</i></label>
                            <input type="text" name="citizenship" id="citizenship" class="form-control" placeholder="Enter citizenship" required>
                        </div>

                        <!-- civil status -->
                        <div class="col-md-3 col-sm-6">
                            <label for="civil-status">Civil Status <i class="text-danger">*</i></label>
                            <select name="civil-status" id="civil-status" class="form-select" required>
                                <option value="" hidden>Select status</option>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="widowed">Widowed</option>
                                <option value="seperated">Seperated</option>
                            </select>
                        </div>

                        <!-- your occupation -->
                        <div class="col-md-3 col-sm-6">
                            <label for="work">Occupation <i class="text-danger">*</i></label>
                            <select name="work" id="work" class="form-select" required>
                                <option value="" hidden>Select occupation</option>
                                <option value="Government Employee">Government Employee</option>
                                <option value="Private Employee">Private Employee</option>
                                <option value="Self-Employed">Self-Employed</option>
                                <option value="Unemployed">Unemployed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Clear</button>
                    <button type="submit" name="btn-submit-customer" id="btn-submit-customer" class="btn btn-primary">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!------------------- Add lot for the customer ------------------------------------->
<?php $customer_information = mysqli_query($mysqli, "SELECT * FROM tbl_customers"); if ($customer_information) : ?>
    <?php while ($row = mysqli_fetch_array($customer_information)) : ?>
        <div class="modal fade" id="view-setup-<?php echo $row["customer_id"] ?>" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl"> 
                <div class="modal-content">
                    <form action="function/customers/addLotSetup.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body p-5">
                            <!-- this is header -->
                            <div class="header-wrapper d-flex justify-content-between mb-3">
                                <div class="header-title">
                                    <h3>Add Owner Setup Record</h2>
                                    <p>Click save add record when you're done.</p>
                                </div>
                                <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <!-- start of modal body -->
                            <div class="row mb-3">
                                <!-- customer full name -->
                                <div class="col-md-4 mb-2">
                                    <label for="owner-fullname-<?php echo $row["customer_id"] ?>">Fullname </label>
                                    <input type="text" class="form-control" id="owner-fullname-<?php echo $row["customer_id"] ?>" name="owner-fullname" value="<?php echo ucwords($row["first_name"].' '.$row["middle_name"].' '.$row["last_name"])?>" disabled>
                                    <input type="hidden" class="form-control" id="customer-id" name="customer-id" value="<?php echo $row["customer_id"]?>">
                                </div>

                                <!-- customer email -->
                                <div class="col-md-4">
                                    <label for="owner-email-<?php echo $row["customer_id"] ?>">Email </label>
                                    <input type="text" class="form-control" id="owner-email-<?php echo $row["customer_id"] ?>" name="owner-email" value="<?php echo $row["email"]?>" disabled>
                                </div>

                                <!-- customer contact -->
                                <div class="col-md-4">
                                    <label for="owner-contact-<?php echo $row["customer_id"] ?>">Contact Number </label>
                                    <input type="text" class="form-control" id="owner-contact-<?php echo $row["customer_id"] ?>" name="owner-contact" value="<?php  echo $row["contact_number"]?>" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- Grave No Section -->
                                <div class="col-md-4">
                                    <label for="customer-site-<?php echo $row["customer_id"] ?>">Grave ID Number <span class="text-danger">*</span></label>
                                    <select name="customer-grave-number" data-id="<?php echo $row["customer_id"]?>" id="customer-grave-number-<?php echo $row["customer_id"]?>" class="form-select customer-site" required>
                                        <option value="" hidden>Select a grave no</option>
                                        <?php 
                                        $graveStatusResult = mysqli_query($mysqli, "SELECT grave_id FROM grave_points WHERE status = 'vacant'");
                                        while ($graveRow = mysqli_fetch_array($graveStatusResult)) {
                                            echo '<option value="' . $graveRow['grave_id'] . '">' . $graveRow['grave_id'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <p class="text-muted"><small>Refer to the map for the grave no.</small></p>
                                </div>

                                <!-- lawn type -->
                                <div class="col-md-4">
                                    <label for="customer-lawn-type-<?php echo $row["customer_id"] ?>">Lawn Type <span class="text-danger">*</span></label>
                                    <select class="form-select customer-lawn-type" id="customer-lawn-type-<?php echo $row["customer_id"] ?>" name="customer-lawn-type" value="<?php echo $row["type"]?>" required>
                                        <option value="" hidden>Select lawn type</option>
                                        <option value="standard">Standard</option>
                                        <option value="deluxe">Deluxe</option>
                                    </select>
                                </div>

                                <!-- payment type -->
                                <div class="col-md-4">
                                    <label for="customer-payment-type-<?php echo $row["customer_id"] ?>">Payment Type <span class="text-danger">*</span></label>
                                    <select class="form-select" data-id="<?php echo $row["customer_id"]?>" id="customer-payment-type-<?php echo $row["customer_id"] ?>" name="customer-payment-type" value="<?php echo $row["payment_type"]?>" required>
                                        <option value="" hidden>Select payment type</option>
                                        <option value="full">Full Payment</option>
                                        <option value="installment">Installment</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- payment frequency -->
                                <div class="col-md-4">
                                    <label for="customer-payment-frequency-<?php echo $row["customer_id"] ?>">Payment Frequency <i class="text-danger">*</i></label>
                                    <select class="form-select customer-payment-frequency" data-id="<?php echo $row["customer_id"]?>" id="customer-payment-frequency-<?php echo $row["customer_id"] ?>" name="customer-payment-frequency" required>
                                        <option value="" hidden class>Select a payment frequency</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="annually">Annual</option>
                                    </select>
                                </div>

                                <!-- deed of sale -->
                                <div class="col-md-4">
                                    <label for="owner-deed-sale-<?php echo $row["customer_id"]?>">Deed of Sale <i class="text-danger">*</i></label>
                                    <input type="file" accept=".pdf, .png, .jpg" name="owner-deed-sale" id="owner-deed-sale-<?php echo $row["customer_id"]?>" class="form-control owner-deed-sale">
                                </div>
                            </div>     
                        </div>

                        <div class="text-center text-danger lot-warning" id="lot-warning-<?php echo $row["customer_id"]?>"></div>

                        <!-- footer -->
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary btn-reset-owner" id="btn-reset-owner" data-id="<?php echo $row["customer_id"]?>">Reset</button>
                            <button type="submit" class="btn btn-primary" name="btn-owner-setup">Add Record</button>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    <?php endwhile; ?>
<?php endif; ?>
<!-- script for disable payment frequency if full payment is chosen -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("[id^='customer-payment-type-']").forEach(function (paymentTypeSelect) {
            paymentTypeSelect.addEventListener("change", function () {
                const customerId = this.getAttribute("data-id");
                const frequencySelect = document.getElementById(`customer-payment-frequency-${customerId}`);
                
                if (this.value === "full") {
                    frequencySelect.disabled = true;
                    frequencySelect.value = ""; // optional: reset selection
                } else {
                    frequencySelect.disabled = false;
                }
            });
        });
    });
</script>

<!---------------------- Edit customer modal ---------------------->
<?php $customer_information = mysqli_query($mysqli, "SELECT * FROM tbl_customers"); if ($customer_information) : ?>
    <?php while ($row = mysqli_fetch_array($customer_information)) : ?>
        <div class="modal fade" id="edit-customer-<?php echo $row["customer_id"] ?>" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl"> 
                <div class="modal-content">
                    <form action="function/customers/editCustomer.php" method="post">
                        <div class="modal-body p-5">
                            <!-- this is header -->
                            <div class="header-wrapper d-flex justify-content-between mb-3">
                                <div class="header-title">
                                    <h3>Edit Customer Record</h3>
                                    <p>Edit <strong><?php echo ucwords($row['first_name'] . ' ' . $row['last_name']); ?></strong> information. Click save changes when you're done.</p>
                                </div>
                                <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            
                            <!-- start of modal body -->
                            <div class="row mb-2">
                                <input type="hidden" name="modal-customer-id" value="<?php echo $row['customer_id'] ?>">
                                
                                <!-- first name -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-first-name">First name <i class="text-danger">*</i></label>
                                    <input type="text" name="modal-first-name" id="modal-first-name" class="form-control" placeholder="e.g. Juan" value="<?php echo $row['first_name'] ?>" required>
                                </div>

                                <!-- middle name -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-middle-name">Middle name</label>
                                    <input type="text" name="modal-middle-name" id="modal-middle-name" class="form-control" placeholder="e.g Dela" value="<?php echo $row['middle_name'] ?>">
                                </div>

                                <!-- last name input -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-family-name">Last name <i class="text-danger">*</i></label>
                                    <input type="text" name="modal-family-name" id="modal-family-name" class="form-control" placeholder="e.g Cruz" value="<?php echo $row['last_name'] ?>" required>
                                </div>

                                <!-- nickname input -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-nickname">Nickname</label>
                                    <input type="text" name="modal-nickname" id="modal-nickname" class="form-control" placeholder="Enter your nickname" value="<?php echo $row['nickname'] ?>">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <!-- address -->
                                <div class="col-md-12 col-sm-12">
                                    <label for="modal-home-address">Home Address <i class="text-danger">*</i></label>
                                    <input type="text" name="modal-home-address" id="modal-home-address" class="form-control" placeholder="House No./Unit/Purok/Subdivision/Village - Brgy. - City - Province" value="<?php echo $row['address'] ?>" required>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <!-- contact number -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-contact">Contact Number<i class="text-danger">*</i></label>
                                    <input type="text" name="modal-contact" id="modal-contact" class="form-control" pattern="(\+?\d{2}?\s?\d{3}\s?\d{3}\s?\d{4})|([0]\d{3}\s?\d{3}\s?\d{4})" maxlength="11" placeholder="09XXxxxxxxx" value="<?php echo $row['contact_number'] ?>" required>
                                </div>

                                <!-- email address -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-email">Email <i class="text-danger">*</i></label>
                                    <input type="email" name="modal-email" id="modal-email" class="form-control" placeholder="Enter email address" value="<?php echo $row['email'] ?>" required>
                                </div>

                                <!-- your birthdate -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-bday">Birth Date <i class="text-danger">*</i></label>
                                    <input type="date" name="modal-bday" id="modal-bday" class="form-control" value="<?php echo $row['birth_date'] ?>" required>
                                </div>

                                <!-- gender -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-gender">Gender <i class="text-danger">*</i></label>
                                    <select name="modal-gender" id="modal-gender" class="form-select" required>
                                        <option value="" hidden>Select gender</option>
                                        <option value="male" <?php if($row['gender'] == 'male') echo 'selected'; ?>>Male</option>
                                        <option value="female" <?php if($row['gender'] == 'female') echo 'selected'; ?>>Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <!-- religion -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-religion">Religion <i class="text-danger">*</i></label>
                                    <input type="text" name="modal-religion" id="modal-religion" class="form-control" placeholder="Enter religion" value="<?php echo $row['religion'] ?>" required>
                                </div>

                                <!-- citizenship -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-citizenship">Citizenship <i class="text-danger">*</i></label>
                                    <input type="text" name="modal-citizenship" id="modal-citizenship" class="form-control" placeholder="Enter citizenship" value="<?php echo $row['citizenship'] ?>" required>
                                </div>

                                <!-- civil status -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-civil-status">Civil Status <i class="text-danger">*</i></label>
                                    <select name="modal-civil-status" id="modal-civil-status" class="form-select" required>
                                        <option value="" hidden>Select status</option>
                                        <option value="single" <?php if($row['status'] == 'single') echo 'selected'; ?>>Single</option>
                                        <option value="married" <?php if($row['status'] == 'married') echo 'selected'; ?>>Married</option>
                                        <option value="widowed" <?php if($row['status'] == 'widowed') echo 'selected'; ?>>Widowed</option>
                                        <option value="seperated" <?php if($row['status'] == 'seperated') echo 'selected'; ?>>Seperated</option>
                                    </select>
                                </div>

                                <!-- your occupation -->
                                <div class="col-md-3 col-sm-6">
                                    <label for="modal-work">Occupation <i class="text-danger">*</i></label>
                                    <select name="modal-work" id="modal-work" class="form-select" required>
                                        <option value="" hidden>Select occupation</option>
                                        <option value="Government Employee" <?php if($row['occupation'] == 'Government Employee') echo 'selected'; ?>>Government Employee</option>
                                        <option value="Private Employee" <?php if($row['occupation'] == 'Private Employee') echo 'selected'; ?>>Private Employee</option>
                                        <option value="Self-Employed" <?php if($row['occupation'] == 'Self-Employed') echo 'selected'; ?>>Self-Employed</option>
                                        <option value="Unemployed" <?php if($row['occupation'] == 'Unemployed') echo 'selected'; ?>>Unemployed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- footer -->
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary btn-reset-owner" id="btn-reset-owner" data-id="<?php echo $row["customer_id"]?>">Reset</button>
                            <button type="submit" class="btn btn-primary" name="btn-update">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    <?php endwhile; ?>
<?php endif; ?>

<!-- View Customer Modal -->
<?php $customer_information = mysqli_query($mysqli, "SELECT * FROM tbl_customers"); if ($customer_information) : ?>
    <?php while ($row = mysqli_fetch_array($customer_information)) : ?>
        <div class="modal fade" id="view-customer-<?php echo $row["customer_id"]?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-full" role="document">
                <div class="modal-content">
                    <div class="modal-body p-5">
                        <!-- Modal Header -->
                        <div class="header-wrapper d-flex justify-content-between mb-4">
                            <div class="header-title">
                                <h3>Customer Information</h3>
                                <p>Viewing customer details and records.</p>
                            </div>
                            <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="section mb-4">
                            <div class="body-header d-flex justify-content-between align-items-center">
                                <div class="body-title">
                                    <h5>Personal Information</h5>
                                </div>
                                <div class="buttons d-flex justify-content-center align">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle me-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bi bi-download"></i>&nbsp; Export
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#" onclick="printTable()">
                                                <i class="bi bi-printer"></i> Print
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="exportToCSV()">
                                                <i class="bi bi-filetype-csv"></i> CSV
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="exportToExcel()">
                                                <i class="bi bi-file-earmark-excel"></i> Excel
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="exportToPDF()">
                                                <i class="bi bi-file-earmark-pdf"></i> PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Display personal information -->
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <p><strong>Full Name</strong><br><span><?php echo ucwords($row["first_name"].' '.$row["middle_name"].' '.$row["last_name"]); ?></span></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Nickname</strong><br><span><?php echo $row["nickname"] ? ucwords($row["nickname"]) : 'N/A'; ?></span></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Address</strong><br><span><?php echo $row["address"] ? ucwords($row['address']) : 'N/A'; ?></span></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Contact Number</strong><br><span><?php echo $row["contact_number"] ? $row['contact_number'] : 'N/A'; ?></span></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Email</strong><br><span><?php echo $row["email"] ? $row['email'] : 'N/A'; ?></span></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Birth Date</strong><br><span><?php echo !empty($row["birth_date"]) ? date('F d, Y', strtotime($row["birth_date"])) : 'N/A'; ?></span></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Age</strong><br><span><?php 
                                        if (!empty($row['birth_date'])) {
                                            $birthDate = new DateTime($row['birth_date']);
                                            $currentDate = new DateTime();
                                            $age = $currentDate->diff($birthDate)->y;
                                            echo $age;
                                        } else {
                                            echo 'N/A';
                                        }
                                    ?></span></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Gender</strong><br><span><?php echo $row["gender"] ? ucwords($row["gender"]) : 'N/A'; ?></span></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Religion</strong><br><span><?php echo $row["religion"] ? ucwords($row["religion"]) : 'N/A'; ?></span></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Citizenship</strong><br><span><?php echo $row["citizenship"] ? ucwords($row["citizenship"]) : 'N/A'; ?></span></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Civil Status</strong><br><span><?php echo $row["status"] ? ucwords($row["status"]) : 'N/A'; ?></span></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Occupation</strong><br><span><?php echo $row["occupation"] ? ucwords($row["occupation"]) : 'N/A'; ?></span></p>
                                </div>
                            </div>
                        </div>
                        <!-- Lot Records Section -->
                        <div class="section">
                            <h5 class="mb-3">Lot Records</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                <?php
                                        $lot_records = mysqli_query($mysqli, "SELECT l.*, g.status 
                                            FROM tbl_lot l 
                                            JOIN grave_points g ON l.grave_id = g.grave_id 
                                            WHERE l.customer_id = " . $row['customer_id']);
                                        
                                        if(mysqli_num_rows($lot_records) > 0):
                                            while($lot = mysqli_fetch_array($lot_records)):
                                        ?>
                                    <thead>
                                        <tr>
                                            <th class="col-0">#</th>
                                            <th class="col-1">Lawn Type</th>
                                            <th class="col-2">Payment Type</th>
                                            <th class="col-2">Payment Frequency</th>
                                            <th class="col-2">Start Date</th>
                                            <th class="col-2">Last Payment Date</th>
                                            <th class="col-2">Next Due Date</th>
                                            <th class="col-1">Status</th>
                                            <!-- <th>Deed of Sale</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $lot['grave_id']?></td>
                                            <td><?php echo ucwords($lot['type'])?></td>
                                            <td><?php echo ucwords($lot['payment_type'])?></td>
                                            <td><?php echo ucwords($lot['payment_frequency'])?></td>
                                            <td><?php echo date('F d, Y', strtotime($lot['start_date']))?></td>
                                            <td><?php echo !empty($lot['last_payment_date']) ? date('F d, Y', strtotime($lot['last_payment_date'])) : 'N/A' ?></td>
                                            <td><?php echo !empty($lot['next_due_date']) ? date('F d, Y', strtotime($lot['next_due_date'])) : 'N/A' ?></td>
                                            <td><?php echo ucwords($lot['lot_status'])?></td>
                                            <!-- <td>
                                                <a href="uploads/deeds/<?php echo $lot['deed_of_sale']?>" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-file-earmark-text"></i> View
                                                </a>
                                            </td> -->
                                        </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No lot records found</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Display Deceased Record                           -->
                        <div class="section">
                            <h5 class="mb-3">Connected Deceased Record</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                <?php
                                        $deceased_query = mysqli_query($mysqli, "SELECT d.* 
                                            FROM tbl_deceased d
                                            JOIN tbl_lot l ON d.lot_id = l.lot_id
                                            WHERE l.customer_id = " . $row['customer_id']);
                                        
                                        if(mysqli_num_rows($deceased_query) > 0):
                                            while($result = mysqli_fetch_array($deceased_query)):
                                            $current_year = date('Y');
                                            $dateBuried = date('Y', strtotime($result['dead_interment']));
                                            $years_buried = $current_year - $dateBuried;
                                        ?>
                                    <thead>
                                        <tr>
                                            <th class="col-0">#</th>
                                            <th class="col-3">Full Name</th>
                                            <th class="col-1">Relationship</th>
                                            <th class="col-2">Date of Birth</th>
                                            <th class="col-2">Date of Death</th>
                                            <th class="col-2">Burial Date</th>
                                            <th class="col-2">Years Buried</th>
                                            <th class="col-1">Visibility</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $result['record_id']?></td>
                                            <td><?php echo ucwords($result['dead_fullname'])?></td>   
                                            <td><?php echo ucwords($result['dead_relationship'])?></td>       
                                            <td><?php echo date('F d, Y', strtotime($result['dead_birth_date']))?></td>
                                            <td><?php echo date('F d, Y', strtotime($result['dead_date_death']))?></td>
                                            <td><?php echo date('F d, Y', strtotime($result['dead_interment']))?></td>
                                            <td><?php echo $years_buried < 1 ? 'Less than a year' : $years_buried . ' years'; ?></td>
                                            <td><?php echo ucwords($result['dead_visibility'])?></td>                      
                                        </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No lot connected deceased record found</td>
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
    <?php endwhile; ?>
<?php endif; ?>

<!-- Error Modal for Incomplete Customer Data -->
<div class="modal fade" id="error-modal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="header-wrapper d-flex justify-content-between mb-4">
                    <div class="header-title">
                        <h5><i class="bi bi-exclamation-triangle"></i> Incomplete Customer Information</h4>
                    </div>
                    <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="alert alert-danger">
                    <strong>Cannot proceed with lot setup!</strong>
                    <p class="mb-2">The following required fields are missing or incomplete:</p>
                    <ul id="missing-fields-list" class="mb-0"></ul>
                </div>
                <p class="text-muted">Please edit the customer information and fill in all required fields before setting up a lot.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="edit-customer-btn" data-bs-dismiss="modal">Edit Customer</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Customer data validation
    const customerData = <?php 
        $customers = [];
        $customer_validation = mysqli_query($mysqli, "SELECT * FROM tbl_customers");
        while ($customer = mysqli_fetch_array($customer_validation)) {
            $customers[] = [
                'customer_id' => $customer['customer_id'],
                'first_name' => $customer['first_name'],
                'last_name' => $customer['last_name'],
                'address' => $customer['address'],
                'contact_number' => $customer['contact_number'],
                'email' => $customer['email'],
                'birth_date' => $customer['birth_date'],
                'gender' => $customer['gender'],
                'religion' => $customer['religion'],
                'citizenship' => $customer['citizenship'],
                'status' => $customer['status'],
                'occupation' => $customer['occupation']
            ];
        }
        echo json_encode($customers);
    ?>;

    function validateCustomerData(customerId) {
        const customer = customerData.find(c => c.customer_id == customerId);
        const missingFields = [];
        
        // Required fields validation
        const requiredFields = {
            'first_name': 'First Name',
            'last_name': 'Last Name', 
            'address': 'Address',
            'contact_number': 'Contact Number',
            'email': 'Email',
            'birth_date': 'Birth Date',
            'gender': 'Gender',
            'religion': 'Religion',
            'citizenship': 'Citizenship',
            'status': 'Civil Status',
            'occupation': 'Occupation'
        };

        // Check for null, empty, or whitespace-only values
        for (const [field, label] of Object.entries(requiredFields)) {
            if (!customer[field] || customer[field].toString().trim() === '' || customer[field] === null) {
                missingFields.push(label);
            }
        }

        if (missingFields.length > 0) {
            // Show error modal
            const missingFieldsList = document.getElementById('missing-fields-list');
            missingFieldsList.innerHTML = missingFields.map(field => `<li>${field}</li>`).join('');
            
            // Set up edit button to open the correct edit modal
            const editBtn = document.getElementById('edit-customer-btn');
            editBtn.onclick = function() {
                const editModal = new bootstrap.Modal(document.getElementById(`edit-customer-${customerId}`));
                editModal.show();
            };
            
            const errorModal = new bootstrap.Modal(document.getElementById('error-modal'));
            errorModal.show();
        } else {
            // All data is complete, open the lot setup modal
            const setupModal = new bootstrap.Modal(document.getElementById(`view-setup-${customerId}`));
            setupModal.show();
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("[id^='customer-payment-type-']").forEach(function (paymentTypeSelect) {
            paymentTypeSelect.addEventListener("change", function () {
                const customerId = this.getAttribute("data-id");
                const frequencySelect = document.getElementById(`customer-payment-frequency-${customerId}`);
                
                if (this.value === "full") {
                    frequencySelect.disabled = true;
                    frequencySelect.value = ""; // optional: reset selection
                } else {
                    frequencySelect.disabled = false;
                }
            });
        });
    });
</script>