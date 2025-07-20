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
                        <li class="breadcrumb-item"><a
                                href="<?php echo WEBROOT; ?>pages/admin/index.php?page=map">Map</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New Record</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Insert New Record via MAP-->
    <?php
    $graveno = $_GET['graveno'];
    $stat = $_GET['stat'];
    $grave_count = 0;
    $check_number = mysqli_query($mysqli, "SELECT * FROM grave_record WHERE grave_id = '$graveno'");
    while ($number_row = mysqli_fetch_array($check_number)) {
        $grave_count = $grave_count + 1;
    }
    if ($stat == 'vacant') {
    ?>
    <div class="card">
        <div class="card-body">
            <form class="record-form" action="function/function.php?action=add" method="POST">
                <div class="row g-2">
                    <!-- Personal Information Section -->
                    <div class="d-flex justify-content-between border border-primary">
                        <h6 class="text-muted"><i class="bi bi-person-fill me-2 fs-5"></i> Deceased Information</h6>
                        <div class="button">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="add-reserved"
                                    name="add-reserved">
                                <label class="form-check-label" for="add-reserved">Add/Reserved</label>
                            </div>         
                        </div>
                        <hr>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-firstname" class="form-label">First Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="deceased-firstname" name="deceased-firstname"
                            required placeholder="e.g. Juan">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-middlename" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="deceased-middlename" name="deceased-middlename"
                            placeholder="e.g. Dela">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-lastname" class="form-label">Last Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="deceased-lastname" name="deceased-lastname" required
                            placeholder="e.g. Cruz">
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-birthday" class="form-label">Date of Birth <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="deceased-birthday" name="deceased-birthday"
                            required>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-deathday" class="form-label">Date of Death <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="deceased-deathday" name="deceased-deathday"
                            required>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-gender" class="form-label">Gender <span
                                class="text-danger">*</span></label>
                        <select name="deceased-gender" id="deceased-gender" class="form-select" required>
                            <option value="" hidden>Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-agegroup" class="form-label">Age Group <span
                                class="text-danger">*</span></label>
                        <select name="deceased-agegroup" id="deceased-agegroup" class="form-select" required>
                            <option value="" hidden>Select age group</option>
                            <option value="Child">Child (0-12)</option>
                            <option value="Teen">Teen (13-19)</option>
                            <option value="Adults">Adults (20-59)</option>
                            <option value="Seniors">Seniors (60+)</option>
                        </select>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="col-12 mt-5">
                        <h6 class="text-muted"><i class="bi bi-telephone-fill me-2 fs-5"></i>Contact Information</h6>
                        <hr>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-contactname" class="form-label">Contact Person <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="deceased-contactname" name="deceased-contactname"
                            required>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-contactno" class="form-label">Contact Number</label>
                        <input type="tel" class="form-control" id="deceased-contactno" name="deceased-contactno"
                            placeholder="09xxxxxxxxx">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-contactemail" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="deceased-contactemail"
                            name="deceased-contactemail">
                    </div>

                    <!-- Grave Information Section -->
                    <div class="col-12 mt-5">
                        <h6 class="text-muted"><i class="bi bi-pin-map-fill me-2 fs-5"></i>Grave Information</h6>
                        <hr>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <label for="grave-no" class="form-label">Grave No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="grave-no" name="grave-no"
                            value="<?php echo $graveno; ?>" readonly>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary" name="btn-submit">Save Record</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    } elseif ($stat == 'occupied' or 'occupied2') {

        $query = mysqli_query($mysqli, "SELECT MAX(record_death) as max FROM grave_record WHERE grave_id = $graveno");
        while ($row = mysqli_fetch_array($query)) {
            $decomposedate = date_create($row['max']);
            $currentdate = date_create(@date('Y-m-d H:i:s'));
            $difference = date_diff($decomposedate, $currentdate)->format('%y year/s and %m month/s');
            if ($grave_count <= 2) {
                if ($difference >= 8) {
        ?>
    <div class="card-header py-3">
        <p class="text-primary m-0 font-weight-bold">Record Form</p>
    </div>
    <div class="card">
        <div class="card-body">
            <form class="record-form" action="function/function.php?count=<?php echo $grave_count; ?> & action=add"
                method="POST">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-firstname" class="form-label label mt-3">First Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="deceased-firstname" value="" required>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-middlename" class="form-label label mt-3">Middle Name (Optional)</label>
                        <input type="text" class="form-control" name="deceased-middlename" value="">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="deceased-lastname" class="form-label label mt-3">Last Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="deceased-lastname" value="" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="deceased-birthday" class="form-label label mt-3">Date of Birth <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="deceased-birthday" value="" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="deceased-deathday" class="form-label label mt-3">Date of Death <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="deceased-deathday" value="" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="deceased-gender" class="form-label label mt-3">Gender <span
                                class="text-danger">*</span></label>
                        <select name="deceased-gender" class="form-control" required>
                            <option hidden>--Select an option--</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="deceased-agegroup" class="form-label label mt-3">Age group <span
                                class="text-danger">*</span></label>
                        <select name="deceased-agegroup" class="form-control" required>
                            <option hidden>--Select an option--</option>
                            <option value="Babies">Babies</option>
                            <option value="Children">Children</option>
                            <option value="Young Adults">Young Adults</option>
                            <option value="Middle-aged Adults">Middle-aged Adults</option>
                            <option value="Old Adults">Old Adults</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="deceased-contactname" class="form-label label mt-3">Contact Person <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="deceased-contactname" value="" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="deceased-contactno" class="form-label label mt-3">Contact No.</label>
                        <input type="number" class="form-control" name="deceased-contactno" value="">
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <label for="deceased-contactemail" class="form-label label mt-3">Email</label>
                        <input type="email" class="form-control" name="deceased-contactemail" value="">
                    </div>
                    <div class="col-12">
                        <label for="grave-no" class="form-label label mt-2">Grave No. <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="grave-no" placeholder="<?php echo $graveno; ?>"
                            value="<?php echo $graveno; ?>" readonly>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary" name="btn-submit">Save Record</button>
                </div>
            </form>
        </div>
    </div>
    <?php
                } else {
                    echo '<div class="card-header py-3">';
                    echo '<p class="text-primary m-0 font-weight-bold text-center"><span class="text-danger">Plot isnt availble for merging</span></p>';
                    echo '</div>';
                    echo '<div class="card">';
                    echo '<div class="card-body">';
                    echo '<p class="text-center">The deceased recently buried was ' . $difference . ' ago, it should be 8 years and above</p>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {

                echo '<div class="card-header py-3">';
                echo '<p class="text-primary m-0 font-weight-bold text-center"><span class="text-danger">Plot isnt availble for merging</span></p>';
                echo '</div>';
                echo '<div class="card">';
                echo '<div class="card-body">';
                echo '<p class="text-center">The number of the deceased buried in this grave exceeded the maximum allowed for merging</p>';
                echo '</div>';
                echo '</div>';
            }
        }
    } else {
        echo '<div class="card-header py-3">';
        echo '<p class="text-primary m-0 font-weight-bold text-center"><span class="text-danger">ERROR</span></p>';
        echo '</div>';
        echo '<div class="card">';
        echo '<div class="card-body">';
        echo '<p class="text-center">Something went wrong please try again later.</p>';
        echo '</div>';
        echo '</div>';
    }
    ?>

</div>