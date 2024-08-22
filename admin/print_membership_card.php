<?php
include('../includes/config.php');

$memberId = $_GET['id'];
$selectQuery = "SELECT members.*, membership_types.type AS membership_type_name
                FROM members
                JOIN membership_types ON members.membership_type = membership_types.id
                WHERE members.id = $memberId";
$result = $conn->query($selectQuery);

if ($result->num_rows > 0) {
    $memberDetails = $result->fetch_assoc();

    $createdDate = new DateTime($memberDetails['created_at']);
    $currentDate = new DateTime();
    $interval = $createdDate->diff($currentDate);
    $daysSinceCreation = $interval->days;
    $membershipStatus = ($daysSinceCreation > 30) ? 'Expired' : 'Active';
} else {
    header("Location: manage_members.php");
    exit();
}
// Visit codeastro.com for more projects
$fetchSettingsQuery = "SELECT system_name FROM settings WHERE id = 1";
$fetchSettingsResult = $conn->query($fetchSettingsQuery);

if ($fetchSettingsResult->num_rows > 0) {
    $settings = $fetchSettingsResult->fetch_assoc();
    $systemName = $settings['system_name'];
} else {
    $systemName = 'codeastro.com';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print Membership Card</title>
    <style>
        body {
            background: #222;
            padding: 2rem;
            font-family: helvetica;
        }

        .card {
            background: rgb(192, 178, 195);
            background: linear-gradient(36deg, rgba(192, 178, 195, 1) 0%, rgba(253, 243, 255, 1) 36%, rgba(246, 235, 248, 1) 64%, rgba(202, 187, 205, 1) 100%);
            border-radius: 10px;
            margin: auto;
            width: 500px;
            height: 280px;
            box-shadow: 2px 5px 15px 5px #00000030;
            display: flex;
            flex-flow: column;
            transition: all 1s;
        }

        .card:hover {
            box-shadow: 10px 10px 15px 5px #00000030;
        }

        img {
            width: 5rem;
            height: 4rem;
        }

        .title {
            display: flex;
            justify-content: space-between;
            flex-flow: row-reverse;
            padding: 0.5rem 1.5rem 0.5rem 1.5rem;
            text-transform: uppercase;
            font-size: 12px;
            color: #00000090;
        }

        .emboss {
            padding: 1rem 1.5rem 0 1.5rem;
            font-size: 18px;
            color: black;
            font-family: courier;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .emboss2 {
            padding: 1rem 1.5rem 0 10rem;
            font-size: 11px;
            color: black;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hologram {
 
            width: 6.5rem; /* Adjust the width as needed */
            height: 6.5rem; /* Adjust the height as needed */
            background-size: 400%;
            float: right;
            /* border-radius: 10px; */
            margin-left: auto;
            margin: -5rem 1.5rem 0 auto;
            /* animation: Gradient 15s ease infinite; */
        }

        .photo {
        width: 5rem; /* Adjust the width as needed */
        height: 5rem; /* Adjust the height as needed */
        border-radius: 50%;
        overflow: hidden;
        margin: 1rem; /* Adjust margin as needed */
        float: right;
    }

    .photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

        @keyframes Gradient {
            0% {
                background-position: 30% 0%;
            }
            50% {
                background-position: 71% 100%;
            }
            100% {
                background-position: 30% 0%;
            }
        }

        
    </style>
</head>
<body>
    <div class="card">
        <span class="title">Membership card
        <!-- <img src="https://i0.wp.com/revisewise.ie/wp-content/uploads/2019/07/Your-Logo-here.png?ssl=1" /> -->
        <?php
            $fetchLogoQuery = "SELECT logo FROM settings WHERE id = 1";
            $fetchLogoResult = $conn->query($fetchLogoQuery);

            if ($fetchLogoResult->num_rows > 0) {
                $logoDetails = $fetchLogoResult->fetch_assoc();
                $logoUrl = $logoDetails['logo'];
                echo "<span class='logo'><img src='{$logoUrl}' alt='Logo' style='width: 150px; height: 25px;'></span>";
            }
        ?>
    </span>
        <span class="emboss"><b><?php echo $systemName; ?></b></span>
        <span class="emboss"><b>#<?php echo $memberDetails['membership_number']; ?></b></span>
        <span class="emboss"><?php echo $memberDetails['fullname']; ?></span>
        <div>
        <span class="emboss"><?php echo $memberDetails['address']; ?>, <?php echo $memberDetails['postcode']; ?></span>

        </div>
        <div>
            <span class="emboss"><b>Membership: <?php echo $memberDetails['membership_type_name']; ?></b></span>
            <?php if (!empty($memberDetails['photo'])): ?>
                <img src="../uploads/images/f-2.jpg" class="hologram" alt="Member Photo">
            <?php else: ?>
                <!-- Default photo if no photo is available -->
                <img src="../files/assets/images/user-card/img-round1.jpg" alt="Default Photo">
            <?php endif; ?>
        </div>
        
        <div>
        <hr><small>
        <span class="emboss2">Valid till <?php echo date('F d, Y', strtotime($memberDetails['expiry_date'])); ?></span>
        </small>
    </div>

    </div>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</body>
<!-- Visit codeastro.com for more projects -->
</html>
