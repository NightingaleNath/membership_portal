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
                                                    <h3><?php echo htmlspecialchars($translations['manage_members']); ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-header end -->

                                <!-- Page-body start -->
                                <div class="page-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <!-- tab content start -->
                                            <div class="tab-content">
                                                <!-- tab pane contact start -->
                                                <div class="tab-pane active" id="contacts" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <!-- contact data table card start -->
                                                                     <?php
                                                                        // Query to fetch attendance records
                                                                        $stmt = mysqli_prepare($conn, "SELECT m.id, m.membership_number, m.fullname, m.contact_number, m.email, m.address, mt.type, m.expiry_date
                                                                                                        FROM members m
                                                                                                        JOIN membership_types mt ON m.membership_type = mt.id");            
                                                                        mysqli_stmt_execute($stmt);
                                                                        $result = mysqli_stmt_get_result($stmt);
                                                                     ?>
                                                                    <div class="card">
                                                                        <div class="card-header">
                                                                            <h5 class="card-header-text"><?php echo htmlspecialchars($translations['members_table']); ?></h5>
                                                                        </div>
                                                                        <div class="card-block contact-details">
                                                                            <div class="data_table_main table-responsive dt-responsive">
                                                                                <table id="simpletable" class="table  table-striped table-bordered nowrap">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>#</th>
                                                                                            <th><?php echo htmlspecialchars($translations['full_name']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['contact_number']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['email']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['address']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['type']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['status']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['action']); ?></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                                                            <?php
                                                                                                // Calculate the membership status
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
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td><?php echo htmlspecialchars($row['membership_number']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                                                                                <td><strong class="label <?php echo $labelClass; ?>"><?php echo $membershipStatus; ?></strong></td>
                                                                                                <td style="white-space: nowrap; width: 1%;">
                                                                                                    <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                                                                    <div class="btn-group btn-group-sm">
                                                                                                        <?php if(isset($row) && !empty($row['expiry_date'])): ?>
                                                                                                            <button type="button" class="tabledit-edit-button btn btn-primary waves-effect waves-light" style="float: none;margin: 5px;" onclick="window.location.href='member_profile.php?view=2&id=<?php echo $row['id']; ?>'">
                                                                                                                <span class="icofont icofont-user-suited"></span>
                                                                                                            </button>
                                                                                                        <?php endif; ?>
                                                                                                        <button type="button" class="tabledit-edit-button btn btn-primary waves-effect waves-light" style="float: none;margin: 5px;" onclick="window.location.href='new_member.php?edit=1&id=<?php echo $row['id']; ?>'">
                                                                                                            <span class="icofont icofont-ui-edit"></span>
                                                                                                        </button>
                                                                                                        <button type="button" class="tabledit-delete-button btn btn-danger waves-effect waves-light" style="float: none;margin: 5px;"  onclick="deleteMember(<?php echo $row['id']; ?>)">
                                                                                                            <span class="icofont icofont-ui-delete"></span>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php endwhile; ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <th>#</th>
                                                                                            <th><?php echo htmlspecialchars($translations['full_name']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['contact_number']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['email']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['address']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['type']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['status']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['action']); ?></th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- contact data table card end -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- tab pane contact end -->
                                            </div>
                                            <!-- tab content end -->
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-body end -->
                            </div>
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
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
        
        $(function() {
            var interval = setInterval(function() {
                var momentNow = moment();
                $('.date').html(momentNow.format('MMMM DD, YYYY'));  
                $('.time').html(momentNow.format('hh:mm:ss A'));
                $('.day').html(momentNow.format('dddd').toUpperCase());
            }, 100);
        });
    </script>
    <script>
        function deleteMember(memberId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this member?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'member_functions.php',
                        type: 'post',
                        data: { action: 'member-delete', id: memberId },
                        success: function(response) {
                            try {
                                var parsedResponse = JSON.parse(response);
                                console.log('Parsed Response:', parsedResponse);
                                if (parsedResponse.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        text: parsedResponse.message,
                                        confirmButtonColor: '#01a9ac',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        text: parsedResponse.message,
                                        confirmButtonColor: '#eb3422',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            } catch (e) {
                                console.error('Error parsing JSON response:', e, response);
                                Swal.fire({
                                    icon: 'error',
                                    text: 'An unexpected error occurred: ' + response,
                                    confirmButtonColor: '#eb3422',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                icon: 'error',
                                text: 'An error occurred: ' + jqXHR.responseText,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>
