<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Orders</h3>
            <p class="text-subtitle text-muted">Manage Orders</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="<?php echo WEBROOT; ?>pages/admin/index.php?page=order">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Receipt</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row receipt-container">
            <div class="col-12">
                <div class="receipt" id="receipt-div">
                    <p class="centered">RECEIPT
                        <br>Minglanilla, Public, Cemetery, Cebu
                        <br>cemeterease.memorial@gmail.com
                    </p>
                    <table class="receipt-table mx-auto">
                        <thead>
                            <tr>
                                <th>Order Code</th>
                                <th>Customer Name</th>
                                <th>Service Name</th>
                                <th>Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $refnumber = $_GET['refnumber'];
                                $result = mysqli_query($mysqli, "SELECT * FROM grave_orders WHERE order_refnumber = '$refnumber'");
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo '<tr>';
                                        echo '<td>'.$row['order_refnumber'].'</td>';
                                        echo '<td>'.$row['orderer_name'].'</td>';
                                        echo '<td>'.$row['order_name'].'</td>';
                                        echo '<td>'.$row['order_total'].'</td>';
                                        echo '</tr>';
                                        echo '<tr>';
                                        echo '<td></td>';   
                                        echo '<td></td>';
                                        echo '<td>TOTAL</td>';
                                        echo '<td>'.$row['order_total'].'</td>';
                                        echo '</tr>';              
                            ?>
                        </tbody>
                    </table>
                    <p class="centered pb-4">Thanks for your purchase!
                    </p>
                </div>
            </div>
            <div class="col-12 d-flex flex-row-reverse">
                <button id="btnPrint" class="btn btn-outline-success btn-sm print"
                    onclick="PrintDiv('receipt-div')">Print</button>
                <?php 
                                        if ($row['orderer_email'] != "") {
                                            echo '<a href="function/order_function.php?email='.$row['orderer_email'].' & name='.$row['orderer_name'].' & refnumber='.$row['order_refnumber'].' & function=email">
                                                <button class="btn btn-info btn-sm send mr-1">Send to email</button>
                                            </a>';
                                        }
                                    }
                                    ?>
            </div>
        </div>
    </div>
</div>
</div>