<?php
include('../includes/config.php');

if (isset($_POST['fromDate']) && isset($_POST['toDate'])) {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];

    // Prepare the query to fetch data based on the date range
    $stmt = mysqli_prepare($conn, "SELECT m.membership_number, m.fullname, m.email, mt.type AS membership_type_name, m.expiry_date
                                    FROM members m
                                    LEFT JOIN membership_types mt ON m.membership_type = mt.id
                                    WHERE m.created_at BETWEEN ? AND ?");
    mysqli_stmt_bind_param($stmt, 'ss', $fromDate, $toDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Start building the response HTML
    $output = '';

    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<tr>';
        $output .= '<td>' . htmlspecialchars($row['membership_number']) . '</td>';
        $output .= '<td>' . htmlspecialchars($row['fullname']) . '</td>';
        $output .= '<td>' . htmlspecialchars($row['email']) . '</td>';
        $output .= '<td>' . htmlspecialchars($row['membership_type_name']) . '</td>';
        $output .= '<td>' . (($row['expiry_date'] === null) ? 'New Member' : htmlspecialchars($row['expiry_date'])) . '</td>';
        $output .= '</tr>';
    }
    echo $output;

    mysqli_stmt_close($stmt);
}
?>
