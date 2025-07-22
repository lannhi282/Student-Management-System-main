<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" style="color:#fd7e14; font-weight:bold; font-size:18px;">Teacher Panel</a>
        <a class="navbar-brand brand-logo-mini" style="color:#fd7e14; font-weight:bold; font-size:18px;">TP</a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">

            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <div class="nav-profile-img">
                        <img src="images/faces/face8.jpg" alt="image">
                        <span class="availability-status online"></span>
                    </div>
                    <div class="nav-profile-text">
                        <p class="mb-1 text-black"><?php echo $_SESSION['teachername']; ?></p>
                    </div>
                </a>
                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="profile.php">
                        <i class="icon-user mr-2 text-success"></i> Profile </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="change-password.php">
                        <i class="icon-key mr-2 text-success"></i> Change Password </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">
                        <i class="icon-power mr-2 text-success"></i> Logout </a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>