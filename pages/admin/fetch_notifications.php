<?php
require_once(__DIR__ . "/../../include/database.php");
require_once(__DIR__ . "/../../include/config.php");

header('Content-Type: application/json');


$sql = "SELECT * FROM tbl_notifications WHERE is_read = 0 ORDER BY created_at DESC";
$notifications = $mysqli->query($sql);

if (mysqli_num_rows($notifications) > 0):
    echo '<div class="list-group list-group-flush">';
    while ($notification = mysqli_fetch_assoc($notifications)):
?>
        <a href="<?php echo WEBROOT; ?>pages/admin/index.php?page=order"
            class="list-group-item list-group-item-action border rounded-3 mb-2 px-3 py-3 <?php echo $notification['is_read'] ? '' : 'bg-light'; ?>">
            <div class="d-flex align-items-start">
                <div class="me-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="iconly-boldNotification text-primary fs-5"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-semibold text-dark mb-1"><?php echo htmlspecialchars($notification['title']); ?></h6>
                        <?php if (!$notification['is_read']): ?>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-2 py-1"
                                style="font-size: 0.7rem;">New</span>
                        <?php endif; ?>
                    </div>
                    <p class="mb-2 text-muted" style="font-size: 0.85rem;">
                        <?php echo htmlspecialchars($notification['message']); ?></p>
                    <div class="d-flex align-items-center text-muted">
                        <i class="bi bi-clock me-1" style="font-size: 0.8rem;"></i>
                        <small
                            style="font-size: 0.8rem;"><?php echo date('M d, Y H:i', strtotime($notification['created_at'])); ?></small>
                    </div>
                </div>
            </div>
        </a>
    <?php
    endwhile;
    echo '</div>';
else:
    ?>
    <div class="text-center py-5">
        <div class="mb-3">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                style="width: 80px; height: 80px;">
                <i class="iconly-boldNotification text-muted" style="font-size: 2rem;"></i>
            </div>
        </div>
        <h6 class="text-muted mb-2">No notifications yet</h6>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">You're all caught up! Check back later for updates.</p>
    </div>
<?php endif; ?>