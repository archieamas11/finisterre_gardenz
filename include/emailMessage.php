<?php
function lotReminderDueDateTemplate($firstName, $lastName, $dueDate, $systemName) {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                background-color: #f8f9fa;
                padding: 20px;
                text-align: center;
                border-radius: 5px;
            }
            .content {
                padding: 20px;
                background-color: #ffffff;
                border-radius: 5px;
                margin-top: 20px;
            }
            .footer {
                text-align: center;
                margin-top: 20px;
                padding: 20px;
                color: #666;
            }
            .highlight {
                color: #dc3545;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2>Payment Reminder</h2>
            </div>
            <div class="content">
                <p>Dear ' . ucwords($firstName) . ' ' . ucwords($lastName) . ',</p>
                <p>This is a reminder that your lot payment is due on <span class="highlight">' . $dueDate . '</span>.</p>
                <p>Please settle your payment to avoid any penalties.</p>
            </div>
            <div class="footer">
                <p>Thank you,<br>' . $systemName . '</p>
            </div>
        </div>
    </body>
    </html>';
}
?>
