<!-- this is lot owners setup information header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="card-title"><i data-lucide="land-plot"></i> Customers Setup Information</h5>
  <div class="buttons d-flex justify-content-center align">
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle me-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-download"></i>&nbsp; Export</button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#"><i class="bi bi-printer"></i> Print</a>
        <a class="dropdown-item" href="#"><i class="bi bi-filetype-csv"></i> CSV</a>
      </div>
    </div>
    <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-setup">+&nbsp;Add Setup</button> -->
  </div>
</div>

<div class="table-responsive">
  <table class="table table-striped" id="table2">
    <?php $result = mysqli_query($mysqli, "SELECT l.*, g.grave_id as location_id, g.dead_fullname, p.block, p.grave_id, CONCAT(c.first_name, ' ', c.middle_name, ' ', c.last_name) AS customer_name
      FROM tbl_lot AS l
      JOIN tbl_customers AS c ON c.customer_id = l.customer_id
      LEFT JOIN tbl_deceased AS g ON g.customer_id = c.customer_id
      LEFT JOIN grave_points AS p ON p.grave_id = l.grave_id WHERE l.lot_status != 'canceled' ORDER BY CASE WHEN l.lot_status = 'active' THEN 1 ELSE 2 END, l.lot_status;");
    ?>
    <thead>
      <th class="col-1">#</th>
      <th class="col-2">LOT OWNER</th>
      <th class="col-2">DECEASED</th>
      <th class="col-2">LOCATION</th>
      <th class="col-3">LAST PAY</th>
      <th class="col-3">NEXT DUE</th>
      <th class="col-1">STATUS</th>
      <th class="col-1 text-center">ACTION</th>
    </thead>
    <tbody>
      <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_array($result)):
          $nametrim = str_replace(',', '</br>', $row['customer_name']);
          if ($row['dead_fullname'] === null) {
            $deadtrim = '<span class="badge bg-light-danger">N/A</span>';
          } else {
            $deadtrim = ucwords(str_replace(',', '</br>', $row['dead_fullname']));
          }
          if (isset($row['location_id']) != NULL) {
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
            <td class="col-1"><?php echo $row['lot_id']; ?></td>
            <td class="col-2"><?php echo ucwords($nametrim); ?></td>
            <td class="col-2"><?php echo $deadtrim; ?></td>
            <td class="col-2"><?php echo ucwords('Block ' . $row['block'] . ' - Grave ' . $row['grave_id']); ?></td>
            <td class="col-3">
              <?php
                if (!empty($row['last_payment_date'])) {
                  echo date('F j, Y', strtotime($row['last_payment_date']));
                } else {
                  echo '<span class="badge bg-light-danger">N/A</span>';
                }
              ?>
            </td>
            <td class="col-3">
              <?php
                if (!empty($row['next_due_date'])) {
                  echo date('F j, Y', strtotime($row['next_due_date']));
                } else {
                  echo '<span class="badge bg-light-danger">N/A</span>';
                }
              ?>
            </td>

            <td class="col-1">
              <?php
              $status = strtolower($row['lot_status']);
              $badgeClass = '';
              switch ($status) {
                case 'active':
                  $badgeClass = 'bg-light-success';
                  break;
                case 'completed':
                  $badgeClass = 'bg-light-primary';
                  break;
                case 'cancelled':
                  $badgeClass = 'bg-light-danger';
                  break;
                default:
                  $badgeClass = 'bg-light-secondary';
              }
              ?>
              <span class="badge <?php echo $badgeClass; ?>"><?php echo ucwords($row['lot_status']); ?></span>
            </td>
            <!-- action button -->
            <td class="align-middle text-center col-1">
              <div class="d-flex gap-1">
                <!-- add deceased record button -->
                <button class="btn btn-<?php echo $btn ?>" data-bs-toggle="modal" data-bs-target="#add-deceased-<?php echo $row['lot_id'] ?>" title="Add Deceased Record" <?php echo $disabled ?>>
                  <i class="bi bi-person-x"></i>
                </button>

                <!-- edit information button -->
                <button class="btn btn-success" data-bs-toggle="modal" data-id="<?php echo $row['lot_id'] ?>" data-bs-target="#edit-setup-<?php echo $row['lot_id'] ?>" title="Edit Lot Record">
                  <i class="bi bi-pencil-square"></i>
                </button>

                <!-- view customers information button -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#view-information-<?php echo $row["lot_id"] ?>" title="View Lot Information">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
    </tbody>
  <?php endif; ?>
  </table>
</div>

<!------------------------------- add deceased record modal -------------------------->
<?php $deceased = mysqli_query($mysqli, 'SELECT * FROM tbl_lot 
INNER JOIN tbl_customers ON tbl_lot.customer_id = tbl_customers.customer_id 
INNER JOIN grave_points ON tbl_lot.grave_id = grave_points.grave_id');
if ($deceased): ?>
  <?php while ($row = mysqli_fetch_array($deceased)) {
    $customerName = ucwords(str_replace(',', '</br>', $row['first_name'] . ' ' . $row['last_name'])); ?>
    <div class="modal fade" id="add-deceased-<?php echo $row['lot_id'] ?>" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
          <form action="function/deceased/insertDeceased.php" method="post" enctype="multipart/form-data">
            <div class="modal-body p-5">
              <div class="header-wrapper d-flex justify-content-between mb-3">
                <div class="header-title">
                  <h3>Add Deceased Record</h2>
                    <p>Relative of <?php echo $row['first_name'] . ' ' . $row['last_name'] ?>. Click save record when you're done.</p>
                    <input type="hidden" name="lot-id" id="lot-id" value="<?php echo $row['lot_id'] ?>">
                    <input type="hidden" name="grave-id" id="grave-id" value="<?php echo $row['grave_id'] ?>">
                    <input type="hidden" name="customer-id" id="customer-id" value="<?php echo $row['customer_id'] ?>">
                </div>
                <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="">
                <div class="row mb-3">
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-first-name">First name <i class="text-danger">*</i></label>
                    <input type="text" name="dead-first-name" id="dead-first-name" class="form-control" placeholder="Given name" required>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-middle-name">Middle name </label>
                    <input type="text" name="dead-middle-name" id="dead-middle-name" class="form-control" placeholder="Middle name">
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-last-name">Last name <i class="text-danger">*</i></label>
                    <input type="text" name="dead-last-name" id="dead-last-name" class="form-control" placeholder="Surname" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-gender">Gender <i class="text-danger">*</i></label>
                    <select type="text" name="dead-gender" id="dead-gender" class="form-select" required>
                      <option value="" hidden>Select gender</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-citizenship">Citizenship <i class="text-danger">*</i></label>
                    <input type="text" name="dead-citizenship" id="dead-citizenship" placeholder="Citizenship" class="form-control" required>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-civil-status">Civil Status <i class="text-danger">*</i></label>
                    <select type="text" name="dead-civil-status" id="dead-civil-status" class="form-select" required>
                      <option value="" hidden>Civil Status</option>
                      <option value="Single">Single</option>
                      <option value="Married">Married</option>
                      <option value="Widowed">Widowed</option>
                      <option value="Seperated">Seperated</option>
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <!-- relative section -->
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-relative">Relative <i class="text-danger">*</i></label>
                    <input type="text" name="dead-relative" id="dead-relative" placeholder="First name" class="form-control" value="<?php echo ucwords($row['first_name'] . ' ' . $row['last_name']) ?>" readonly disabled required>
                    <input type="hidden" id="lot-owner-id" value="<?php echo $row['lot_id'] ?>" name="lot-owner-id">
                    <input type="hidden" id="customer-id" value="<?php echo $row['customer_id'] ?>" name="customer-id">
                    <input type="hidden" id="site-id" value="<?php echo $row['grave_id'] ?>" name="site-id">
                    <input type="hidden" id="lot-id" value="<?php echo $row['lot_id'] ?>" name="lot-id">
                    <small class="text-danger" id="relative-error"></small>
                  </div>

                  <!-- relationship section -->
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-relationship">Relationship <i class="text-danger">*</i></label>
                    <select type="text" name="dead-relationship" id="dead-relationship" class="form-select" required>
                      <option value="" hidden>Relationship</option>
                      <option value="Child">Child</option>
                      <option value="Father">Father</option>
                      <option value="Mother">Mother</option>
                      <option value="Sibling">Sibling</option>
                      <option value="Spouse">Spouse</option>
                      <option value="Grandparent">Grandparent</option>
                      <option value="Grandchild">Grandchild</option>
                      <option value="Parent's sibling">Parent's sibling</option>
                      <option value="Sibling's child">Sibling's child</option>
                      <option value="Cousin">Cousin</option>
                      <option value="Father-In-Law">Father-In-Law</option>
                      <option value="Mother-In-Law">Mother-In-Law</option>
                      <option value="Brother-In-Law">Brother-In-Law</option>
                      <option value="Sister-In-Law">Sister-In-Law</option>
                    </select>
                  </div>

                  <!-- Visibiliy -->
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-visibility" class="form-label label">Grave Visibility <span class="text-danger">*</span></label>
                    <select name="dead-visibility" id="dead-visibility" class="form-select" required>
                      <option value="" hidden>Select visibility</option>
                      <option value="Public">Public</option>
                      <option value="Private">Private</option>
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <!-- internment date section -->
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-intern">Interment Date <i class="text-danger">*</i></label>
                    <input type="date" name="dead-intern" id="dead-intern" class="form-control" required>
                  </div>

                  <!-- date of birth -->
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-bday">Date of Birth <i class="text-danger">*</i></label>
                    <input type="date" name="dead-bday" id="dead-bday" class="form-control" required>
                  </div>

                  <!-- place of death -->
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-death">Date of Death <i class="text-danger">*</i></label>
                    <input type="date" name="dead-death" id="dead-death" class="form-control" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <!-- Death certificate -->
                  <div class="col-md-4 col-sm-4">
                    <label for="death-cert">Death Certificate <i class="text-danger">*</i></label>
                    <input type="file" name="death-cert" id="death-cert" class="form-control" accept=".pdf, .docx, .jpg, .png">
                    <small class="text-muted d-block mt-1">Accepted formats: .pdf, .docx, .jpg, .png (max: 2MB)</small>
                  </div>

                  <!-- burial permit -->
                  <div class="col-md-4 col-sm-4">
                    <label for="burial-permit">Burial Permit <i class="text-danger">*</i></label>
                    <input type="file" name="burial-permit" id="burial-permit" class="form-control" accept=".pdf, .docx, .jpg, .png">
                    <small class="text-muted d-block mt-1">Accepted formats: .pdf, .docx, .jpg, .png (max: 2MB)</small>
                  </div>

                  <!-- grave image or deceased person images -->
                  <div class="col-md-4 col-sm-4">
                    <label for="grave-images">Grave Images</label>
                    <input type="file" name="grave-images" id="grave-images" class="form-control" accept=".jpg,.jpeg,.png" max-size="2097152">
                    <small class="text-muted d-block mt-1">Accepted formats: .jpg, .jpeg, .png (max: 2MB)</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- footer -->
            <div class="modal-footer">
              <button type="reset" class="btn btn-secondary">Reset</button>
              <button type="submit" name="btn-submit-dead" id="btn-submit-dead" class="btn btn-primary">Save Record</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php } ?>
<?php endif; ?>

<!------------------------------- edit deceased record modal -------------------------->
<?php 
// Use prepared statement to prevent SQL injection
$query = 'SELECT l.*, c.*, g.* FROM tbl_lot l
INNER JOIN tbl_customers c ON l.customer_id = c.customer_id 
INNER JOIN grave_points g ON l.grave_id = g.grave_id';
$lot_information = mysqli_query($mysqli, $query);

if ($lot_information && mysqli_num_rows($lot_information) > 0) : ?>
  <?php while ($row = mysqli_fetch_assoc($lot_information)) : ?>
    <div class="modal fade" id="edit-setup-<?php echo htmlspecialchars($row["lot_id"]) ?>" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <form action="function/lotOwners/editLotOwners.php" method="post" enctype="multipart/form-data">
            <div class="modal-body p-5">
              <!-- this is header -->
              <div class="header-wrapper d-flex justify-content-between mb-3">
                <div class="header-title">
                  <h3>Edit Owner Setup Record</h3>
                  <p>Click save changes when you're done.</p>
                </div>
                <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <!-- start of modal body -->
              <div class="row mb-3">
                <!-- customer full name -->
                <div class="col-md-4 mb-2">
                  <label for="edit-owner-fullname-<?php echo htmlspecialchars($row["lot_id"]) ?>">Fullname </label>
                  <input type="text" class="form-control" id="edit-owner-fullname-<?php echo htmlspecialchars($row["lot_id"]) ?>" name="edit-owner-fullname" 
                    value="<?php echo htmlspecialchars(ucwords($row["first_name"] . ' ' . $row["middle_name"] . ' ' . $row["last_name"])) ?>" disabled>
                  <input type="hidden" name="lot-id" value="<?php echo htmlspecialchars($row["lot_id"]) ?>">
                  <input type="hidden" name="customer-id" value="<?php echo htmlspecialchars($row["customer_id"]) ?>">
                </div>
                
                <!-- customer email -->
                <div class="col-md-4">
                  <label for="edit-owner-email-<?php echo htmlspecialchars($row["lot_id"]) ?>">Email </label>
                  <input type="text" class="form-control" id="edit-owner-email-<?php echo htmlspecialchars($row["lot_id"]) ?>" name="edit-owner-email" 
                    value="<?php echo htmlspecialchars($row["email"]) ?>" disabled>
                </div>

                <!-- customer contact -->
                <div class="col-md-4">
                  <label for="edit-owner-contact-<?php echo htmlspecialchars($row["lot_id"]) ?>">Contact Number </label>
                  <input type="text" class="form-control" id="edit-owner-contact-<?php echo htmlspecialchars($row["lot_id"]) ?>" name="edit-owner-contact" 
                    value="<?php echo htmlspecialchars($row["contact_number"]) ?>" disabled>
                </div>
              </div>

              <div class="row mb-3">
                <!-- Grave No Section -->
                <div class="col-md-4">
                  <label for="edit-customer-grave-number-<?php echo htmlspecialchars($row["lot_id"]) ?>">Grave ID Number <span class="text-danger">*</span></label>
                  <select name="customer-grave-number" data-id="<?php echo htmlspecialchars($row["lot_id"]) ?>" 
                    id="edit-customer-grave-number-<?php echo htmlspecialchars($row["lot_id"]) ?>" class="form-select customer-site" required>
                    <option value="" hidden>Select a grave no</option>
                    <?php
                    // Use prepared statement for grave status query
                    $graveQuery = "SELECT grave_id FROM grave_points WHERE status = 'vacant' OR grave_id = ? ORDER BY grave_id";
                    $stmt = mysqli_prepare($mysqli, $graveQuery);
                    mysqli_stmt_bind_param($stmt, 's', $row['grave_id']);
                    mysqli_stmt_execute($stmt);
                    $graveResult = mysqli_stmt_get_result($stmt);
                    
                    while ($graveRow = mysqli_fetch_assoc($graveResult)) {
                      $selected = ($row['grave_id'] == $graveRow['grave_id']) ? ' selected' : '';
                      echo '<option value="' . htmlspecialchars($graveRow['grave_id']) . '"' . $selected . '>' . 
                        htmlspecialchars($graveRow['grave_id']) . '</option>';
                    }
                    mysqli_stmt_close($stmt);
                    ?>
                  </select>
                </div>

                <!-- lawn type -->
                <div class="col-md-4">
                  <label for="edit-customer-lawn-type-<?php echo htmlspecialchars($row["lot_id"]) ?>">Lawn Type <span class="text-danger">*</span></label>
                  <select class="form-select customer-lawn-type" id="edit-customer-lawn-type-<?php echo htmlspecialchars($row["lot_id"]) ?>" name="customer-lawn-type" required>
                    <option value="" hidden>Select lawn type</option>
                    <option value="standard" <?php echo ($row["type"] == "standard") ? " selected" : ""; ?>>Standard</option>
                    <option value="deluxe" <?php echo ($row["type"] == "deluxe") ? " selected" : ""; ?>>Deluxe</option>
                  </select>
                </div>

                <!-- payment type -->
                <div class="col-md-4">
                  <label for="edit-customer-payment-type-<?php echo htmlspecialchars($row["lot_id"]) ?>">Payment Type <span class="text-danger">*</span></label>
                  <select class="form-select" data-id="<?php echo htmlspecialchars($row["lot_id"]) ?>" 
                    id="edit-customer-payment-type-<?php echo htmlspecialchars($row["lot_id"]) ?>" name="customer-payment-type" required>
                    <option value="" hidden>Select payment type</option>
                    <option value="full" <?php echo ($row["payment_type"] == "full") ? " selected" : ""; ?>>Full Payment</option>
                    <option value="installment" <?php echo ($row["payment_type"] == "installment") ? " selected" : ""; ?>>Installment</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <!-- payment frequency -->
                <div class="col-md-4">
                  <label for="edit-customer-payment-frequency-<?php echo htmlspecialchars($row["lot_id"]) ?>">Payment Frequency <i class="text-danger">*</i></label>
                  <select class="form-select customer-payment-frequency" data-id="<?php echo htmlspecialchars($row["lot_id"]) ?>" 
                    id="edit-customer-payment-frequency-<?php echo htmlspecialchars($row["lot_id"]) ?>" name="edit-payment-frequency" required>
                    <option value="" hidden>Select a payment frequency</option>
                    <option value="monthly" <?php echo ($row["payment_frequency"] == "monthly") ? " selected" : ""; ?>>Monthly</option>
                    <option value="annually" <?php echo ($row["payment_frequency"] == "annually") ? " selected" : ""; ?>>Annual</option>
                  </select>
                </div>
                <!-- lot status -->
                <div class="col-md-4">
                  <label for="edit-lot-status-<?php echo htmlspecialchars($row["lot_id"]) ?>">Lot Status <span class="text-danger">*</span></label>
                  <select name="owner-lot-status" id="edit-lot-status-<?php echo htmlspecialchars($row["lot_id"]) ?>" class="form-select" required>
                    <option value="" hidden>Select status</option>
                    <option value="active" <?php echo ($row['lot_status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="cancelled" <?php echo ($row['lot_status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    <option value="completed" <?php echo ($row['lot_status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                  </select>
                </div>
              </div>
              <div class="text-center text-danger lot-warning" id="lot-warning-<?php echo htmlspecialchars($row["lot_id"]) ?>"></div>       
              <!-- footer -->
              <div class="modal-footer">
                <button type="reset" class="btn btn-secondary btn-reset-owner" id="btn-reset-owner-<?php echo htmlspecialchars($row["lot_id"]) ?>" 
                  data-id="<?php echo htmlspecialchars($row["lot_id"]) ?>">Reset</button>
                <button type="submit" class="btn btn-primary" name="btn-edit-owner-setup">Save Changes</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
<?php endif; ?>

<!------------------------------- view lot information modal -------------------------->
<?php $view_lot_info = mysqli_query($mysqli, 'SELECT l.*, g.dead_fullname, p.block, p.grave_id, 
CONCAT(c.first_name, " ", c.middle_name, " ", c.last_name) AS customer_name,
c.first_name, c.middle_name, c.last_name, c.email, c.contact_number, c.address, c.gender, c.birth_date AS date_of_birth
FROM tbl_lot AS l
JOIN tbl_customers AS c ON c.customer_id = l.customer_id
LEFT JOIN tbl_deceased AS g ON g.customer_id = c.customer_id
LEFT JOIN grave_points AS p ON p.grave_id = l.grave_id');
if ($view_lot_info) : ?>
  <?php while ($row = mysqli_fetch_array($view_lot_info)) : ?>
    <div class="modal fade" id="view-information-<?php echo $row["lot_id"] ?>" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <div class="modal-body p-5">
            <!-- Header -->
            <div class="header-wrapper d-flex justify-content-between mb-4">
              <div class="header-title">
                <h3>Lot Information Details</h3>
                <p class="text-muted">Complete information for Lot ID: <?php echo $row['lot_id'] ?></p>
              </div>
              <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Lot Owner Information -->
            <div class="section mb-4">
              <div class="body-header d-flex justify-content-between align-items-center">
                <div class="body-title">
                  <h5 class="card-title mb-0">Lot Owner Information</h5>
                </div>
                <div class="buttons d-flex justify-content-center align">
                  <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle me-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="bi bi-download"></i>&nbsp; Export
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="#" onclick="printTable()"><i class="bi bi-printer"></i> Print</a>
                      <a class="dropdown-item" href="#" onclick="exportToCSV()"><i class="bi bi-filetype-csv"></i> CSV</a>
                      <a class="dropdown-item" href="#" onclick="exportToExcel()"><i class="bi bi-file-earmark-excel"></i> Excel</a>
                      <a class="dropdown-item" href="#" onclick="exportToPDF()"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row g-3">
                <div class="col-md-4">
                  <p><strong>Full Name</strong><br><span><?php echo ucwords($row["first_name"] . ' ' . $row["middle_name"] . ' ' . $row["last_name"]); ?></span></p>
                </div>
                <div class="col-md-4">
                  <p><strong>Email</strong><br><span><?php echo $row['email']; ?></span></p>
                </div>
                <div class="col-md-4">
                  <p><strong>Contact Number</strong><br><span><?php echo $row['contact_number']; ?></span></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <p><strong>Gender</strong><br><span><?php echo ucwords($row['gender']); ?></span></p>
                </div>
                <div class="col-md-4">
                  <p><strong>Date of Birth</strong><br><span><?php echo date('F j, Y', strtotime($row['date_of_birth'])); ?></span></p>
                </div>
                <div class="col-md-4">
                  <p><strong>Address</strong><br><span><?php echo ucwords($row['address']); ?></span></p>
                </div>
              </div>
            </div>

            <div class="section mb-4">
              <div class="body-title">
                <h5 class="card-title mb-0">Lot Details</h5>
              </div>
              <div class="row mt-2">
                <div class="col-md-4">
                  <p><strong>Lot & Location</strong><br>
                    <span>
                      <?php echo "Lot ID: " . $row['lot_id']; ?> &bull;
                      Block <?php echo $row['block']; ?> - Grave <?php echo $row['grave_id']; ?>
                    </span>
                  </p>
                </div>
                <div class="col-md-4">
                  <p><strong>Lawn Type</strong><br><span><?php echo ucwords($row['type']); ?></span></p>
                </div>
                <div class="col-md-4">
                  <p><strong>Status</strong><br>
                    <?php
                    $status = strtolower($row['lot_status']);
                    $badgeClass = '';
                    switch ($status) {
                      case 'active':
                        $badgeClass = 'bg-success';
                        break;
                      case 'completed':
                        $badgeClass = 'bg-primary';
                        break;
                      case 'cancelled':
                        $badgeClass = 'bg-danger';
                        break;
                      default:
                        $badgeClass = 'bg-secondary';
                    }
                    ?>
                    <span class="text-white badge <?php echo $badgeClass; ?>"><?php echo ucwords($row['lot_status']); ?></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <p><strong>Date Purchased</strong><br><span><?php echo date('F j, Y', strtotime($row['start_date'])); ?></span></p>
                </div>
                <div class="col-md-4">
                  <p><strong>Payment Type</strong><br><span><?php echo ucwords($row['payment_type']); ?></span></p>
                </div>
                <div class="col-md-4">
                  <p><strong>Payment Frequency</strong><br><span><?php echo $row['payment_frequency'] ? ucwords($row['payment_frequency']) : 'N/A'; ?></span></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
<?php endif; ?>

<!-- script for disable payment frequency if full payment is chosen -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Store original values when modal opens
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('show.bs.modal', function() {
            const customerId = this.querySelector("[id^='edit-customer-payment-type-']").getAttribute("data-id");
            const paymentTypeSelect = document.getElementById(`edit-customer-payment-type-${customerId}`);
            const frequencySelect = document.getElementById(`edit-customer-payment-frequency-${customerId}`);
            const statusSelect = document.getElementById(`edit-lot-status-${customerId}`);

            // Store original values as data attributes
            paymentTypeSelect.setAttribute('data-original', paymentTypeSelect.value);
            frequencySelect.setAttribute('data-original', frequencySelect.value);
            statusSelect.setAttribute('data-original', statusSelect.value);
            
            // Initial state setup
            if (paymentTypeSelect.value === "full") {
                statusSelect.disabled = true;
                statusSelect.value = "";
                frequencySelect.disabled = true;
                frequencySelect.value = "";
            } else {
                frequencySelect.disabled = false;
                statusSelect.disabled = false;
            }
        });

        // Reset to original values when modal closes
        modal.addEventListener('hidden.bs.modal', function() {
            const customerId = this.querySelector("[id^='edit-customer-payment-type-']").getAttribute("data-id");
            const paymentTypeSelect = document.getElementById(`edit-customer-payment-type-${customerId}`);
            const frequencySelect = document.getElementById(`edit-customer-payment-frequency-${customerId}`);
            const statusSelect = document.getElementById(`edit-lot-status-${customerId}`);

            // Restore original values
            paymentTypeSelect.value = paymentTypeSelect.getAttribute('data-original');
            frequencySelect.value = frequencySelect.getAttribute('data-original');
            statusSelect.value = statusSelect.getAttribute('data-original');

            // Reset disabled states based on original payment type
            if (paymentTypeSelect.value === "full") {
                statusSelect.disabled = true;
                frequencySelect.disabled = true;
            } else {
                frequencySelect.disabled = false;
                statusSelect.disabled = false;
            }
        });
    });

    // Handle payment type changes
    document.querySelectorAll("[id^='edit-customer-payment-type-']").forEach(function(paymentTypeSelect) {
        paymentTypeSelect.addEventListener("change", function() {
            const customerId = this.getAttribute("data-id");
            const frequencySelect = document.getElementById(`edit-customer-payment-frequency-${customerId}`);
            const statusSelect = document.getElementById(`edit-lot-status-${customerId}`);

            if (this.value === "full") {
                frequencySelect.disabled = true;
                statusSelect.disabled = true;
                frequencySelect.value = "";
                statusSelect.value = "";
            } else {
                frequencySelect.disabled = false;
                statusSelect.disabled = false;
            }
        });
    });
});
</script>