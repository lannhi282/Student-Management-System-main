<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsstuid'] == 0)) {
    header('location:logout.php');
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Student Management System|| View Classmates</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <!-- endinject -->
        <!-- Plugin css for this page -->
        <link rel="stylesheet" href="vendors/select2/select2.min.css">
        <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
        <!-- End plugin css for this page -->
        <!-- inject:css -->
        <!-- endinject -->
        <!-- Layout styles -->
        <link rel="stylesheet" href="css/style.css" />
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
                            <h3 class="page-title"> View Classmates </h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"> View Classmates</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">My Classmates</h4>

                                        <?php
                                        $stuclass = $_SESSION['stuclass'];
                                        $current_student_id = $_SESSION['sturecmsuid'];

                                        $sql = "SELECT tblstudent.StuID, tblstudent.StudentName, tblstudent.StudentEmail, 
                           tblstudent.DateofAdmission, tblclass.ClassName, tblclass.Section 
                           FROM tblstudent 
                           JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                           WHERE tblstudent.StudentClass = :stuclass 
                           AND tblstudent.ID != :current_student_id
                           ORDER BY tblstudent.StudentName";

                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
                                        $query->bindParam(':current_student_id', $current_student_id, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        if ($query->rowCount() > 0) {
                                        ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="font-weight-bold">S.No</th>
                                                            <th class="font-weight-bold">Student ID</th>
                                                            <th class="font-weight-bold">Student Name</th>
                                                            <th class="font-weight-bold">Email</th>
                                                            <th class="font-weight-bold">Class</th>
                                                            <th class="font-weight-bold">Section</th>
                                                            <th class="font-weight-bold">Admission Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $cnt = 1;
                                                        foreach ($results as $row) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($row->StuID); ?></td>
                                                                <td><?php echo htmlentities($row->StudentName); ?></td>
                                                                <td><?php echo htmlentities($row->StudentEmail); ?></td>
                                                                <td><?php echo htmlentities($row->ClassName); ?></td>
                                                                <td><?php echo htmlentities($row->Section); ?></td>
                                                                <td><?php echo htmlentities($row->DateofAdmission); ?></td>
                                                            </tr>
                                                        <?php
                                                            $cnt++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } else { ?>
                                            <div class="alert alert-info">
                                                <strong>No classmates found!</strong> You are the only student in this class.
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
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>
        <!-- endinject -->
    </body>

    </html>
<?php } ?>