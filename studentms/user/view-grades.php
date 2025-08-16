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
        <style>
            .subject-row {
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .subject-row:hover {
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
                                        <div class="d-sm-flex align-items-center mb-4">
                                            <h4 class="card-title mb-sm-0">Bảng Điểm Của Tôi</h4>

                                        </div>

                                        <div class="table-responsive border rounded p-1">
                                            <table class="table table-striped">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th width="5%">STT</th>
                                                        <th width="45%">Môn học</th>
                                                        <th width="25%">Điểm TB</th>
                                                        <th width="25%">Xếp hạng</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sid = $_SESSION['sturecmsstuid'];

                                                    // Lấy danh sách môn học và điểm trung bình
                                                    $sql = "SELECT sub.ID, sub.SubjectName, 
                                                           AVG(g.Score) as AvgScore,
                                                           COUNT(g.ID) as TotalGrades
                                                           FROM tblgrade g
                                                           JOIN tblstudent s ON g.StudentID = s.ID
                                                           JOIN tblsubject sub ON g.SubjectID = sub.ID
                                                           WHERE s.StuID = :sid
                                                           GROUP BY sub.ID, sub.SubjectName
                                                           ORDER BY sub.SubjectName";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $subjects = $query->fetchAll(PDO::FETCH_OBJ);

                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($subjects as $subject) {
                                                            $avgScore = round($subject->AvgScore, 2);
                                                            if ($avgScore >= 8.5) {
                                                                $rank = 'Giỏi';
                                                                $badgeClass = 'success';
                                                            } elseif ($avgScore >= 6.5) {
                                                                $rank = 'Khá';
                                                                $badgeClass = 'primary';
                                                            } elseif ($avgScore >= 5.0) {
                                                                $rank = 'Trung bình';
                                                                $badgeClass = 'warning';
                                                            } else {
                                                                $rank = 'Yếu';
                                                                $badgeClass = 'danger';
                                                            }
                                                    ?>
                                                            <tr class="subject-row" onclick="toggleGradeDetails(<?php echo $subject->ID; ?>)">
                                                                <td><?php echo $cnt; ?></td>
                                                                <td>
                                                                    <i class="icon-arrow-right expand-icon" id="icon-<?php echo $subject->ID; ?>"></i>
                                                                    <?php echo htmlentities($subject->SubjectName); ?>
                                                                </td>
                                                                <td><?php echo $avgScore; ?></td>
                                                                <td>
                                                                    <span class="badge badge-<?php echo $badgeClass; ?>">
                                                                        <?php echo $rank; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr class="grade-details" id="details-<?php echo $subject->ID; ?>">
                                                                <td colspan="4">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <?php
                                                                            // Lấy tất cả điểm của môn học này
                                                                            $sql_grades = "SELECT g.*, gt.TypeName, gt.Weight
                                                                                         FROM tblgrade g
                                                                                         JOIN tblstudent s ON g.StudentID = s.ID
                                                                                         JOIN tblgradetype gt ON g.GradeTypeID = gt.ID
                                                                                         JOIN tblteacher t ON g.TeacherID = t.ID
                                                                                         WHERE s.StuID = :sid AND g.SubjectID = :subjectid
                                                                                         ORDER BY gt.Weight DESC, g.CreationDate DESC";
                                                                            $query_grades = $dbh->prepare($sql_grades);
                                                                            $query_grades->bindParam(':sid', $sid, PDO::PARAM_STR);
                                                                            $query_grades->bindParam(':subjectid', $subject->ID, PDO::PARAM_INT);
                                                                            $query_grades->execute();
                                                                            $grades = $query_grades->fetchAll(PDO::FETCH_OBJ);

                                                                            if ($query_grades->rowCount() > 0) {
                                                                            ?>
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-sm grade-table">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Loại điểm</th>
                                                                                                <th>Điểm</th>
                                                                                                <th>Trọng số</th>

                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            $totalWeightedScore = 0;
                                                                                            $totalWeight = 0;

                                                                                            foreach ($grades as $grade) {
                                                                                                $totalWeightedScore += $grade->Score * $grade->Weight;
                                                                                                $totalWeight += $grade->Weight;
                                                                                            ?>
                                                                                                <tr>
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

                                                                                                </tr>
                                                                                            <?php } ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>


                                                                            <?php } else { ?>
                                                                                <div class="alert alert-info">Chưa có điểm nào cho môn học này.</div>
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
                                                            <td colspan="4" style="text-align:center; color:red;">Chưa có điểm nào</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <?php
                                        if ($query->rowCount() > 0) {
                                            // Tính điểm tổng kết
                                            $sql_overall = "SELECT AVG(avg_scores.AvgScore) as OverallAverage
                                                          FROM (
                                                              SELECT AVG(g.Score) as AvgScore
                                                              FROM tblgrade g
                                                              JOIN tblstudent s ON g.StudentID = s.ID
                                                              WHERE s.StuID = :sid
                                                              GROUP BY g.SubjectID
                                                          ) as avg_scores";
                                            $query_overall = $dbh->prepare($sql_overall);
                                            $query_overall->bindParam(':sid', $sid, PDO::PARAM_STR);
                                            $query_overall->execute();
                                            $overall = $query_overall->fetch(PDO::FETCH_OBJ);

                                            if ($overall && $overall->OverallAverage) {
                                                $finalAverage = round($overall->OverallAverage, 2);
                                                if ($finalAverage >= 8.5) {
                                                    $finalRank = 'Giỏi';
                                                } elseif ($finalAverage >= 6.5) {
                                                    $finalRank = 'Khá';
                                                } elseif ($finalAverage >= 5.0) {
                                                    $finalRank = 'Trung bình';
                                                } else {
                                                    $finalRank = 'Yếu';
                                                }
                                        ?>
                                                <div class="row mt-2">
                                                    <div class="col-md-12 d-flex justify-content-center">
                                                        <div class="alert alert-primary text-center"
                                                            style="background: linear-gradient(45deg, #007bff, #0056b3); 
                                                                     color: white; 
                                                                     border: none; 
                                                                    font-size: 0.9em; 
                                                                     padding: 6px 12px; 
                                                                    border-radius: 4px; 
                                                                    max-width: 320px;">
                                                            <h6 class="mb-1" style="color: white; font-size: 0.95em;">
                                                                <i class="icon-trophy" style="margin-right: 4px;"></i>
                                                                <strong>Điểm Trung Bình Tổng Kết:
                                                                    <span style="font-size: 1.1em; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                                                                        <?php echo $finalAverage; ?> - <?php echo $finalRank; ?>
                                                                    </span>
                                                                </strong>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>
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
            function toggleGradeDetails(subjectId) {
                var detailsRow = document.getElementById('details-' + subjectId);
                var icon = document.getElementById('icon-' + subjectId);

                if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                    detailsRow.style.display = 'table-row';
                    icon.classList.add('rotated');
                } else {
                    detailsRow.style.display = 'none';
                    icon.classList.remove('rotated');
                }
            }
        </script>
    </body>

    </html>
<?php } ?>