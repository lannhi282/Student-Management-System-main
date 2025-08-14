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
        <title>Student Management System|| View Grades</title>
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="css/style.css" />
    </head>

    <body>
        <div class="container-scroller">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid page-body-wrapper">
                <?php include_once('includes/sidebar.php'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="page-header">
                            <h3 class="page-title">Xem Điểm</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">View Grades</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Bảng Điểm Của Tôi</h4>

                                        <!-- Filters -->
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <select class="form-control" id="filterSubject" onchange="filterGrades()">
                                                    <option value="">Tất cả môn học</option>
                                                    <?php
                                                    $sid = $_SESSION['sturecmsstuid'];
                                                    $sql = "SELECT DISTINCT sub.ID, sub.SubjectName FROM tblgrade g 
                                                       JOIN tblsubject sub ON g.SubjectID = sub.ID 
                                                       JOIN tblstudent s ON g.StudentID = s.ID 
                                                       WHERE s.StuID = :sid ORDER BY sub.SubjectName";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($results as $row) { ?>
                                                        <option value="<?php echo $row->ID; ?>">
                                                            <?php echo htmlentities($row->SubjectName); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" id="filterGradeType" onchange="filterGrades()">
                                                    <option value="">Tất cả loại điểm</option>
                                                    <?php
                                                    $sql = "SELECT DISTINCT gt.ID, gt.TypeName FROM tblgrade g 
                                                       JOIN tblgradetype gt ON g.GradeTypeID = gt.ID 
                                                       JOIN tblstudent s ON g.StudentID = s.ID 
                                                       WHERE s.StuID = :sid ORDER BY gt.Weight";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($results as $row) { ?>
                                                        <option value="<?php echo $row->ID; ?>">
                                                            <?php echo htmlentities($row->TypeName); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="table-responsive border rounded p-1">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="font-weight-bold">STT</th>
                                                        <th class="font-weight-bold">Môn học</th>
                                                        <th class="font-weight-bold">Loại điểm</th>
                                                        <th class="font-weight-bold">Điểm</th>
                                                        <th class="font-weight-bold">Ngày thi</th>
                                                        <th class="font-weight-bold">Giáo viên</th>
                                                        <th class="font-weight-bold">Ghi chú</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT g.*, sub.SubjectName, gt.TypeName, t.TeacherName, g.ExamDate, g.Remarks
                                                       FROM tblgrade g
                                                       JOIN tblstudent s ON g.StudentID = s.ID
                                                       JOIN tblsubject sub ON g.SubjectID = sub.ID
                                                       JOIN tblgradetype gt ON g.GradeTypeID = gt.ID
                                                       JOIN tblteacher t ON g.TeacherID = t.ID
                                                       WHERE s.StuID = :sid
                                                       ORDER BY g.CreationDate DESC";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                    ?>
                                                            <tr data-subject="<?php echo $row->SubjectID; ?>" data-gradetype="<?php echo $row->GradeTypeID; ?>">
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($row->SubjectName); ?></td>
                                                                <td><?php echo htmlentities($row->TypeName); ?></td>
                                                                <td>
                                                                    <span class="badge badge-<?php
                                                                                                if ($row->Score >= 8.5) echo 'success';
                                                                                                elseif ($row->Score >= 6.5) echo 'primary';
                                                                                                elseif ($row->Score >= 5.0) echo 'warning';
                                                                                                else echo 'danger';
                                                                                                ?>">
                                                                        <?php echo htmlentities($row->Score); ?>
                                                                    </span>
                                                                </td>
                                                                <td><?php echo htmlentities($row->ExamDate); ?></td>
                                                                <td><?php echo htmlentities($row->TeacherName); ?></td>
                                                                <td><?php echo htmlentities($row->Remarks); ?></td>
                                                            </tr>
                                                        <?php $cnt = $cnt + 1;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="7" style="color:red; text-align:center;">Chưa có điểm nào</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Grade Summary -->
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h5>Điểm Trung Bình Theo Môn</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Môn học</th>
                                                                <th>Điểm TB</th>
                                                                <th>Xếp hạng</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $sql = "SELECT sub.SubjectName, 
                                                               AVG(g.Score) as AvgScore,
                                                               COUNT(g.ID) as TotalGrades
                                                               FROM tblgrade g
                                                               JOIN tblstudent s ON g.StudentID = s.ID
                                                               JOIN tblsubject sub ON g.SubjectID = sub.ID
                                                               WHERE s.StuID = :sid
                                                               GROUP BY sub.ID, sub.SubjectName
                                                               ORDER BY AvgScore DESC";
                                                            $query = $dbh->prepare($sql);
                                                            $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                                            $query->execute();
                                                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                            foreach ($results as $row) {
                                                                $avgScore = round($row->AvgScore, 2);
                                                                if ($avgScore >= 8.5) $rank = 'Giỏi';
                                                                elseif ($avgScore >= 6.5) $rank = 'Khá';
                                                                elseif ($avgScore >= 5.0) $rank = 'Trung bình';
                                                                else $rank = 'Yếu';
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo htmlentities($row->SubjectName); ?></td>
                                                                    <td><?php echo $avgScore; ?></td>
                                                                    <td>
                                                                        <span class="badge badge-<?php
                                                                                                    if ($avgScore >= 8.5) echo 'success';
                                                                                                    elseif ($avgScore >= 6.5) echo 'primary';
                                                                                                    elseif ($avgScore >= 5.0) echo 'warning';
                                                                                                    else echo 'danger';
                                                                                                    ?>">
                                                                            <?php echo $rank; ?>
                                                                        </span>
                                                                    </td>
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
                    <?php include_once('includes/footer.php'); ?>
                </div>
            </div>
        </div>

        <script src="vendors/js/vendor.bundle.base.js"></script>
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>

        <script>
            function filterGrades() {
                var subjectFilter = document.getElementById('filterSubject').value;
                var gradeTypeFilter = document.getElementById('filterGradeType').value;
                var table = document.querySelector('.table tbody');
                var rows = table.querySelectorAll('tr[data-subject]');

                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var showRow = true;

                    if (subjectFilter && row.getAttribute('data-subject') !== subjectFilter) {
                        showRow = false;
                    }
                    if (gradeTypeFilter && row.getAttribute('data-gradetype') !== gradeTypeFilter) {
                        showRow = false;
                    }

                    row.style.display = showRow ? '' : 'none';
                }
            }
        </script>
    </body>

    </html>
<?php } ?>