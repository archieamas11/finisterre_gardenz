<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>View Accounts Table</h3>
                <p class="text-subtitle text-muted">Manage Account Table</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Accounts Table</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="card-title"><i data-lucide="circle-user"></i> Accounts Information</h5>                    
            <div class="buttons d-flex justify-content-center align">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle me-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-download"></i>&nbsp; Export</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#"><i class="bi bi-printer"></i> Print</a>
                        <a class="dropdown-item" href="#"><i class="bi bi-filetype-csv"></i> CSV</a>
                    </div>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-account">+&nbsp;Add Account</button>
            </div>
        </div>
        <div class="table-responsive">
        <table class="table table-striped" id="table3">
            <?php
            $result = mysqli_query($mysqli, "SELECT users.*, customers.* FROM tbl_users AS users 
            JOIN tbl_customers AS customers ON users.user_id = customers.user_id WHERE user_type != 'user'");
            if ($result) : ?>
            <thead>
                <th class="col-1">#</th>
                <th class="col-3">Name</th>
                <th class="col-3">Contact</th>
                <th class="col-1">User Type</th>
                <th class="col-2 text-center">Status</th>
                <th class="col-3">Date Created</th>
                <th class="cold-1 text-center">Action</th>
            </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($result)) : 
                    $nametrim = str_replace(',', '</br>', $row['first_name'] . ' ' . $row['last_name']);
                    $createdtrim = str_replace(',', '</br>', $row['date_created']); ?>
                    <tr>
                        <td class="col-1"><?php echo $row['user_id'];?></td>
                        <td class="col-3"><?php echo ucwords($nametrim); ?></td>
                        <td class="col-3"><?php echo ucwords($row['email']); ?></td>
                        <td class="col-1"><?php echo ucwords($row['user_type']); ?></td>
                        <td class="col-2 text-center">
                            <?php 
                            $status = ($row['status']);
                            $badgeClass = '';
                            switch($status) {
                                case 'verified':
                                $badgeClass = 'bg-success';
                                break;
                                case 'pending':
                                $badgeClass = 'bg-warning';
                                break;
                                case 'inactive':
                                $badgeClass = 'bg-danger';
                                break;
                                default:
                                $badgeClass = 'bg-secondary';
                            }
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo ucwords($row['status']); ?></span>
                        </td>
                        <td class="col-3"><?php echo date('F d, Y', strtotime($createdtrim)); ?></td>
                        <td class="align-middle text-center col-1">
                            <div class="d-flex gap-1 justify-content-center">
                                <!-- Edit information button -->
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit-account-<?php echo $row['user_id'] ?>" title="Edit Account Information">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <!-- Archive button -->
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#archive-account-<?php echo $row['user_id'] ?>" title="Archive Account">
                                    <i class="bi bi-archive"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <?php endif; ?>
        </table>
    </div>
    </div>
</div>
</div>


