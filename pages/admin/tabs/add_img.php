<?php
$graveid = $_GET['graveid'];
?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Photo Uploader</h3>
                <p class="text-subtitle text-muted">Upload images for grave ID: <?php echo $graveid; ?></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=map">Map</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Upload Photo</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <form class="service-form" action="function/function.php?graveid=<?php echo $graveid; ?>&action=img" method="POST" enctype="multipart/form-data">
                    <p class="card-text">Please upload images only (accepted formats: .jpg, .jpeg, .png, max size: 2MB)</p>
                    <input type="file" class="with-validation-filepond" name="grave-img" accept="image/jpeg,image/jpg,image/png" required multiple data-max-file-size="2MB" value=""></input>
                        <div class="mt-4 text-end">
                            <button class="btn btn-primary btn-submit mt-3 float-right" name="btn-save" value="Submit">Save Photo</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

