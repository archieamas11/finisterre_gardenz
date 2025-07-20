<?php
$name = $_GET['name'];
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Services Form</h3>
                <p class="text-subtitle text-muted">Add new services that customers could subscribe</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=shop">Services</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Service</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                <table class="table my-0 table-bordered">
                    <?php
                    $result = mysqli_query($mysqli, "SELECT * FROM grave_record WHERE record_name = '$name'");
                    ?>
                    <thead>
                        <tr>
                            <th>Contact Person</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['record_contactperson'] . '</td>';
                            echo '<td>' . $row['record_contactno'] . '</td>';
                            echo '<td>' . $row['record_contactemail'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>