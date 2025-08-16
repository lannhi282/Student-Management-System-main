<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['teachermsaid'] == 0)) {
    header('location:logout.php');
} else {

    if (isset($_POST['submit'])) {
        $fdate = $_POST['fromdate'];
        $tdate = $_POST['todate'];
        $teacher_id = $_SESSION['teachermsaid'];
    }

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <title>Teacher Management System|||Between Dates Reports</title>
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
                            <h3 class="page-title"> Homework Reports </h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"> Homework Reports</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <h4 class="card-title mb-sm-0">Homework Reports</h4>
                                        </div>
                                        <form method="post">
                                            <div class="form-group">
                                                <label>From Date:</label>
                                                <input type="date" class="form-control" name="fromdate" required="true">
                                            </div>
                                            <div class="form-group">
                                                <label>To Date:</label>
                                                <input type="date" class="form-control" name="todate" required="true">
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                        </form>

                                        <?php if (isset($_POST['submit'])) { ?>
                                            <h5 align="center" style="color:blue">Homework Report from <?php echo $fdate ?> to <?php echo $tdate ?></h5>
                                            <div class="table-responsive border rounded p-1">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="font-weight-bold">S.No</th>
                                                            <th class="font-weight-bold">Homework Title</th>
                                                            <th class="font-weight-bold">Class</th>
                                                            <th class="font-weight-bold">Section</th>
                                                            <th class="font-weight-bold">Last Submission Date</th>
                                                            <th class="font-weight-bold">Posting Date</th>
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
                                                        $no_of_records_per_page = 15;
                                                        $offset = ($pageno - 1) * $no_of_records_per_page;

                                                        $teacher_id = $_SESSION['teachermsaid'];
                                                        $ret = "SELECT h.ID FROM tblhomework h 
        INNER JOIN tblteacherclass tc ON h.classId = tc.ClassID 
        WHERE tc.TeacherID = '$teacher_id'";
                                                        $query1 = $dbh->prepare($ret);
                                                        $query1->execute();
                                                        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                                                        $total_rows = $query1->rowCount();

                                                        $total_pages = ceil($total_rows / $no_of_records_per_page);
                                                        $sql = "SELECT tblclass.ID,tblclass.ClassName,tblclass.Section,tblhomework.homeworkTitle,tblhomework.postingDate,tblhomework.lastDateofSubmission,tblhomework.id as hwid 
      from tblhomework 
      join tblclass on tblclass.ID=tblhomework.classId  
      join tblteacherclass on tblclass.ID=tblteacherclass.ClassID
      where date(tblhomework.postingDate) between '$fdate' and '$tdate' 
      and tblteacherclass.TeacherID='$teacher_id'
      LIMIT $offset, $no_of_records_per_page";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                        $cnt = 1;
                                                        if ($query->rowCount() > 0) {
                                                            foreach ($results as $row) {               ?>
                                                                <tr>

                                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                                    <td><?php echo htmlentities($row->homeworkTitle); ?></td>
                                                                    <td><?php echo htmlentities($row->ClassName); ?></td>
                                                                    <td><?php echo htmlentities($row->Section); ?></td>
                                                                    <td><?php echo htmlentities($row->lastDateofSubmission); ?></td>
                                                                    <td><?php echo htmlentities($row->postingDate); ?></td>
                                                                    <td>
                                                                        <div><a href="edit-homework.php?hwid=<?php echo htmlentities($row->hwid); ?>" class="btn btn-info btn-xs" target="blank">Edit</a>
                                                                            <a href="manage-homework.php?del=<?php echo ($row->hwid); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-xs"> Delete</a>

                                                                            <a href="uploaded-hw.php?hwid=<?php echo htmlentities($row->hwid); ?>" class="btn btn-info btn-xs" target="blank">Uploaded HW</a>
                                                                        </div>
                                                                    </td>
                                                                </tr><?php $cnt = $cnt + 1;
                                                                    }
                                                                } else { ?>
                                                            <tr>
                                                                <th colspan="4" style="color:red;">No Record Found</th>
                                                            </tr>

                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div align="left" class="mt-4">
                                                <ul class="pagination">
                                                    <li><a href="?pageno=1"><strong>First ></strong></a></li>
                                                    <li class="<?php if ($pageno <= 1) {
                                                                    echo 'disabled';
                                                                } ?>">
                                                        <a href="<?php if ($pageno <= 1) {
                                                                        echo '#';
                                                                    } else {
                                                                        echo "?pageno=" . ($pageno - 1);
                                                                    } ?>"><strong style="padding-left: 10px">Prev ></strong></a>
                                                    </li>
                                                    <li class="<?php if ($pageno >= $total_pages) {
                                                                    echo 'disabled';
                                                                } ?>">
                                                        <a href="<?php if ($pageno >= $total_pages) {
                                                                        echo '#';
                                                                    } else {
                                                                        echo "?pageno=" . ($pageno + 1);
                                                                    } ?>"><strong style="padding-left: 10px">Next ></strong></a>
                                                    </li>
                                                    <li><a href="?pageno=<?php echo $total_pages; ?>"><strong style="padding-left: 10px">Last</strong></a></li>
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

<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['teachermsaid'] == 0)) {
    header('location:logout.php');
} else {

    if (isset($_POST['submit'])) {
        $fdate = $_POST['fromdate'];
        $tdate = $_POST['todate'];
        $teacher_id = $_SESSION['teachermsaid'];
    }

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <title>Teacher Management System|||Between Dates Reports</title>
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
                            <h3 class="page-title"> Homework Reports </h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"> Homework Reports</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <h4 class="card-title mb-sm-0">Homework Reports</h4>
                                        </div>
                                        <form method="post">
                                            <div class="form-group">
                                                <label>From Date:</label>
                                                <input type="date" class="form-control" name="fromdate" required="true">
                                            </div>
                                            <div class="form-group">
                                                <label>To Date:</label>
                                                <input type="date" class="form-control" name="todate" required="true">
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                        </form>

                                        <?php if (isset($_POST['submit'])) { ?>
                                            <h5 align="center" style="color:blue">Homework Report from <?php echo $fdate ?> to <?php echo $tdate ?></h5>
                                            <div class="table-responsive border rounded p-1">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="font-weight-bold">S.No</th>
                                                            <th class="font-weight-bold">Homework Title</th>
                                                            <th class="font-weight-bold">Class</th>
                                                            <th class="font-weight-bold">Section</th>
                                                            <th class="font-weight-bold">Last Submission Date</th>
                                                            <th class="font-weight-bold">Posting Date</th>
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
                                                        $no_of_records_per_page = 15;
                                                        $offset = ($pageno - 1) * $no_of_records_per_page;

                                                        $teacher_id = $_SESSION['teachermsaid'];
                                                        $ret = "SELECT h.ID FROM tblhomework h 
        INNER JOIN tblteacherclass tc ON h.classId = tc.ClassID 
        WHERE tc.TeacherID = '$teacher_id'";
                                                        $query1 = $dbh->prepare($ret);
                                                        $query1->execute();
                                                        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                                                        $total_rows = $query1->rowCount();

                                                        $total_pages = ceil($total_rows / $no_of_records_per_page);
                                                        $sql = "SELECT tblclass.ID,tblclass.ClassName,tblclass.Section,tblhomework.homeworkTitle,tblhomework.postingDate,tblhomework.lastDateofSubmission,tblhomework.id as hwid 
      from tblhomework 
      join tblclass on tblclass.ID=tblhomework.classId  
      join tblteacherclass on tblclass.ID=tblteacherclass.ClassID
      where date(tblhomework.postingDate) between '$fdate' and '$tdate' 
      and tblteacherclass.TeacherID='$teacher_id'
      LIMIT $offset, $no_of_records_per_page";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                        $cnt = 1;
                                                        if ($query->rowCount() > 0) {
                                                            foreach ($results as $row) {               ?>
                                                                <tr>

                                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                                    <td><?php echo htmlentities($row->homeworkTitle); ?></td>
                                                                    <td><?php echo htmlentities($row->ClassName); ?></td>
                                                                    <td><?php echo htmlentities($row->Section); ?></td>
                                                                    <td><?php echo htmlentities($row->lastDateofSubmission); ?></td>
                                                                    <td><?php echo htmlentities($row->postingDate); ?></td>
                                                                    <td>
                                                                        <div><a href="edit-homework.php?hwid=<?php echo htmlentities($row->hwid); ?>" class="btn btn-info btn-xs" target="blank">Edit</a>
                                                                            <a href="manage-homework.php?del=<?php echo ($row->hwid); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-xs"> Delete</a>

                                                                            <a href="uploaded-hw.php?hwid=<?php echo htmlentities($row->hwid); ?>" class="btn btn-info btn-xs" target="blank">Uploaded HW</a>
                                                                        </div>
                                                                    </td>
                                                                </tr><?php $cnt = $cnt + 1;
                                                                    }
                                                                } else { ?>
                                                            <tr>
                                                                <th colspan="4" style="color:red;">No Record Found</th>
                                                            </tr>

                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div align="left" class="mt-4">
                                                <ul class="pagination">
                                                    <li><a href="?pageno=1"><strong>First ></strong></a></li>
                                                    <li class="<?php if ($pageno <= 1) {
                                                                    echo 'disabled';
                                                                } ?>">
                                                        <a href="<?php if ($pageno <= 1) {
                                                                        echo '#';
                                                                    } else {
                                                                        echo "?pageno=" . ($pageno - 1);
                                                                    } ?>"><strong style="padding-left: 10px">Prev ></strong></a>
                                                    </li>
                                                    <li class="<?php if ($pageno >= $total_pages) {
                                                                    echo 'disabled';
                                                                } ?>">
                                                        <a href="<?php if ($pageno >= $total_pages) {
                                                                        echo '#';
                                                                    } else {
                                                                        echo "?pageno=" . ($pageno + 1);
                                                                    } ?>"><strong style="padding-left: 10px">Next ></strong></a>
                                                    </li>
                                                    <li><a href="?pageno=<?php echo $total_pages; ?>"><strong style="padding-left: 10px">Last</strong></a></li>
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