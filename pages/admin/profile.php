<?php 
            $profile_result = mysqli_query($mysqli, "SELECT users.*, customers.* FROM tbl_users AS users 
            JOIN tbl_customers AS customers ON users.user_id = customers.user_id WHERE customers.customer_id = '{$_SESSION['customer_id']}'");
            $profile_data = mysqli_fetch_assoc($profile_result);
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>My Profile</h3>
                <p class="text-subtitle text-muted">Manage Your Profile Information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl">
                                <img src="<?php echo WEBROOT; ?>assets/compiled/jpg/2.jpg" alt="Avatar">
                            </div>
                            <h3 class="mt-3"><?php echo ucwords($profile_data['first_name'] . ' ' . $profile_data['last_name']); ?></h3>
                            <p class="text-small"><?php echo ucwords($profile_data['user_type']); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-2">Control Center</h5>
                        <div class="card-content">
                            <div class="form-group mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <label class="form-label mb-0">Email Notifications</label>
                                        <small class="text-muted d-block">Receive email alerts for system updates</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <label class="form-label mb-0">Push Notifications</label>
                                        <small class="text-muted d-block">Receive browser notifications</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <label class="form-label mb-0">SMS Notifications</label>
                                        <small class="text-muted d-block">Receive text message alerts</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="smsNotifications">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="function/customers/editProfile.php" method="post">
                            <div class="row">
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="name" class="form-label">First Name</label>
                                    <input type="hidden" name="customer_id" id="customer_id" class="form-control" value="<?php echo htmlspecialchars($profile_data['customer_id'] ?? ''); ?>" required>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Your First Name" value="<?php echo htmlspecialchars($profile_data['first_name'] ?? ''); ?>" required>
                                </div>
                                <div class="col-lg-4 col-md-6 form-group">
                                    <label for="mname" class="form-label">Middle Name</label>
                                    <input type="text" name="mname" id="mname" class="form-control" placeholder="Your Middle Name" value="<?php echo htmlspecialchars($profile_data['middle_name'] ?? ''); ?>">
                                </div>
                                <div class="col-lg-4 col-md-6 form-group">
                                    <label for="lname" class="form-label">Last Name</label>
                                    <input type="text" name="lname" id="lname" class="form-control" placeholder="Your Last Name" value="<?php echo htmlspecialchars($profile_data['last_name'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nickname" class="form-label">Nickname</label>
                                <input type="text" name="nickname" id="nickname" class="form-control" placeholder="Your Nickname" value="<?php echo htmlspecialchars($profile_data['nickname'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" value="<?php echo htmlspecialchars($profile_data['email'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" name="phone" id="phone" class="form-control" placeholder="Your Phone" value="<?php echo htmlspecialchars($profile_data['contact_number'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="Your Address" value="<?php echo htmlspecialchars($profile_data['address'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="birthday" class="form-label">Birthday</label>
                                <input type="date" name="birthday" id="birthday" class="form-control" value="<?php echo htmlspecialchars($profile_data['birth_date'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value="" hidden>Select Gender</option>
                                    <option value="male" <?php echo ($profile_data['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo ($profile_data['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>
                            <div class="form-group modal-footer mt-5">
                                <button type="submit" name="save-edit-profile" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>