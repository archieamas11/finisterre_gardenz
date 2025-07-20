<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Deceased Records Table</h3>
            <p class="text-subtitle text-muted">List of Deceased Records</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="?tab=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Deceased Table</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <span>
                <a href="?tab=insert">
                    <button class="btn btn-primary rounded-circle shadow" style="width: 50px; height: 50px;">+</button>
                </a>
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table1">
                    <?php
                    $result = mysqli_query($mysqli, "SELECT * FROM grave_record 
                    LEFT JOIN grave_points ON grave_record.grave_id=grave_points.grave_id 
                    WHERE status != 'vacant'");
                    if ($result) :
                    ?>
                    <thead>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Gender</th>
                        <th>Birth Date</th>
                        <th>Death Date</th>
                        <th>Age Group</th>
                        <th>Years Buried</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_array($result)) : 
                            $nametrim = str_replace(',', '</br>', $row['record_name']);
                            $birthtrim = str_replace(',', '</br>', $row['record_birth']);
                            $deathtrim = str_replace(',', '</br>', $row['record_death']);
                            $current_year = date('Y');
                            $death_year = date('Y', strtotime($row['record_death']));
                            $years_buried = $current_year - $death_year;
                        ?>
                        <tr>
                            <td><?php echo ucwords($nametrim); ?></td>
                            <td><?php echo $row['block'] . ' - ' . $row['grave_id']; ?></td>
                            <td><?php echo $row['record_gender']; ?></td>
                            <td><?php echo $birthtrim; ?></td>
                            <td><?php echo $deathtrim; ?></td>
                            <td><?php echo $row['record_agegroup']; ?></td>
                            <td><?php echo $years_buried < 1 ? 'Less than a year' : $years_buried . ' years'; ?></td>
                            <td>
                                <div class="btn-group me-1 mb-1 dropstart">
                                    <button type="button" class="btn btn-outline-secondary rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="fas fa-ellipsis-h"></span>
                                    </button>
                                    <div class="dropdown-menu" style="border-radius: 8px; min-width: 160px; max-width: 200px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);">
                                        <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" 
                                            data-name="<?php echo $row['record_name']; ?>"
                                            data-contact-name="<?php echo $row['record_contactperson']; ?>"
                                            data-contact-phone="<?php echo $row['record_contactno']; ?>"
                                            data-contact-email="<?php echo $row['record_contactemail']; ?>">
                                            <i class="bi bi-person me-2"></i>
                                            <span>Contact Person</span>
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="index.php?id=<?php echo $row['record_id']; ?>&page=edit_record">
                                            <i class="bi bi-pencil-fill me-2"></i>
                                            <span>Edit</span>
                                        </a>
                                    </div>
                                </div>
                            </td>    
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <?php endif; ?>
                </table>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
                    role="document">
                    <div class="modal-content">
                        <div class="modal-header">    
                            <h5 class="modal-title" id="exampleModalCenterTitle">Contact Person</h5>
                            <button type="button" class="close" data-bs-dismiss="modal"
                                aria-label="Close">
                                <i data-feather="x"></i>
                            </button>
                        </div>
                        <div class="modal-body" style="padding: 20px;">       
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Get the modal
const modal = document.getElementById('exampleModalCenter');
// Add event listener for modal show
modal.addEventListener('show.bs.modal', function (event) {
    // Get the button that triggered the modal
    const button = event.relatedTarget;

    // Get the data attributes for contact info
    const name = button.getAttribute('data-name');
    const contactName = button.getAttribute('data-contact-name');
    const contactPhone = button.getAttribute('data-contact-phone');
    const contactEmail = button.getAttribute('data-contact-email');

    // Find the modal elements to display contact info
    const modalTitle = modal.querySelector('.modal-title');
    const modalBody = modal.querySelector('.modal-body');

    // Update modal content with the contact information
    modalTitle.textContent = `Contact Person for ${name}`;
    modalBody.innerHTML = `
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-person-circle me-2"></i>
            <span>${contactName}</span>
        </div>
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-telephone-fill me-2"></i>
            <span>${contactPhone}</span>
        </div>
        <div class="d-flex align-items-center">
            <i class="bi bi-envelope-fill me-2"></i>
            <span>${contactEmail}</span>
        </div>
    `;
});
</script>
