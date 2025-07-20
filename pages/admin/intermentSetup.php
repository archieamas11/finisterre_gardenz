<!-- Customers Tab Header -->
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Customers Records</h3>
            <p class="text-subtitle text-muted">List of Customers</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="<?php echo WEBROOT; ?>pages/admin/index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Customers Table</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Customers Tab -->
<section class="section">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="customers-tab" data-bs-toggle="tab" href="#customers" role="tab" aria-controls="customers" aria-selected="true">Customers</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="setup-tab" data-bs-toggle="tab" href="#setup" role="tab"aria-controls="setup" aria-selected="true">Lot Owners</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="deceased-tab" data-bs-toggle="tab" href="#deceased" role="tab"aria-controls="deceased" aria-selected="true">Deceased Records</a>
                </li>
            </ul>
            <hr>
            <div class="tab-content" id="myTabContent">
                <!-- Customers Tab -->
                <div class="tab-pane fade show active" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                    <?php require_once 'customersTable.php'; ?>
                </div>

                <!-- Lot Owners Tab -->
                <div class="tab-pane fade" id="setup" role="tabpanel" aria-labelledby="setup-tab">
                    <?php require_once 'lotOwners.php'; ?>
                </div>

                <!-- Deceased Records Tab -->
                <div class="tab-pane fade" id="deceased" role="tabpanel" aria-labelledby="deceased-tab">
                    <?php require_once 'deceasedTable.php'; ?>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const customersTab = document.getElementById("customers-tab");
        const setupTab = document.getElementById("setup-tab");
        const deceasedTab = document.getElementById("deceased-tab");
        const title = document.querySelector(".page-title h3");
        const subtitle = document.querySelector(".page-title p");
        const breadtitle = document.querySelector(".breadcrumb-item.active");

        customersTab.addEventListener("shown.bs.tab", function () {
            title.textContent = "Customers Records";
            subtitle.textContent = "List of Customers";
            breadtitle.textContent = "Customers Table";
        });

        setupTab.addEventListener("shown.bs.tab", function () {
            title.textContent = "Lot Owner Records";
            subtitle.textContent = "List of Lot Owners";
            breadtitle.textContent = "Lot Owner Table";
        });

        deceasedTab.addEventListener("shown.bs.tab", function () {
            title.textContent = "Deceased Records";
            subtitle.textContent = "List of Deceased Records";
            breadtitle.textContent = "Deceased Table";
        });
    });
</script>

<script src="<?php echo WEBROOT; ?>assets/js/admin.js" defer></script>





