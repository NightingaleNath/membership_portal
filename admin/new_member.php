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
                <?php $page_name = "new_members"; ?>
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
                                                    <h4><?php echo htmlspecialchars($translations['new_staff']); ?></h4>
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
                                                                            <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['member_profile']); ?> *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="file" id="image_path" name="image_path" class="form-control">
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['full_name']); ?> *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="fullname" name="fullname" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['fullname']) ? $row['fullname'] : ''; ?>">
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['contact_number']); ?> *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="tel" id="contact" name="contact" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['contact_number']) ? $row['contact_number'] : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['address']); ?> *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="address" name="address" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['address']) ? $row['address'] : ''; ?>">
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
                                                                                        echo '<option value="" disabled selected>' . htmlspecialchars($translations['select_membership_type']) . '</option>';
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
                                                                </form>
                                                            </div>
                                                            <div class="col-sm-6 mobile-inputs">
                                                                <h4 class="sub-title"></h4>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-6">
                                                                        <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['date_of_birth']); ?></label>
                                                                        <input name="dob" id="dropper-animation" class="form-control dob" type="text" autocomplete="off" placeholder="" value="<?php echo isset($row['dob']) ? $row['dob'] : ''; ?>">
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['gender']); ?></label>
                                                                        <select class="js-example-disabled-results col-sm-12" name="gender" id="gender" required>
                                                                            <?php
                                                                            // Check if we are coming from an edit page and $row['gender'] is set
                                                                            if (isset($row['gender']) && !empty($row['gender'])) {
                                                                                $selectedGender = $row['gender'];

                                                                                // Define the gender options
                                                                                $genders = ['Male', 'Female'];

                                                                                // Output the selected option
                                                                                foreach ($genders as $gender) {
                                                                                    if ($gender == $selectedGender) {
                                                                                        echo '<option value="' . htmlspecialchars($gender) . '" selected>' . htmlspecialchars($gender) . '</option>';
                                                                                    } else {
                                                                                        echo '<option value="' . htmlspecialchars($gender) . '">' . htmlspecialchars($gender) . '</option>';
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                // Output the first option as "Select gender" and disabled
                                                                                echo '<option value="" disabled selected>' . htmlspecialchars($translations['select_gender']) . '</option>';

                                                                                // Define the gender options
                                                                                $genders = ['Male', 'Female'];

                                                                                // Output all options
                                                                                foreach ($genders as $gender) {
                                                                                    echo '<option value="' . htmlspecialchars($gender) . '">' . htmlspecialchars($gender) . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['email']); ?> *</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="email" id="email" name="email" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['country']); ?> *</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" id="country" name="country" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['country']) ? $row['country'] : ''; ?>">
                                                                    </div>
                                                                </div>       
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['occupation']); ?> *</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" id="occupation" name="occupation" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['occupation']) ? $row['occupation'] : ''; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['postcode']); ?></label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" id="postcode" name="postcode" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['postcode']) ? $row['postcode'] : ''; ?>">
                                                                    </div>
                                                                </div>             
                                                            </div>
                                                       </div>
                                                       <label class="col-sm-5"></label>
                                                       <div class="row">
                                                            <label class="col-sm-5"></label>
                                                            <div class="col-sm-5">
                                                                <?php if(isset($row) && !empty($row)): ?>
                                                                    <button id="member-update" type="submit" class="btn btn-primary m-b-0"><?php echo htmlspecialchars($translations['update']); ?></button>
                                                                <?php else: ?>
                                                                    <button id="member-add" type="submit" class="btn btn-primary m-b-0"><?php echo htmlspecialchars($translations['submit']); ?></button>
                                                                <?php endif; ?>
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
        function convertDateFormat(dateStr) {
            if (dateStr.includes('/')) {
                var parts = dateStr.split('/');
                return `${parts[2]}-${parts[0].padStart(2, '0')}-${parts[1].padStart(2, '0')}`;
            } else {
                return dateStr;
            }
        }
      $(document).ready(function() {
        $('#member-update').click(function(event){
            event.preventDefault();

            (async () => {
                var id = $('#member-id').val();
                var dob = convertDateFormat($('#dropper-animation').val());

                if (!dob) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please fill in Date of Birth.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                var image_path = $('#image_path')[0].files[0];

                var formData = new FormData();
                formData.append('id', id);
                formData.append('fullname', $('#fullname').val());
                formData.append('contact', $('#contact').val());
                formData.append('gender', $('#gender').val());
                formData.append('email', $('#email').val());
                formData.append('address', $('#address').val());
                formData.append('country', $('#country').val());
                formData.append('postcode', $('#postcode').val());
                formData.append('occupation', $('#occupation').val());
                formData.append('membership_type', $('#membership_type').val());
                formData.append('dob', dob);
                formData.append('action', 'member-update');
                if (image_path) {
                    formData.append('image_path', image_path);
                }

                $.ajax({
                    url: 'member_functions.php',
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var parsedResponse = JSON.parse(response);
                        if (parsedResponse.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                html: parsedResponse.message,
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'manage_members.php';
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
            })();
        });

      });
    </script>
    <script>
        $('#member-add').click(function(event){
            event.preventDefault(); // prevent the default form submission
            
            (async () => {
                var dob = convertDateFormat($('#dropper-animation').val());

                if (!dob) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please fill in Date of Birth.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                var image_path = $('#image_path')[0].files[0];
                if (!image_path) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please select an image file',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                var formData = new FormData();
                formData.append('fullname', $('#fullname').val());
                formData.append('contact', $('#contact').val());
                formData.append('gender', $('#gender').val());
                formData.append('email', $('#email').val());
                formData.append('address', $('#address').val());
                formData.append('country', $('#country').val());
                formData.append('postcode', $('#postcode').val());
                formData.append('occupation', $('#occupation').val());
                formData.append('membership_type', $('#membership_type').val());
                formData.append('dob', dob);
                formData.append('image_path', image_path);
                formData.append('action', 'member-add');

                if (!formData.get('fullname') || !formData.get('contact') || 
                    !formData.get('gender') || !formData.get('email') || !formData.get('address') || 
                    !formData.get('country') || !formData.get('postcode') || !formData.get('occupation') || 
                    !formData.get('membership_type')) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                console.log('Data HERE: ' + JSON.stringify(formData));
                $.ajax({
                    url: 'member_functions.php',
                    type: 'post',
                    data: formData,
                    processData: false, 
                    contentType: false,
                    success: function(response) {
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
                                        location.reload();
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

    <script>
        $(document).ready(function() {
            $('#generate').on('click', function() {
                $.ajax({
                    url: 'generate_id.php',
                    type: 'GET',
                    success: function(response) {
                        $('#staff_id').val(response);
                    },
                    error: function() {
                        alert('Error generating ID');
                    }
                });
            });
        });
    </script>
 </body>

</html>
