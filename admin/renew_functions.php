<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Africa/Accra');
session_start();
include('../includes/config.php');

function renewMembership($memberId, $membershipTypeId, $renewDuration, $totalAmount) {
    global $conn;

    if (empty($memberId) || empty($membershipTypeId) || empty($renewDuration) || empty($totalAmount)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all required fields');
        echo json_encode($response);
        exit;
    }

    // Calculate the new expiry date
    $expiryDate = date('Y-m-d', strtotime("+$renewDuration months"));

    // Update the member's membership type and expiry date
    $stmt = mysqli_prepare($conn, "UPDATE members SET membership_type = ?, expiry_date = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'isi', $membershipTypeId, $expiryDate, $memberId);
    $updateMemberResult = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($updateMemberResult) {
        // Insert the renewal record
        $renewDate = date('Y-m-d');
        $stmt = mysqli_prepare($conn, "INSERT INTO renew (member_id, total_amount, renew_date) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ids', $memberId, $totalAmount, $renewDate);
        $insertRenewResult = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($insertRenewResult) {
            // Respond with success
            $response = array('status' => 'success', 'message' => 'Membership renewed successfully');
            echo json_encode($response);
            exit; 
        } else {
            $response = array('status' => 'error', 'message' => 'Error inserting renewal record.');
            echo json_encode($response);
            exit;
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Error updating membership.');
        echo json_encode($response);
        exit;
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'member-renew') {
    $memberId = $_POST['memberId'];
    $membershipTypeId = $_POST['membershipType'];
    $renewDuration = $_POST['extend'];
    $totalAmount = $_POST['totalAmount'];

    renewMembership($memberId, $membershipTypeId, $renewDuration, $totalAmount);
}


?>