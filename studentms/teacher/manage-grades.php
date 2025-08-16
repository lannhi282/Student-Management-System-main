<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['teachermsaid']) == 0) {
    header('location:logout.php');
} else {
    $teacherid = $_SESSION['teachermsaid'];

    // Handle delete - Only allow deletion of grades from teacher's classes
    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);

        // Check if this grade belongs to a student in teacher's class
        $verify_sql = "SELECT g.ID FROM tblgrade g 
                      JOIN tblstudent s ON g.StudentID = s.ID 
                      JOIN tblteacherclass tc ON s.StudentClass = tc.ClassID 
                      WHERE g.ID = :rid AND tc.TeacherID = :teacherid";
        $verify_query = $dbh->prepare($verify_sql);
        $verify_query->bindParam(':rid', $rid, PDO::PARAM_INT);
        $verify_query->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);
        $verify_query->execute();

        if ($verify_query->rowCount() > 0) {
            $sql = "DELETE FROM tblgrade WHERE ID=:rid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':rid', $rid, PDO::PARAM_INT);
            $query->execute();
            echo "<script>alert('Đã xóa điểm!');</script>";
        } else {
            echo "<script>alert('Bạn không có quyền xóa điểm này!');</script>";
        }
        echo "<script>window.location.href = 'manage-grades.php'</script>";
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Teacher Management System || Manage Grades</title>
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="./css/style.css">
        <style>
            .student-row {
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .student-row:hover {
                background-color: #f8f9fa;
            }

            .grade-details {
                display: none;
                background-color: #f8f9fa;
                padding: 15px;
                border-left: 4px solid #007bff;
            }

            .grade-table {
                font-size: 0.9em;
            }

            .total-score {
                font-weight: bold;
                font-size: 1.1em;
                color: #007bff;
            }

            .expand-icon {
                transition: transform 0.3s;
            }

            .expand-icon.rotated {
                transform: rotate(90deg);
            }
        </style>
    </head>

    <body>
        <div class="container-scroller">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid page-body-wrapper">
                <?php include_once('includes/sidebar.php'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="page-header">
                            <h3 class="page-title">Quản Lý Điểm Học Sinh Của Tôi</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Manage Grades</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <h4 class="card-title mb-sm-0">Danh Sách Học Sinh Của Tôi</h4>
                                            <div class="ms-auto">
                                                <a href="add-grades.php" class="btn btn-primary">Thêm Điểm</a>
                                            </div>
                                        </div>



                                        <div class="table-responsive border rounded p-1">
                                            <table class="table table-striped">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th width="5%">STT</th>
                                                        <th width="45%">Tên Học Sinh</th>
                                                        <th width="35%">Lớp</th>
                                                        <th width="15%">Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Only get students from classes that teacher is assigned to
                                                    $sql = "SELECT DISTINCT s.ID, s.StudentName, c.ClassName, c.Section, s.StudentClass
                                                           FROM tblstudent s 
                                                           JOIN tblclass c ON s.StudentClass = c.ID
                                                           JOIN tblteacherclass tc ON c.ID = tc.ClassID
                                                           WHERE tc.TeacherID = :teacherid
                                                           ORDER BY c.ClassName, c.Section, s.StudentName";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);
                                                    $query->execute();
                                                    $students = $query->fetchAll(PDO::FETCH_OBJ);

                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($students as $student) {
                                                    ?>
                                                            <tr class="student-row"
                                                                data-class="<?php echo $student->StudentClass; ?>"
                                                                data-student="<?php echo strtolower($student->StudentName); ?>"
                                                                onclick="toggleGradeDetails(<?php echo $student->ID; ?>)">
                                                                <td><?php echo $cnt; ?></td>
                                                                <td>
                                                                    <i class="icon-arrow-right expand-icon" id="icon-<?php echo $student->ID; ?>"></i>
                                                                    <?php echo htmlentities($student->StudentName); ?>
                                                                </td>
                                                                <td><?php echo htmlentities($student->ClassName . ' - ' . $student->Section); ?></td>
                                                                <td>
                                                                    <span class="badge badge-primary">Chi tiết</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="grade-details" id="details-<?php echo $student->ID; ?>">
                                                                <td colspan="4">
                                                                    <div class="row">
                                                                        <div class="col-md-12">

                                                                            <h6 class="mt-3"><strong>Bảng điểm chi tiết:</strong></h6>
                                                                            <?php
                                                                            // Only get grades from subjects that this teacher teaches
                                                                            $sql_grades = "SELECT g.*, sub.SubjectName, gt.TypeName, gt.Weight, t.TeacherName
                                                                                         FROM tblgrade g
                                                                                         JOIN tblsubject sub ON g.SubjectID = sub.ID
                                                                                         JOIN tblgradetype gt ON g.GradeTypeID = gt.ID
                                                                                         JOIN tblteacher t ON g.TeacherID = t.ID
                                                                                         JOIN tblteachersubject ts ON sub.ID = ts.SubjectID
                                                                                         WHERE g.StudentID = :studentid AND ts.TeacherID = :teacherid
                                                                                         ORDER BY sub.SubjectName, gt.Weight DESC";
                                                                            $query_grades = $dbh->prepare($sql_grades);
                                                                            $query_grades->bindParam(':studentid', $student->ID, PDO::PARAM_INT);
                                                                            $query_grades->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);
                                                                            $query_grades->execute();
                                                                            $grades = $query_grades->fetchAll(PDO::FETCH_OBJ);

                                                                            if ($query_grades->rowCount() > 0) {
                                                                                // Nhóm điểm theo môn học
                                                                                $subjects = [];
                                                                                foreach ($grades as $grade) {
                                                                                    $subjects[$grade->SubjectName][] = $grade;
                                                                                }
                                                                            ?>
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-sm grade-table">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Môn học</th>
                                                                                                <th>Loại điểm</th>
                                                                                                <th>Điểm</th>
                                                                                                <th>Trọng số</th>
                                                                                                <th>Thao tác</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            foreach ($subjects as $subjectName => $subjectGrades) {
                                                                                                $firstRow = true;
                                                                                                $totalWeightedScore = 0;
                                                                                                $totalWeight = 0;

                                                                                                // Tính điểm trung bình môn
                                                                                                foreach ($subjectGrades as $grade) {
                                                                                                    $totalWeightedScore += $grade->Score * $grade->Weight;
                                                                                                    $totalWeight += $grade->Weight;
                                                                                                }
                                                                                                $averageScore = $totalWeight > 0 ? round($totalWeightedScore / $totalWeight, 2) : 0;

                                                                                                foreach ($subjectGrades as $grade) {
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <?php if ($firstRow) { ?>
                                                                                                            <td rowspan="<?php echo count($subjectGrades) + 1; ?>" style="vertical-align: middle; font-weight: bold;">
                                                                                                                <?php echo htmlentities($subjectName); ?>
                                                                                                            </td>
                                                                                                        <?php $firstRow = false;
                                                                                                        } ?>
                                                                                                        <td><?php echo htmlentities($grade->TypeName); ?></td>
                                                                                                        <td>
                                                                                                            <span class="badge badge-<?php
                                                                                                                                        if ($grade->Score >= 8.5) echo 'success';
                                                                                                                                        elseif ($grade->Score >= 6.5) echo 'primary';
                                                                                                                                        elseif ($grade->Score >= 5.0) echo 'warning';
                                                                                                                                        else echo 'danger';
                                                                                                                                        ?>"><?php echo $grade->Score; ?></span>
                                                                                                        </td>
                                                                                                        <td><?php echo $grade->Weight; ?></td>
                                                                                                        <td>
                                                                                                            <a href="edit-grades.php?editid=<?php echo $grade->ID; ?>"
                                                                                                                class="btn btn-sm btn-primary">Sửa</a>
                                                                                                            <a href="manage-grades.php?delid=<?php echo $grade->ID; ?>"
                                                                                                                onclick="return confirm('Bạn có chắc muốn xóa điểm này?');"
                                                                                                                class="btn btn-sm btn-danger">Xóa</a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                <?php } ?>
                                                                                                <tr style="background-color: #e9ecef;">
                                                                                                    <td><strong>Điểm trung bình môn</strong></td>
                                                                                                    <td class="total-score"><?php echo $averageScore; ?></td>
                                                                                                    <td colspan="3"></td>
                                                                                                </tr>
                                                                                            <?php } ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>

                                                                                <?php
                                                                                // Tính điểm tổng kết (trung bình tất cả môn mà giáo viên dạy)
                                                                                $totalSubjectScore = 0;
                                                                                $subjectCount = 0;
                                                                                foreach ($subjects as $subjectGrades) {
                                                                                    $subjectWeightedScore = 0;
                                                                                    $subjectWeight = 0;
                                                                                    foreach ($subjectGrades as $grade) {
                                                                                        $subjectWeightedScore += $grade->Score * $grade->Weight;
                                                                                        $subjectWeight += $grade->Weight;
                                                                                    }
                                                                                    if ($subjectWeight > 0) {
                                                                                        $totalSubjectScore += $subjectWeightedScore / $subjectWeight;
                                                                                        $subjectCount++;
                                                                                    }
                                                                                }
                                                                                $finalAverage = $subjectCount > 0 ? round($totalSubjectScore / $subjectCount, 2) : 0;
                                                                                ?>

                                                                                <div class="mt-2 p-2"
                                                                                    style="background-color: #007bff; color: white; border-radius: 5px; 
                                                                                            max-width: 300px; margin: auto; text-align: center;">
                                                                                    <h5 class="mb-0">
                                                                                        <strong>Điểm Trung Bình:
                                                                                            <span class="total-score" style="color: #fff; font-size: 1.3em;">
                                                                                                <?php echo $finalAverage; ?>
                                                                                            </span>
                                                                                            (<?php
                                                                                                if ($finalAverage >= 8.5) echo 'Giỏi';
                                                                                                elseif ($finalAverage >= 6.5) echo 'Khá';
                                                                                                elseif ($finalAverage >= 5.0) echo 'Trung bình';
                                                                                                else echo 'Yếu';
                                                                                                ?>)
                                                                                        </strong>
                                                                                    </h5>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <div class="alert alert-info">Chưa có điểm nào cho học sinh này trong các môn bạn dạy.</div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                            $cnt++;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="4" style="text-align:center; color:red;">Không có học sinh nào trong lớp của bạn</td>
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
            function toggleGradeDetails(studentId) {
                var detailsRow = document.getElementById('details-' + studentId);
                var icon = document.getElementById('icon-' + studentId);

                if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                    detailsRow.style.display = 'table-row';
                    icon.classList.add('rotated');
                } else {
                    detailsRow.style.display = 'none';
                    icon.classList.remove('rotated');
                }
            }


            function searchStudents() {
                var searchTerm = document.getElementById('searchStudent').value.toLowerCase();
                var table = document.querySelector('.table tbody');
                var rows = table.querySelectorAll('tr.student-row');

                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var detailsRow = row.nextElementSibling;
                    var studentName = row.getAttribute('data-student');

                    if (studentName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                        if (detailsRow && detailsRow.classList.contains('grade-details')) {
                            detailsRow.style.display = 'none';
                        }
                    }
                }
            }
        </script>
    </body>

    </html>
<?php } ?>