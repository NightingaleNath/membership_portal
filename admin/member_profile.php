<?php 
include('../includes/header.php');
include('../localization.php');
?>
<?php
// Check if the user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

// Check if the user has the role of Manager or Admin
$userRole = $_SESSION['role'];
if ($userRole !== 'admin') {
    header('Location: ../index.php');
    exit();
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

                <?php $page_name = "manage_members"; ?>
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
                                                    <h4><?php echo htmlspecialchars($translations['member_profile']); ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-header end -->

                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <!--profile cover start-->

                                        <?php
                                            // Check if the edit parameter is set and fetch the record from the database
                                            if(isset($_GET['view']) && $_GET['view'] == 2 && isset($_GET['id'])) {
                                                $memberId = $_GET['id'];
                                                $stmt = mysqli_prepare($conn, "SELECT members.*, membership_types.type AS membership_type_name
                                                                        FROM members
                                                                        JOIN membership_types ON members.membership_type = membership_types.id
                                                                        WHERE members.id = ?");
                                                mysqli_stmt_bind_param($stmt, "i", $memberId);
                                                mysqli_stmt_execute($stmt);
                                                $result = mysqli_stmt_get_result($stmt);
                                                $row = mysqli_fetch_assoc($result);

                                                 // Check if expiry date is null or empty
                                                if (empty($row['expiry_date'])) {
                                                    $membershipStatus = 'Inactive';
                                                    $labelClass = 'label-warning';
                                                } else {
                                                    // Calculate the membership status
                                                    $expiryDate = strtotime($row['expiry_date']);
                                                    $currentDate = time();
                                                    $daysDifference = floor(($expiryDate - $currentDate) / (60 * 60 * 24));

                                                    $membershipStatus = ($daysDifference < 0) ? 'Expired' : 'Active';
                                                    $labelClass = ($membershipStatus === 'Active') ? 'label-primary' : 'label-danger';
                                                }
                                            }
                                        ?>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="cover-profile">
                                                    <div class="profile-bg-img">
                                                        <img class="profile-bg-img img-fluid" src="..\files\assets\images\user-profile\bg-img1.jpg" alt="bg-img">
                                                        <div class="card-block user-info">
                                                            <div class="col-md-12">
                                                                <div class="media-left">
                                                                    <a class="profile-image">
                                                                         <img class="user-img img-radius" style="width: 108px; height: 108px;" src="<?php echo !empty($row['photo']) ? htmlspecialchars($row['photo']) : '../files/assets/images/user-card/img-round1.jpg'; ?>" alt="user-img">
                                                                    </a>
                                                                </div>
                                                                <div class="media-body row">
                                                                    <div class="col-lg-12">
                                                                        <div class="user-title">
                                                                            <h2><?php echo htmlspecialchars($row['fullname']); ?></h2>
                                                                            <span class="text-white"><?php echo htmlspecialchars($row['membership_number']); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="pull-right cover-btn">
                                                                    <a href="print_membership_card.php?id=<?php echo $memberId; ?>" target="_blank" class="print-button">
                                                                        <button type="button" class="btn btn-primary m-r-10 m-b-5">
                                                                            <i class="icofont icofont-user-suited"></i> <?php echo htmlspecialchars($translations['membership_card']); ?>
                                                                        </button>
                                                                    </a>
                                                                    </div>   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--profile cover end-->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <!-- tab header start -->
                                                <div class="tab-header card">
                                                    <ul class="nav nav-tabs md-tabs tab-timeline" role="tablist" id="mytab">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-toggle="tab" href="#personal" role="tab"><?php echo htmlspecialchars($translations['member_info']); ?></a>
                                                            <div class="slide"></div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- tab header end -->
                                                <!-- tab content start -->
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="personal" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <!-- contact data table card start -->
                                                                        <div class="card">
                                                                            <div class="card-header">
                                                                                <h5 class="card-header-text"><?php echo htmlspecialchars($translations['about_member']); ?></h5>
                                                                            </div>
                                                                             <div class="card-block">
                                                                                <div class="view-info">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-12">
                                                                                            <div class="general-info">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-12 col-xl-6">
                                                                                                        <div class="table-responsive">
                                                                                                            <table class="table m-0">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['membership_number']); ?></th>
                                                                                                                        <td><?php echo htmlspecialchars($row['membership_number']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['full_name']); ?></th>
                                                                                                                        <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['date_of_birth']); ?></th>
                                                                                                                        <td><?php echo htmlspecialchars(date('jS F, Y', strtotime($row['dob']))); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['gender']); ?></th>
                                                                                                                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['contact_number']); ?></th>
                                                                                                                       <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['email']); ?></th>
                                                                                                                       <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <!-- end of table col-lg-6 -->
                                                                                                    <div class="col-lg-12 col-xl-6">
                                                                                                        <div class="table-responsive">
                                                                                                            <table class="table">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['address']); ?></th>
                                                                                                                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['country']); ?></th>
                                                                                                                        <td><?php echo htmlspecialchars($row['country']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['postcode']); ?></th>
                                                                                                                        <td><?php echo htmlspecialchars($row['postcode']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['occupation']); ?></th>
                                                                                                                        <td><?php echo htmlspecialchars($row['occupation']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['membership_type']); ?></th>
                                                                                                                        <td><?php echo htmlspecialchars($row['membership_type_name']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row"><?php echo htmlspecialchars($translations['status']); ?></th>
                                                                                                                        <td>
                                                                                                                            <strong class="label <?php echo $labelClass; ?>">
                                                                                                                                <?php echo $membershipStatus; ?>
                                                                                                                            </strong>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <!-- end of table col-lg-6 -->
                                                                                                </div>
                                                                                                <!-- end of row -->
                                                                                            </div>
                                                                                            <!-- end of general info -->
                                                                                        </div>
                                                                                        <!-- end of col-lg-12 -->
                                                                                    </div>
                                                                                    <!-- end of row -->
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- contact data table card end -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                                <!-- tab content end -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Page-body end -->
                                </div>
                            </div>
                            <!-- Change password modal start -->
                            <div id="change-password-dialog" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <div class="login-card card-block login-card-modal">
                                        <form class="md-float-material">
                                            <div class="card m-t-15">
                                                <div class="auth-box card-block">
                                                <div class="row m-b-20">
                                                    <div class="col-md-12">
                                                        <h3 class="text-center txt-primary">Change your Password</h3>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="input-group">
                                                    <input id="old_password" type="password" class="form-control" placeholder="Old Password">
                                                    <span class="md-line"></span>
                                                </div>
                                                <div class="input-group">
                                                    <input id="new_password" type="password" class="form-control" placeholder="New Password">
                                                    <span class="md-line"></span>
                                                </div>
                                                <div class="input-group">
                                                    <input id="confirm_password" type="password" class="form-control" placeholder="Confirm New Password">
                                                    <span class="md-line"></span>
                                                </div>
                                                <div class="row m-t-15">
                                                    <div class="col-md-12">
                                                        <button id="change_password" type="button" class="btn btn-primary btn-md btn-block waves-effect text-center">Change</button>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <p class="text-inverse text-left"><b>You will be authenticated after password is changed.</b></p>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
                                        <!-- end of form -->
                                    </div>
                                </div>
                            </div>
                            <!-- Change password modal end-->
                            <!-- Main body end -->
                            <div id="styleSelector">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Required Jquery -->
<?php include('../includes/scripts.php')?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'UA-23581568-13');
</script>

<script>
    function printMembershipCard() {
        window.print();
    }
</script>

</body>

</html>
