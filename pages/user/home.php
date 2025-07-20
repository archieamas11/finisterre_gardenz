<?php
$list_of_memorials = mysqli_query($mysqli, "SELECT dead.* FROM tbl_customers AS customers 
                                                JOIN tbl_deceased as dead ON customers.customer_id = dead.customer_id 
                                                WHERE customers.customer_id = " . $_SESSION['customer_id']);

$get_death_anniversary = mysqli_query($mysqli, "SELECT * FROM tbl_deceased WHERE customer_id = '" . $_SESSION['customer_id'] . "'");
$death_anniversary_data = mysqli_fetch_assoc($get_death_anniversary);
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Memorial Dashboard</h3>
                <p class="text-subtitle text-muted">Manage memorial services and view cemetery information</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon bg-primary mb-2">
                                <i class="bi bi-people-fill d-flex align-items-center justify-content-center"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Memorials</h6>
                            <?php $memorials = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM tbl_deceased WHERE customer_id = '" . $_SESSION['customer_id'] . "'");
                            $memorial_count = mysqli_fetch_assoc($memorials); ?>
                            <h6 class="font-extrabold mb-0"><?php echo $memorial_count['total']; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon bg-success mb-2">
                                <i class="bi bi-calendar-check d-flex align-items-center justify-content-center"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Upcoming Events</h6>
                            <h6 class="font-extrabold mb-0">3</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon bg-warning mb-2">
                                <i class="bi bi-flower1 d-flex align-items-center justify-content-center"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Maintenance</h6>
                            <h6 class="font-extrabold mb-0">2 Pending</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon bg-info mb-2">
                                <i class="bi bi-chat-square-text d-flex align-items-center justify-content-center"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Messages</h6>
                            <h6 class="font-extrabold mb-0">5 Unread</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Memorials -->
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Recent Memorials</h4>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <!-- Family Memorials Section -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Sample Family Member Card 1 -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <?php while ($memorial_count = mysqli_fetch_assoc($list_of_memorials)): ?>
                                    <div class="card h-100 memorial-card shadow-sm">
                                        <div class="position-relative">
                                            <?php
                                            $profile_image = empty($memorial_count['dead_profile_link']) ? WEBROOT . 'assets/compiled/jpg/1.jpg' : $memorial_count['dead_profile_link'];
                                            ?>
                                            <img src="<?php echo $profile_image; ?>" class="card-img-top" alt="deceased-image" style="height: 200px; object-fit: cover;">
                                            <div class="memorial-overlay">
                                                <span class="badge bg-primary opacity-90"><?php echo $memorial_count['dead_relationship']; ?></span>
                                            </div>
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title"><?php echo $memorial_count['dead_fullname']; ?></h5>
                                            <p class="card-text text-muted">
                                                <i class="bi bi-calendar3 me-2"></i> <?php echo date('Y', strtotime($memorial_count['dead_birth_date'])); ?> - <?php echo date('Y', strtotime($memorial_count['dead_date_death'])); ?>
                                            </p>
                                            <p class="card-text ita" style="font-style: italic;">
                                                "<?php echo $memorial_count['dead_message']; ?>"
                                            </p>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-sm btn-outline-primary">
                                                    <i data-lucide="flame"></i> Light Candle
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i data-lucide="flower"></i> Leave Flowers
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-top-0 d-flex justify-content-center">
                                            <button class="btn btn-sm btn-light me-2 rounded-circle" title="View Deceased Details">
                                                <i class="bi bi-eye-fill text-secondary"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light rounded-circle" title="Edit Deceased Details">
                                                <i class="bi bi-pencil-fill text-secondary"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>

                            <!-- Add New Family Member Card -->
                            <!-- <div class="col-12 col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 add-memorial-card"
                                    style="border: 2px dashed #dee2e6; min-height: 300px;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addFamilyMemberModal">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center text-center text-muted">
                                        <div class="add-icon mb-3">
                                            <i class="bi bi-plus-circle" style="font-size: 3rem;"></i>
                                        </div>
                                        <h5>Add Family Member</h5>
                                        <p class="small">Click to create a memorial for a loved one</p>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>

                <!-- Add Family Member Modal -->
                <div class="modal fade" id="addFamilyMemberModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Family Member</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-5">
                                <form id="addFamilyMemberForm">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="image-upload-container text-center">
                                                <div class="image-preview mb-3" style="width: 200px; height: 200px; margin: 0 auto; border: 2px dashed #dee2e6; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                    <img id="memberImagePreview" src="<?php echo WEBROOT; ?>assets/compiled/jpg/1.jpg" alt="Member Photo" style="max-width: 100%; max-height: 100%; object-fit: cover; display: none;">
                                                    <div id="uploadPlaceholder" class="text-center p-3">
                                                        <i class="bi bi-person-plus" style="font-size: 3rem; color: #6c757d;"></i>
                                                        <p class="small mb-0">Click to upload photo</p>
                                                    </div>
                                                </div>
                                                <input type="file" id="memberImage" name="memberImage" accept="assets/compiled/jpg/1.jpg" class="d-none">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="fullName" class="form-label">Full Name</label>
                                                <input type="text" class="form-control" id="fullName" required placeholder="Enter full name of the deceased">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="birthDate" class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" id="birthDate" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="deathDate" class="form-label">Date of Passing</label>
                                                    <input type="date" class="form-control" id="deathDate" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="relationship" class="form-label">Relationship</label>
                                                <select class="form-select" id="relationship">
                                                    <option value="parent">Parent</option>
                                                    <option value="spouse">Spouse</option>
                                                    <option value="sibling">Sibling</option>
                                                    <option value="child">Child</option>
                                                    <option value="grandparent">Grandparent</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="memorialMessage" class="form-label">Memorial Message</label>
                                                <textarea class="form-control" id="memorialMessage" rows="3" placeholder="A short message in loving memory..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary">Save Memorial</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Upcoming Events -->
        <div class="col-12 col-lg-4 position-sticky">
            <!-- Quick Actions -->
            <div class="card position-sticky">
                <div class="card-header">
                    <h4>Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo WEBROOT; ?>pages/user/index.php?page=service" class="btn btn-primary mb-2">
                            <i class="bi bi-plus-circle me-2"></i> Request Memorial Service
                        </a>
                        <a href="<?php echo WEBROOT; ?>pages/user/index.php?page=map" class="btn btn-outline-primary mb-2">
                            <i class="bi bi-geo-alt me-2"></i> View Cemetery Map
                        </a>
                        <a href="#" class="btn btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#scheduleVisit">
                            <i class="bi bi-calendar-plus me-2"></i> Schedule a Visit
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="bi bi-flower1 me-2"></i> Request Maintenance
                        </a>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Upcoming Events</h4>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-content">
                    <div class="recent-message p-3">
                        <a class="list-group-item list-group-item-action border-0 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg bg-primary bg-opacity-10 p-3 rounded-4">
                                    <i data-lucide="flame"></i>
                                </div>
                                <div class="ms-3 name">
                                    <h6 class="mb-0">All Souls' Day</h6>
                                    <p class="text-muted mb-0 small">November 2, 2023</p>
                                </div>
                            </div>
                        </a>
                        <a class="list-group-item list-group-item-action border-0 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg bg-secondary bg-opacity-10 p-3 rounded-4">
                                    <i data-lucide="flower"></i>
                                </div>
                                <div class="ms-3 name">
                                    <h6 class="mb-0">All Saints Day</h6>
                                    <p class="text-muted mb-0 small">November 1 , 2025</p>
                                </div>
                            </div>
                        </a>
                        <a class="list-group-item list-group-item-action border-0 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg bg-success bg-opacity-10 p-3 rounded-4">
                                    <i data-lucide="party-popper"></i>
                                </div>
                                <div class="ms-3 name">
                                    <h6 class="mb-0">Death Anniversary</h6>
                                    <p class="text-muted mb-0 small"><?php echo date('F j, Y', strtotime(date('Y') . '-' . date('m-d', strtotime($death_anniversary_data['dead_date_death'])))); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Recent Activity</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-light-primary p-2 me-3 rounded">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Payment Received</h6>
                                    <p class="mb-0 small">Payment for Memorial Service #MS-0012 has been received</p>
                                </div>
                            </div>
                            <span class="text-muted small">2 hours ago</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-light-warning p-2 me-3 rounded">
                                    <i class="bi bi-flower1 text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Maintenance Scheduled</h6>
                                    <p class="mb-0 small">Plot maintenance scheduled for Block A, Lot 12</p>
                                </div>
                            </div>
                            <span class="text-muted small">1 day ago</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Visit Modal -->
<div class="modal fade" id="scheduleVisit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule a Visit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Select Memorial</label>
                        <select class="form-select">
                            <option selected>Choose memorial...</option>
                            <?php
                            $memorials = mysqli_query($mysqli, "SELECT * FROM tbl_deceased WHERE record_id = '" . $_SESSION['id'] . "'");
                            while ($row = mysqli_fetch_assoc($memorials)) {
                                echo '<option value="' . $row['id'] . '">' . $row['full_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Visit Date</label>
                        <input type="date" class="form-control" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Visit Time</label>
                        <select class="form-select">
                            <option>08:00 AM - 10:00 AM</option>
                            <option>10:00 AM - 12:00 PM</option>
                            <option>01:00 PM - 03:00 PM</option>
                            <option>03:00 PM - 05:00 PM</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional Notes</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Schedule Visit</button>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>