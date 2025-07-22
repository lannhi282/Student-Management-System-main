<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile border-bottom">
            <a href="#" class="nav-link flex-column">
                <div class="nav-profile-image">
                    <img src="images/faces/face8.jpg" alt="profile">
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex ml-0 mb-3 flex-column">
                    <span class="font-weight-semibold mb-1 mt-2 text-center"><?php echo $_SESSION['teachername']; ?></span>
                </div>
            </a>
        </li>
        <li class="nav-item pt-3">
            <a class="nav-link d-block" href="dashboard.php">
                <img class="sidebar-brand-logo" src="" alt="" />
                <img class="sidebar-brand-logomini" src="" alt="" />
                <div class="small font-weight-light pt-1">Teacher Panel</div>
            </a>

        </li>
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <span class="menu-title">Dashboard</span>
                <i class="icon-screen-desktop menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="my-classes.php">
                <span class="menu-title">My Classes</span>
                <i class="icon-book-open menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#homework" aria-expanded="false" aria-controls="homework">
                <span class="menu-title">Homework</span>
                <i class="icon-notebook menu-icon"></i>
            </a>
            <div class="collapse" id="homework">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="add-homework.php">Add Homework</a></li>
                    <li class="nav-item"> <a class="nav-link" href="manage-homework.php">Manage Homework</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#notices" aria-expanded="false" aria-controls="notices">
                <span class="menu-title">Notices</span>
                <i class="icon-bell menu-icon"></i>
            </a>
            <div class="collapse" id="notices">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="add-notice.php">Add Notice</a></li>
                    <li class="nav-item"> <a class="nav-link" href="manage-notice.php">Manage Notice</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="profile.php">
                <span class="menu-title">Profile</span>
                <i class="icon-user menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>