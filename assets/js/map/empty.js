function getButtons(feature) {
    return `
            <div class="buttons">
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== null && $_SESSION['user_type'] != 'user') { ?>
                ${feature.properties['Status'] !== 'vacant' && feature.properties['Status'] !== 'reserved' ? `
                <span class="edit-record-btn">
                <a href="index.php?id=${feature.properties['id'] !== null ? feature.properties['id'] : ''}&page=edit_record">
                    <button type="button" class="btn btn-secondary btn-sm"  data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Deceased Record">
                    <i class="bi bi-pencil-square"></i>
                    </button>
                </a>
                </span>
                ` : ''}
                <?php } ?>
            </div>`;
}