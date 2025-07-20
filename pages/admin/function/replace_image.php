<?php
declare(strict_types=1);

require_once '../../../include/database.php';
require_once '../../../include/initialize.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);

    if (!$id || !$type || !isset($_FILES['file'])) {
        throw new Exception('Invalid parameters or no file uploaded');
    }

    $file = $_FILES['file'];

    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error');
    }

    // Check file size (2MB limit)
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new Exception('File size too large. Maximum 2MB allowed.');
    }

    // Check file type
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_types)) {
        throw new Exception('Invalid file type. Only JPEG and PNG allowed.');
    }

    $success = false;
    $message = '';

    if ($type === 'deceased') {
        // Replace in tbl_files
        $query = "SELECT grave_filename FROM tbl_files WHERE id = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $existing_file = mysqli_fetch_assoc($result);

        if ($existing_file) {
            // Create new filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = 'uploads/deceased/' . uniqid() . '.' . $extension;
            $upload_path = '../../../' . $new_filename;

            // Ensure directory exists
            $upload_dir = dirname($upload_path);
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Delete old file
                $old_file_path = '../../../' . $existing_file['grave_filename'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }

                // Update database
                $update_query = "UPDATE tbl_files SET grave_filename = ? WHERE id = ?";
                $update_stmt = mysqli_prepare($mysqli, $update_query);
                mysqli_stmt_bind_param($update_stmt, "si", $new_filename, $id);
                
                if (mysqli_stmt_execute($update_stmt)) {
                    $success = true;
                    $message = 'Deceased image replaced successfully';
                } else {
                    // Delete uploaded file if database update fails
                    unlink($upload_path);
                    throw new Exception('Failed to update deceased image in database');
                }
            } else {
                throw new Exception('Failed to upload deceased image file');
            }
        } else {
            throw new Exception('Deceased image not found');
        }

    } elseif ($type === 'plot') {
        // Replace in tbl_plot_files
        $query = "SELECT file_name FROM tbl_plot_files WHERE plot_files_id = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $existing_file = mysqli_fetch_assoc($result);

        if ($existing_file) {
            // Create new filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = 'uploads/plots/' . uniqid() . '.' . $extension;
            $upload_path = '../../../' . $new_filename;

            // Ensure directory exists
            $upload_dir = dirname($upload_path);
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Delete old file
                $old_file_path = '../../../' . $existing_file['file_name'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }

                // Update database
                $update_query = "UPDATE tbl_plot_files SET file_name = ? WHERE plot_files_id = ?";
                $update_stmt = mysqli_prepare($mysqli, $update_query);
                mysqli_stmt_bind_param($update_stmt, "si", $new_filename, $id);
                
                if (mysqli_stmt_execute($update_stmt)) {
                    $success = true;
                    $message = 'Plot image replaced successfully';
                } else {
                    // Delete uploaded file if database update fails
                    unlink($upload_path);
                    throw new Exception('Failed to update plot image in database');
                }
            } else {
                throw new Exception('Failed to upload plot image file');
            }
        } else {
            throw new Exception('Plot image not found');
        }

    } else {
        throw new Exception('Invalid image type');
    }

    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
