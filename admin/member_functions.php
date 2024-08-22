<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Africa/Accra');
session_start();
include('../includes/config.php');

function resizeImage($sourcePath, $destinationPath, $width, $height) {
    if (!function_exists('imagecreatefromjpeg') || !function_exists('imagejpeg')) {
        throw new Exception('GD library is not available');
    }
    
    list($originalWidth, $originalHeight) = getimagesize($sourcePath);
    $src = imagecreatefromjpeg($sourcePath);
    $dst = imagecreatetruecolor($width, $height);
    
    // Resize
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
    
    // Save the resized image
    imagejpeg($dst, $destinationPath);
    
    // Free memory
    imagedestroy($src);
    imagedestroy($dst);
}

function addMemberRecord($fullname, $contact, $gender, $email, $address, $country, $postcode, $occupation, $membership_type, $dob, $imagePath) {
    global $conn;

    if (empty($fullname) || empty($contact) || empty($gender) || empty($email) ||
        empty($address) || empty($country) || empty($postcode) ||
        empty($occupation) || empty($membership_type) || empty($dob) || empty($imagePath)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all required fields');
        echo json_encode($response);
        exit;
    }

    // Generate a unique membership number
    $membershipNumber = 'CA-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

    // Check if the record already exists
    $stmt = mysqli_prepare($conn, "SELECT id FROM members WHERE membership_number=?");
    mysqli_stmt_bind_param($stmt, 's', $membershipNumber);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $num_rows = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($num_rows > 0) {
        $response = array('status' => 'error', 'message' => 'Member with this membership number already exists');
        echo json_encode($response);
        exit;
    }

    // Validate and upload the image
    $allowed_types = array('image/jpeg', 'image/png');
    if (!in_array($imagePath['type'], $allowed_types)) {
        $response = array('status' => 'error', 'message' => 'Invalid image type');
        echo json_encode($response);
        exit;
    }

    $finalImagePath = '../uploads/images/' . basename($imagePath['name']);
    if (!move_uploaded_file($imagePath['tmp_name'], $finalImagePath)) {
        $response = array('status' => 'error', 'message' => 'Failed to upload the image');
        echo json_encode($response);
        exit;
    }

    // Resize the image
    resizeImage($finalImagePath, $finalImagePath, 230, 230);

    $initialMembershipDuration = 1;
    $expiryDate = date('Y-m-d', strtotime("+$initialMembershipDuration months"));

    // Insert the record into the database with the final image path
    $stmt = mysqli_prepare($conn, "INSERT INTO members (fullname, contact_number, gender, email, address, country, postcode, occupation, membership_type, dob, photo, membership_number, expiry_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssssssssissss', $fullname, $contact, $gender, $email, $address, $country, $postcode, $occupation, $membership_type, $dob, $finalImagePath, $membershipNumber, $expiry_date);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Respond with success
        $response = array('status' => 'success', 'message' => 'Member added successfully');
        echo json_encode($response);
    } else {
        // If insertion fails, delete the final image
        unlink($finalImagePath);
        $response = array('status' => 'error', 'message' => 'Failed to add member');
        echo json_encode($response);
    }

    exit;
}

function updateMemberRecord($id, $fullname, $contact, $gender, $email, $address, $country, $postcode, $occupation, $membership_type, $dob, $imagePath) {
    global $conn;

    if (empty($id) || empty($fullname) || empty($contact) || empty($gender) || empty($email) ||
        empty($address) || empty($country) || empty($postcode) ||
        empty($occupation) || empty($membership_type) || empty($dob)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all required fields');
        echo json_encode($response);
        exit;
    }

    // Handle image upload and resizing if a new image is provided
    if ($imagePath) {
        $allowed_types = array('image/jpeg', 'image/png');
        if (!in_array($imagePath['type'], $allowed_types)) {
            $response = array('status' => 'error', 'message' => 'Invalid image type');
            echo json_encode($response);
            exit;
        }

        $finalImagePath = '../uploads/images/' . basename($imagePath['name']);
        if (!move_uploaded_file($imagePath['tmp_name'], $finalImagePath)) {
            $response = array('status' => 'error', 'message' => 'Failed to upload the image');
            echo json_encode($response);
            exit;
        }

        // Resize the image
        resizeImage($finalImagePath, $finalImagePath, 230, 230);
    } else {
        // Fetch the existing image path from the database
        $stmt = mysqli_prepare($conn, "SELECT photo FROM members WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $finalImagePath);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }

    // Update the record in the database
    $stmt = mysqli_prepare($conn, "UPDATE members SET fullname=?, contact_number=?, gender=?, email=?, address=?, country=?, postcode=?, occupation=?, membership_type=?, dob=?, photo=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'ssssssssissi', $fullname, $contact, $gender, $email, $address, $country, $postcode, $occupation, $membership_type, $dob, $finalImagePath, $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Member updated successfully');
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update member');
        echo json_encode($response);
    }

    exit;
}

function deleteMemberRecord($id) {
    global $conn;

    if (empty($id)) {
        $response = array('status' => 'error', 'message' => 'Invalid member ID');
        echo json_encode($response);
        exit;
    }

    // Check if the member exists in the renew table
    $stmt = mysqli_prepare($conn, "SELECT id FROM renew WHERE member_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $renewExists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);

    if ($renewExists) {
        // Delete the records from the renew table
        $stmt = mysqli_prepare($conn, "DELETE FROM renew WHERE member_id=?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $resultRenew = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if (!$resultRenew) {
            $response = array('status' => 'error', 'message' => 'Failed to delete renew records');
            echo json_encode($response);
            exit;
        }
    }

    // Fetch the image path to delete the file
    $stmt = mysqli_prepare($conn, "SELECT photo FROM members WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $imagePath);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Delete the record from the members table
    $stmt = mysqli_prepare($conn, "DELETE FROM members WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Delete the image file from the server if it exists
        if (!empty($imagePath) && file_exists($imagePath)) {
            unlink($imagePath);
        }

        $response = array('status' => 'success', 'message' => 'Member deleted successfully');
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete member');
        echo json_encode($response);
    }

    exit;
}


if (isset($_POST['action']) && $_POST['action'] === 'member-add') {
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $postcode = $_POST['postcode'];
    $occupation = $_POST['occupation'];
    $membership_type = intval($_POST['membership_type']);
    $dob = $_POST['dob'];
    if (isset($_FILES['image_path'])) {
        $image_path = $_FILES['image_path'];
    } else {
        $image_path = '';
    }

    if (!$image_path) {
        $response = array('status' => 'error', 'message' => 'No image uploaded');
        echo json_encode($response);
        exit;
    }

    $response = addMemberRecord($fullname, $contact, $gender, $email, $address, $country, $postcode, $occupation, $membership_type, $dob, $image_path);
    echo $response;

} elseif (isset($_POST['action']) && $_POST['action'] === 'member-update') {
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $postcode = $_POST['postcode'];
    $occupation = $_POST['occupation'];
    $membership_type = intval($_POST['membership_type']);
    $dob = $_POST['dob'];
    $image_path = isset($_FILES['image_path']) ? $_FILES['image_path'] : null;

    $response = updateMemberRecord($id, $fullname, $contact, $gender, $email, $address, $country, $postcode, $occupation, $membership_type, $dob, $image_path);
    echo $response;
} elseif (isset($_POST['action']) && $_POST['action'] === 'member-delete') {
    $id = $_POST['id'];
    $response = deleteMemberRecord($id);
    echo $response;
}
?>


<?php
// Retrieve the search query and department filter from the AJAX request
$searchQuery = $_POST['recentQuery'];

// Generate the SQL query based on the search query and department filter
$sql = "SELECT e.*, d.department_name 
        FROM tblemployees e 
        LEFT JOIN tbldepartments d ON e.department = d.id";

if ($recentQuery !== '') {
    $sql .= ($departmentFilter === '') ? " WHERE" : " AND";
    $sql .= " (e.first_name LIKE '%$searchQuery%' OR e.last_name LIKE '%$searchQuery%' OR e.designation LIKE '%$searchQuery%')";
}

// Execute the SQL query and fetch the staff data
$employeeData = []; // Array to store the fetched staff data
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $employeeData[] = $row;
    }
}

// Generate and return the HTML markup for the staff cards
if (empty($employeeData)) {
    echo '<div class="col-lg-12 text-center">
            <img src="../files/assets/images/no_data.png" class="img-radius" alt="No Data Found" style="width: 200px; height: auto;">
          </div>';
} else {
    foreach ($employeeData as $employee) {
        $imagePath = empty($employee['image_path']) ? '../files/assets/images/user-card/img-round1.jpg' : $employee['image_path'];
        echo '<div class="col-lg-6 col-xl-3 col-md-6">
                <div class="card rounded-card user-card">
                    <div class="card-block">
                        <div class="img-hover">
                            <img class="img-fluid img-radius" src="' . $imagePath . '" alt="round-img">
                            <div class="img-overlay img-radius">
                                <span>
                                    <a href="staff_detailed.php?id=' . $employee['emp_id'] . '&view=2" class="btn btn-sm btn-primary" style="margin-top: 1px;" data-popup="lightbox"><i class="icofont icofont-eye-alt"></i></a>';
                                     // Check if the user role is Admin or Manager and the employee's designation is not 'Administrator'
                                    if ($userRole === 'Admin' || ($userRole === 'Manager' && $employee['designation'] !== 'Administrator')) {
                                        echo '<a href="new_staff.php?id=' . $employee['emp_id'] . '&edit=1" class="btn btn-sm btn-primary" data-popup="lightbox" style="margin-left: 8px; margin-top: 1px;"><i class="icofont icofont-edit"></i></a>';
                                        
                                        // Only show the delete icon if the employee's designation is not 'Administrator'
                                        if ($employee['designation'] !== 'Administrator') {
                                            echo '<a href="#" class="btn btn-sm btn-primary delete-staff" style="margin-top: 1px;" data-id="' . $employee['emp_id'] . '"><i class="icofont icofont-ui-delete"></i></a>';
                                        }
                                    }

                                echo '</span>
                            </div>
                        </div>
                        <div class="user-content">
                            <h4 class="">' . $employee['first_name'] . ' ' . $employee['middle_name'] . ' ' . $employee['last_name'] . '</h4>
                            <p class="m-b-0 text-muted">' . $employee['designation'] . '</p>
                        </div>
                    </div>
                </div>
            </div>';
    }
}
?>