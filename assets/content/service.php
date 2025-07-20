<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Services</h3>
                <p class="text-subtitle text-muted">View all available cemetery services</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo web_root; ?>pages/user/index.php?page=home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="parent-container">
        <?php
        $query = mysqli_query($mysqli, "SELECT * FROM tbl_services");
        while ($row = mysqli_fetch_array($query)) {
            if ($row['service_availability'] != 'unavailable') {
        ?>
            <div class="card-containers">
                <div class="service-title">
                    <span><?php echo $row['service_name']; ?></span>
                </div>
                <div class="service-price">
                    <h2>&#8369; <?php echo number_format($row['service_cost']); ?></h2>
                    <div class="service-completion">
                        <i class="bi bi-clock" style="margin-right: 5px;"></i>
                        <span class="text-muted">Completion Time: <?php echo intval($row['service_completion']); ?> <?php echo intval($row['service_completion']) > 1 ? 'days' : 'day'; ?></span>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <?php
                    echo '<a href="index.php?page=request&service_id='.$row['service_id'].'" class="float-left">';
                    echo '<button type="button" class="btn btn-success btn-sm mb-2">';
                    echo '<span class="bi bi-cart2">  Book Now</span>';
                    echo '</button>';
                    echo '</a>';
                    ?>
                    <?php
                    ?>
                </div>
            </div>
        <?php
            }
        }
        ?>
    </div>
</div>