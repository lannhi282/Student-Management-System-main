<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['teachermsaid']) == 0) {
    header('location:logout.php');
} else {
    $teacherid = $_SESSION['teachermsaid'];

    // Handle form submission
    if (isset($_POST['submit'])) {
        $gradeid = $_GET['editid'];
        $studentid = $_POST['studentid'];
        $subjectid = $_POST['subjectid'];
        $classid = $_POST['classid'];
        $gradetypeid = $_POST['gradetypeid'];
        $score = $_POST['score'];
        $examdate = $_POST['examdate'];
        $remarks = $_POST['remarks'];

        // Validate score
        if ($score < 0 || $score > 10) {
            echo "<script>alert('Điểm phải từ 0 đến 10!');</script>";
        } else {
            $sql = "UPDATE tblgrade SET StudentID=:studentid, SubjectID=:subjectid, ClassID=:classid, 
                    GradeTypeID=:gradetypeid, Score=:score, ExamDate=:examdate, Remarks=:remarks, 
                    UpdateDate=CURRENT_TIMESTAMP WHERE ID=:gradeid AND TeacherID=:teacherid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':studentid', $studentid, PDO::PARAM_INT);
            $query->bindParam(':subjectid', $subjectid, PDO::PARAM_INT);
            $query->bindParam(':classid', $classid, PDO::PARAM_INT);
            $query->bindParam(':gradetypeid', $gradetypeid, PDO::PARAM_INT);
            $query->bindParam(':score', $score, PDO::PARAM_STR);
            $query->bindParam(':examdate', $examdate, PDO::PARAM_STR);
            $query->bindParam(':remarks', $remarks, PDO::PARAM_STR);
            $query->bindParam(':gradeid', $gradeid, PDO::PARAM_INT);
            $query->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);

            if ($query->execute()) {
                echo "<script>alert('Đã cập nhật điểm thành công!');</script>";
                echo "<script>window.location.href='manage-grades.php'</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra!');</script>";
            }
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Teacher Management System || Edit Grade</title>
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="./css/style.css">
    </head>

    <body>
        <div class="container-scroller">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid page-body-wrapper">
                <?php include_once('includes/sidebar.php'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="page-header">
                            <h3 class="page-title">Chỉnh Sửa Điểm</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit Grade</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Chỉnh Sửa Điểm</h4>
                                        <?php
                                        $gradeid = $_GET['editid'];
                                        $sql = "SELECT g.*, s.StudentName, c.ClassName, c.Section, sub.SubjectName, gt.TypeName 
                                           FROM tblgrade g
                                           JOIN tblstudent s ON g.StudentID = s.ID
                                           JOIN tblclass c ON g.ClassID = c.ID
                                           JOIN tblsubject sub ON g.SubjectID = sub.ID
                                           JOIN tblgradetype gt ON g.GradeTypeID = gt.ID
                                           WHERE g.ID = :gradeid AND g.TeacherID = :teacherid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':gradeid', $gradeid, PDO::PARAM_INT);
                                        $query->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                                <form class="forms-sample" method="post">
                                                    <div class="form-group">
                                                        <label for="classid">Lớp</label>
                                                        <select class="form-control" id="classid" name="classid" required>
                                                            <?php
                                                            $sql2 = "SELECT c.ID, c.ClassName, c.Section FROM tblclass c 
                                                               JOIN tblteacherclass tc ON c.ID = tc.ClassID 
                                                               WHERE tc.TeacherID = :teacherid";
                                                            $query2 = $dbh->prepare($sql2);
                                                            $query2->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);
                                                            $query2->execute();
                                                            $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                            foreach ($results2 as $row2) { ?>
                                                                <option value="<?php echo $row2->ID; ?>" <?php if ($row->ClassID == $row2->ID) echo 'selected'; ?>>
                                                                    <?php echo htmlentities($row2->ClassName . ' - ' . $row2->Section); ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="subjectid">Môn học</label>
                                                        <select class="form-control" id="subjectid" name="subjectid" required>
                                                            <?php
                                                            $sql3 = "SELECT s.ID, s.SubjectName FROM tblsubject s 
                                                               JOIN tblteachersubject ts ON s.ID = ts.SubjectID 
                                                               WHERE ts.TeacherID = :teacherid";
                                                            $query3 = $dbh->prepare($sql3);
                                                            $query3->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);
                                                            $query3->execute();
                                                            $results3 = $query3->fetchAll(PDO::FETCH_OBJ);
                                                            foreach ($results3 as $row3) { ?>
                                                                <option value="<?php echo $row3->ID; ?>" <?php if ($row->SubjectID == $row3->ID) echo 'selected'; ?>>
                                                                    <?php echo htmlentities($row3->SubjectName); ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="studentid">Học sinh</label>
                                                        <select class="form-control" id="studentid" name="studentid" required>
                                                            <?php
                                                            $sql4 = "SELECT ID, StudentName FROM tblstudent WHERE StudentClass = :classid ORDER BY StudentName";
                                                            $query4 = $dbh->prepare($sql4);
                                                            $query4->bindParam(':classid', $row->ClassID, PDO::PARAM_INT);
                                                            $query4->execute();
                                                            $results4 = $query4->fetchAll(PDO::FETCH_OBJ);
                                                            foreach ($results4 as $row4) { ?>
                                                                <option value="<?php echo $row4->ID; ?>" <?php if ($row->StudentID == $row4->ID) echo 'selected'; ?>>
                                                                    <?php echo htmlentities($row4->StudentName); ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="gradetypeid">Loại điểm</label>
                                                        <select class="form-control" id="gradetypeid" name="gradetypeid" required>
                                                            <?php
                                                            $sql5 = "SELECT * FROM tblgradetype ORDER BY Weight";
                                                            $query5 = $dbh->prepare($sql5);
                                                            $query5->execute();
                                                            $results5 = $query5->fetchAll(PDO::FETCH_OBJ);
                                                            foreach ($results5 as $row5) { ?>
                                                                <option value="<?php echo $row5->ID; ?>" <?php if ($row->GradeTypeID == $row5->ID) echo 'selected'; ?>>
                                                                    <?php echo htmlentities($row5->TypeName . ' (Trọng số: ' . $row5->Weight . ')'); ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="score">Điểm (0-10)</label>
                                                        <input type="number" class="form-control" id="score" name="score"
                                                            min="0" max="10" step="0.1" value="<?php echo $row->Score; ?>" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="examdate">Ngày kiểm tra</label>
                                                        <input type="date" class="form-control" id="examdate" name="examdate"
                                                            value="<?php echo $row->ExamDate; ?>" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="remarks">Ghi chú</label>
                                                        <textarea class="form-control" id="remarks" name="remarks" rows="3"><?php echo $row->Remarks; ?></textarea>
                                                    </div>

                                                    <button type="submit" name="submit" class="btn btn-primary mr-2">Cập Nhật</button>
                                                    <a href="manage-grades.php" class="btn btn-light">Hủy</a>
                                                </form>
                                            <?php }
                                        } else { ?>
                                            <div class="alert alert-danger">Không tìm thấy điểm hoặc bạn không có quyền chỉnh sửa!</div>
                                        <?php } ?>
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