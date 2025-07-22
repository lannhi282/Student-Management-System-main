<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['teachermsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $nticetitle = $_POST['noticeTitle'];
        $classid = $_POST['classid'];
        $nticemsg = $_POST['noticemsg'];
        $teacher_id = $_SESSION['teachermsaid'];

        // Verify teacher has access to this class
        $verify_sql = "SELECT ClassID FROM tblteacherclass WHERE ClassID=:classid AND TeacherID=:teacher_id";
        $verify_query = $dbh->prepare($verify_sql);
        $verify_query->bindParam(':classid', $classid, PDO::PARAM_STR);
        $verify_query->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
        $verify_query->execute();

        if ($verify_query->rowCount() > 0) {
            $sql = "INSERT INTO tblnotice(NoticeTitle,ClassId,NoticeMsg) VALUES(:nticetitle,:classid,:nticemsg)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':nticetitle', $nticetitle, PDO::PARAM_STR);
            $query->bindParam(':classid', $classid, PDO::PARAM_STR);
            $query->bindParam(':nticemsg', $nticemsg, PDO::PARAM_STR);
            $query->execute();
            $LastInsertId = $dbh->lastInsertId();
            if ($LastInsertId > 0) {
                echo '<script>alert("Notice has been added.")</script>';
                echo "<script>window.location.href ='manage-notice.php'</script>";
            } else {
                echo '<script>alert("Something Went Wrong. Please try again")</script>';
            }
        } else {
            echo '<script>alert("You are not authorized to add notice for this class.")</script>';
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Teacher Management System|| Add Notice</title>
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="vendors/select2/select2.min.css">
        <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
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
                            <h3 class="page-title">Add Notice</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add Notice</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title" style="text-align: center;">Add Notice</h4>
                                        <form class="forms-sample" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="exampleInputName1">Notice Title</label>
                                                <input type="text" name="noticeTitle" value="" class="form-control" required='true'>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail3">Notice For</label>
                                                <select name="classid" class="form-control" required='true'>
                                                    <option value="">Select Class</option>
                                                    <?php
                                                    $teacher_id = $_SESSION['teachermsaid'];
                                                    $sql2 = "SELECT c.ID, c.ClassName, c.Section FROM tblclass c 
                                                             INNER JOIN tblteacherclass tc ON c.ID = tc.ClassID 
                                                             WHERE tc.TeacherID = :teacher_id";
                                                    $query2 = $dbh->prepare($sql2);
                                                    $query2->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
                                                    $query2->execute();
                                                    $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($result2 as $row1) {
                                                    ?>
                                                        <option value="<?php echo htmlentities($row1->ID); ?>"><?php echo htmlentities($row1->ClassName); ?> <?php echo htmlentities($row1->Section); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputName1">Notice Message</label>
                                                <textarea name="noticemsg" class="form-control" required='true'></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary mr-2" name="submit">Add</button>
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
        <script src="vendors/select2/select2.min.js"></script>
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>
        <script src="js/select2.js"></script>
    </body>

    </html>
<?php } ?>