<?php
require_once "../../../include/initialize.php";

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';
switch ($action) {
    case 'accept':
        accept();
        break;

    case 'deny':
        deny();
        break;

    case 'add':
        add();
        break;

    case 'update':
        update();
        break;

    case 'plot':
        plot();
        break;

    case 'img':
        img();
        break;

    case 'remove_image':
        remove_image();
        break;

    case 'replace_image':
        replace_image();
        break;
}

function accept()
{
    global $mysqli;
    $grave_no = $_GET['graveid'];
    $sql = "UPDATE grave_point SET status = ? WHERE grave_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ss", $param_status, $param_graveno);
        $param_status = 'occupied';
        $param_graveno = $grave_no;
        if ($stmt->execute()) {
            $path = '../../../upload/';
            $fetch = mysqli_query($mysqli, "SELECT * FROM tbl_files WHERE data_id = $grave_no");
            if ($row = mysqli_fetch_array($fetch)) {
                if (unlink($path . $row['user_file']) && unlink($path . $row['deceased_file'])) {
                    $sqldelete = "DELETE FROM tbl_files WHERE data_id = $grave_no";
                    if (mysqli_query($mysqli, $sqldelete)) {
                        header("location: ../index.php?page=request");
                    } else {
                        message("Something went wrong please try again later", "error");
                        header("location: ../index.php?page=request");
                    }
                } else {
                    message("Something went wrong please try again later", "error");
                    header("location: ../index.php?page=request");
                }
            } else {
                message("Something went wrong please try again later", "error");
                header("location: ../index.php?page=request");
            }
        } else {
            message("Something went wrong please try again later", "error");
            header("location: ../index.php?page=request");
        }
    } else {
        message("Something went wrong please try again later", "error");
        header("location: ../index.php?page=request");
    }
    $stmt->close();
    $mysqli->close();
}

function deny()
{
    global $mysqli;
    $grave_no = $_GET['graveid'];
    $sql = "UPDATE grave_point SET status = ? WHERE grave_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("si", $param_status, $param_graveno);
        $param_status = 'vacant';
        $param_graveno = $grave_no;
        if ($stmt->execute()) {
            $path = '../../../upload/';
            $fetch = mysqli_query($mysqli, "SELECT * FROM tbl_files WHERE data_id = $grave_no");
            if ($row = mysqli_fetch_array($fetch)) {
                if (unlink($path . $row['user_file']) && unlink($path . $row['deceased_file'])) {
                    $sqldelete = "DELETE FROM tbl_files WHERE data_id = $grave_no";
                    if (mysqli_query($mysqli, $sqldelete)) {
                        $del_record = "DELETE FROM grave_record WHERE grave_id = $grave_no";
                        if (mysqli_query($mysqli, $del_record)) {
                            header("location: ../index.php?page=request");
                        }
                    } else {
                        message("Something went wrong please try again later", "error");
                        header("location: ../index.php?page=request");
                    }
                } else {
                    message("Something went wrong please try again later", "error");
                    header("location: ../index.php?page=request");
                }
            } else {
                message("Something went wrong please try again later", "error");
                header("location: ../index.php?page=request");
            }
        } else {
            message("Something went wrong please try again later", "error");
            header("location: ../index.php?page=request");
        }
    } else {
        message("Something went wrong please try again later", "error");
        header("location: ../index.php?page=request");
    }
    $stmt->close();
    $mysqli->close();
}

function add()
{
    global $mysqli;

    // Early return if not a POST request
    if (!isset($_POST['btn-submit'])) {
        return;
    }

    // Define required fields and their validation rules
    $required_fields = [
        'deceased-firstname' => '/^[a-zA-Z]+$/',
        'deceased-lastname' => '/^[a-zA-Z]+$/',
        'deceased-birthday' => null,
        'deceased-deathday' => null,
        'deceased-gender' => null,
        'deceased-agegroup' => null,
        'deceased-contactname' => null,
        'grave-no' => null,
        'deceased-visibility' => null,
    ];

    // Optional fields
    $optional_fields = [
        'deceased-middlename',
        'deceased-contactemail',
        'deceased-contactno'
    ];

    // Validate all required fields
    $errors = [];
    $data = [];
    foreach ($required_fields as $field => $pattern) {
        if (empty($_POST[$field])) {
            $errors[] = $field;
        } elseif ($pattern && !preg_match($pattern, $_POST[$field])) {
            $errors[] = $field . " can only contain letters";
        } else {
            $data[$field] = trim($_POST[$field]);
        }
    }

    // Get optional fields
    foreach ($optional_fields as $field) {
        $data[$field] = trim($_POST[$field] ?? '');
    }

    // If there are validation errors
    if (!empty($errors)) {
        message("Required fields must not be empty", "error");
        header("location: ../index.php?page=record");
        exit();
    }

    try {
        // Prepare SQL statement
        $sql = "INSERT INTO grave_record (
            record_name, record_birth, record_death, 
            record_gender, record_agegroup, record_contactperson, 
            record_contactno, record_contactemail, grave_id, record_visibility
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $mysqli->error);
        }

        // Create full name (with optional middle name)
        $name_parts = [$data['deceased-firstname']];
        if (!empty($data['deceased-middlename'])) {
            $name_parts[] = $data['deceased-middlename'];
        }
        $name_parts[] = $data['deceased-lastname'];
        $full_name = implode(" ", $name_parts);

        // Bind parameters
        $stmt->bind_param("ssssssssss",
            $full_name,
            $data['deceased-birthday'],
            $data['deceased-deathday'],
            $data['deceased-gender'],
            $data['deceased-agegroup'],
            $data['deceased-contactname'],
            $data['deceased-contactno'],
            $data['deceased-contactemail'],
            $data['grave-no'],
            $data['deceased-visibility']
        );

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        // Update grave point status
        $count = $_GET['count'] ?? 0;
        $plot_status = "occupied" . ($count + 1);
        $update_sql = "UPDATE grave_points SET status = ? WHERE grave_id = ?";
        
        $update_stmt = $mysqli->prepare($update_sql);
        $update_stmt->bind_param("ss", $plot_status, $data['grave-no']);
        
        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update grave status");
        }


        // Handle optional image upload if a file is selected
        if (isset($_FILES["grave-img"]) && $_FILES["grave-img"]["error"] === UPLOAD_ERR_OK) {
            $file = $_FILES['grave-img'];
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            $max_size = 2 * 1024 * 1024; // 2MB

            if (!in_array($file['type'], $allowed_types)) {
            throw new Exception("File must be jpeg, jpg, or png");
            }

            if ($file['size'] > $max_size) {
            throw new Exception("File size must be less than 2MB");
            }

            $cloudinary = getCloudinaryInstance();

            // Upload to Cloudinary
            $response = $cloudinary->uploadApi()->upload(
            $file['tmp_name'],
            [
                'folder' => 'grave_images',
                'public_id' => uniqid('grave_'),
            ]
            );

            if (!isset($response['secure_url'])) {
            throw new Exception("Failed to upload image to Cloudinary");
            }

            $image_url = $response['secure_url'];

            // Save URL to database
            $created = date('Y-m-d H:i:s');
            $stmt = $mysqli->prepare("INSERT INTO tbl_files (grave_filename, record_id, date_uploaded) VALUES (?, ?, ?)");

            if (!$stmt) {
            throw new Exception("Database prepare failed");
            }

            $stmt->bind_param("sis", $image_url, $data['grave-no'], $created);

            if (!$stmt->execute()) {
            throw new Exception("Database insert failed");
            }
        }

        message("Record Added", "Grave record has been successfully registered in the system", "success");
        header("location: ../index.php?page=record");

    } catch (Exception $e) {
        message("Record Creation Failed", "An error occurred while adding the grave record: " . $e->getMessage(), "error");
        header("location: ../index.php?page=record");
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        if (isset($update_stmt)) {
            $update_stmt->close();
        }
    }
}

function update()
{
    global $mysqli;

    // Early return if not a POST request
    if (!isset($_POST['btn-submit'])) {
        return;
    }

    $record_id = $_GET['record_id'] ?? 0;

    // Define required fields
    $required_fields = [
        'deceased-name', 'deceased-birthday', 'deceased-deathday',
        'deceased-gender', 'deceased-agegroup', 'deceased-contactname',
        'deceased-contactno', 'deceased-contactemail', 'grave-no',
    ];

    // Validate all required fields
    $errors = [];
    $data = [];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = $field;
        } else {
            $data[$field] = trim($_POST[$field]);
        }
    }

    // If there are validation errors
    if (!empty($errors)) {
        message("Validation Error", "All required fields must be filled out completely before submitting the form", "error");
        header("location: ../index.php?page=record");
        exit();
    }

    try {
        $sql = "UPDATE grave_record SET
                record_name = ?, record_birth = ?, record_death = ?,
                record_gender = ?, record_agegroup = ?, record_contactperson = ?,
                record_contactno = ?, record_contactemail = ?, grave_id = ?
                WHERE record_id = ?";

        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $mysqli->error);
        }

        $stmt->bind_param("ssssssssis",
            $data['deceased-name'],
            $data['deceased-birthday'],
            $data['deceased-deathday'],
            $data['deceased-gender'],
            $data['deceased-agegroup'],
            $data['deceased-contactname'],
            $data['deceased-contactno'],
            $data['deceased-contactemail'],
            $data['grave-no'],
            $record_id
        );

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        // Handle image upload if a file is selected
            if (!isset($_FILES["grave-img"]) || $_FILES["grave-img"]["error"] !== UPLOAD_ERR_OK) {
                message("Image Upload Required", "Please select an image file to upload before proceeding with the record update", "error");
                header("location: ../index.php?graveid=" . $data['grave-no'] . "&page=add_img");
                exit();
            }
        
            $file = $_FILES['grave-img'];
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            $max_size = 2 * 1024 * 1024; // 2MB
        
            try {
                if (!in_array($file['type'], $allowed_types)) {
                    throw new Exception("File must be jpeg, jpg, or png");
                }
        
                if ($file['size'] > $max_size) {
                    throw new Exception("File size must be less than 2MB");
                }
        
                $cloudinary = getCloudinaryInstance();
        
                // Upload to Cloudinary
                $response = $cloudinary->uploadApi()->upload(
                    $file['tmp_name'],
                    [
                        'folder' => 'grave_images',
                        'public_id' => uniqid('grave_'),
                    ]
                );
        
                if (!isset($response['secure_url'])) {
                    throw new Exception("Failed to upload image to Cloudinary");
                }
        
                $image_url = $response['secure_url'];
        
                // Save URL to database
                $created = date('Y-m-d H:i:s');
                $stmt = $mysqli->prepare("INSERT INTO tbl_files (grave_filename, record_id, date_uploaded) VALUES (?, ?, ?)");
        
                if (!$stmt) {
                    throw new Exception("Database prepare failed");
                }
        
                $stmt->bind_param("sis", $image_url,  $data['grave-no'], $created);
        
                if (!$stmt->execute()) {
                    throw new Exception("Database insert failed");
                }
        
                message("Image Upload Successful", "The grave image has been successfully uploaded and associated with the record", "success");
                header("location: ../index.php?page=map");
        
            } catch (Exception $e) {
                message("Image Upload Failed", "Failed to upload grave image: " . $e->getMessage(), "error");
                header("location: ../index.php?graveid=" .  $data['grave-no'] . "&page=add_img");
            } finally {
                if (isset($stmt)) {
                    $stmt->close();
                }
                if (isset($mysqli)) {
                    $mysqli->close();
                }
            }
        

        message("Record Update Successful", "The grave record has been successfully updated with all changes saved", "success");
        header("location: ../index.php?page=deceased");

    } catch (Exception $e) {
        message("Record Update Failed", "An error occurred while updating the grave record: " . $e->getMessage(), "error");
        header("location: ../index.php?page=deceased");
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}

function plot()
{
    global $mysqli;
    $point = $coordinate = "";
    $point_error = $coordinate_error = "";
    if (isset($_POST['btn-insert'])) {

        if (empty($_POST["plot-point"])) {
            $point_error = "true";
        } else {
            $point = $_POST["plot-point"];
        }

        if (empty($_POST["plot-coordinate"])) {
            $coordinate_error = "true";
        } else {
            $coordinate = $_POST["plot-coordinate"];
        }

        // SHOW TABLE STATUS LIKE 'grave_emp';
        $query = "SELECT COUNT(grave_no) as max_no FROM grave_points";
        $result = mysqli_query($mysqli, $query);
        $ai = mysqli_fetch_array($result);
        $max_id = $ai['max_no'] + 1;

        if (empty($plot_coordinate) && empty($plot_point)) {
            $sql = "INSERT INTO grave_points (grave_id, coordinates) VALUES (?,?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ss", $param_graveno, $param_coordinate);
                $param_graveno = $max_id;
                $param_coordinate = $point . ", " . $coordinate;
                if ($stmt->execute()) {
                    message("Plot Creation Successful", "The new plot has been successfully added to the cemetery map and is now available for assignment", "success");
                    header("location: ../index.php?page=map");
                } else {
                    message("Plot Creation Failed", "An error occurred while adding the plot to the system. Please verify your input and try again", "error");
                    header("location: ../index.php?page=insert_plot");
                }
                $stmt->close();
            } else {
                message("Database Connection Error", "Unable to prepare the database statement for plot insertion. Please contact system administrator if the problem persists", "error");
                header("location: ../index.php?page=insert_plot");
            }
        } else {
            message("Validation Error", "Plot coordinates and point information are required fields. Please fill in all required information before submitting", "error");
            header("location: ../index.php?page=insert_plot");
        }
    }
}

function img()
{
    global $mysqli;

    if (!isset($_POST['btn-save'])) {
        return;
    }

    $grave_id = filter_input(INPUT_GET, 'graveid', FILTER_VALIDATE_INT);
    if (!$grave_id) {
        message("Invalid Grave ID", "The grave ID provided is not valid or missing. Please select a valid grave to upload an image", "error");
        header("location: ../index.php?page=map");
        exit();
    }

    if (!isset($_FILES["grave-img"]) || $_FILES["grave-img"]["error"] !== UPLOAD_ERR_OK) {
        message("Image Upload Required", "Please select an image file to upload before proceeding with the grave record", "error");
        header("location: ../index.php?graveid=" . $grave_id . "&page=add_img");
        exit();
    }

    $file = $_FILES['grave-img'];
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $max_size = 2 * 1024 * 1024; // 2MB

    try {
        if (!in_array($file['type'], $allowed_types)) {
            throw new Exception("File must be jpeg, jpg, or png");
        }

        if ($file['size'] > $max_size) {
            throw new Exception("File size must be less than 2MB");
        }

        $cloudinary = getCloudinaryInstance();

        // Upload to Cloudinary
        $response = $cloudinary->uploadApi()->upload(
            $file['tmp_name'],
            [
                'folder' => 'grave_images',
                'public_id' => uniqid('grave_'),
            ]
        );

        if (!isset($response['secure_url'])) {
            throw new Exception("Failed to upload image to Cloudinary");
        }

        $image_url = $response['secure_url'];

        // Save URL to database
        $created = date('Y-m-d H:i:s');
        $stmt = $mysqli->prepare("INSERT INTO tbl_files (grave_filename, record_id, date_uploaded) VALUES (?, ?, ?)");

        if (!$stmt) {
            throw new Exception("Database prepare failed");
        }

        $stmt->bind_param("sis", $image_url, $grave_id, $created);

        if (!$stmt->execute()) {
            throw new Exception("Database insert failed");
        }

        message("Image Upload Successful", "The grave image has been successfully uploaded and associated with the record", "success");
        header("location: ../index.php?page=map");

        } catch (Exception $e) {
        message("Image Upload Failed", "Failed to upload grave image: " . $e->getMessage(), "error");
        header("location: ../index.php?graveid=" . $grave_id . "&page=add_img");
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        if (isset($mysqli)) {
            $mysqli->close();
        }
    }
}

function remove_image() {
    global $mysqli;

    if (isset($_POST['id'])) {
        $imageId = $_POST['id'];

        // Fetch image details based on the passed image_id
        $stmt = $mysqli->prepare("SELECT * FROM tbl_files WHERE id = ?");
        $stmt->bind_param('i', $imageId);
        $stmt->execute();
        $fetch = $stmt->get_result();

        if ($row = mysqli_fetch_assoc($fetch)) {
            $full_url = $row['grave_filename'];
            $grave_filename = basename($full_url, '.jpg'); // Assuming file extension is .jpg

            $cloudinary = getCloudinaryInstance();

            try {
                // Delete the image from Cloudinary
                $cloudinary->uploadApi()->destroy('grave_images/' . $grave_filename);

                // Delete the record from the database
                $sqldelete = "DELETE FROM tbl_files WHERE id = ?";
                $stmtDelete = $mysqli->prepare($sqldelete);
                $stmtDelete->bind_param('i', $imageId);
                if ($stmtDelete->execute()) {
                    echo 'success';
                } else {
                    echo 'Error: Something went wrong with database deletion';
                }
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        } else {
            echo 'Error: No file record found';
        }
    } else {
        echo 'Error: Invalid request';
    }
}

function replace_image() {
    global $mysqli;

    if (!isset($_POST['image_id']) || !isset($_FILES['file'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        return;
    }

    $imageId = $_POST['image_id'];
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'Error with the uploaded file']);
        return;
    }

    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (!in_array($file['type'], $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => 'File must be jpeg, jpg, or png']);
        return;
    }

    if ($file['size'] > $max_size) {
        echo json_encode(['status' => 'error', 'message' => 'File size must be less than 2MB']);
        return;
    }

    // Fetch the current image details from the database
    $stmt = $mysqli->prepare("SELECT grave_filename FROM tbl_files WHERE id = ?");
    $stmt->bind_param('i', $imageId);
    $stmt->execute();
    $fetch = $stmt->get_result();

    if ($row = mysqli_fetch_assoc($fetch)) {
        $full_url = $row['grave_filename'];
        $grave_filename = basename($full_url, '.jpg'); // Assuming file extension is .jpg

        $cloudinary = getCloudinaryInstance();

        try {
            // Delete the existing image from Cloudinary
            $cloudinary->uploadApi()->destroy('grave_images/' . $grave_filename);

            // Upload the new image to Cloudinary
            $response = $cloudinary->uploadApi()->upload(
                $file['tmp_name'],
                [
                    'folder' => 'grave_images',
                    'public_id' => uniqid('grave_'),
                ]
            );

            if (!isset($response['secure_url'])) {
                throw new Exception("Failed to upload image to Cloudinary");
            }

            $image_url = $response['secure_url'];

            // Update the database with the new image URL
            $stmtUpdate = $mysqli->prepare("UPDATE tbl_files SET grave_filename = ? WHERE id = ?");
            $stmtUpdate->bind_param('si', $image_url, $imageId);

            if ($stmtUpdate->execute()) {
                echo json_encode(['status' => 'success', 'image_url' => $image_url]);
            } else {
                throw new Exception("Failed to update database");
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No file record found']);
    }
}
