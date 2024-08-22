<?php 
date_default_timezone_set('Africa/Accra');
include('../includes/config.php');

function saveMembershipType($memberType, $amount) {
    global $conn;

    if (empty($memberType) || empty($amount)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all fields');
        echo json_encode($response);
        exit;
    }

    // Check if the membership type already exists
    $stmt = mysqli_prepare($conn, "SELECT * FROM membership_types WHERE type = ?");
    mysqli_stmt_bind_param($stmt, "s", $memberType);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count = mysqli_num_rows($result);

    if ($count > 0) { 
        $response = array('status' => 'error', 'message' => 'Membership type already exists');
        echo json_encode($response);
        exit;
    } else {
        // Insert a new membership type
        $stmt = mysqli_prepare($conn, "INSERT INTO membership_types (type, amount) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $memberType, $amount);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $response = array('status' => 'success', 'message' => 'Membership type added successfully');
            echo json_encode($response);
            exit;
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to add membership type');
            echo json_encode($response);
            exit;
        }
    }
}

// Function to update an existing membership type
function updateMembershipType($id, $memberType, $amount) {
    global $conn;

    if (empty($id) || empty($memberType) || empty($amount)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all fields');
        echo json_encode($response);
        exit;
    }

    // Update the membership type
    $stmt = mysqli_prepare($conn, "UPDATE membership_types SET type = ?, amount = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $memberType, $amount, $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $response = array('status' => 'success', 'message' => 'Membership type updated successfully');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update membership type');
        echo json_encode($response);
        exit;
    }
}

function deleteMembershipType($id) {
    global $conn;

    if (empty($id)) {
        $response = array('status' => 'error', 'message' => 'Invalid ID');
        echo json_encode($response);
        exit;
    }

    // Delete the membership type
    $stmt = mysqli_prepare($conn, "DELETE FROM membership_types WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $response = array('status' => 'success', 'message' => 'Membership type deleted successfully');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete membership type');
        echo json_encode($response);
        exit;
    }
}

// Handling POST requests
if ($_POST['action'] === 'save') {
    $memberType = $_POST['member_type'];
    $amount = $_POST['amount'];
    saveMembershipType($memberType, $amount);
} elseif ($_POST['action'] === 'update') {
    $id = $_POST['id'];
    $memberType = $_POST['member_type'];
    $amount = $_POST['amount'];
    updateMembershipType($id, $memberType, $amount);
} elseif ($_POST['action'] === 'delete') {
    $id = $_POST['id'];
    deleteMembershipType($id);
}

?>