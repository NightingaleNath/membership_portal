<nav class="pcoded-navbar">
    <div class="pcoded-inner-navbar main-menu">
        <?php if ($session_srole == 'admin') : ?>
            <div class="pcoded-navigatio-lavel"><?php echo htmlspecialchars($translations['nav']); ?></div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="<?php echo ($page_name == 'dashboard') ? 'active' : ''; ?>">
                    <a href="index.php">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext"><?php echo htmlspecialchars($translations['dashboard']); ?></span>
                    </a>
                </li>
            </ul>
            <div class="pcoded-navigatio-lavel"><?php echo htmlspecialchars($translations['applications']); ?></div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="<?php echo ($page_name == 'membership_type') ? 'active' : ''; ?>">
                    <a href="membership_type.php">
                        <span class="pcoded-micon"><i class="feather icon-user"></i></span>
                        <span class="pcoded-mtext"><?php echo htmlspecialchars($translations['membership_types']); ?></span>
                    </a>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'members' || $page_name == 'new_members' || $page_name == 'manage_members') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                        <span class="pcoded-mtext"><?php echo htmlspecialchars($translations['members']); ?></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo ($page_name == 'new_members') ? 'active' : ''; ?>">
                            <a href="new_member.php">
                                <span class="pcoded-mtext"><?php echo htmlspecialchars($translations['new_member']); ?></span>
                            </a>
                        </li>
                        <li class="<?php echo ($page_name == 'manage_members') ? 'active' : ''; ?>">
                            <a href="manage_members.php">
                                <span class="pcoded-mtext"><?php echo htmlspecialchars($translations['members_list']); ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php echo ($page_name == 'renewal') ? 'active' : ''; ?>">
                    <a href="renewal.php">
                        <span class="pcoded-micon"><i class="feather icon-shuffle"></i></span>
                        <span class="pcoded-mtext"><?php echo htmlspecialchars($translations['renewal']); ?></span>
                    </a>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'report' || $page_name == 'membership_report' || $page_name == 'revenue_report') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-bar-chart"></i></span>
                        <span class="pcoded-mtext"><?php echo htmlspecialchars($translations['reports']); ?></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo ($page_name == 'membership_report') ? 'active' : ''; ?>">
                            <a href="membership_report.php">
                                <span class="pcoded-mtext"><?php echo htmlspecialchars($translations['membership_report']); ?></span>
                            </a>
                        </li>
                         <li class="<?php echo ($page_name == 'revenue_report') ? 'active' : ''; ?>">
                            <a href="revenue_report.php">
                                <span class="pcoded-mtext"><?php echo htmlspecialchars($translations['revenue_report']); ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="pcoded-item pcoded-left-item">
                <li class="<?php echo ($page_name == 'settings') ? 'active' : ''; ?>">
                    <a href="settings.php">
                        <span class="pcoded-micon"><i class="feather icon-settings"></i></span>
                        <span class="pcoded-mtext"><?php echo htmlspecialchars($translations['settings']); ?></span>
                    </a>
                </li>
            </ul>
        <?php endif; ?>    
        <div class="pcoded-navigatio-lavel"><?php echo htmlspecialchars($translations['support']); ?></div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="">
                <a href="https://kpro.netlify.app/" target="_blank">
                    <span class="pcoded-micon"><i class="feather icon-monitor"></i></span>
                    <span class="pcoded-mtext"><?php echo htmlspecialchars($translations['portfolio']); ?></span>
                </a>
            </li>
            <li class="">
                <a href="https://www.youtube.com/@codelytical" target="_blank">
                    <span class="pcoded-micon"><i class="feather icon-monitor"></i></span>
                    <span class="pcoded-mtext">CodeLytical</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
