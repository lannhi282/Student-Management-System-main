<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Code for deletion
if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "DELETE FROM tblteacherclass WHERE ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Assignment removed successfully');</script>";
    echo "<script>window.location.href = 'assign-teacher-class.php'</script>";
}

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $teacherid = $_POST['teacherid'];
        $classid = $_POST['classid'];

        // Check if assignment already exists
        $checksql = "SELECT ID FROM tblteacherclass WHERE TeacherID=:teacherid AND ClassID=:classid";
        $checkquery = $dbh->prepare($checksql);
        $checkquery->bindParam(':teacherid', $teacherid, PDO::PARAM_STR);
        $checkquery->bindParam(':classid', $classid, PDO::PARAM_STR);
        $checkquery->execute();

        if ($checkquery->rowCount() > 0) {
            echo '<script>alert("This teacher is already assigned to this class.")</script>';
        } else {
            $sql = "INSERT INTO tblteacherclass(TeacherID,ClassID) VALUES(:teacherid,:classid)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':teacherid', $teacherid, PDO::PARAM_STR);
            $query->bindParam(':classid', $classid, PDO::PARAM_STR);
            $query->execute();
            $LastInsertId = $dbh->lastInsertId();
            if ($LastInsertId > 0) {
                echo '<script>alert("Teacher has been assigned to class successfully.")</script>';
                echo "<script>window.location.href ='assign-teacher-class.php'</script>";
            } else {
                echo '<script>alert("Something went wrong. Please try again.")</script>';
            }
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Student Management System|| Assign Teacher to Class</title>
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
                            <h3 class="page-title">Assign Teacher to Class</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Assign Teacher to Class</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-6 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title" style="text-align: center;">Assign Teacher</h4>
                                        <form class="forms-sample" method="post">
                                            <div class="form-group">
                                                <label for="teacherid">Select Teacher</label>
                                                <select name="teacherid" class="form-control" required='true'>
                                                    <option value="">Select Teacher</option>
                                                    <?php
                                                    $sql2 = "SELECT * FROM tblteacher";
                                                    $query2 = $dbh->prepare($sql2);
                                                    $query2->execute();
                                                    $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($result2 as $row2) {
                                                    ?>
                                                        <option value="<?php echo htmlentities($row2->ID); ?>"><?php echo htmlentities($row2->TeacherName); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="classid">Select Class</label>
                                                <select name="classid" class="form-control" required='true'>
                                                    <option value="">Select Class</option>
                                                    <?php
                                                    $sql = "SELECT * FROM tblclass";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                    ?>
                                                            <option value="<?php echo htmlentities($row->ID); ?>"><?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary mr-2" name="submit">Assign</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <h4 class="card-title mb-sm-0">Current Assignments</h4>
                                        </div>
                                        <div class="table-responsive border rounded p-1">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="font-weight-bold">S.No</th>
                                                        <th class="font-weight-bold">Teacher</th>
                                                        <th class="font-weight-bold">Class</th>
                                                        <th class="font-weight-bold">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT tc.ID, t.TeacherName, c.ClassName, c.Section 
                                                       FROM tblteacherclass tc
                                                       JOIN tblteacher t ON tc.TeacherID = t.ID
                                                       JOIN tblclass c ON tc.ClassID = c.ID";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($row->TeacherName); ?></td>
                                                                <td><?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?></td>
                                                                <td>
                                                                    <a href="assign-teacher-class.php?delid=<?php echo ($row->ID); ?>" onclick="return confirm('Do you really want to remove this assignment?');" class="btn btn-danger btn-xs">Remove</a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                            $cnt = $cnt + 1;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="4" style="text-align:center">No assignments found</td>
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
        <script src="vendors/select2/select2.min.js"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page -->
        <script src="vendors/select2/select2.min.js"></script>
        <!-- End custom js for this page -->
    </body>

    </html><?php } ?>