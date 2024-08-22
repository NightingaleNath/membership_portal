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

// Check if the user has the role of Admin
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

                <?php $page_name = "settings"; ?>
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
                                                    <h4><?php echo htmlspecialchars($translations['settings']); ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-header end -->
                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <?php
                                            // Check if the edit parameter is set and fetch the record from the database
                                            
                                            $id = 1;
                                            $stmt = mysqli_prepare($conn, "SELECT * FROM settings WHERE id = ?");
                                            mysqli_stmt_bind_param($stmt, "i", $id);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            $row = mysqli_fetch_assoc($result);
                                           
                                        ?>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <!-- tab content start -->
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="personal" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-xl-4">
                                                                <!-- user contact card left side start -->
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h5 class="card-header-text"><?php echo htmlspecialchars($translations['system_settings']); ?></h5>
                                                                    </div>
                                                                    <div class="card-block groups-contact">
                                                                        <form enctype="multipart/form-data">
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-12">
                                                                                    <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['system_name']); ?> *</label>
                                                                                </div>
                                                                                <div class="col-sm-12">
                                                                                    <input type="text" id="systemName" name="systemName" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['system_name']) ? $row['system_name'] : ''; ?>" style="font-weight: bold;">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-12">
                                                                                    <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['logo']); ?> *</label>
                                                                                </div>
                                                                                <div class="col-sm-12">
                                                                                    <input type="file" id="logo" name="logo" class="form-control">
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group row">
                                                                                <div class="col-sm-12">
                                                                                    <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['currency']); ?> *</label>
                                                                                </div>
                                                                                <div class="col-sm-12">
                                                                                    <input type="text" id="currency" name="currency" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['currency']) ? $row['currency'] : ''; ?>" style="font-weight: bold;">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-12">
                                                                                    <button id="update_settings" type="button" class="btn btn-primary btn-md btn-block waves-effect text-center"><?php echo htmlspecialchars($translations['update_settings']); ?></button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <!-- user contact card left side end -->
                                                            </div>
                                                            <div class="col-xl-4">
                                                                <!-- user contact card left side start -->
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h5 class="card-header-text"><?php echo htmlspecialchars($translations['account_settings']); ?></h5>
                                                                    </div>
                                                                    <div class="card-block groups-contact">
                                                                        <form enctype="multipart/form-data">
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-12">
                                                                                    <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['old_password']); ?> *</label>
                                                                                </div>
                                                                                <div class="col-sm-12">
                                                                                    <input id="old_password" type="password" class="form-control" placeholder="">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-12">
                                                                                    <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['new_password']); ?> *</label>
                                                                                </div>
                                                                                <div class="col-sm-12">
                                                                                    <input id="new_password" type="password" class="form-control" placeholder="">
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group row">
                                                                                <div class="col-sm-12">
                                                                                    <label for="userName-2" class="block"><?php echo htmlspecialchars($translations['confirm_new_password']); ?> *</label>
                                                                                </div>
                                                                                <div class="col-sm-12">
                                                                                    <input id="confirm_password" type="password" class="form-control" placeholder="">
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group row">
                                                                                <div class="col-sm-12">
                                                                                    <button id="change_password" type="button" class="btn btn-primary btn-md btn-block waves-effect text-center"><?php echo htmlspecialchars($translations['change_password']); ?></button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <!-- user contact card left side end -->
                                                            </div>
                                                            <div class="col-xl-4">
                                                                <!-- user contact card left side start -->
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h5 class="card-header-text"><?php echo htmlspecialchars($translations['localization']); ?></h5>
                                                                    </div>
                                                                    <div class="card-block groups-contact">
                                                                        <form enctype="multipart/form-data">
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-12">
                                                                                    <div class="col-sm-12">
                                                                                        <select class="form-control" name="language" id="language" required>
                                                                                            <option value="" disabled selected><?php echo htmlspecialchars($translations['select_language']); ?></option>
                                                                                            <?php
                                                                                                $languages = [
                                                                                                    'en' => 'English',
                                                                                                    'fr' => 'French',
                                                                                                    'es' => 'Spanish',
                                                                                                    'hi' => 'Hindi',
                                                                                                    'fil' => 'Filipino',
                                                                                                    'id' => 'Indonesian',
                                                                                                    'pk' => 'Pakistani',
                                                                                                    'my' => 'Malaysian',
                                                                                                    'bd' => 'Bangladeshi',
                                                                                                    'sw' => 'Swahili',
                                                                                                    'am' => 'Amharic',
                                                                                                    'si' => 'Sinhala',
                                                                                                ];

                                                                                                // Output all options
                                                                                                foreach ($languages as $code => $name) {
                                                                                                    echo '<option value="' . htmlspecialchars($code) . '">' . htmlspecialchars($name) . '</option>';
                                                                                                }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group row m-r-5 m-l-5">
                                                                                <div class="col-sm-12">
                                                                                    <button id="update-language" type="button" class="btn btn-primary btn-md btn-block waves-effect text-center"><?php echo htmlspecialchars($translations['update_language']); ?></button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <!-- user contact card left side end -->
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

<script type="text/javascript">
    $('#update_settings').click(function(event) {
        event.preventDefault();

        (async () => {
            var formData = new FormData();
            formData.append('action', 'settings-update');
            formData.append('systemName', $('#systemName').val());
            formData.append('currency', $('#currency').val());

            if (!formData.get('systemName') || !formData.get('currency')) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Please fill in all fields.',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if ($('#logo')[0].files.length > 0) {
                formData.append('logo', $('#logo')[0].files[0]);
            }
            
            console.log('CALLING HERE')
            $.ajax({
                url: 'settings_function.php',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('success function called');
                    response = JSON.parse(response);
                    console.log('RESPONSE HERE: ' + response.status)
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
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        })()
    });
</script>

<script>
    $('#update-language').click(function(event) {
        event.preventDefault();
        
        (async () => {
            var language = $('#language').val();

            if (!language) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Please select your preferred language.',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK'
                });
                return;
            }

            var data = {
                language: language,
                action: "update-language", // Ensure this matches the PHP condition
            };

            console.log('Data HERE: ' + JSON.stringify(data));

            $.ajax({
                url: 'settings_function.php',
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

<script type="text/javascript">
    $('#change_password').click(function(event) {
        event.preventDefault();

        (async () => {
            var data = {
                old_password: $('#old_password').val(),
                new_password: $('#new_password').val(),
                confirm_password: $('#confirm_password').val(),
                action: "change_password",
            };

            if (data.old_password.trim() === '' || data.new_password.trim() === '' || data.confirm_password.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    text: 'Please fill in all fields.',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (data.new_password !== data.confirm_password) {
                Swal.fire({
                    icon: 'warning',
                    text: 'New password and confirmation password do not match.',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK'
                });
                return;
            }

            $.ajax({
                url: 'settings_function.php',
                type: 'post',
                data: data,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Reset Successfully',
                            text: 'Your password has been changed successfully. Kindly login again',
                            confirmButtonColor: '#01a9ac',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = '../logout.php';
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
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        })()
    });
</script>

</body>

</html>
