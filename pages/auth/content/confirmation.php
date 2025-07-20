<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CemeterEase confirmation page">
    <title>CemeterEase | Email Verification</title>
    <style>
    :root {
        --primary-color: #4058a0;
        --background-color: #f9fafb;
        --text-color: #374151;
        --text-light: #6b7280;
        --white: #ffffff;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        background-color: var(--background-color);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        line-height: 1.6;
        color: var(--text-color);
        animation: fadeIn 0.6s ease-out;
        overflow: hidden;
    }

    .confirmation-container {
        width: 100%;
        max-width: 400px;
        background-color: var(--white);
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: fadeIn 0.6s ease-out;
        margin-bottom: 20px;
    }

    .confirmation-header {
        background: var(--primary-color);
        color: var(--white);
        padding: 20px;
        text-align: center;
    }

    .confirmation-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .confirmation-header p {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .confirmation-content {
        padding: 30px;
        text-align: center;
    }

    .confirmation-message {
        color: var(--text-light);
        margin-bottom: 25px;
        animation: fadeIn 0.6s ease-out;
    }

    .confirmation-message a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: bold;
    }

    .confirmation-message a:hover {
        text-decoration: underline;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 480px) {
        .confirmation-container {
            margin: 15px;
            width: calc(100% - 30px);
        }
    }
    </style>
</head>

<body>


    <?php
    if ($_GET['key'] && $_GET['token']) {

        $email = mysqli_real_escape_string($mysqli, $_GET['key']);
        $token = mysqli_real_escape_string($mysqli, $_GET['token']);

        // Check if user exists and verification status
        $query = mysqli_query($mysqli, "SELECT * FROM tbl_users WHERE verification_token='{$token}'");

        if (mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_array($query);            
            if ($row['is_verified'] == 0) {
                $update = mysqli_query(
                    $mysqli,
                    "UPDATE tbl_users SET is_verified=1, verification_token=NULL, updated_at=NOW() WHERE verification_token='{$token}'"
                );

                if ($update) {                    
                    // Insert user into customers table
                    $insertToCustomers = mysqli_query(
                        $mysqli,
                        "INSERT INTO tbl_customers (user_id, first_name, last_name, email, date_created) VALUES ('{$row['user_id']}', '{$_SESSION['temp_user_firstname']}', '{$_SESSION['temp_user_lastname']}', '{$email}', NOW())"
                    );
                    
                    if ($insertToCustomers) {
                        $msg = "Congratulations! Your email has been verified. Click here to <a href='index.php'>Login</a>";
                    } else {
                        $msg = "Email verified, but failed to create customer record. Error: " . mysqli_error($mysqli);
                    }
                } else {
                    $msg = "Verification failed. Please try again. Error: " . mysqli_error($mysqli);
                }
            }else {
                $msg = "You have already verified your account. Click here to <a href='index.php'>Login</a>";
            }
        } else {
            $msg = "Invalid verification link or email not found.";
        }
    } else {
        $msg = "Invalid verification parameters.";
    }
    ?>


    <div class="confirmation-container">
        <div class="confirmation-header">
            <h2>CemeterEase</h2>
            <p>Email Verification</p>
        </div>

        <div class="confirmation-content">
            <p class="confirmation-message">
                <?php echo $msg; ?>
            </p>
        </div>
    </div>
    <script>
    // Prevent form resubmission
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</body>

</html>