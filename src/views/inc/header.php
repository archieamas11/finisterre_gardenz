<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Page Meta Tags-->
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">

    <!-- Page Title -->
    <title><?php echo isset($data['title']) ? $data['title'] : SITENAME; ?></title>
    
    <!-- Custom CSS -->
    <!-- data tables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo URLROOT; ?>/public/assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo URLROOT; ?>/public/assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo URLROOT; ?>/public/assets/images/favicon/favicon-16x16.png">
    <link rel="mask-icon" href="<?php echo URLROOT; ?>/public/assets/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- Google Font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/libs.bundle.css" />

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/theme.bundle.css" />


    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/custom.css" />


    <!-- Fix for custom scrollbar if JS is disabled-->
    <noscript>
        <style>
        /**
        * Reinstate scrolling for non-JS clients
        */
        .simplebar-content-wrapper {
            overflow: auto;
        }
        </style>
    </noscript>
</head>
<body>

</body>
    <!-- Custom JS -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script> let table = new DataTable('#myTable'); </script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/vendor.bundle.js"></script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/theme.bundle.js"></script>
</html>