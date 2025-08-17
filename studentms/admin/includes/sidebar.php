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
          $aid = $_SESSION['sturecmsaid'];
          $sql = "SELECT * from tbladmin where ID=:aid";

          $query = $dbh->prepare($sql);
          $query->bindParam(':aid', $aid, PDO::PARAM_STR);
          $query->execute();
          $results = $query->fetchAll(PDO::FETCH_OBJ);

          $cnt = 1;
          if ($query->rowCount() > 0) {
            foreach ($results as $row) {               ?>
              <p class="profile-name"><?php echo htmlentities($row->AdminName); ?></p>
              <p class="designation"><?php echo htmlentities($row->Email); ?></p><?php $cnt = $cnt + 1;
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
      <a class="nav-link" data-toggle="collapse" href="#class" aria-expanded="false" aria-controls="class">
        <span class="menu-title">Class</span>
        <i class="icon-layers menu-icon"></i>
      </a>
      <div class="collapse" id="class">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-class.php">Add Class</a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-class.php">Manage Class</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#teachers" aria-expanded="false" aria-controls="teachers">
        <span class="menu-title">Teachers</span>
        <i class="icon-graduation menu-icon"></i>
      </a>
      <div class="collapse" id="teachers">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-teacher.php">Add Teacher</a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-teachers.php">Manage Teachers</a></li>
          <li class="nav-item"> <a class="nav-link" href="assign-teacher-class.php">Assign Classes</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic1">
        <span class="menu-title">Students</span>
        <i class="icon-people menu-icon"></i>
      </a>
      <div class="collapse" id="ui-basic1">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-students.php">Add Students</a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-students.php">Manage Students</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#hw" aria-expanded="false" aria-controls="hw">
        <span class="menu-title">Homework</span>
        <i class="icon-doc menu-icon"></i>
      </a>
      <div class="collapse" id="hw">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-homework.php">Add </a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-homeworks.php">Manage</a></li>
        </ul>
      </div>
    </li>


    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <span class="menu-title">Notice</span>
        <i class="icon-doc menu-icon"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="add-notice.php"> Add Notice </a></li>
          <li class="nav-item"> <a class="nav-link" href="manage-notice.php"> Manage Notice </a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="manage-grades.php">
        <span class="menu-title">Manage All Grades</span>
        <i class="icon-graduation menu-icon"></i>
      </a>
    </li>
    </li>
  </ul>
</nav>