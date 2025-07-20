function deleteImage(imageId) {
    if (confirm('Are you sure you want to delete this image?')) {
        // Send a request to function.php to delete the image
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'function/function.php?action=remove_image', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = xhr.responseText.trim();
                if (response === 'success') {
                    var imageElement = document.getElementById('img-' + imageId);
                    if (imageElement) {
                        imageElement.parentNode.removeChild(imageElement);
                        alert('Image deleted successfully!');
                        updateImageCounter();
                    }
                } else {
                    alert('Error: ' + response);
                }
            }
        };
        xhr.send('id=' + imageId);
    }
}

function updateImageCounter() {
    var counter = document.querySelectorAll('.image-item').length;
    var addNewImagesSection = document.getElementById('add-new-images-section');
    if (counter < 3) {
        addNewImagesSection.style.display = 'block';
    } else {
        addNewImagesSection.style.display = 'none';
    }
}

// Initial call to set the correct display state
document.addEventListener('DOMContentLoaded', function() {
    updateImageCounter();
});


function triggerReplaceImage(imageId) {
    // Trigger the hidden file input to allow user to select a file
    document.getElementById('file-input-' + imageId).click();
}

function replaceImage(imageId) {
    const fileInput = document.getElementById('file-input-' + imageId);
    const file = fileInput.files[0];

    if (!file) return; // No file selected, return

    const formData = new FormData();
    formData.append('file', file);
    formData.append('image_id', imageId); // ID of the image to replace

    // Make an AJAX request to upload the image
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'function/function.php?action=replace_image', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText.trim());
            if (response.status === 'success') {
                // Update the image element on the page after successful upload
                const imageElement = document.getElementById('img-' + imageId).querySelector('img');
                imageElement.src = response.image_url; // Update the displayed image immediately
                alert('Image replaced successfully!');
            } else {
                alert('Error: ' + response.message);
            }
        }
    };
    xhr.send(formData);
}

// ================================
// SAMPLE/TEST IMAGE MANAGEMENT FUNCTIONS
// (For testing purposes - not connected to backend)
// ================================

/**
 * Delete a sample image from the display
 */
function deleteSampleImage(imageId) {
    if (confirm('Are you sure you want to remove this sample image? (This is just a test - no backend connection)')) {
        const imageElement = document.getElementById('sample-img-' + imageId);
        if (imageElement) {
            // Add fade out animation
            imageElement.style.transition = 'opacity 0.3s ease';
            imageElement.style.opacity = '0';

            setTimeout(() => {
                imageElement.remove();
                console.log('Sample image ' + imageId + ' removed from display');

                // Show notification
                showNotification('Sample image removed successfully!', 'success');
            }, 300);
        }
    }
}

/**
 * Trigger file input for replacing sample image
 */
function triggerReplaceSampleImage(imageId) {
    document.getElementById('sample-file-input-' + imageId).click();
}

/**
 * Replace sample image with selected file
 */
function replaceSampleImage(imageId) {
    const fileInput = document.getElementById('sample-file-input-' + imageId);
    const file = fileInput.files[0];

    if (!file) return;

    // Validate file type
    if (!file.type.match(/^image\/(jpeg|jpg|png)$/)) {
        alert('Please select a valid image file (JPEG, JPG, or PNG)');
        return;
    }

    // Validate file size (2MB limit)
    if (file.size > 2 * 1024 * 1024) {
        alert('File size must be less than 2MB');
        return;
    }

    // Create file reader to preview the image
    const reader = new FileReader();
    reader.onload = function(e) {
        const imageElement = document.getElementById('sample-img-' + imageId).querySelector('img');
        if (imageElement) {
            // Add loading effect
            imageElement.style.opacity = '0.5';

            setTimeout(() => {
                imageElement.src = e.target.result;
                imageElement.style.opacity = '1';
                console.log('Sample image ' + imageId + ' replaced with: ' + file.name);

                // Show notification
                showNotification('Sample image replaced successfully!', 'success');
            }, 500);
        }
    };
    reader.readAsDataURL(file);
}

/**
 * Preview new images before upload
 */
function previewNewImages(input) {
    const files = input.files;
    const previewContainer = document.getElementById('preview-container');
    const previewSection = document.getElementById('new-images-preview');

    if (files.length > 0) {
        previewContainer.innerHTML = ''; // Clear previous previews
        previewSection.style.display = 'block';

        Array.from(files).forEach((file, index) => {
            // Validate file type
            if (!file.type.match(/^image\/(jpeg|jpg|png)$/)) {
                showNotification('Invalid file type: ' + file.name, 'error');
                return;
            }

            // Validate file size
            if (file.size > 2 * 1024 * 1024) {
                showNotification('File too large: ' + file.name, 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'position-relative';
                previewItem.innerHTML = `
                    <div class="image-item" style="width: 100px; height: 100px;">
                        <img src="${e.target.result}" alt="Preview ${index + 1}" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                        <div class="image-overlay">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removePreviewImage(this)" title="Remove preview">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                    <small class="text-muted d-block text-center mt-1">${file.name}</small>
                `;
                previewContainer.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        });

        console.log(files.length + ' new images selected for preview');
        showNotification(files.length + ' images ready for upload', 'info');
    } else {
        previewSection.style.display = 'none';
    }
}

/**
 * Remove image from preview
 */
function removePreviewImage(button) {
    const previewItem = button.closest('.position-relative');
    if (previewItem) {
        previewItem.remove();

        // Hide preview section if no images left
        const remainingPreviews = document.querySelectorAll('#preview-container .position-relative');
        if (remainingPreviews.length === 0) {
            document.getElementById('new-images-preview').style.display = 'none';
            document.getElementById('new-images-input').value = ''; // Clear file input
        }
    }
}

/**
 * Show notification message
 */
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className =
        `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="bi bi-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Initialize drag and drop functionality for new images
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.querySelector('#add-new-images-section .border-dashed');
    const fileInput = document.getElementById('new-images-input');

    if (uploadArea && fileInput) {
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        uploadArea.addEventListener('drop', handleDrop, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            uploadArea.classList.add('border-primary', 'bg-light');
        }

        function unhighlight() {
            uploadArea.classList.remove('border-primary', 'bg-light');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            fileInput.files = files;
            previewNewImages(fileInput);
        }
    }
});