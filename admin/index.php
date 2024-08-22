<?php 
include('../includes/header.php');
include('../localization.php');
?>
<?php include('data_values.php')?>

<?php
// Generate the SQL query to fetch the first four recent members
$sql = "SELECT id, fullname, dob, gender, contact_number, email, address, country, postcode, occupation, membership_type, membership_number, created_at, photo, expiry_date
        FROM members
        ORDER BY created_at DESC
        LIMIT 4";

// Execute the SQL query and fetch the member data
$memberData = [];
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $memberData[] = $row;
    }
}
?>
<body>
    <!-- Pre-loader start -->
    <?php include('../includes/loader.php')?>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <?php include('../includes/topbar.php')?>
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php $page_name = "dashboard"; ?>
                    <?php include('../includes/sidebar.php')?>
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <!-- Page-header start -->
                                    <div class="page-header">
                                        <div class="row align-items-end">
                                            <div class="col-lg-8">
                                                <div class="page-header-title">
                                                    <div class="d-inline">
                                                        <h3><?php echo htmlspecialchars($translations['dashboard']); ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Page-header end -->
                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            <!-- user card  start -->
                                            <div class="col-md-6 col-xl-3">
                                                <div class="card user-widget-card bg-c-blue">
                                                    <div class="card-block">
                                                        <i class="feather icon-user bg-simple-c-blue card1-icon"></i>
                                                        <h4><?php echo $totalMembers; ?></h4>
                                                        <p><?php echo htmlspecialchars($translations['total_member']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xl-3">
                                                <div class="card user-widget-card bg-c-pink">
                                                    <div class="card-block">
                                                        <i class="feather icon-home bg-simple-c-pink card1-icon"></i>
                                                        <h4><?php echo $membershipTypes; ?></h4>
                                                        <p><?php echo htmlspecialchars($translations['membership_types']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xl-3">
                                                <div class="card user-widget-card bg-c-green">
                                                    <div class="card-block">
                                                        <i class="feather icon-alert-triangle bg-simple-c-green card1-icon"></i>
                                                        <h4><?php echo $expiredMembers; ?></h4>
                                                        <p><?php echo htmlspecialchars($translations['expired_membership']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xl-3">
                                                <div class="card user-widget-card bg-c-yellow">
                                                    <div class="card-block">
                                                        <i class="feather icon-twitter bg-simple-c-yellow card1-icon"></i>
                                                        <h4><?php echo $formattedRevenue; ?></h4>
                                                        <p><?php echo htmlspecialchars($translations['total_revenue']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- user card  end -->
                                            <!-- Recently Joined Members  start -->
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4><?php echo htmlspecialchars($translations['recently_joined_members']); ?></h4>
                                                        <div class="card-header-right">
                                                            <ul class="list-unstyled card-option">
                                                                <li><i class="feather icon-maximize full-card"></i></li>
                                                                <li><i class="feather icon-minus minimize-card"></i></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="row card-block">
                                                        <?php if (!empty($memberData)): ?>
                                                            <?php foreach ($memberData as $member): ?>
                                                                <div class="col-lg-5 col-xl-3 col-md-5">
                                                                    <div class="card rounded-card user-card">
                                                                        <div class="card-block">
                                                                            <div class="img-hover">
                                                                                <img class="img-fluid img-radius" src="<?php echo !empty($member['photo']) ? $member['photo'] : '../files/assets/images/user-card/img-round1.jpg'; ?>" alt="round-img">
                                                                                <div class="img-overlay img-radius">
                                                                                </div>
                                                                            </div>
                                                                            <div class="user-content">
                                                                                <h4 class=""><?php echo $member['fullname']; ?></h4>
                                                                                <p class="m-b-0 text-muted"><?php echo htmlspecialchars($translations['membership_number']); ?> <?php echo $member['membership_number']; ?></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>    
                                                            <?php endforeach; ?>     
                                                        <?php else: ?>
                                                            <div class="col-12 text-center">
                                                                <div class="alert" style="color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb;" role="alert">
                                                                    <i class="fa fa-info-circle fa-3x"></i>
                                                                    <p class="m-b-0"><?php echo htmlspecialchars($translations['no_recent_members']); ?></p>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Recently Joined Members  end -->
                                             
                                        </div>
                                    </div>
                                    <!-- Page-body end -->
                                </div>
                                <div id="styleSelector"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Jquery -->
    <?php include('../includes/scripts.php')?>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>
     <script type="text/javascript">
        $(document).ready(function() {

            function fetchStaff() {
                $.ajax({
                    url: 'member_functions.php',
                    type: 'POST',
                    success: function(response) {
                        // Clear the existing staff cards
                        $('#staffContainer').empty();

                        // Append the fetched staff cards to the container
                        $('#staffContainer').append(response);
                    }
                });
            }
            
            // Fetch the initial Members based on the default filter
            fetchStaff();
        });
    </script>
</body>

</html>
