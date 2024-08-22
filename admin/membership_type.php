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
                 <?php $page_name = "membership_type"; ?>
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
                                                    <h4><?php echo htmlspecialchars($translations['manage_membership_type']); ?></h4>
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
                                                        <div class="col-xl-3">
                                                            <?php
                                                                // Check if the edit parameter is set and fetch the record from the database
                                                                if (isset($_GET['edit']) && $_GET['edit'] == 1 && isset($_GET['id'])) {
                                                                    $id = $_GET['id'];
                                                                    $stmt = mysqli_prepare($conn, "SELECT * FROM membership_types WHERE id = ?");
                                                                    mysqli_stmt_bind_param($stmt, "i", $id);
                                                                    mysqli_stmt_execute($stmt);
                                                                    $result = mysqli_stmt_get_result($stmt);
                                                                    $row = mysqli_fetch_assoc($result);
                                                                }
                                                            ?>
                                                            <!-- user contact card left side start -->
                                                            <div class="card">
                                                                <div class="tabbed-modal m-b-20 m-t-10 m-r-10 m-l-10">
                                                                    <div class="tab-content">
                                                                        <div class="tab-pane active" id="clock_out" role="tabpanel">
                                                                            <div class="auth-box col-xl-12">
                                                                                <div class="row m-t-20" style="display: flex; justify-content: center; align-items: center;">
                                                                                    <div class="col-md-9">
                                                                                        <div class="card text-center text-white" style="background-color: #404E67;">
                                                                                            <div class="card-block">
                                                                                                <h6 class="day m-b-0"></h6>
                                                                                                <h4 class="time m-t-10 m-b-10"></h4>
                                                                                                <p class="date m-b-0"></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" id="id" value="<?php echo isset($row['id']) ? htmlspecialchars($row['id']) : ''; ?>">
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control" placeholder='.<?php echo htmlspecialchars($translations['membership_type']); ?>.' id="member_type" name="member_type" value="<?php echo isset($row['type']) ? htmlspecialchars($row['type']) : ''; ?>">
                                                                                    <span class="md-line"></span>
                                                                                </div>
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control" placeholder='.<?php echo htmlspecialchars($translations['amount']); ?>.' id="amount" name="amount" value="<?php echo isset($row['amount']) ? htmlspecialchars($row['amount']) : ''; ?>">
                                                                                    <span class="md-line"></span>
                                                                                </div>
                                                                                <div class="row m-t-15">
                                                                                    <div class="col-md-12">
                                                                                        <?php if (isset($row) && !empty($row)): ?>
                                                                                            <button id="type-update" type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center"><?php echo htmlspecialchars($translations['update']); ?></button>
                                                                                        <?php else: ?>
                                                                                            <button id="type-add" type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center"><?php echo htmlspecialchars($translations['submit']); ?></button>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- user contact card left side end -->
                                                        </div>
                                                        <div class="col-xl-9">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <!-- contact data table card start -->
                                                                     <?php
                                                                        $settingsQuery = "SELECT currency FROM settings LIMIT 1";
                                                                        $settingsResult = mysqli_query($conn, $settingsQuery);
                                                                        $currency = mysqli_fetch_assoc($settingsResult)['currency'];

                                                                        // Query to fetch attendance records
                                                                        $stmt = mysqli_prepare($conn, "SELECT * FROM membership_types");
                                                                        mysqli_stmt_execute($stmt);
                                                                        $result = mysqli_stmt_get_result($stmt);
                                                                     ?>
                                                                    <div class="card">
                                                                        <div class="card-header">
                                                                            <h5 class="card-header-text"><?php echo htmlspecialchars($translations['membership_type_records']); ?></h5>
                                                                        </div>
                                                                        <div class="card-block contact-details">
                                                                            <div class="data_table_main table-responsive dt-responsive">
                                                                                <table id="simpletable" class="table  table-striped table-bordered nowrap">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>#</th>
                                                                                            <th><?php echo htmlspecialchars($translations['type']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['amount']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['action']); ?></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php $count = 1; ?>
                                                                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                                                            <tr>
                                                                                                <td><?php echo $count++; ?></td>
                                                                                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                                                                                 <td><?php echo htmlspecialchars($currency . ' ' . number_format($row['amount'], 2)); ?></td>
                                                                                                <td style="white-space: nowrap; width: 1%;">
                                                                                                    <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                                                                    <div class="btn-group btn-group-sm">
                                                                                                        <button type="button" class="tabledit-edit-button btn btn-primary waves-effect waves-light" style="float: none;margin: 5px;" onclick="window.location.href='?edit=1&id=<?php echo $row['id']; ?>'">
                                                                                                            <span class="icofont icofont-ui-edit"></span>
                                                                                                        </button>
                                                                                                        <button type="button" class="tabledit-delete-button btn btn-danger waves-effect waves-light" style="float: none;margin: 5px;" onclick="confirmDelete(<?php echo $row['id']; ?>)">
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
                                                                                            <th><?php echo htmlspecialchars($translations['type']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['amount']); ?></th>
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
        $('#type-add').click(function(event) {
            event.preventDefault(); 
            (async () => {
                var memberType = $('#member_type').val().trim();
                var amount = $('#amount').val().trim();

                if (!memberType || !amount) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                var data = {
                    member_type: memberType,
                    amount: amount,
                    action: "save",
                };

                console.log('Data HERE: ' + JSON.stringify(data));

                $.ajax({
                    url: 'type_functions.php',
                    type: 'post',
                    data: data,
                    success: function(response) {
                        console.log('success function called');
                        response = JSON.parse(response);
                        console.log('RESPONSE HERE: ' + response.status);
                        console.log(`RESPONSE HERE: ${response.message}`);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                html: response.message,
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.message,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Data HERE: ' + JSON.stringify(data));
                        console.log("Response from server: " + jqXHR.responseText);
                        console.log("Status:", textStatus);
                        console.log("Error:", errorThrown);
                        Swal.fire({
                            icon: 'error',
                            text: jqXHR.responseText,
                            confirmButtonColor: '#eb3422',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            })();
        });

        $('#type-update').click(function(event) {
            event.preventDefault();
            (async () => {
                var id = $('#id').val().trim();
                var memberType = $('#member_type').val().trim();
                var amount = $('#amount').val().trim();

                if (!id || !memberType || !amount) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                var data = {
                    id: id,
                    member_type: memberType,
                    amount: amount,
                    action: "update",
                };

                console.log('Data HERE: ' + JSON.stringify(data));

                $.ajax({
                    url: 'type_functions.php',
                    type: 'post',
                    data: data,
                    success: function(response) {
                        console.log('success function called');
                        response = JSON.parse(response);
                        console.log('RESPONSE HERE: ' + response.status);
                        console.log(`RESPONSE HERE: ${response.message}`);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                html: response.message,
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.message,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Data HERE: ' + JSON.stringify(data));
                        console.log("Response from server: " + jqXHR.responseText);
                        console.log("Status:", textStatus);
                        console.log("Error:", errorThrown);
                        Swal.fire({
                            icon: 'error',
                            text: jqXHR.responseText,
                            confirmButtonColor: '#eb3422',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            })();
        });
    </script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'type_functions.php', 
                        type: 'post',
                        data: {
                            action: 'delete',
                            id: id
                        },
                        success: function(response) {
                            response = JSON.parse(response);
                            if (response.status == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'The membership type has been deleted.',
                                    confirmButtonColor: '#01a9ac',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message,
                                    confirmButtonColor: '#eb3422',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                icon: 'error',
                                title: 'AJAX Error',
                                text: 'Something went wrong with the request.',
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
