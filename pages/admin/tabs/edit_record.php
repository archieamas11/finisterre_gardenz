<!-- Update Record-->
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Record</h3>
                <p class="text-subtitle text-muted">Edit the Deceased Records</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                href="<?php echo WEBROOT; ?>pages/admin/index.php?page=map">Map</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Record</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php
                // Handle both edit record and plot-only scenarios
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                $grave_no = isset($_GET['grave_no']) ? $_GET['grave_no'] : null;
                
                $row = null;
                $plot_data = null;
                $show_left_panel = false;
                $show_right_panel = true; // Always show right panel for plot management
                
                // If ID is provided, fetch deceased record
                if ($id) {
                    $query = mysqli_query($mysqli, "SELECT tbl_deceased.*, tbl_customers.* FROM tbl_deceased
                    LEFT JOIN tbl_customers ON tbl_customers.customer_id = tbl_deceased.customer_id
                    WHERE tbl_deceased.record_id = '$id'");
                    $row = mysqli_fetch_assoc($query);
                    
                    if ($row) {
                        $show_left_panel = true;
                        $grave_no = $row['grave_id']; // Get grave_id from deceased record
                    }
                }
                
                // Fetch plot data if grave_no is available (either from deceased record or URL)
                if ($grave_no) {
                    $plot_query = mysqli_query($mysqli, "SELECT * FROM grave_points WHERE grave_id = '$grave_no'");
                    $plot_data = mysqli_fetch_assoc($plot_query);
                }
                
                // Start form with appropriate action
                $form_action = $id ? "function/function.php?record_id=$id&action=update" : "function/function.php?grave_no=$grave_no&action=create_plot";
                ?>
            <form class="record-form" action="<?php echo $form_action; ?>" method="POST" enctype="multipart/form-data">
                <!-- Hidden input for grave number -->
                <input type="hidden" name="grave_no" value="<?php echo $current_grave_id ?? $grave_no; ?>">

                <div class="row g-4">
                    <?php if ($show_left_panel && $row): ?>
                    <form class="record-form" action="function/function.php?record_id=<?php echo $id; ?>&action=update"
                        method="POST" enctype="multipart/form-data">
                        <div class="row g-4">
                            <!-- Left Column - Deceased Information -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-0">
                                        <h5 class="card-title mb-0 fw-semibold">
                                            <i class="bi bi-person-fill text-primary me-2"></i>Deceased Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="deceased-name" class="form-label">Full Name</label>
                                                <input type="text" class="form-control" name="deceased-name"
                                                    value="<?php echo $row['dead_fullname']; ?>" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="deceased-gender" class="form-label">Gender</label>
                                                <select name="deceased-gender" class="form-control" required>
                                                    <option hidden><?php echo $row['dead_gender']; ?></option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="deceased-birthday" class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control" name="deceased-birthday"
                                                    value="<?php echo $row['dead_birth_date']; ?>" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="deceased-deathday" class="form-label">Date of Death</label>
                                                <input type="date" class="form-control" name="deceased-deathday"
                                                    value="<?php echo $row['dead_date_death']; ?>" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="grave-no" class="form-label">Grave Number</label>
                                                <input type="number" class="form-control" name="grave-no"
                                                    value="<?php echo $row['grave_id']; ?>"
                                                    onkeypress="return (event.charCode !=8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <=57))"
                                                    required>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="deceased-visibility" class="form-label">Visibility</label>
                                                <select name="deceased-visibility" class="form-control" required>
                                                    <option hidden><?php echo $row['dead_visibility']; ?></option>
                                                    <option value="Public">Public</option>
                                                    <option value="Private">Private</option>
                                                </select>
                                            </div>

                                            <div class="deceased-images-container mb-3">
                                                <?php 
                                            $getFiles = mysqli_query($mysqli, "SELECT * FROM tbl_files WHERE record_id = '{$row['grave_id']}'");
                                            $photoCounter = 0;
                                            if ($getFiles && mysqli_num_rows($getFiles) > 0) {
                                                while ($fileRow = mysqli_fetch_assoc($getFiles)) {
                                                    $imageId = $fileRow['id'];
                                                    $imageSrc = $fileRow['grave_filename'];
                                                    $photoCounter++;
                                            ?>
                                                <div class='image-item' id='deceased-img-<?php echo $imageId; ?>'
                                                    data-id='<?php echo $imageId; ?>' data-type='deceased'>
                                                    <img src='<?php echo $imageSrc; ?>'
                                                        alt='Deceased Image <?php echo $imageId; ?>'
                                                        class='img-thumbnail'>
                                                    <div class='image-overlay'>
                                                        <button aria-label='Delete Image' class='btn btn-danger btn-sm'
                                                            onclick='deleteDeceasedImage(<?php echo $imageId; ?>)'>
                                                            <i class='bi bi-trash'></i>
                                                        </button>
                                                        <button aria-label='Replace Image'
                                                            class='btn btn-primary btn-sm'
                                                            onclick='triggerReplaceDeceasedImage(<?php echo $imageId; ?>)'>
                                                            <i class='bi bi-arrow-repeat'></i>
                                                        </button>
                                                        <input type='file'
                                                            id='deceased-file-input-<?php echo $imageId; ?>' hidden
                                                            onchange='replaceDeceasedImage(<?php echo $imageId; ?>)'
                                                            accept='image/jpeg,image/jpg,image/png'>
                                                    </div>
                                                </div>
                                                <?php 
                                                }
                                            } else {
                                                echo "<div class='text-muted'>No deceased images found.</div>";
                                            }
                                            ?>
                                            </div>
                                            <!-- New Deceased Images Upload Section -->
                                            <?php if ($photoCounter < 2): ?>
                                            <div id="add-new-deceased-images-section">
                                                <div
                                                    class="border-2 border-dashed border-secondary p-4 rounded text-center">
                                                    <i class="bi bi-cloud-upload fs-1 text-muted mb-2"></i>
                                                    <h6 class="fw-semibold mb-2">Upload Deceased Images</h6>
                                                    <p class="text-muted small mb-3">Drag and drop images here or click
                                                        to browse</p>
                                                    <input type="file" class="d-none" id="new-deceased-images-input"
                                                        name="deceased-img[]" accept="image/jpeg,image/jpg,image/png"
                                                        multiple onchange="previewNewDeceasedImages(this)">
                                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                                        onclick="document.getElementById('new-deceased-images-input').click()">
                                                        <i class="bi bi-plus-circle me-1"></i>Choose Images
                                                    </button>
                                                    <small class="text-muted d-block mt-2">Accepted formats: .jpg,
                                                        .jpeg, .png (max: 2MB each, limit: 2 images)</small>
                                                </div>

                                                <!-- Preview Container for New Deceased Images -->
                                                <div id="new-deceased-images-preview" class="mt-3"
                                                    style="display: none;">
                                                    <h6 class="fw-semibold mb-2">
                                                        <i class="bi bi-eye me-1"></i>Preview New Images
                                                    </h6>
                                                    <div id="deceased-preview-container" class="d-flex flex-wrap gap-2">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <div class="col-12">
                                                <h6 class="fw-semibold text-dark mt-3 mb-3">
                                                    <i class="bi bi-person-rolodex me-2"></i>Contact Information
                                                </h6>
                                            </div>

                                            <div class="col-12">
                                                <label for="deceased-contactname" class="form-label">Contact
                                                    Person</label>
                                                <input type="text" class="form-control" name="deceased-contactname"
                                                    value="<?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?>"
                                                    required>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="deceased-contactno" class="form-label">Contact
                                                    Number</label>
                                                <input type="text" class="form-control" name="deceased-contactno"
                                                    value="<?php echo $row['contact_number']; ?>"
                                                    onkeypress="return (event.charCode !=8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <=57))"
                                                    required>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="deceased-contactemail" class="form-label">Email</label>
                                                <input type="email" class="form-control" name="deceased-contactemail"
                                                    value="<?php echo $row['email']; ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="deceased-contactaddress" class="form-label">Address</label>
                                                <input type="text" class="form-control" name="deceased-contactaddress"
                                                    value="<?php echo $row['address']; ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="deceased-contactrelationship"
                                                    class="form-label">Relationship</label>
                                                <input type="text" class="form-control"
                                                    name="deceased-contactrelationship"
                                                    value="<?php echo $row['dead_relationship']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (!$show_left_panel && !$row && !$grave_no): ?>
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    No record ID or grave number provided. Please provide either a record ID to edit a
                                    deceased record or a grave number to manage plot information.
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Right Column - Grave Information -->
                            <?php if ($grave_no || ($row && $row['grave_id'])): 
                        $current_grave_id = $grave_no ? $grave_no : $row['grave_id'];
                    ?>
                            <div class="col-lg-<?php echo $show_left_panel ? '6' : '12'; ?>">
                                <div class="row g-3">
                                    <!-- Plot Images Section -->
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-white border-0 pb-0">
                                                <h5 class="card-title mb-0 fw-semibold">
                                                    <i class="bi bi-images text-primary me-2"></i>Plot Gallery
                                                </h5>
                                                <p class="text-muted small mb-0">Manage plot images for Grave
                                                    #<?php echo $current_grave_id; ?></p>
                                            </div>
                                            <div class="card-body">
                                                <!-- Display all images from tbl_plot_files -->
                                                <div class="existing-images-container mb-3">
                                                    <?php 
                                            $getPlotFiles = mysqli_query($mysqli, "SELECT * FROM tbl_plot_files WHERE grave_id = '$current_grave_id'");
                                            $plotPhotoCounter = 0;
                                            if ($getPlotFiles && mysqli_num_rows($getPlotFiles) > 0) {
                                                while ($plotFileRow = mysqli_fetch_assoc($getPlotFiles)) {
                                                    $plotImageId = $plotFileRow['plot_files_id'];
                                                    $plotImageSrc = $plotFileRow['file_name'];
                                                    $plotPhotoCounter++;
                                            ?>
                                                    <div class='image-item' id='plot-img-<?php echo $plotImageId; ?>'
                                                        data-id='<?php echo $plotImageId; ?>' data-type='plot'>
                                                        <img src='<?php echo $plotImageSrc; ?>'
                                                            alt='Plot Image <?php echo $plotImageId; ?>'
                                                            class='img-thumbnail'>
                                                        <div class='image-overlay'>
                                                            <button aria-label='Delete Plot Image'
                                                                class='btn btn-danger btn-sm'
                                                                onclick='deletePlotImage(<?php echo $plotImageId; ?>)'>
                                                                <i class='bi bi-trash'></i>
                                                            </button>
                                                            <button aria-label='Replace Plot Image'
                                                                class='btn btn-primary btn-sm'
                                                                onclick='triggerReplacePlotImage(<?php echo $plotImageId; ?>)'>
                                                                <i class='bi bi-arrow-repeat'></i>
                                                            </button>
                                                            <input type='file'
                                                                id='plot-file-input-<?php echo $plotImageId; ?>' hidden
                                                                onchange='replacePlotImage(<?php echo $plotImageId; ?>)'
                                                                accept='image/jpeg,image/jpg,image/png'>
                                                        </div>
                                                    </div>
                                                    <?php 
                                                }
                                            } else {
                                                echo "<div class='text-muted'>No plot images found.</div>";
                                            }
                                            ?>
                                                </div>

                                                <!-- New Plot Images Upload Section -->
                                                <div id="add-new-plot-images-section">
                                                    <div
                                                        class="border-2 border-dashed border-secondary p-4 rounded text-center">
                                                        <i class="bi bi-cloud-upload fs-1 text-muted mb-2"></i>
                                                        <h6 class="fw-semibold mb-2">Upload New Plot Images</h6>
                                                        <p class="text-muted small mb-3">Drag and drop images here or
                                                            click to browse</p>
                                                        <input type="file" class="d-none" id="new-plot-images-input"
                                                            name="plot-img[]" accept="image/jpeg,image/jpg,image/png"
                                                            multiple onchange="previewNewPlotImages(this)">
                                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                            onclick="document.getElementById('new-plot-images-input').click()">
                                                            <i class="bi bi-plus-circle me-1"></i>Choose Images
                                                        </button>
                                                        <small class="text-muted d-block mt-2">Accepted formats: .jpg,
                                                            .jpeg, .png (max: 2MB each)</small>
                                                    </div>

                                                    <!-- Preview Container for New Plot Images -->
                                                    <div id="new-plot-images-preview" class="mt-3"
                                                        style="display: none;">
                                                        <h6 class="fw-semibold mb-2">
                                                            <i class="bi bi-eye me-1"></i>Preview New Plot Images
                                                        </h6>
                                                        <div id="plot-preview-container" class="d-flex flex-wrap gap-2">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Plot Location Details -->
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-white border-0 pb-0">
                                                <h5 class="card-title mb-0 fw-semibold">
                                                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>Location Details
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <div class="text-center p-3 bg-light rounded">
                                                            <div class="text-muted small">Plot ID</div>
                                                            <div class="fw-semibold"><?php echo $current_grave_id; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-center p-3 bg-light rounded">
                                                            <div class="text-muted small">Section</div>
                                                            <div class="fw-semibold">
                                                                <?php echo $plot_data['section'] ?? 'Section A'; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if ($plot_data): ?>
                                                <div class="row g-3 mt-2">
                                                    <div class="col-6">
                                                        <div class="text-center p-2 bg-light rounded">
                                                            <div class="text-muted small">Coordinates</div>
                                                            <div class="fw-semibold small ">
                                                                <?php echo ($plot_data['coordinates']); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-center p-3 bg-light rounded">
                                                            <div class="text-muted small">Status</div>
                                                            <div class="fw-semibold">
                                                                <?php echo $plot_data['status'] ?? 'Available'; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <div class="mt-3">
                                                    <button type="button" class="btn btn-outline-primary w-100"
                                                        onclick="viewPlotOnMap(<?php echo $current_grave_id; ?>)">
                                                        <i class="bi bi-map me-2"></i>View on Map
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Plot Specifications -->
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-white border-0 pb-0">
                                                <h5 class="card-title mb-0 fw-semibold">
                                                    <i class="bi bi-rulers text-primary me-2"></i>Plot Specifications
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <!-- Dimensions Section -->
                                                    <div class="col-12">
                                                        <h6 class="mb-3 fw-semibold text-dark">
                                                            <i class="bi bi-aspect-ratio me-2"></i>Dimensions
                                                        </h6>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="plot-width" class="form-label">Width
                                                            (meters)</label>
                                                        <input type="number" class="form-control" name="plot-width"
                                                            id="plot-width"
                                                            value="<?php echo $plot_data['width'] ?? '2'; ?>" step="0.1"
                                                            min="0">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="plot-length" class="form-label">Length
                                                            (meters)</label>
                                                        <input type="number" class="form-control" name="plot-length"
                                                            id="plot-length"
                                                            value="<?php echo $plot_data['length'] ?? '3'; ?>"
                                                            step="0.1" min="0">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="plot-depth" class="form-label">Depth
                                                            (meters)</label>
                                                        <input type="number" class="form-control" name="plot-depth"
                                                            id="plot-depth"
                                                            value="<?php echo $plot_data['depth'] ?? '2'; ?>" step="0.1"
                                                            min="0">
                                                    </div>

                                                    <!-- Plot Information Section -->
                                                    <div class="col-12 mt-4">
                                                        <h6 class="mb-3 fw-semibold text-dark">
                                                            <i class="bi bi-info-circle me-2"></i>Plot Information
                                                        </h6>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="plot-section" class="form-label">Section</label>
                                                        <select class="form-control" name="plot-section"
                                                            id="plot-section">
                                                            <option value="Section A"
                                                                <?php echo ($plot_data['section'] ?? '') == 'Section A' ? 'selected' : ''; ?>>
                                                                Section A</option>
                                                            <option value="Section B"
                                                                <?php echo ($plot_data['section'] ?? '') == 'Section B' ? 'selected' : ''; ?>>
                                                                Section B</option>
                                                            <option value="Section C"
                                                                <?php echo ($plot_data['section'] ?? '') == 'Section C' ? 'selected' : ''; ?>>
                                                                Section C</option>
                                                            <option value="Section D"
                                                                <?php echo ($plot_data['section'] ?? '') == 'Section D' ? 'selected' : ''; ?>>
                                                                Section D</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="plot-facing" class="form-label">Facing
                                                            Direction</label>
                                                        <select class="form-control" name="plot-facing"
                                                            id="plot-facing">
                                                            <option value="East"
                                                                <?php echo ($plot_data['facing'] ?? '') == 'East' ? 'selected' : ''; ?>>
                                                                East</option>
                                                            <option value="West"
                                                                <?php echo ($plot_data['facing'] ?? '') == 'West' ? 'selected' : ''; ?>>
                                                                West</option>
                                                            <option value="North"
                                                                <?php echo ($plot_data['facing'] ?? '') == 'North' ? 'selected' : ''; ?>>
                                                                North</option>
                                                            <option value="South"
                                                                <?php echo ($plot_data['facing'] ?? '') == 'South' ? 'selected' : ''; ?>>
                                                                South</option>
                                                            <option value="Northeast"
                                                                <?php echo ($plot_data['facing'] ?? '') == 'Northeast' ? 'selected' : ''; ?>>
                                                                Northeast</option>
                                                            <option value="Northwest"
                                                                <?php echo ($plot_data['facing'] ?? '') == 'Northwest' ? 'selected' : ''; ?>>
                                                                Northwest</option>
                                                            <option value="Southeast"
                                                                <?php echo ($plot_data['facing'] ?? '') == 'Southeast' ? 'selected' : ''; ?>>
                                                                Southeast</option>
                                                            <option value="Southwest"
                                                                <?php echo ($plot_data['facing'] ?? '') == 'Southwest' ? 'selected' : ''; ?>>
                                                                Southwest</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="plot-access" class="form-label">Access Type</label>
                                                        <select class="form-control" name="plot-access"
                                                            id="plot-access">
                                                            <option value="Standard"
                                                                <?php echo ($plot_data['access_type'] ?? '') == 'Standard' ? 'selected' : ''; ?>>
                                                                Standard</option>
                                                            <option value="Wheelchair Accessible"
                                                                <?php echo ($plot_data['access_type'] ?? '') == 'Wheelchair Accessible' ? 'selected' : ''; ?>>
                                                                Wheelchair Accessible</option>
                                                            <option value="Vehicle Access"
                                                                <?php echo ($plot_data['access_type'] ?? '') == 'Vehicle Access' ? 'selected' : ''; ?>>
                                                                Vehicle Access</option>
                                                            <option value="Limited Access"
                                                                <?php echo ($plot_data['access_type'] ?? '') == 'Limited Access' ? 'selected' : ''; ?>>
                                                                Limited Access</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="plot-status" class="form-label">Plot Status</label>
                                                        <select class="form-control" name="plot-status"
                                                            id="plot-status">
                                                            <option value="Available"
                                                                <?php echo ($plot_data['status'] ?? '') == 'Available' ? 'selected' : ''; ?>>
                                                                Available</option>
                                                            <option value="Reserved"
                                                                <?php echo ($plot_data['status'] ?? '') == 'Reserved' ? 'selected' : ''; ?>>
                                                                Reserved</option>
                                                            <option value="Occupied"
                                                                <?php echo ($plot_data['status'] ?? '') == 'Occupied' ? 'selected' : ''; ?>>
                                                                Occupied</option>
                                                            <option value="Maintenance"
                                                                <?php echo ($plot_data['status'] ?? '') == 'Maintenance' ? 'selected' : ''; ?>>
                                                                Maintenance</option>
                                                        </select>
                                                    </div>

                                                    <!-- Features Section -->
                                                    <div class="col-12 mt-4">
                                                        <h6 class="mb-3 fw-semibold text-dark">
                                                            <i class="bi bi-star me-2"></i>Plot Features
                                                        </h6>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label">Special Features</label>
                                                        <?php 
                                                $plot_features = $plot_data['features'] ?? '';
                                                $features_array = $plot_features ? explode(',', $plot_features) : [];
                                                ?>
                                                        <div class="row g-2">
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="features[]" value="Garden view"
                                                                        id="feature-garden"
                                                                        <?php echo in_array('Garden view', $features_array) ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label"
                                                                        for="feature-garden">
                                                                        <i class="bi bi-tree-fill me-1"></i>Garden view
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="features[]" value="Main access"
                                                                        id="feature-access"
                                                                        <?php echo in_array('Main access', $features_array) ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label"
                                                                        for="feature-access">
                                                                        <i class="bi bi-signpost me-1"></i>Main access
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="features[]" value="Water view"
                                                                        id="feature-water"
                                                                        <?php echo in_array('Water view', $features_array) ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label" for="feature-water">
                                                                        <i class="bi bi-water me-1"></i>Water view
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="features[]" value="Peaceful area"
                                                                        id="feature-peaceful"
                                                                        <?php echo in_array('Peaceful area', $features_array) ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label"
                                                                        for="feature-peaceful">
                                                                        <i class="bi bi-peace me-1"></i>Peaceful area
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="features[]" value="Memorial benches"
                                                                        id="feature-benches"
                                                                        <?php echo in_array('Memorial benches', $features_array) ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label"
                                                                        for="feature-benches">
                                                                        <i
                                                                            class="bi bi-bookmark-heart me-1"></i>Memorial
                                                                        benches
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="features[]" value="Stone pathways"
                                                                        id="feature-pathways"
                                                                        <?php echo in_array('Stone pathways', $features_array) ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label"
                                                                        for="feature-pathways">
                                                                        <i class="bi bi-bricks me-1"></i>Stone pathways
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4" name="btn-submit">
                                    <i class="bi bi-check-circle me-2"></i>Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>

    <script>
    // Functions for deceased images (left panel - tbl_files)
    function deleteDeceasedImage(imageId) {
        if (confirm('Are you sure you want to delete this deceased image?')) {
            fetch('function/delete_image.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${imageId}&type=deceased`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`deceased-img-${imageId}`).remove();
                        location.reload(); // Refresh to update photo counter
                    } else {
                        alert('Error deleting image: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting image');
                });
        }
    }

    function triggerReplaceDeceasedImage(imageId) {
        document.getElementById(`deceased-file-input-${imageId}`).click();
    }

    function replaceDeceasedImage(imageId) {
        const fileInput = document.getElementById(`deceased-file-input-${imageId}`);
        const file = fileInput.files[0];

        if (file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('id', imageId);
            formData.append('type', 'deceased');

            fetch('function/replace_image.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Refresh to show new image
                    } else {
                        alert('Error replacing image: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error replacing image');
                });
        }
    }

    function previewNewDeceasedImages(input) {
        const preview = document.getElementById('new-deceased-images-preview');
        const container = document.getElementById('deceased-preview-container');

        if (input.files && input.files.length > 0) {
            container.innerHTML = '';

            // Check limit
            if (input.files.length > 2) {
                alert('You can only upload up to 2 images for deceased records');
                input.value = '';
                return;
            }

            Array.from(input.files).forEach((file, index) => {
                if (file.size > 2 * 1024 * 1024) { // 2MB limit
                    alert(`File ${file.name} is too large. Maximum size is 2MB.`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgDiv = document.createElement('div');
                    imgDiv.className = 'position-relative';
                    imgDiv.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                            onclick="removePreviewImage(this, 'new-deceased-images-input', 'new-deceased-images-preview', ${index})">
                        <i class="bi bi-x"></i>
                    </button>
                `;
                    container.appendChild(imgDiv);
                };
                reader.readAsDataURL(file);
            });

            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    // Functions for plot images (right panel - tbl_plot_files)
    function deletePlotImage(imageId) {
        if (confirm('Are you sure you want to delete this plot image?')) {
            fetch('function/delete_image.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${imageId}&type=plot`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`plot-img-${imageId}`).remove();
                    } else {
                        alert('Error deleting image: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting image');
                });
        }
    }

    function triggerReplacePlotImage(imageId) {
        document.getElementById(`plot-file-input-${imageId}`).click();
    }

    function replacePlotImage(imageId) {
        const fileInput = document.getElementById(`plot-file-input-${imageId}`);
        const file = fileInput.files[0];

        if (file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('id', imageId);
            formData.append('type', 'plot');

            fetch('function/replace_image.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Refresh to show new image
                    } else {
                        alert('Error replacing image: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error replacing image');
                });
        }
    }

    function previewNewPlotImages(input) {
        const preview = document.getElementById('new-plot-images-preview');
        const container = document.getElementById('plot-preview-container');

        if (input.files && input.files.length > 0) {
            container.innerHTML = '';

            Array.from(input.files).forEach((file, index) => {
                if (file.size > 2 * 1024 * 1024) { // 2MB limit
                    alert(`File ${file.name} is too large. Maximum size is 2MB.`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgDiv = document.createElement('div');
                    imgDiv.className = 'position-relative';
                    imgDiv.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                            onclick="removePreviewImage(this, 'new-plot-images-input', 'new-plot-images-preview', ${index})">
                        <i class="bi bi-x"></i>
                    </button>
                `;
                    container.appendChild(imgDiv);
                };
                reader.readAsDataURL(file);
            });

            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    // Common functions
    function removePreviewImage(button, inputId, previewId, index) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        // Remove from file input
        const dt = new DataTransfer();
        const files = input.files;

        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }

        input.files = dt.files;

        // Remove preview element
        button.parentElement.remove();

        // Hide preview container if no images
        if (input.files.length === 0) {
            preview.style.display = 'none';
        }
    }

    function viewPlotOnMap(graveId) {
        // Redirect to map view with the specific grave highlighted
        window.open(`<?php echo WEBROOT; ?>pages/admin/index.php?page=map&highlight=${graveId}`, '_blank');
    }
    </script>

    <style>
    .deceased-images-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .existing-images-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .image-item {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .image-item:hover {
        transform: scale(1.05);
    }

    .img-thumbnail {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .image-item:hover .image-overlay {
        opacity: 1;
    }

    .btn-sm {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 5px;
    }

    /* Upload area styles */
    .border-dashed {
        border-style: dashed !important;
    }

    .border-dashed:hover {
        border-color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.05);
    }

    /* Preview styles */
    #deceased-preview-container,
    #plot-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .position-relative .btn-danger {
        border-radius: 50%;
        width: 25px;
        height: 25px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {

        .deceased-images-container,
        .existing-images-container {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }

        .col-lg-6 {
            margin-bottom: 2rem;
        }
    }

    /* Form improvements */
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    /* Alert improvements */
    .alert {
        border: none;
        border-radius: 10px;
    }

    .alert-warning {
        background-color: #fff3cd;
        color: #856404;
    }
    </style>