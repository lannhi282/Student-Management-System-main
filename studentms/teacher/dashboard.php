<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['teachermsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Teacher Management System|||Dashboard</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <!-- endinject -->
        <!-- Plugin css for this page -->
        <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
        <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
        <!-- End plugin css for this page -->
        <!-- inject:css -->
        <!-- endinject -->
        <!-- Layout styles -->
        <link rel="stylesheet" href="./css/style.css">
        <!-- End layout styles -->
    </head>

    <body>
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <?php include_once('includes/header.php'); ?>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <!-- partial:partials/_sidebar.html -->
                <?php include_once('includes/sidebar.php'); ?>
                <!-- partial -->
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="row purchace-popup">
                            <div class="col-12 stretch-card grid-margin">
                                <div class="card card-secondary">
                                    <span class="card-body d-lg-flex align-items-center">
                                        <p class="mb-lg-0">Welcome to Teacher Dashboard</p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                                <div class="card card-statistics">
                                    <div class="card-body">
                                        <div class="clearfix">
                                            <div class="float-left">
                                                <i class="icon-book-open text-warning icon-lg"></i>
                                            </div>
                                            <div class="float-right">
                                                <div class="fluid-container">
                                                    <h3 class="card-title text-right">
                                                        <?php
                                                        $teacher_id = $_SESSION['teachermsaid'];
                                                        $sql1 = "SELECT COUNT(*) as total_classes FROM tblteacherclass WHERE TeacherID=:teacher_id";
                                                        $query1 = $dbh->prepare($sql1);
                                                        $query1->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
                                                        $query1->execute();
                                                        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                                                        foreach ($results1 as $row1) {
                                                            echo $row1->total_classes;
                                                        }
                                                        ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-0 text-right">Total Classes</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                                <div class="card card-statistics">
                                    <div class="card-body">
                                        <div class="clearfix">
                                            <div class="float-left">
                                                <i class="icon-people text-success icon-lg"></i>
                                            </div>
                                            <div class="float-right">
                                                <div class="fluid-container">
                                                    <h3 class="card-title text-right">
                                                        <?php
                                                        $sql2 = "SELECT COUNT(*) as total_students FROM tblstudent s 
                                  INNER JOIN tblteacherclass tc ON s.StudentClass = tc.ClassID 
                                  WHERE tc.TeacherID=:teacher_id";
                                                        $query2 = $dbh->prepare($sql2);
                                                        $query2->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
                                                        $query2->execute();
                                                        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                        foreach ($results2 as $row2) {
                                                            echo $row2->total_students;
                                                        }
                                                        ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-0 text-right">Total Students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                                <div class="card card-statistics">
                                    <div class="card-body">
                                        <div class="clearfix">
                                            <div class="float-left">
                                                <i class="icon-notebook text-info icon-lg"></i>
                                            </div>
                                            <div class="float-right">
                                                <div class="fluid-container">
                                                    <h3 class="card-title text-right">
                                                        <?php
                                                        $sql3 = "SELECT COUNT(*) as total_homework FROM tblhomework h 
                                  INNER JOIN tblteacherclass tc ON h.classId = tc.ClassID 
                                  WHERE tc.TeacherID=:teacher_id";
                                                        $query3 = $dbh->prepare($sql3);
                                                        $query3->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
                                                        $query3->execute();
                                                        $results3 = $query3->fetchAll(PDO::FETCH_OBJ);
                                                        foreach ($results3 as $row3) {
                                                            echo $row3->total_homework;
                                                        }
                                                        ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-0 text-right">Total Homework</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
                                <div class="card card-statistics">
                                    <div class="card-body">
                                        <div class="clearfix">
                                            <div class="float-left">
                                                <i class="icon-bell text-danger icon-lg"></i>
                                            </div>
                                            <div class="float-right">
                                                <div class="fluid-container">
                                                    <h3 class="card-title text-right">
                                                        <?php
                                                        $sql4 = "SELECT COUNT(*) as total_notices FROM tblnotice n 
                                  INNER JOIN tblteacherclass tc ON n.ClassId = tc.ClassID 
                                  WHERE tc.TeacherID=:teacher_id";
                                                        $query4 = $dbh->prepare($sql4);
                                                        $query4->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
                                                        $query4->execute();
                                                        $results4 = $query4->fetchAll(PDO::FETCH_OBJ);
                                                        foreach ($results4 as $row4) {
                                                            echo $row4->total_notices;
                                                        }
                                                        ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-0 text-right">Total Notices</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <?php include_once('includes/footer.php'); ?>
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <!-- plugins:js -->
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="vendors/daterangepicker/daterangepicker.js"></script>
        <script src="vendors/chartist/chartist.min.js"></script>
        <script src="vendors/progressbar.js/progressbar.min.js"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page -->
        <script src="js/dashboard.js"></script>
        <!-- End custom js for this page -->
    </body>

    </html>
<?php } ?>