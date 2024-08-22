<?php
include('../includes/config.php');

if (isset($_POST['fromDate']) && isset($_POST['toDate'])) {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];

    // Query to fetch revenue records
    $stmt = mysqli_prepare($conn, "SELECT m.fullname, m.membership_number, r.total_amount, r.renew_date, s.currency
                                    FROM renew r
                                    JOIN members m ON r.member_id = m.id
                                    LEFT JOIN settings s ON s.id = 1
                                    WHERE r.renew_date BETWEEN ? AND ?");
    mysqli_stmt_bind_param($stmt, 'ss', $fromDate, $toDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $output = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $date = new DateTime($row['renew_date']);
        $formattedDate = $date->format('jS F, Y');

        $output .= '<tr>';
        $output .= '<td>' . htmlspecialchars($row['fullname']) . '</td>';
        $output .= '<td>' . htmlspecialchars($row['membership_number']) . '</td>';
        $output .= '<td>' . htmlspecialchars($row['currency'] . '' . number_format($row['total_amount'], 2)) . '</td>';
        $output .= '<td>' . $formattedDate . '</td>';
        $output .= '</tr>';
    }

    echo $output;

    mysqli_stmt_close($stmt);

}
?>
