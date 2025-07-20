<?php
function printSession() {
    if (!empty($_SESSION)) {
        echo '<div class="session-debug"><details>';
        echo '<summary>Session ('.count($_SESSION).')</summary>';
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre></details></div>';
    } else {
        echo '<p class="session-empty">Session is empty</p>';
    }
}
?>

<style>
    .session-debug {
        position: fixed;
        bottom: 10px;
        right: 10px;
        overflow: auto;
        background: rgba(0,0,0,0.8);
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        font-size: 12px;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .session-debug summary {
        cursor: pointer;
        font-weight: bold;
        margin-bottom: 5px;
        outline: none;
    }
    .session-debug pre {
        margin: 0;
        white-space: pre-wrap;
        font-family: monospace;
        padding: 20px;
    }
    .session-empty {
        display: none; /* Hide empty session message */
    }
</style>