<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Our Services</h3>
                <p class="text-subtitle text-muted">Compassionate and professional cemetery services for your loved ones</p>
            </div>
        </div>
    </div>

    <div class="row">
        <?php
        $query = mysqli_query($mysqli, "SELECT * FROM tbl_services WHERE service_availability != 'unavailable'");
        while ($row = mysqli_fetch_array($query)) {
            $service_images = [
                1 => 'service-burial.jpg',
                2 => 'service-cremation.jpg',
                3 => 'service-memorial.jpg',
                4 => 'service-maintenance.jpg'
            ];
            $default_image = 'service-default.jpg';
            $image = isset($service_images[$row['service_id']]) ? $service_images[$row['service_id']] : $default_image;
        ?>
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card h-100 service-card">
                <div class="position-relative">
                    <img src="<?php echo WEBROOT; ?>assets/compiled/jpg/1.jpg" class="card-img-top service-image" alt="<?php echo htmlspecialchars($row['service_name']); ?>">
                    <!-- <img src="<?php echo WEBROOT; ?>assets/images/services/<?php echo $image; ?>" class="card-img-top service-image" alt="<?php echo htmlspecialchars($row['service_name']); ?>">   -->
                    <?php if ($row['service_availability'] == 'limited'): ?>
                    <span class="badge bg-warning position-absolute top-0 end-0 m-2">Limited Availability</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="card-title mb-0"><?php echo $row['service_name']; ?></h4>
                        <span class="price-tag">₱<?php echo number_format($row['service_cost']); ?></span>
                    </div>
                    
                    <p class="card-text text-muted mb-3">
                        <?php 
                        // Sample descriptions - replace with actual service descriptions from your database
                        $descriptions = [
                            1 => "Traditional burial services including plot selection, casket placement, and graveside ceremony.",
                            2 => "Cremation services with options for urns and memorial services.",
                            3 => "Memorial services and monuments to honor your loved ones.",
                            4 => "Ongoing grave maintenance and care services."
                        ];
                        echo $descriptions[$row['service_id']] ?? 'Professional service with our compassionate staff.';
                        ?>
                    </p>
                    
                    <ul class="list-unstyled service-features mb-3">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Completion: <?php echo intval($row['service_completion']); ?> 
                            <?php echo intval($row['service_completion']) > 1 ? 'days' : 'day'; ?>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Professional Staff
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Customizable Options
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0">
                    <a href="index.php?page=request&service_id=<?php echo $row['service_id']; ?>" 
                       class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-cart-plus me-2"></i> Request Service
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Service Inquiry Section -->
    <div class="card mt-4">
        <div class="card-body text-center p-5 bg-light rounded-3">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h3 class="mb-3">Need Help Choosing a Service?</h3>
                    <p class="text-muted mb-4">Our compassionate staff is available to guide you through the process and answer any questions you may have about our services.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="tel:+1234567890" class="btn btn-outline-primary">
                            <i class="bi bi-telephone me-2"></i> Call Us
                        </a>
                        <a href="mailto:info@cemeterease.com" class="btn btn-primary">
                            <i class="bi bi-envelope me-2"></i> Email Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.service-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    border-radius: 10px;
    overflow: hidden;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.service-image {
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.service-card:hover .service-image {
    transform: scale(1.03);
}

.price-tag {
    background-color: #f8f9fa;
    color: #0d6efd;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 1.1rem;
}

.service-features {
    color: #495057;
}

.service-features i {
    font-size: 0.9rem;
}

.card-title {
    color: #2c3e50;
    font-weight: 600;
}

.btn-primary {
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.25);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .service-card {
        margin-bottom: 1.5rem;
    }
}
</style>