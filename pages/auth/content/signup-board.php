<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CemeterEase confirmation page">
    <title>CemeterEase | Confirmation</title>
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
    margin-bottom: 20px; /* Add margin to create space for the footer */
}

.confirmation-header {
    background: var(--primary-color);
    color: var(--white);
    padding: 20px;
    text-align: center;
    animation: fadeIn 0.6s ease-out;
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
    animation: fadeIn 0.6s ease-out;
}

.confirmation-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 25px;
    background-color: #10b981;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.6s ease-out;
}

.confirmation-icon svg {
    color: white;
    width: 50px;
    height: 50px;
}

.confirmation-message {
    color: var(--text-light);
    margin-bottom: 25px;
    animation: fadeIn 0.6s ease-out;
}

.login-button {
    display: inline-block;
    width: 100%;
    padding: 12px 20px;
    background: var(--primary-color);
    color: var(--white);
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-align: center;
    animation: fadeIn 0.6s ease-out;
}

.login-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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
    <div class="confirmation-container">
        <div class="confirmation-header">
            <h2>CemeterEase</h2>
            <p>Registration Confirmation</p>
        </div>
        
        <div class="confirmation-content">
            <div class="confirmation-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <p class="confirmation-message">
                Please check your email inbox and click the link to verify your registration. 
                Make sure to check your spam folder if you don't see the email in your inbox.
            </p>
            
            <a href="index.php" class="login-button">
                Go to Login
            </a>
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