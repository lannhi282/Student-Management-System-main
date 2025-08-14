<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="profile-image">
                    <img class="img-xs rounded-circle" src="images/faces/face8.jpg" alt="profile image">
                    <div class="dot-indicator bg-success"></div>
                </div>
                <div class="text-wrapper">
                    <?php
                    $tid = $_SESSION['teachermsaid'];
                    $sql = "SELECT * from tblteacher where ID=:tid";

                    $query = $dbh->prepare($sql);
                    $query->bindParam(':tid', $tid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results as $row) {               ?>
                            <p class="profile-name"><?php echo htmlentities($row->TeacherName); ?></p>
                            <p class="designation"><?php echo htmlentities($row->TeacherEmail); ?></p><?php $cnt = $cnt + 1;
                                                                                                    }
                                                                                                } ?>
                </div>

            </a>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Dashboard</span>
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
                <i class="icon-layers menu-icon"></i>
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