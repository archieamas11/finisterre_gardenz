<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'App' ?></title>
</head>
<body>
<li class="menu-item">
    <a class="d-flex align-items-center" href="?tab=logout">
        <span class="menu-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                <path fill-rule="evenodd"
                    d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
            </svg>
        </span>
        <span class="menu-link">Logout</span>
    </a>
</li>
     <h1>This is user dashboard</h1>
    <?php
    if (!empty($_SESSION)) {
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
    } else {
        echo '<p style="color: red;">Session is empty</p>';
    }
    ?>
    <main>
        <?php require_once $data['content']; ?>
    </main>
</body>
</html>
