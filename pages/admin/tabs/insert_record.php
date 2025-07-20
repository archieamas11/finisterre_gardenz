            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Add New Record</h3>
                            <p class="text-subtitle text-muted">Add new Deceased Records</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=deceased">Deceased Table</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add New Record</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Insert New Record-->
                <div class="card">
                    <div class="card-body">
                        <?php $count = 0; ?>
                        <form class="record-form" action="function/function.php?count=<?php echo $count; ?>& action=add" method="POST">
                            <div class="row g-2">
                                <!-- Personal Information Section -->
                                <div class="col-12">
                                    <h6 class="text-muted"><i class="bi bi-person-fill me-2 fs-5"></i> Deceased Information</h6>
                                    <hr>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <label for="deceased-firstname" class="form-label label mt-3">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="deceased-firstname" name="deceased-firstname" required placeholder="e.g. Juan">
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label for="deceased-middlename" class="form-label label mt-3">Middle Name</label>
                                    <input type="text" class="form-control" id="deceased-middlename" name="deceased-middlename" placeholder="e.g. Dela">
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label for="deceased-lastname" class="form-label label mt-3">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="deceased-lastname" name="deceased-lastname" required placeholder="e.g. Cruz">
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <label for="deceased-birthday" class="form-label label mt-3">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="deceased-birthday" name="deceased-birthday" required>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label for="deceased-deathday" class="form-label label mt-3">Date of Death <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="deceased-deathday" name="deceased-deathday" required>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label for="deceased-gender" class="form-label label mt-3">Gender <span class="text-danger">*</span></label>
                                    <select name="deceased-gender" id="deceased-gender" class="form-select" required>
                                        <option value="" hidden>Select gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label for="deceased-agegroup" class="form-label label mt-3">Age Group <span class="text-danger">*</span></label>
                                    <select name="deceased-agegroup" id="deceased-agegroup" class="form-select" required>
                                        <option value="" hidden>Select age group</option>
                                        <option value="Child">Child (0-12)</option>
                                        <option value="Teen">Teen (13-19)</option>
                                        <option value="Adults">Adults (20-59)</option>
                                        <option value="Seniors">Seniors (60+)</option>
                                    </select>
                                </div>


                                <!-- Contact Information Section -->
                                <div class="col-md-4 col-sm-12">
                                    <label for="deceased-contactname" class="form-label label mt-3">Contact Person <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="deceased-contactname" name="deceased-contactname" required placeholder="Full Name">
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label for="deceased-contactno" class="form-label label mt-3">Contact Number</label>
                                    <input type="tel" class="form-control" id="deceased-contactno" name="deceased-contactno" placeholder="09xxxxxxxxx">
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label for="deceased-contactemail" class="form-label label mt-3">Email Address</label>
                                    <input type="email" class="form-control" id="deceased-contactemail" name="deceased-contactemail" placeholder="example@gmail.com">
                                </div>

                                <!-- Grave No Section -->
                                <div class="col-md-4 col-sm-12">
                                    <label for="grave-no" class="form-label label mt-3">Grave Number<span class="text-danger">*</span></label>
                                    <select name="grave-no" id="graveno" class="form-control" required>
                                        <option value="" hidden>Select a grave no</option>
                                        <?php
                                        $result = mysqli_query($mysqli, "SELECT * FROM grave_points WHERE status = 'vacant'");
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo  '<option value=' . $row['grave_id'] . '>' . $row['grave_id'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <p class="grave-caption text-muted"><small>Refer to the map for the grave no.</small></p>
                                </div>

                                <!-- Visibiliy -->
                                <div class="col-md-4 col-sm-12">
                                    <label for="deceased-visibility" class="form-label label mt-3">Visibility <span class="text-danger">*</span></label>
                                    <select name="deceased-visibility" id="deceased-visibility" class="form-select" required>
                                        <option value="" hidden>Select visibility</option>
                                        <option value="Public">Public</option>        
                                        <option value="Private">Private</option>            
                                    </select>
                                </div>
                                
                                <!-- New Images Upload -->
                                <div id="add-new-images-section" style="display: <?php echo ($counter < 2) ? 'block' : 'none'; ?>;">
                                    <div class="col-12">
                                        <label class="form-label">Add New Images</label>
                                        <input type="file" class="image-preview-filepond" name="grave-img">
                                        <small class="text-muted d-block mt-1">Accepted formats: .jpg, .jpeg, .png (max: 2MB)</small>
                                    </div>
                                    <?php ?>
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary" name="btn-submit">Save Record</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>