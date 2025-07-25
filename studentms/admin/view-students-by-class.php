<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {

    // Get class ID from URL
    $classid = intval($_GET['classid']);

    // Get class information
    $sql = "SELECT ClassName, Section FROM tblclass WHERE ID=:classid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':classid', $classid, PDO::PARAM_STR);
    $query->execute();
    $classinfo = $query->fetch(PDO::FETCH_OBJ);

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <title>Student Management System|||Students in Class</title>
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
                            <h3 class="page-title">Students in Class: <?php echo htmlentities($classinfo->ClassName); ?> <?php echo htmlentities($classinfo->Section); ?></h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="manage-class.php">Manage Class</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Students in Class</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <h4 class="card-title mb-sm-0">Students in <?php echo htmlentities($classinfo->ClassName); ?> <?php echo htmlentities($classinfo->Section); ?></h4>
                                            <a href="manage-class.php" class="text-dark ml-auto mb-3 mb-sm-0">← Back to Classes</a>
                                        </div>
                                        <div class="table-responsive border rounded p-1">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="font-weight-bold">S.No</th>
                                                        <th class="font-weight-bold">Student ID</th>
                                                        <th class="font-weight-bold">Student Name</th>
                                                        <th class="font-weight-bold">Student Email</th>
                                                        <th class="font-weight-bold">Admission Date</th>
                                                        <th class="font-weight-bold">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (isset($_GET['pageno'])) {
                                                        $pageno = $_GET['pageno'];
                                                    } else {
                                                        $pageno = 1;
                                                    }
                                                    // Formula for pagination
                                                    $no_of_records_per_page = 10;
                                                    $offset = ($pageno - 1) * $no_of_records_per_page;
                                                    $ret = "SELECT ID FROM tblstudent WHERE StudentClass=:classid";
                                                    $query1 = $dbh->prepare($ret);
                                                    $query1->bindParam(':classid', $classid, PDO::PARAM_STR);
                                                    $query1->execute();
                                                    $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                                                    $total_rows = $query1->rowCount();
                                                    $total_pages = ceil($total_rows / $no_of_records_per_page);

                                                    $sql = "SELECT tblstudent.StuID,tblstudent.ID as sid,tblstudent.StudentName,tblstudent.StudentEmail,tblstudent.DateofAdmission,tblclass.ClassName,tblclass.Section from tblstudent join tblclass on tblclass.ID=tblstudent.StudentClass WHERE tblstudent.StudentClass=:classid LIMIT $offset, $no_of_records_per_page";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':classid', $classid, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {               ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($row->StuID); ?></td>
                                                                <td><?php echo htmlentities($row->StudentName); ?></td>
                                                                <td><?php echo htmlentities($row->StudentEmail); ?></td>
                                                                <td><?php echo htmlentities($row->DateofAdmission); ?></td>
                                                                <td>
                                                                    <div><a href="edit-student-detail.php?editid=<?php echo htmlentities($row->sid); ?>" class="btn btn-info btn-xs">Edit</a></div>
                                                                </td>
                                                            </tr><?php $cnt = $cnt + 1;
                                                                }
                                                            } else { ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center">No students found in this class</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php if ($total_rows > 0) { ?>
                                            <div align="left" class="mt-4">
                                                <ul class="pagination">
                                                    <li><a href="?classid=<?php echo $classid; ?>&pageno=1"><strong>First></strong></a></li>
                                                    <li class="<?php if ($pageno <= 1) {
                                                                    echo 'disabled';
                                                                } ?>">
                                                        <a href="<?php if ($pageno <= 1) {
                                                                        echo '#';
                                                                    } else {
                                                                        echo "?classid=" . $classid . "&pageno=" . ($pageno - 1);
                                                                    } ?>"><strong style="padding-left: 10px">Prev></strong></a>
                                                    </li>
                                                    <li class="<?php if ($pageno >= $total_pages) {
                                                                    echo 'disabled';
                                                                } ?>">
                                                        <a href="<?php if ($pageno >= $total_pages) {
                                                                        echo '#';
                                                                    } else {
                                                                        echo "?classid=" . $classid . "&pageno=" . ($pageno + 1);
                                                                    } ?>"><strong style="padding-left: 10px">Next></strong></a>
                                                    </li>
                                                    <li><a href="?classid=<?php echo $classid; ?>&pageno=<?php echo $total_pages; ?>"><strong style="padding-left: 10px">Last</strong></a></li>
                                                </ul>
                                            </div>
                                        <?php } ?>
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