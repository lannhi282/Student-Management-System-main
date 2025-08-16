<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['teachermsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $hwtitle = $_POST['homeworkTitle'];
        $classid = $_POST['classid'];
        $hwdescription = $_POST['homeworkdescription'];
        $ldsubmission = $_POST['ldsubmission'];
        $hwid = intval($_GET['hwid']);
        $teacher_id = $_SESSION['teachermsaid'];

        // Verify teacher has access to this homework
        $verify_sql = "SELECT h.id FROM tblhomework h 
                       INNER JOIN tblteacherclass tc ON h.classId = tc.ClassID 
                       WHERE h.id=:hwid AND tc.TeacherID=:teacher_id";
        $verify_query = $dbh->prepare($verify_sql);
        $verify_query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
        $verify_query->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
        $verify_query->execute();

        if ($verify_query->rowCount() > 0) {
            $sql = "update tblhomework set homeworkTitle=:hwtitle,classId=:classid,homeworkDescription=:hwdescription,lastDateofSubmission=:ldsubmission where id=:hwid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':hwtitle', $hwtitle, PDO::PARAM_STR);
            $query->bindParam(':classid', $classid, PDO::PARAM_STR);
            $query->bindParam(':hwdescription', $hwdescription, PDO::PARAM_STR);
            $query->bindParam(':ldsubmission', $ldsubmission, PDO::PARAM_STR);
            $query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
            $query->execute();

            echo '<script>alert("Homework has been updated.")</script>';
            echo "<script>window.location.href ='manage-homework.php'</script>";
        } else {
            echo '<script>alert("You are not authorized to edit this homework.")</script>';
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Teacher Management System|| Edit Homework</title>
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
                            <h3 class="page-title">Edit Homework</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit Homework</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Edit Homework</h4>
                                        <form class="forms-sample" method="post">
                                            <?php
                                            $hwid = intval($_GET['hwid']);
                                            $teacher_id = $_SESSION['teachermsaid'];

                                            // Verify and get homework details
                                            $sql = "SELECT h.homeworkTitle, h.classId, h.homeworkDescription, h.lastDateofSubmission
                                                FROM tblhomework h
                                                INNER JOIN tblteacherclass tc ON h.classId = tc.ClassID
                                                WHERE h.id=:hwid AND tc.TeacherID=:teacher_id";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
                                            $query->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                                            if ($query->rowCount() == 0) {
                                                echo "<script>alert('Access Denied');</script>";
                                                echo "<script>window.location.href = 'manage-homework.php'</script>";
                                                exit;
                                            }

                                            foreach ($results as $row) {
                                            ?>
                                                <div class="form-group">
                                                    <label for="exampleInputName1">Homework Title</label>
                                                    <input type="text" name="homeworkTitle" value="<?php echo htmlentities($row->homeworkTitle); ?>" class="form-control" required='true'>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail3">Class</label>
                                                    <select name="classid" class="form-control" required='true'>
                                                        <option value="<?php echo htmlentities($row->classId); ?>">
                                                            <?php
                                                            // Get current class name
                                                            $sql3 = "SELECT ClassName, Section from tblclass where ID=:classid";
                                                            $query3 = $dbh->prepare($sql3);
                                                            $query3->bindParam(':classid', $row->classId, PDO::PARAM_STR);
                                                            $query3->execute();
                                                            $results3 = $query3->fetchAll(PDO::FETCH_OBJ);
                                                            foreach ($results3 as $row3) {
                                                                echo htmlentities($row3->ClassName) . " " . htmlentities($row3->Section);
                                                            }
                                                            ?>
                                                        </option>
                                                        <?php
                                                        // Get teacher's classes
                                                        $sql2 = "SELECT c.ID, c.ClassName, c.Section 
                                                             FROM tblclass c 
                                                             INNER JOIN tblteacherclass tc ON c.ID = tc.ClassID 
                                                             WHERE tc.TeacherID = :teacher_id";
                                                        $query2 = $dbh->prepare($sql2);
                                                        $query2->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
                                                        $query2->execute();
                                                        $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

                                                        foreach ($result2 as $row1) {
                                                            if ($row1->ID != $row->classId) {
                                                        ?>
                                                                <option value="<?php echo htmlentities($row1->ID); ?>"><?php echo htmlentities($row1->ClassName); ?> <?php echo htmlentities($row1->Section); ?></option>
                                                        <?php
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputName1">Homework Description</label>
                                                    <textarea name="homeworkdescription" class="form-control" required='true' rows="8"><?php echo htmlentities($row->homeworkDescription); ?></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="exampleInputName1">Last Date of Submission</label>
                                                    <input type="date" name="ldsubmission" value="<?php echo htmlentities($row->lastDateofSubmission); ?>" class="form-control" required='true'>
                                                </div>
                                            <?php } ?>

                                            <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                                            <a href="manage-homework.php" class="btn btn-secondary">Cancel</a>
                                        </form>
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