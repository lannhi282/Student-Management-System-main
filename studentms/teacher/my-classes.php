<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['teachermsaid']) == 0) {
    header('location:logout.php');
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Teacher Management System|| My Classes</title>
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <div class="container-scroller">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid page-body-wrapper">
                <?php include_once('includes/sidebar.php'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="page-header">
                            <h3 class="page-title">My Classes</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">My Classes</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <h4 class="card-title mb-sm-0">My Classes</h4>
                                        </div>
                                        <div class="table-responsive border rounded p-1">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="font-weight-bold">S.No</th>
                                                        <th class="font-weight-bold">Class Name</th>
                                                        <th class="font-weight-bold">Section</th>
                                                        <th class="font-weight-bold">Total Students</th>
                                                        <th class="font-weight-bold">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $teacherid = $_SESSION['teachermsaid'];
                                                    $sql = "SELECT c.ID, c.ClassName, c.Section, 
                                COUNT(s.ID) as total_students
                                FROM tblclass c 
                                JOIN tblteacherclass tc ON c.ID = tc.ClassId 
                                LEFT JOIN tblstudent s ON c.ID = s.StudentClass 
                                WHERE tc.TeacherId = :teacherid 
                                GROUP BY c.ID, c.ClassName, c.Section";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':teacherid', $teacherid, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($row->ClassName); ?></td>
                                                                <td><?php echo htmlentities($row->Section); ?></td>
                                                                <td><?php echo htmlentities($row->total_students); ?></td>
                                                                <td>
                                                                    <a href="view-students.php?classid=<?php echo htmlentities($row->ID); ?>" class="btn btn-info btn-xs">View Students</a>
                                                                </td>
                                                            </tr>
                                                        <?php $cnt = $cnt + 1;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="5" style="color:red; text-align:center;">No Classes Assigned</td>
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
                    <?php include_once('includes/footer.php'); ?>
                </div>
            </div>
        </div>
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>
    </body>

    </html><?php } ?>