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
            $sql = "INSERT INTO tblgrade (StudentID, SubjectID, ClassID, GradeTypeID, Score, TeacherID, ExamDate, Remarks) 
                    VALUES (:studentid, :subjectid, :classid, :gradetypeid, :score, :teacherid, :examdate, :remarks)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':studentid', $studentid, PDO::PARAM_INT);
            $query->bindParam(':subjectid', $subjectid, PDO::PARAM_INT);
            $query->bindParam(':classid', $classid, PDO::PARAM_INT);
            $query->bindParam(':gradetypeid', $gradetypeid, PDO::PARAM_INT);
            $query->bindParam(':score', $score, PDO::PARAM_STR);
            $query->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);
            $query->bindParam(':examdate', $examdate, PDO::PARAM_STR);
            $query->bindParam(':remarks', $remarks, PDO::PARAM_STR);

            if ($query->execute()) {
                echo "<script>alert('Đã nhập điểm thành công!');</script>";
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
        <title>Teacher Management System || Add Grades</title>
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
                            <h3 class="page-title">Nhập Điểm</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add Grades</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Thêm Điểm Mới</h4>
                                        <form class="forms-sample" method="post">
                                            <div class="form-group">
                                                <label for="classid">Lớp</label>
                                                <select class="form-control" id="classid" name="classid" onchange="loadStudents()" required>
                                                    <option value="">Chọn lớp</option>
                                                    <?php
                                                    $sql = "SELECT c.ID, c.ClassName, c.Section FROM tblclass c 
                                                       JOIN tblteacherclass tc ON c.ID = tc.ClassID 
                                                       WHERE tc.TeacherID = :teacherid";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($results as $row) { ?>
                                                        <option value="<?php echo $row->ID; ?>">
                                                            <?php echo htmlentities($row->ClassName . ' - ' . $row->Section); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="subjectid">Môn học</label>
                                                <select class="form-control" id="subjectid" name="subjectid" required>
                                                    <option value="">Chọn môn học</option>
                                                    <?php
                                                    $sql = "SELECT s.ID, s.SubjectName FROM tblsubject s 
                                                       JOIN tblteachersubject ts ON s.ID = ts.SubjectID 
                                                       WHERE ts.TeacherID = :teacherid";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($results as $row) { ?>
                                                        <option value="<?php echo $row->ID; ?>">
                                                            <?php echo htmlentities($row->SubjectName); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="studentid">Học sinh</label>
                                                <select class="form-control" id="studentid" name="studentid" required>
                                                    <option value="">Chọn lớp trước</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="gradetypeid">Loại điểm</label>
                                                <select class="form-control" id="gradetypeid" name="gradetypeid" required>
                                                    <option value="">Chọn loại điểm</option>
                                                    <?php
                                                    $sql = "SELECT * FROM tblgradetype ORDER BY Weight";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($results as $row) { ?>
                                                        <option value="<?php echo $row->ID; ?>">
                                                            <?php echo htmlentities($row->TypeName . ' (Trọng số: ' . $row->Weight . ')'); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="score">Điểm (0-10)</label>
                                                <input type="number" class="form-control" id="score" name="score"
                                                    min="0" max="10" step="0.1" placeholder="Nhập điểm" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="examdate">Ngày kiểm tra</label>
                                                <input type="date" class="form-control" id="examdate" name="examdate" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="remarks">Ghi chú</label>
                                                <textarea class="form-control" id="remarks" name="remarks" rows="3"
                                                    placeholder="Nhập ghi chú (tùy chọn)"></textarea>
                                            </div>

                                            <button type="submit" name="submit" class="btn btn-primary mr-2">Thêm Điểm</button>
                                            <button type="reset" class="btn btn-light">Reset</button>
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

        <script>
            function loadStudents() {
                var classid = document.getElementById('classid').value;
                var studentSelect = document.getElementById('studentid');

                if (classid) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', 'get-students.php?classid=' + classid, true);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            studentSelect.innerHTML = xhr.responseText;
                        }
                    };
                    xhr.send();
                } else {
                    studentSelect.innerHTML = '<option value="">Chọn lớp trước</option>';
                }
            }

            // Set default date to today
            document.getElementById('examdate').valueAsDate = new Date();
        </script>
    </body>

    </html>
<?php } ?>