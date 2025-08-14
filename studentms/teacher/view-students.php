<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['teachermsaid']) == 0) {
    header('location:logout.php');
} else {
    $classid = intval($_GET['classid']);
    $teacherid = $_SESSION['teachermsaid'];

    // Verify teacher has access to this class
    $verify_sql = "SELECT c.ClassName, c.Section FROM tblclass c 
                 JOIN tblteacherclass tc ON c.ID = tc.ClassID 
                 WHERE c.ID = :classid AND tc.TeacherID = :teacherid";
    $verify_query = $dbh->prepare($verify_sql);
    $verify_query->bindParam(':classid', $classid, PDO::PARAM_STR);
    $verify_query->bindParam(':teacherid', $teacherid, PDO::PARAM_STR);
    $verify_query->execute();

    if ($verify_query->rowCount() == 0) {
        echo "<script>alert('Access Denied!');</script>";
        echo "<script>window.location.href='my-classes.php'</script>";
        exit;
    }
    $class_info = $verify_query->fetch(PDO::FETCH_OBJ);
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Teacher Management System|| View Students</title>
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
                        <div class="page-header">
                            <h3 class="page-title">Students in Class: <?php echo htmlentities($class_info->ClassName . ' - ' . $class_info->Section); ?></h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="my-classes.php">My Classes</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">View Students</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <h4 class="card-title mb-sm-0">Students List</h4>
                                            <a href="my-classes.php" class="text-dark ml-auto mb-3 mb-sm-0"> View All Classes</a>
                                        </div>
                                        <div class="table-responsive border rounded p-1">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="font-weight-bold">S.No</th>
                                                        <th class="font-weight-bold">Student Name</th>
                                                        <th class="font-weight-bold">Student Email</th>
                                                        <th class="font-weight-bold">Contact Number</th>
                                                        <th class="font-weight-bold">Registration Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT StudentName, StudentEmail, MobileNumber, DateofAdmission 
                                FROM tblstudent 
                                WHERE StudentClass = :classid 
                                ORDER BY StudentName";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':classid', $classid, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($row->StudentName); ?></td>
                                                                <td><?php echo htmlentities($row->StudentEmail); ?></td>
                                                                <td><?php echo htmlentities($row->MobileNumber); ?></td>
                                                                <td><?php echo htmlentities($row->DateofAdmission); ?></td>
                                                            </tr>
                                                        <?php $cnt = $cnt + 1;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="5" style="color:red; text-align:center;">No Students Found</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
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
        <script src="vendors/chart.js/Chart.min.js"></script>
        <script src="vendors/moment/moment.min.js"></script>
        <script src="vendors/daterangepicker/daterangepicker.js"></script>
        <script src="vendors/chartist/chartist.min.js"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page -->
        <script src="js/dashboard.js"></script>
        <!-- End custom js for this page -->
    </body>

    </html><?php } ?>