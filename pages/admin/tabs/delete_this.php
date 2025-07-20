<?php
require_once "../../../include/initialize.php";

global $mysqli;

// Fetch the file details from the database using prepared statement
$stmt = $mysqli->prepare("SELECT * FROM tbl_files WHERE record_id = 10");
$stmt->execute();
$fetch = $stmt->get_result();

if ($row = mysqli_fetch_array($fetch)) {
    // Get the grave_filename and extract just the ID
    $full_url = $row['grave_filename'];
    $grave_filename = basename($full_url, '.jpg'); // Remove file extension
    $grave_filename = explode('/', $grave_filename);
    $grave_filename = end($grave_filename); // Get the last part of the path

    $cloudinary = getCloudinaryInstance();

    try {
        // Delete the image from Cloudinary
        $cloudinary->uploadApi()->destroy('grave_images/' . $grave_filename);

        // Delete the file record from the database
        $sqldelete = "DELETE FROM tbl_files WHERE id = 29";
        if (mysqli_query($mysqli, $sqldelete)) {
            header("location: ../index.php?page=request");
        } else {
            message("Something went wrong with database deletion", "error");
            header("location: ../index.php?page=request");
        }
    } catch (Exception $e) {
        message("Error deleting image from Cloudinary: " . $e->getMessage(), "error");
        header("location: ../index.php?page=request");
    }
} else {
    message("No file record found", "error");
    header("location: ../index.php?page=request");
}
?>
