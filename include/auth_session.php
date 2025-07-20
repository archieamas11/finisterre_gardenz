<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../../login/index.php");
    exit();
}

function message($title = "", $message = "", $messageType = "")
{
    if (!empty($message)) {
        $_SESSION['messages'][] = [
            'title' => $title,
            'message' => $message,
            'type' => $messageType
        ];
    }
}

function check_message()
{
    if (isset($_SESSION['messages']) && is_array($_SESSION['messages'])) {
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {';

        foreach ($_SESSION['messages'] as $m) {
            echo 'showToast({
                    title: "' . htmlspecialchars($m['title'], ENT_QUOTES) . '",
                    description: "' . htmlspecialchars($m['message'], ENT_QUOTES) . '",
                    type: "' . htmlspecialchars($m['type'], ENT_QUOTES) . '"
                });';
        }

        echo '});
            </script>';

        unset($_SESSION['messages']);
    }
}
