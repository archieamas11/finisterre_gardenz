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

    if (!$id || !$type) {
        throw new Exception('Invalid parameters');
    }

    $success = false;
    $message = '';

    if ($type === 'deceased') {
        // Delete from tbl_files
        $query = "SELECT grave_filename FROM tbl_files WHERE id = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $file = mysqli_fetch_assoc($result);

        if ($file) {
            // Delete physical file
            $file_path = '../../../' . $file['grave_filename'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Delete database record
            $delete_query = "DELETE FROM tbl_files WHERE id = ?";
            $delete_stmt = mysqli_prepare($mysqli, $delete_query);
            mysqli_stmt_bind_param($delete_stmt, "i", $id);
            
            if (mysqli_stmt_execute($delete_stmt)) {
                $success = true;
                $message = 'Deceased image deleted successfully';
            } else {
                throw new Exception('Failed to delete deceased image from database');
            }
        } else {
            throw new Exception('Deceased image not found');
        }

    } elseif ($type === 'plot') {
        // Delete from tbl_plot_files
        $query = "SELECT file_name FROM tbl_plot_files WHERE plot_files_id = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $file = mysqli_fetch_assoc($result);

        if ($file) {
            // Delete physical file
            $file_path = '../../../' . $file['file_name'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Delete database record
            $delete_query = "DELETE FROM tbl_plot_files WHERE plot_files_id = ?";
            $delete_stmt = mysqli_prepare($mysqli, $delete_query);
            mysqli_stmt_bind_param($delete_stmt, "i", $id);
            
            if (mysqli_stmt_execute($delete_stmt)) {
                $success = true;
                $message = 'Plot image deleted successfully';
            } else {
                throw new Exception('Failed to delete plot image from database');
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
