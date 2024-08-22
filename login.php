<?php
session_start();
include('includes/config.php');

// Enable error logging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'path_to_your_error_log_file.log'); // Set the path to your error log file
error_reporting(E_ALL);

// Start output buffering
ob_start();

function login($email, $password) {
    global $conn;

    $password = md5($password); // Hash the password

    // Query staff table
    $staffQuery = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    if (!$staffQuery) {
        error_log("MySQL error: " . mysqli_error($conn));
        return array('status' => 'error', 'message' => "Error: " . mysqli_error($conn));
    } else {
        $staffCount = mysqli_num_rows($staffQuery);
        if ($staffCount > 0) {
            $recordsRow = mysqli_fetch_assoc($staffQuery);
            return checkAndSetSession($recordsRow);
        } else {
            return array('status' => 'error', 'message' => 'Invalid Details');
        }
    }
}

function checkAndSetSession($userRecord) {
    // Set session variables and redirect based on user type
    $_SESSION['id'] = $userRecord['id'];
    $_SESSION['email'] = $userRecord['email'];
    $_SESSION['password'] = $userRecord['password'];
    $_SESSION['first_name'] = $userRecord['first_name'];
    $_SESSION['last_name'] = $userRecord['last_name'];
    $_SESSION['middle_name'] = $userRecord['middle_name'];
    $_SESSION['role'] = $userRecord['role'];
    $_SESSION['locale'] = $userRecord['locale'];
    $_SESSION['last_activity'] = time(); // Set the last activity time

    $userType = $userRecord['role'];

    return array(
        'status' => 'success',
        'message' => 'Successfully logged in',
        'role' => $userType
    );
}

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'save') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $loginResponse = login($email, $password);

            // Clean (erase) the output buffer before sending the JSON response
            ob_end_clean();

            header('Content-Type: application/json'); // Set the content type to JSON
            echo json_encode($loginResponse);
        } catch (Exception $e) {
            ob_end_clean();
            error_log("Exception: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(array('status' => 'error', 'message' => 'Internal Server Error', 'error' => $e->getMessage()));
        }
        exit;
    }
}
?>
