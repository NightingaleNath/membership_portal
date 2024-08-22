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
                 <?php $page_name = "revenue_report"; ?>
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
                                                    <h4><?php echo htmlspecialchars($translations['revenue_report']); ?></h4>
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
                                                            <!-- user contact card left side start -->
                                                            <div class="card">
                                                                <div class="tabbed-modal m-b-20 m-t-10 m-r-10 m-l-10">
                                                                    <div class="tab-content">
                                                                        <div class="tab-pane active" id="clock_in" role="tabpanel">
                                                                            <div class="auth-box col-xl-12">
                                                                                <div class="row m-t-20" style="display: flex; justify-content: center; align-items: center;">
                                                                                    <div class="col-md-9">
                                                                                        <div class="card text-center text-white" style="background-color: #404E67;">
                                                                                            <div class="card-block">
                                                                                                <h6 class="day m-b-0"></h6>
                                                                                                <h4 class="time m-t-10 m-b-10"><i class="feather icon-arrow-down m-r-15"></i></h4>
                                                                                                <p class="date m-b-0"></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row" style="margin-bottom: 1px;">
                                                                                    <div class="col-sm-12">
                                                                                        <label for="fromDate" class="block"><?php echo htmlspecialchars($translations['from_date']); ?>:</label>
                                                                                    </div>
                                                                                    <div class="col-sm-12">
                                                                                        <div class="input-group">
                                                                                            <input name="fromDate" id="dropper-animation" class="form-control fromDate" type="text" autocomplete="off" placeholder="">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>   
                                                                                <div class="form-group row">
                                                                                    <div class="col-sm-12">
                                                                                        <label for="toDate" class="block"><?php echo htmlspecialchars($translations['to_date']); ?>:</label>
                                                                                    </div>
                                                                                    <div class="col-sm-12">
                                                                                        <div class="input-group">
                                                                                            <input id="dropper-default" class="form-control toDate" name="toDate" type="text" autocomplete="off" placeholder="">
                                                                                            <span class="md-line"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>   
                                                                                <div class="row m-t-15">
                                                                                    <div class="col-md-12">
                                                                                        <button id="generate_revenue" type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center"><?php echo htmlspecialchars($translations['generate_report']); ?></button>
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
                                                                    <div class="card">
                                                                        <div class="col-sm-3">
                                                                            <button id="print_report" class="btn btn-primary m-t-15" style="margin-left: 5px;"><?php echo htmlspecialchars($translations['print_report']); ?></button>
                                                                        </div>
                                                                        <div class="card-block contact-details">
                                                                            <div class="data_table_main table-responsive dt-responsive">
                                                                                <table id="simpletable" class="table  table-striped table-bordered nowrap">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th><?php echo htmlspecialchars($translations['full_name']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['membership_number']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['total_amount']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['date']); ?></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody id="revenue-table-body">
                                                                                        <!-- Rows will be dynamically inserted here -->
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <th><?php echo htmlspecialchars($translations['full_name']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['membership_number']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['total_amount']); ?></th>
                                                                                            <th><?php echo htmlspecialchars($translations['date']); ?></th>
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
        function convertDateFormat(dateStr) {
            if (dateStr.includes('/')) {
                var parts = dateStr.split('/');
                return `${parts[2]}-${parts[0].padStart(2, '0')}-${parts[1].padStart(2, '0')}`;
            } else {
                return dateStr;
            }
        }

        $(document).ready(function() {
            // Fetch and display the revenue report
            $('#generate_revenue').on('click', function() {
                var fromDate = convertDateFormat($('.fromDate').val());
                var toDate = convertDateFormat($('.toDate').val());

                console.error('DATA HERE: ' + fromDate + toDate);

                $.ajax({
                    type: 'POST',
                    url: 'generate_revenue.php', // Your PHP file to handle the revenue query
                    data: { fromDate: fromDate, toDate: toDate },
                    success: function(response) {
                        $('#revenue-table-body').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + error);
                    }
                });
            });

            // Print the revenue report
            $('#print_report').on('click', function() {
                var printContent = document.getElementById('simpletable').outerHTML;
                var printWindow = window.open('', '', 'height=600,width=800');
                printWindow.document.write('<html><head><title>Print Report</title>');
                printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>');
                printWindow.document.write('</head><body>');
                printWindow.document.write(printContent);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            });
        });
    </script>

</body>

</html>
