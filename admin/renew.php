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
                <?php $page_name = "renewal"; ?>
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
                                                    <h4><?php echo htmlspecialchars($translations['renew_membership']); ?></h4>
                                                 </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <!-- Page-header end -->
                                   
                                    <!-- Page body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <!-- Basic Inputs Validation start -->
                                                <?php
                                                    // Check if the edit parameter is set and fetch the record from the database
                                                    if(isset($_GET['edit']) && $_GET['edit'] == 1 && isset($_GET['id'])) {
                                                        $id = $_GET['id'];
                                                        $stmt = mysqli_prepare($conn, "SELECT * FROM members WHERE id = ?");
                                                        mysqli_stmt_bind_param($stmt, "i", $id);
                                                        mysqli_stmt_execute($stmt);
                                                        $result = mysqli_stmt_get_result($stmt);
                                                        $row = mysqli_fetch_assoc($result);
                                                    }
                                                ?>
                                                <div class="card">
                                                    <div class="card-block">
                                                        <div class="row">
                                                            <div class="col-sm-6 mobile-inputs">
                                                                <h4 class="sub-title"></h4>
                                                                <input type="hidden" id="member-id" name="member-id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>">
                                                                <form enctype="multipart/form-data">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['full_name']); ?></label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="fullname" name="fullname" readonly autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['fullname']) ? $row['fullname'] : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['membership_type']); ?> *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <select class="js-example-disabled-results col-sm-12" name="membership_type" id="membership_type" required>
                                                                                <?php
                                                                                    // Fetch currency from settings
                                                                                    $settingsQuery = "SELECT currency FROM settings LIMIT 1";
                                                                                    $settingsResult = mysqli_query($conn, $settingsQuery);
                                                                                    $currency = mysqli_fetch_assoc($settingsResult)['currency'];

                                                                                    if (isset($row['membership_type']) && !empty($row['membership_type'])) {
                                                                                             $selectedMembershipId = $row['membership_type'];

                                                                                            // Query the database to get the department details
                                                                                            $stmt = mysqli_prepare($conn, "SELECT id, type, amount FROM membership_types WHERE id = ?");
                                                                                            mysqli_stmt_bind_param($stmt, "i", $selectedMembershipId);
                                                                                            mysqli_stmt_execute($stmt);
                                                                                             mysqli_stmt_bind_result($stmt, $id, $type, $amount);
                                                                                            mysqli_stmt_fetch($stmt);
                                                                                            mysqli_stmt_close($stmt);
                                                                                            // Output the selected option
                                                                                            echo '<option value="' . $id . '" selected>' . htmlspecialchars($type) . ' (' . htmlspecialchars($currency . ' ' . number_format($amount, 2)) . ')</option>';
                                                                                            // Output the rest of the options
                                                                                            $stmt = mysqli_prepare($conn, "SELECT id, type, amount FROM membership_types");
                                                                                            mysqli_stmt_execute($stmt);
                                                                                            mysqli_stmt_store_result($stmt);
                                                                                            mysqli_stmt_bind_result($stmt, $id, $type, $amount);
                                                                                            while (mysqli_stmt_fetch($stmt)) {
                                                                                                if ($id != $selectedMembershipId) {
                                                                                                    echo '<option value="' . $id . '">' . htmlspecialchars($type) . ' (' . htmlspecialchars($currency . ' ' . number_format($amount, 2)) . ')</option>';
                                                                                                }
                                                                                            }
                                                                                            mysqli_stmt_close($stmt);
                                                                                    } else {
                                                                                        // Output the first option as "Select department" and disabled
                                                                                        echo '<option value="" disabled selected>Select membership type</option>';
                                                                                        // Output the rest of the options
                                                                                        $stmt = mysqli_prepare($conn, "SELECT id, type, amount FROM membership_types");
                                                                                        mysqli_stmt_execute($stmt);
                                                                                        mysqli_stmt_store_result($stmt);
                                                                                        mysqli_stmt_bind_result($stmt, $id, $type, $amount);
                                                                                        while (mysqli_stmt_fetch($stmt)) {
                                                                                            if ($id != $selectedMembershipId) {
                                                                                                echo '<option value="' . $id . '">' . htmlspecialchars($type) . ' (' . htmlspecialchars($currency . ' ' . number_format($amount, 2)) . ')</option>';
                                                                                            }
                                                                                        }
                                                                                        mysqli_stmt_close($stmt);
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['total_amount']); ?></label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon" id="basic-addon1"><?php echo htmlspecialchars($currency); ?></span>
                                                                                <input type="text" id="total_amount" name="total_amount" readonly autocomplete="off" class="form-control" placeholder="">
                                                                            </div>
                                                                        </div>
                                                                    </div>   
                                                                </form>
                                                            </div>
                                                            <div class="col-sm-6 mobile-inputs">
                                                                <h4 class="sub-title"></h4>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['membership_number']); ?></label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" id="membership_number" name="membership_number" readonly autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['membership_number']) ? $row['membership_number'] : ''; ?>">
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['renew_upto']); ?></label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <select class="js-example-disabled-results col-sm-12" name="extend" id="extend" required>
                                                                            <option value="1"><?php echo htmlspecialchars($translations['one_month']); ?></option>
                                                                            <option value="3"><?php echo htmlspecialchars($translations['three_months']); ?></option>
                                                                            <option value="6"><?php echo htmlspecialchars($translations['six_months']); ?></option>
                                                                            <option value="12"><?php echo htmlspecialchars($translations['one_year']); ?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>            
                                                            </div>
                                                       </div>
                                                       <label class="col-sm-5"></label>
                                                       <div class="row">
                                                            <label class="col-sm-5"></label>
                                                            <div class="col-sm-5">
                                                                <button id="member-renew" type="submit" class="btn btn-primary m-b-0"><?php echo htmlspecialchars($translations['submit']); ?></button>
                                                            </div>
                                                       </div>
                                                    </div>
                                                </div>
                                                <!-- Basic Inputs Validation end -->
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Page body end -->
                                </div>
                            </div>
                            <!-- Main-body end -->
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
    </script>
    <script>
        $(document).ready(function () {
            function updateTotalAmount() {
                // Extract the selected membership amount
                var selectedOptionText = $('#membership_type option:selected').text();
                
                // Extract the amount from the selected option text
                var membershipTypeAmount = parseFloat(
                    selectedOptionText.match(/[\d,]+\.\d{2}/) ? selectedOptionText.match(/[\d,]+\.\d{2}/)[0].replace(/,/g, '') : 0
                );

                console.log("VALUE HERE: " + membershipTypeAmount);

                // Get the renew duration and ensure it's a number
                var renewDuration = parseFloat($('#extend').val()) || 0;

                // Calculate the total amount
                var totalAmount = membershipTypeAmount * renewDuration;

                // Display the total amount
                $('#total_amount').val(totalAmount.toFixed(2));
            }

            // Attach the update function to change events
            $('#membership_type, #extend').change(updateTotalAmount);

            // Initial calculation
            updateTotalAmount();
        });

    </script>

    <script>
        $('#member-renew').click(function(event){
            event.preventDefault();
            
            (async () => {
                var memberId = $('#member-id').val();
                var membershipTypeId = $('#membership_type').val();
                var renewDuration = $('#extend').val();
                var totalAmount = $('#total_amount').val();

                if (!memberId || !membershipTypeId || !renewDuration || !totalAmount) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                var data = {
                    memberId: memberId,
                    membershipType: membershipTypeId,
                    extend: renewDuration,
                    totalAmount: totalAmount,
                    action: "member-renew",
                };

                console.log('Data HERE: ' + JSON.stringify(data));

                $.ajax({
                    url: 'renew_functions.php',
                    type: 'post',
                    data: data,
                    success: function(response) {
                        console.log('Success function called');
                        console.log('Raw Response:', response);
                       try {
                            var parsedResponse = JSON.parse(response);
                            console.log('Parsed Response:', parsedResponse);
                            if (parsedResponse.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    html: parsedResponse.message,
                                    confirmButtonColor: '#01a9ac',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'renewal.php';
                                    }
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
                            console.error('Error parsing JSON response:', e);
                            Swal.fire({
                                icon: 'error',
                                text: 'An error occurred: ' + response,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Data HERE: ' + textStatus);
                        Swal.fire({
                            icon: 'error',
                            text: 'An error occurred: ' + jqXHR.responseText,
                            confirmButtonColor: '#eb3422',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            })();
        });
    </script>
 </body>

</html>
