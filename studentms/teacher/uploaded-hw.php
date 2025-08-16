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

        <title>Teacher Management System|||Uploaded Homework</title>
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
                            <h3 class="page-title"> Uploaded Homework </h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"> Uploaded Homework</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <?php
                                            $hwid = intval($_GET['hwid']);
                                            $teacher_id = $_SESSION['teachermsaid'];

                                            // Verify teacher has access to this homework
                                            $verify_sql = "SELECT h.homeworkTitle, h.postingDate, h.lastDateofSubmission, h.classId 
               FROM tblhomework h 
               INNER JOIN tblteacherclass tc ON h.classId = tc.ClassID 
               WHERE h.id=:hwid AND tc.TeacherID=:teacher_id";
                                            $verify_query = $dbh->prepare($verify_sql);
                                            $verify_query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
                                            $verify_query->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
                                            $verify_query->execute();
                                            $results = $verify_query->fetchAll(PDO::FETCH_OBJ);

                                            if ($verify_query->rowCount() == 0) {
                                                echo "<script>alert('Access Denied');</script>";
                                                echo "<script>window.location.href = 'manage-homework.php'</script>";
                                                exit;
                                            }

                                            foreach ($results as $row) {
                                                $classid = $row->classId;
                                            ?>
                                                <p><strong>Title:</strong> <?php echo htmlentities($row->homeworkTitle) ?><br />
                                                    <strong>Last Date of Submission:</strong> <?php echo htmlentities($row->lastDateofSubmission) ?>
                                                </p>

                                            <?php } ?>

                                        </div>
                                        <div class="table-responsive border rounded p-1">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="font-weight-bold">S.No</th>
                                                        <th class="font-weight-bold">Student ID</th>
                                                        <th class="font-weight-bold">Student Class</th>
                                                        <th class="font-weight-bold">Student Name</th>
                                                        <th class="font-weight-bold">Student Email</th>
                                                        <th class="font-weight-bold">Submission Date</th>
                                                        <th class="font-weight-bold">Status</th>
                                                        <th class="font-weight-bold">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Get students who have submitted this homework
                                                    $sql = "SELECT tblstudent.StuID, tblstudent.ID as sid, tblstudent.StudentName, tblstudent.StudentEmail, 
             tblstudent.DateofAdmission, tblclass.ClassName, tblclass.Section,
             tbluploadedhomeworks.postinDate, tbluploadedhomeworks.adminRemark
      FROM tblstudent 
      JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
      JOIN tbluploadedhomeworks ON tbluploadedhomeworks.studentId = tblstudent.ID
      WHERE tblstudent.StudentClass = '$classid' AND tbluploadedhomeworks.homeworkId = '$hwid'
      ORDER BY tbluploadedhomeworks.postinDate DESC";

                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {               ?>
                                                            <tr>

                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($row->StuID); ?></td>
                                                                <td><?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?></td>
                                                                <td><?php echo htmlentities($row->StudentName); ?></td>
                                                                <td><?php echo htmlentities($row->StudentEmail); ?></td>
                                                                <td><?php echo htmlentities($row->postinDate); ?></td>
                                                                <td>
                                                                    <?php if ($row->adminRemark != '') { ?>
                                                                        <span class="badge badge-success">Graded</span>
                                                                    <?php } else { ?>
                                                                        <span class="badge badge-warning">Pending</span>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <div><a href="view-hw.php?stid=<?php echo htmlentities($row->sid); ?>&&hwid=<?php echo htmlentities($hwid); ?>" class="btn btn-info btn-xs" target="blank">View</a>
                                                                    </div>
                                                                </td>
                                                            </tr><?php $cnt = $cnt + 1;
                                                                }
                                                            } else { ?>
                                                        <tr>
                                                            <td colspan="8" style="color:red; text-align:center;">No submissions found for this homework</td>
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
        <script src="./vendors/chart.js/Chart.min.js"></script>
        <script src="./vendors/moment/moment.min.js"></script>
        <script src="./vendors/daterangepicker/daterangepicker.js"></script>
        <script src="./vendors/chartist/chartist.min.js"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page -->
        <script src="./js/dashboard.js"></script>
        <!-- End custom js for this page -->
    </body>

    </html><?php }  ?>