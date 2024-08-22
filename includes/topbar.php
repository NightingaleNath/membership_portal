 <nav class="navbar header-navbar pcoded-header">
                <div class="navbar-wrapper">
                    <?php
                        // Assuming you have already connected to your database using $conn
                        $query = "SELECT logo FROM settings WHERE id = 1"; // Adjust the WHERE clause as needed
                        $result = mysqli_query($conn, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $logoPath = $row['logo'];
                        } else {
                            // Fallback logo path if not found in the database
                            $logoPath = '..\files\assets\images\logo.png';
                        }
                    ?>
                    <div class="navbar-logo">
                        <a class="mobile-menu" id="mobile-collapse" href="#!">
                            <i class="feather icon-menu"></i>
                        </a>
                        <a href="..admin/index.php">
                            <img class="img-fluid" src="<?php echo htmlspecialchars($logoPath); ?>" alt="Theme-Logo" style="width: 150px; height: 30px;">
                        </a>
                        <a class="mobile-options">
                            <i class="feather icon-more-horizontal"></i>
                        </a>
                    </div>

                    <div class="navbar-container container-fluid">
                        <ul class="nav-left">
                            <li class="header-search">
                                <div class="main-search morphsearch-search">
                                    <div class="input-group">
                                        <span class="input-group-addon search-close"><i
                                                class="feather icon-x"></i></span>
                                        <input type="text" class="form-control">
                                        <span class="input-group-addon search-btn"><i
                                                class="feather icon-search"></i></span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="#!" onclick="javascript:toggleFullScreen()">
                                    <i class="feather icon-maximize full-screen"></i>
                                </a>
                            </li>
                        </ul>
                        <?php include('topbarHeaderMessage.php')?>
                    </div>
                </div>
            </nav>