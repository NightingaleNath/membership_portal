<?php
session_start();

// Check whether the session variable SESS_MEMBER_ID is present or not
if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '')) {
    // Redirect to the login page
    header("Location: ../index.php");
    exit;
}

// Check if the session has expired
$lastActivity = $_SESSION['last_activity'];
$sessionExpiration = 60 * 30; // Session expires after 5 minutes of inactivity

if (time() - $lastActivity > $sessionExpiration) {
    // Session has expired, destroy the session and redirect to the login page
    session_unset();
    session_destroy();
    
    echo "<script>alert('Your session has expired. Please log in again.');</script>";

    // Redirect to the login page
    echo "<script>window.location = '../index.php';</script>";
    exit;
}

// Update the last activity time
$_SESSION['last_activity'] = time();

$session_sid = $_SESSION['id'];
$session_srole = $_SESSION['role'];
$session_semail = $_SESSION['email'];
$session_sfirstname = $_SESSION['first_name'];
$session_slastname = $_SESSION['last_name'];
$session_smiddlename = $_SESSION['middle_name'];
// session locale
$session_slocale = $_SESSION['locale'];

?>
