<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Africa/Accra');
session_start();
include('../includes/config.php');

function resizeImage($sourcePath, $destinationPath, $maxWidth, $maxHeight) {
    $image_info = getimagesize($sourcePath);
    $originalWidth = $image_info[0];
    $originalHeight = $image_info[1];
    $mime = $image_info['mime'];

    $ratio = $originalWidth / $originalHeight;
    
    if ($maxWidth / $maxHeight > $ratio) {
        $newWidth = $maxHeight * $ratio;
        $newHeight = $maxHeight;
    } else {
        $newHeight = $maxWidth / $ratio;
        $newWidth = $maxWidth;
    }

    if ($mime === 'image/jpeg') {
        $src = imagecreatefromjpeg($sourcePath);
    } elseif ($mime === 'image/png') {
        $src = imagecreatefrompng($sourcePath);
    } else {
        throw new Exception('Unsupported image type');
    }

    $dst = imagecreatetruecolor($newWidth, $newHeight);

    // Preserve transparency for PNG images
    if ($mime === 'image/png') {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $transparent);
    }

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

    // Save the resized image
    if ($mime === 'image/jpeg') {
        imagejpeg($dst, $destinationPath, 90); // 90 is the quality level for JPEG
    } elseif ($mime === 'image/png') {
        imagepng($dst, $destinationPath);
    }

    // Free memory
    imagedestroy($src);
    imagedestroy($dst);
}

function updateSettings($systemName, $currency, $logo) {
    global $conn;
    
    if (empty($systemName) || empty($currency)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all required fields');
        echo json_encode($response);
        exit;
    }

    // Handle logo upload if provided
    $finalLogoPath = null;
    if ($logo) {
        // Check if the file size exceeds 2MB (2 * 1024 * 1024 bytes)
        if ($logo['size'] > 2 * 1024 * 1024) {
            $response = array('status' => 'error', 'message' => 'Image size should not exceed 2MB');
            echo json_encode($response);
            exit;
        }

        $allowed_types = array('image/jpeg', 'image/png');
        if (!in_array($logo['type'], $allowed_types)) {
            $response = array('status' => 'error', 'message' => 'Invalid image type');
            echo json_encode($response);
            exit;
        }

        $finalLogoPath = '../uploads/logo/' . basename($logo['name']);
        if (!move_uploaded_file($logo['tmp_name'], $finalLogoPath)) {
            $response = array('status' => 'error', 'message' => 'Failed to upload the image');
            echo json_encode($response);
            exit;
        }
    } else {
        // Fetch the existing image path from the database
        $stmt = mysqli_prepare($conn, "SELECT logo FROM settings WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $finalLogoPath);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }

    // Update the settings in the database
    $stmt = mysqli_prepare($conn, "UPDATE settings SET system_name=?, currency=?, logo=? WHERE id=1");
    mysqli_stmt_bind_param($stmt, 'sss', $systemName, $currency, $finalLogoPath);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Settings updated successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update settings');
    }
    echo json_encode($response);
    exit;
}

function updateLanguage($userId, $language) {
    global $conn;

    // Validate the input
    if (empty($language)) {
        $response = array('status' => 'error', 'message' => 'Language is required');
        echo json_encode($response);
        exit;
    }

    // Validate language code
    $allowed_languages = ['en', 'fr', 'es', 'hi', 'fil', 'id', 'pk', 'my', 'bd', 'sw', 'am', 'si']; // Add more languages as needed

    if (!in_array($language, $allowed_languages)) {
        $response = array('status' => 'error', 'message' => 'Invalid language code');
        echo json_encode($response);
        exit;
    }

    // Update the user's language preference in the database
    $stmt = mysqli_prepare($conn, "UPDATE users SET locale=? WHERE id=?");
    if (!$stmt) {
        $response = array('status' => 'error', 'message' => 'Failed to prepare statement');
        echo json_encode($response);
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'si', $language, $userId);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Language updated successfully');
        echo json_encode($response);
        $_SESSION['locale'] = $language;
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update language');
        echo json_encode($response);
        exit;
    }

}

function changePassword($email, $oldPassword, $newPassword) {
    global $conn;

    if (empty($oldPassword) || empty($newPassword)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all fields');
        echo json_encode($response);
        exit;
    }

    // Check if the email exists and retrieve the current password hash
    $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count = mysqli_num_rows($result);

    if ($count == 0) {
        $response = array('status' => 'error', 'message' => 'Email not found');
        echo json_encode($response);
        exit;
    } else {
        $row = mysqli_fetch_assoc($result);
        $currentPasswordHash = $row['password'];

        // Verify the old password using MD5
        if (md5($oldPassword) !== $currentPasswordHash) {
            $response = array('status' => 'error', 'message' => 'Old password is incorrect');
            echo json_encode($response);
            exit;
        }

        // Hash the new password using MD5
        $hashedNewPassword = md5($newPassword);

        // Check if the new password is the same as the old password
        if ($hashedNewPassword === $currentPasswordHash) {
            $response = array('status' => 'error', 'message' => 'New password cannot be the same as the old password');
            echo json_encode($response);
            exit;
        }
        
        // Prepare the query to update the password
        $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "ss", $hashedNewPassword, $email);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $response = array('status' => 'success', 'message' => 'Password reset successfully');
            echo json_encode($response);
            exit;
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to reset password');
            echo json_encode($response);
            exit;
        }
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'settings-update') {
    $systemName = $_POST['systemName'];
    $currency = $_POST['currency'];
    $logo = isset($_FILES['logo']) ? $_FILES['logo'] : null;

    try {
        updateSettings($systemName, $currency, $logo);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif (isset($_POST['action']) && $_POST['action'] === 'update-language') {
    $userId = $_SESSION['id'];
    $language = $_POST['language'];

    try {
        updateLanguage($userId, $language);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif ($_POST['action'] === 'change_password') {
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $response = changePassword($email, $oldPassword, $newPassword);
        echo $response;
    } else {
        $response = array('status' => 'error', 'message' => 'User not logged in');
        echo json_encode($response);
        exit;
    }
}

?>
