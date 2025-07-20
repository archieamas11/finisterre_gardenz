<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="card-title"><i data-lucide="cross"></i> Deceased Information</h5>                    
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
    <table class="table table-striped" id="table3">
        <?php
        $result = mysqli_query($mysqli, "SELECT tbl_deceased.*, tbl_customers.first_name, tbl_customers.last_name, tbl_customers.religion, grave_points.block FROM tbl_deceased
        LEFT JOIN tbl_customers ON tbl_deceased.customer_id=tbl_customers.customer_id
        LEFT JOIN grave_points ON tbl_deceased.grave_id=grave_points.grave_id
        WHERE grave_points.status != 'vacant'");
        ?>
        <thead>
            <tr>
                <th class="col-1">#</th>
                <th class="col-2">LOT OWNER</th>
                <th class="col-3">DECEASED</th>
                <th class="col-2">LOCATION</th>
                <th class="col-2">BURIAL DATE</th>
                <th class="col-1 text-center">YEARS BURIED</th>
                <th class="col-1 text-center">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$result) { echo mysqli_error($mysqli); } ?>
            <?php while ($row = mysqli_fetch_array($result)) : 
            $lotOwner = $row['first_name'] . ' ' . $row['last_name'];
            $nametrim = str_replace(',', '</br>', $row['dead_fullname']);
            $burialDate = $row['dead_interment'];
            $current_year = date('Y');
            $dateBuried = date('Y', strtotime($row['dead_interment']));
            $years_buried = $current_year - $dateBuried; ?>
            <tr>
                <td class="col-0"><?php echo $row['record_id'];?></td>
                <td class="col-2"><?php echo ucwords($lotOwner); ?></td>
                <td class="col-3"><?php echo ucwords($nametrim); ?></td>
                <td class="col-2"><?php echo ucwords('Block ' . $row['block'] . ' - Grave ' . $row['grave_id']); ?></td>
                <td class="col-2"><?php echo date('F d, Y', strtotime($burialDate)); ?></td>
                <td class="col-1 text-center"><?php echo $years_buried < 1 ? 'Less than a year' : $years_buried . ' years'; ?></td>
                <td class="align-middle text-center col-1">
                    <div class="d-flex gap-1 justify-content-center">
                        <!-- Edit information button -->
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit-deceased-<?php echo $row['record_id'] ?>" title="Edit Deceased Record">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- view deceased record information button -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#view-deceased-<?php echo $row["record_id"]?>" title="View Deceased Information">
                        <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </td>
            </tr>

            <!-- View deceased record modal for this row -->
            <div class="modal fade" id="view-deceased-<?php echo $row["record_id"]?>" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-5">
                            <!-- Header -->
                            <div class="header-wrapper d-flex justify-content-between mb-4">
                                <div class="header-title">
                                    <h3>Deceased Information Details</h3>
                                    <p class="text-muted">Complete deceased information for: <?php echo $row['dead_fullname'] ?></p>
                                </div>
                                <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="row g-4">
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Full Name</strong><br><span><?php echo ucwords($row["dead_fullname"]) ?? 'N/A'; ?></span></p>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Gender</strong><br><span><?php echo ucwords($row["dead_gender"]) ?? 'N/A'; ?></span></p>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Citizenship</strong><br><span><?php echo ucwords($row["dead_citizenship"]) ?? 'N/A'; ?></span></p>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Civil Status</strong><br><span><?php echo ucwords($row["dead_civil_status"]) ?? 'N/A'; ?></span></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Religion</strong><br><span><?php echo ucwords($row["religion"]) ?? 'N/A'; ?></span></p>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Date of Birth</strong><br><span><?php echo date('F j, Y', strtotime($row['dead_birth_date'])); ?></span></p>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Date of Death</strong><br><span><?php echo date('F j, Y', strtotime($row['dead_date_death'])); ?></span></p>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Date of Interment</strong><br><span><?php echo date('F j, Y', strtotime($row['dead_interment'])); ?></span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Relative</strong><br><span><?php echo ucwords($row['first_name'] . ' ' . $row['last_name']); ?></span></p>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Relationship</strong><br><span><?php echo $row['dead_relationship'] ? ucwords($row['dead_relationship']) : 'N/A'; ?></span></p>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Location</strong><br><span><?php echo ucwords('Block ' . $row['block'] . ' - Grave ' . $row['grave_id']); ?></span></p>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Visibility</strong><br><span><?php echo ucwords($row["dead_visibility"]); ?></span></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <p><strong>Years Buried</strong><br><span><?php echo $years_buried < 1 ? 'Less than a year' : $years_buried . ' years'; ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit deceased record modal for this row -->
            <div class="modal fade" id="edit-deceased-<?php echo $row["record_id"]?>" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-5">
                            <!-- Header -->
                             <div class="header-wrapper d-flex justify-content-between mb-4">
                                <div class="header-title">
                                    <h3>Edit Deceased Information</h3>
                                    <p class="text-muted">Complete deceased information for: <?php echo $row['dead_fullname'] ?></p>
                                </div>
                                <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="editDeceased.php" method="post">
                                <div class="row g-4">
                                    <!-- Content here -->
                                </div>
                            </form>
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>