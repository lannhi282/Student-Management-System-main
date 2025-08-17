<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    // Handle delete
    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $sql = "DELETE FROM tblgrade WHERE ID=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Đã xóa điểm!');</script>";
        echo "<script>window.location.href = 'manage-grades.php'</script>";
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Student Management System || Manage All Grades</title>
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
                            <h3 class="page-title">Quản Lý Tất Cả Điểm</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Manage All Grades</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <h4 class="card-title mb-sm-0">Danh Sách Tất Cả Điểm</h4>
                                        </div>

                                        <!-- Filters -->
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <select class="form-control" id="filterClass" onchange="filterGrades()">
                                                    <option value="">Tất cả lớp</option>
                                                    <?php
                                                    $sql = "SELECT DISTINCT c.ID, c.ClassName, c.Section FROM tblclass c 
                                                       JOIN tblgrade g ON c.ID = g.ClassID";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($results as $row) { ?>
                                                        <option value="<?php echo $row->ID; ?>">
                                                            <?php echo htmlentities($row->ClassName . ' - ' . $row->Section); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control" id="filterSubject" onchange="filterGrades()">
                                                    <option value="">Tất cả môn</option>
                                                    <?php
                                                    $sql = "SELECT DISTINCT s.ID, s.SubjectName FROM tblsubject s 
                                                       JOIN tblgrade g ON s.ID = g.SubjectID";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($results as $row) { ?>
                                                        <option value="<?php echo $row->ID; ?>">
                                                            <?php echo htmlentities($row->SubjectName); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control" id="filterTeacher" onchange="filterGrades()">
                                                    <option value="">Tất cả giáo viên</option>
                                                    <?php
                                                    $sql = "SELECT DISTINCT t.ID, t.TeacherName FROM tblteacher t 
                                                       JOIN tblgrade g ON t.ID = g.TeacherID";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($results as $row) { ?>
                                                        <option value="<?php echo $row->ID; ?>">
                                                            <?php echo htmlentities($row->TeacherName); ?>
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
                                                        <th class="font-weight-bold">Học sinh</th>
                                                        <th class="font-weight-bold">Lớp</th>
                                                        <th class="font-weight-bold">Môn học</th>
                                                        <th class="font-weight-bold">Loại điểm</th>
                                                        <th class="font-weight-bold">Điểm</th>
                                                        <th class="font-weight-bold">Giáo viên</th>
                                                        <!-- <th class="font-weight-bold">Ngày thi</th>
                                                        <th class="font-weight-bold">Ghi chú</th> -->
                                                        <th class="font-weight-bold">Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT g.*, s.StudentName, c.ClassName, c.Section, 
                                                              sub.SubjectName, gt.TypeName, t.TeacherName 
                                                       FROM tblgrade g
                                                       JOIN tblstudent s ON g.StudentID = s.ID
                                                       JOIN tblclass c ON g.ClassID = c.ID
                                                       JOIN tblsubject sub ON g.SubjectID = sub.ID
                                                       JOIN tblgradetype gt ON g.GradeTypeID = gt.ID
                                                       JOIN tblteacher t ON g.TeacherID = t.ID
                                                       ORDER BY g.CreationDate DESC";
                                                    $query = $dbh->prepare($sql);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                    ?>
                                                            <tr data-class="<?php echo $row->ClassID; ?>" data-subject="<?php echo $row->SubjectID; ?>" data-teacher="<?php echo $row->TeacherID; ?>">
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($row->StudentName); ?></td>
                                                                <td><?php echo htmlentities($row->ClassName . '-' . $row->Section); ?></td>
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
                                                                <td><?php echo htmlentities($row->TeacherName); ?></td>

                                                                <td>
                                                                    <a href="manage-grades.php?delid=<?php echo ($row->ID); ?>" onclick="return confirm('Bạn có chắc muốn xóa điểm này?');" class="btn btn-sm btn-danger">Xóa</a>
                                                                </td>
                                                            </tr>
                                                        <?php $cnt = $cnt + 1;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="10" style="color:red; text-align:center;">Chưa có điểm nào</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Statistics -->
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h5>Thống Kê Điểm</h5>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="card bg-primary text-white">
                                                            <div class="card-body">
                                                                <h6>Tổng số điểm</h6>
                                                                <h3><?php echo $query->rowCount(); ?></h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card bg-success text-white">
                                                            <div class="card-body">
                                                                <h6>Điểm giỏi (≥8.5)</h6>
                                                                <?php
                                                                $sql_good = "SELECT COUNT(*) as count FROM tblgrade WHERE Score >= 8.5";
                                                                $query_good = $dbh->prepare($sql_good);
                                                                $query_good->execute();
                                                                $good_count = $query_good->fetch(PDO::FETCH_OBJ);
                                                                ?>
                                                                <h3><?php echo $good_count->count; ?></h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card bg-warning text-white">
                                                            <div class="card-body">
                                                                <h6>Điểm trung bình (5-6.5)</h6>
                                                                <?php
                                                                $sql_avg = "SELECT COUNT(*) as count FROM tblgrade WHERE Score >= 5.0 AND Score < 6.5";
                                                                $query_avg = $dbh->prepare($sql_avg);
                                                                $query_avg->execute();
                                                                $avg_count = $query_avg->fetch(PDO::FETCH_OBJ);
                                                                ?>
                                                                <h3><?php echo $avg_count->count; ?></h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="card bg-danger text-white">
                                                            <div class="card-body">
                                                                <h6>Điểm yếu (<5)< /h6>
                                                                        <?php
                                                                        $sql_poor = "SELECT COUNT(*) as count FROM tblgrade WHERE Score < 5.0";
                                                                        $query_poor = $dbh->prepare($sql_poor);
                                                                        $query_poor->execute();
                                                                        $poor_count = $query_poor->fetch(PDO::FETCH_OBJ);
                                                                        ?>
                                                                        <h3><?php echo $poor_count->count; ?></h3>
                                                            </div>
                                                        </div>
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
            function filterGrades() {
                var classFilter = document.getElementById('filterClass').value;
                var subjectFilter = document.getElementById('filterSubject').value;
                var teacherFilter = document.getElementById('filterTeacher').value;
                var table = document.querySelector('.table tbody');
                var rows = table.querySelectorAll('tr[data-class]');

                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var showRow = true;

                    if (classFilter && row.getAttribute('data-class') !== classFilter) {
                        showRow = false;
                    }
                    if (subjectFilter && row.getAttribute('data-subject') !== subjectFilter) {
                        showRow = false;
                    }
                    if (teacherFilter && row.getAttribute('data-teacher') !== teacherFilter) {
                        showRow = false;
                    }

                    row.style.display = showRow ? '' : 'none';
                }
            }
        </script>
    </body>

    </html>
<?php } ?>