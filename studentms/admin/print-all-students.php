<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>In danh sách toàn bộ sinh viên</title>
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="./css/style.css">
        <style>
            @media print {
                .no-print {
                    display: none !important;
                }

                body {
                    font-size: 12px;
                }

                .print-title {
                    text-align: center;
                    margin-bottom: 30px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                th,
                td {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: left;
                }

                th {
                    background-color: #f2f2f2;
                }
            }

            .print-title {
                text-align: center;
                margin-bottom: 30px;
            }

            .print-date {
                text-align: right;
                margin-bottom: 20px;
            }
        </style>
    </head>

    <body>
        <div class="container-fluid">
            <div class="no-print" style="margin: 20px 0;">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="icon-printer"></i> In danh sách
                </button>
                <button onclick="window.close()" class="btn btn-secondary ml-2">
                    <i class="icon-close"></i> Đóng
                </button>
            </div>

            <div class="print-content">
                <div class="print-title">
                    <h2>DANH SÁCH TOÀN BỘ SINH VIÊN</h2>
                    <h4>HỆ THỐNG QUẢN LÝ SINH VIÊN</h4>
                </div>

                <div class="print-date">
                    <strong>Ngày in: <?php echo date('d/m/Y H:i:s'); ?></strong>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã sinh viên</th>
                            <th>Tên sinh viên</th>
                            <th>Lớp</th>
                            <th>Email</th>
                            <th>Ngày nhập học</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT tblstudent.StuID,tblstudent.StudentName,tblstudent.StudentEmail,tblstudent.DateofAdmission,tblclass.ClassName,tblclass.Section from tblstudent join tblclass on tblclass.ID=tblstudent.StudentClass ORDER BY tblstudent.StudentName";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                        $cnt = 1;
                        if ($query->rowCount() > 0) {
                            foreach ($results as $row) {
                        ?>
                                <tr>
                                    <td><?php echo htmlentities($cnt); ?></td>
                                    <td><?php echo htmlentities($row->StuID); ?></td>
                                    <td><?php echo htmlentities($row->StudentName); ?></td>
                                    <td><?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?></td>
                                    <td><?php echo htmlentities($row->StudentEmail); ?></td>
                                    <td><?php echo htmlentities($row->DateofAdmission); ?></td>
                                </tr>
                            <?php
                                $cnt = $cnt + 1;
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Không có dữ liệu</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <div style="margin-top: 50px; text-align: center;">
                    <p><strong>Tổng số sinh viên: <?php echo ($cnt - 1); ?></strong></p>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php } ?>