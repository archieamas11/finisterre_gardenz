<?php 
    $refnumber = $_GET['refnumber'];
?>
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Order Details</h3>
            <p class="text-subtitle text-muted">Summary of your order</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=home">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Order</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header py-3 d-flex justify-content-between">
            <button id="btnPrint" class="btn btn-outline-primary btn-sm print" onclick="PrintDiv('dataTable')">Print</button>
        </div>
        <div class="card-body">
            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                <table class="table my-0 table-bordered">
                    <?php 
                        $result = mysqli_query($mysqli, "SELECT grave.*, users.user_name FROM tbl_orders AS grave JOIN tbl_users AS users ON grave.orderer_id = users.user_id WHERE order_refnumber = '$refnumber'");
                    ?>
                    <tbody>
                        <?php
                            while ($row = mysqli_fetch_array($result)) {
                                echo '<tr>';
                                echo '<th scope="row" width="150">Order Code:</th><td>'.$row['order_refnumber'].'</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row" width="150">Orderer Name:</th><td>'.$row['user_name'].'</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row" width="150">Email:</th><td>'.$row['orderer_email'].'</td>';
                                echo '</tr>';
                                // echo '<tr>';
                                // echo '<th scope="row" width="150">Contact Number:</th><td>'.$row['orderer_contact'].'</td>';
                                // echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row" width="150">Order Name:</th><td>'.$row['order_name'].'</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row" width="150">Grave Number:</th><td>'.$row['selected_grave'].'</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row" width="150">Deceased Name:</th><td>'.$row['deceased_name'].'</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row" width="150">Order Total:</th><td>'.$row['order_total'].'</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row" width="150">Payment Status:</th><td>'.$row['payment_status'].'</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row" width="150">Order Status:</th><td>'.$row['order_status'].'</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row" width="150">Order Date:</th><td>'.$row['order_date'].'</td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<th scope="row" width="150">Instruction:</th><td>'.$row['instruction'].'</td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>



