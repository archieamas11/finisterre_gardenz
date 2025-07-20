<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Order Records</h3>
            <p class="text-subtitle text-muted">List of all new records</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="<?php echo WEBROOT; ?>pages/admin/index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New Orders Table</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="section">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="default-tab" data-bs-toggle="tab" href="#default" role="tab" aria-controls="default" aria-selected="true">New Orders</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="active-tab" data-bs-toggle="tab" href="#active" role="tab" aria-controls="active" aria-selected="false">Active Orders</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="history-tab" data-bs-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">Orders History</a>
                </li>
            </ul>
            <hr>
            <div class="tab-content" id="orderTabContent">
                <div class="tab-pane fade show active" id="default" role="tabpanel" aria-labelledby="default-tab">
                    <?php require_once 'tabs/new_order.php'; ?>
                </div>
                <div class="tab-pane fade" id="active" role="tabpanel" aria-labelledby="active-tab">
                    <?php require_once 'tabs/active_order.php'; ?>
                </div>
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <?php require_once 'tabs/order_history.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const defaultTab = document.getElementById("default-tab");
        const activeTab = document.getElementById("active-tab");
        const historyTab = document.getElementById("history-tab");

        const title = document.querySelector(".page-title h3");
        const subtitle = document.querySelector(".page-title p");
        const breadtitle = document.querySelector(".breadcrumb-item.active");

        defaultTab.addEventListener("shown.bs.tab", function () {
            title.textContent = "Order Records";
            subtitle.textContent = "List of all new records";
            breadtitle.textContent = "New Orders Table";
        });

        activeTab.addEventListener("shown.bs.tab", function () {
            title.textContent = "Active Orders";
            subtitle.textContent = "List of all active orders";
            breadtitle.textContent = "Active Orders Table";
        });

        historyTab.addEventListener("shown.bs.tab", function () {
            title.textContent = "Orders History";
            subtitle.textContent = "List of all past orders";
            breadtitle.textContent = "Orders History Table";
        });
    });
</script>