<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['teachermsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_GET['del'])) {
        $rid = intval($_GET['del']);
        $teacher_id = $_SESSION['teachermsaid'];

        // Verify teacher has access to this homework
        $verify_sql = "SELECT h.id FROM tblhomework h 
                       INNER JOIN tblteacherclass tc ON h.classId = tc.ClassID 
                       WHERE h.id=:rid AND tc.TeacherID=:teacher_id";
        $verify_query = $dbh->prepare($verify_sql);
        $verify_query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $verify_query->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
        $verify_query->execute();

        if ($verify_query->rowCount() > 0) {
            $sql = "DELETE FROM tblhomework WHERE ID=:rid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':rid', $rid, PDO::PARAM_STR);
            $query->execute();
            echo "<script>alert('Data deleted');</script>";
            echo "<script>window.location.href = 'manage-homework.php'</script>";
        } else {
            echo "<script>alert('Access Denied');</script>";
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Teacher Management System|| Manage Homework</title>
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
                            <h3 class="page-title">Manage Homework</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Manage Homework</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <h4 class="card-title mb-sm-0">Manage Homework</h4>
                                            <div class="ml-auto">
                                                <a href="add-homework.php" class="btn btn-primary">Add New Homework</a>
                                            </div>
                                        </div>
                                        <div class="table-responsive border rounded p-1">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="font-weight-bold">S.No</th>
                                                        <th class="font-weight-bold">Homework Title</th>
                                                        <th class="font-weight-bold">Class</th>
                                                        <th class="font-weight-bold">Section</th>
                                                        <th class="font-weight-bold">Last Date of Submission</th>
                                                        <th class="font-weight-bold">Posting Date</th>
                                                        <th class="font-weight-bold">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $teacher_id = $_SESSION['teachermsaid'];
                                                    $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, 
                                                           tblhomework.homeworkTitle, tblhomework.postingDate, 
                                                           tblhomework.lastDateofSubmission, tblhomework.id as hwid 
                                                           FROM tblhomework 
                                                           JOIN tblclass ON tblclass.ID = tblhomework.classId 
                                                           JOIN tblteacherclass ON tblclass.ID = tblteacherclass.ClassID
                                                           WHERE tblteacherclass.TeacherID = :teacher_id";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($row->homeworkTitle); ?></td>
                                                                <td><?php echo htmlentities($row->ClassName); ?></td>
                                                                <td><?php echo htmlentities($row->Section); ?></td>
                                                                <td><?php echo htmlentities($row->lastDateofSubmission); ?></td>
                                                                <td><?php echo htmlentities($row->postingDate); ?></td>
                                                                <td>
                                                                    <div>
                                                                        <a href="edit-homework.php?hwid=<?php echo htmlentities($row->hwid); ?>" class="btn btn-primary btn-sm">Edit</a>
                                                                        <a href="uploaded-hw.php?hwid=<?php echo htmlentities($row->hwid); ?>" class="btn btn-success btn-sm">View Submissions</a>
                                                                        <a href="manage-homework.php?del=<?php echo htmlentities($row->hwid); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-sm">Delete</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php $cnt = $cnt + 1;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="7" style="color:red; text-align:center;">No Homework Found</td>
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

    </html>
<?php } ?>