<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
  header('location:logout.php');
} else {
  // Code for deletion
  if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "delete from tblstudent where ID=:rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Data deleted');</script>";
    echo "<script>window.location.href = 'manage-students.php'</script>";
  }
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <title>Student Management System | Manage Students</title>
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
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
              <h3 class="page-title"> Manage Students </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page"> Manage Students</li>
                </ol>
              </nav>
            </div>
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-sm-flex align-items-center mb-4">
                      <h4 class="card-title mb-sm-0">Manage Students</h4>
                      <div class="ml-auto">
                        <a href="print-all-students.php" target="_blank" class="btn btn-success btn-sm mr-2">
                          <i class="icon-printer"></i> In danh sách
                        </a>
                        <a href="#" class="text-dark mb-3 mb-sm-0"> View all Students</a>
                      </div>
                    </div>
                    <!-- Search Form -->
                    <div class="mb-4">
                      <form method="GET" action="manage-students.php">
                        <div class="input-group">
                          <input type="text" name="search" class="form-control" placeholder="Search by Student Name or ID" value="<?php echo isset($_GET['search']) ? htmlentities($_GET['search']) : ''; ?>">
                          <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Search</button>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="table-responsive border rounded p-1">
                      <table class="table">
                        <thead>
                          <tr>
                            <th class="font-weight-bold">S.No</th>
                            <th class="font-weight-bold">Student ID</th>
                            <th class="font-weight-bold">Student Class</th>
                            <th class="font-weight-bold">Student Name</th>
                            <th class="font-weight-bold">Student Email</th>
                            <th class="font-weight-bold">Admission Date</th>
                            <th class="font-weight-bold">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
                            $page_no = $_GET['page_no'];
                          } else {
                            $page_no = 1;
                          }

                          $total_records_per_page = 10;
                          $offset = ($page_no - 1) * $total_records_per_page;
                          $previous_page = $page_no - 1;
                          $next_page = $page_no + 1;
                          $adjacents = "2";

                          // Prepare SQL for counting total records with search filter
                          $search = isset($_GET['search']) ? $_GET['search'] : '';
                          $ret = "SELECT ID FROM tblstudent WHERE StudentName LIKE :search OR StuID LIKE :search";
                          $query1 = $dbh->prepare($ret);
                          $query1->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
                          $query1->execute();
                          $total_records = $query1->rowCount();
                          $total_no_of_pages = ceil($total_records / $total_records_per_page);
                          $second_last = $total_no_of_pages - 1;

                          // Prepare SQL for fetching records with search filter
                          $sql = "SELECT tblstudent.StuID, tblstudent.ID as sid, tblstudent.StudentName, tblstudent.StudentEmail, tblstudent.DateofAdmission, tblclass.ClassName, tblclass.Section 
                                FROM tblstudent 
                                JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                                WHERE tblstudent.StudentName LIKE :search OR tblstudent.StuID LIKE :search 
                                LIMIT :offset, :total_records_per_page";
                          $query = $dbh->prepare($sql);
                          $query->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
                          $query->bindValue(':offset', $offset, PDO::PARAM_INT);
                          $query->bindValue(':total_records_per_page', $total_records_per_page, PDO::PARAM_INT);
                          $query->execute();
                          $results = $query->fetchAll(PDO::FETCH_OBJ);

                          $cnt = 1;
                          if ($query->rowCount() > 0) {
                            foreach ($results as $row) { ?>
                              <tr>
                                <td><?php echo htmlentities($cnt); ?></td>
                                <td><?php echo htmlentities($row->StuID); ?></td>
                                <td><?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?></td>
                                <td><?php echo htmlentities($row->StudentName); ?></td>
                                <td><?php echo htmlentities($row->StudentEmail); ?></td>
                                <td><?php echo htmlentities($row->DateofAdmission); ?></td>
                                <td>
                                  <div>
                                    <a href="edit-student-detail.php?editid=<?php echo htmlentities($row->sid); ?>" class="btn btn-info btn-xs" target="_blank">Edit</a>
                                    <a href="manage-students.php?delid=<?php echo ($row->sid); ?>&search=<?php echo htmlentities($search); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger btn-xs"> Delete</a>
                                  </div>
                                </td>
                              </tr>
                          <?php $cnt = $cnt + 1;
                            }
                          } else {
                            echo '<tr><td colspan="7">No records found</td></tr>';
                          } ?>
                        </tbody>
                      </table>
                    </div>
                    <div align="left">
                      <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'></div>
                      <hr />
                      <ul class="pagination">
                        <li <?php if ($page_no <= 1) {
                              echo "class='disabled'";
                            } ?>>
                          <a <?php if ($page_no > 1) {
                                echo "href='?page_no=$previous_page&search=" . urlencode($search) . "'";
                              } ?>>Previous</a>
                        </li>
                        <?php
                        if ($total_no_of_pages <= 10) {
                          for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                            if ($counter == $page_no) {
                              echo "<li class='active'><a>$counter</a></li>";
                            } else {
                              echo "<li><a href='?page_no=$counter&search=" . urlencode($search) . "'>$counter</a></li>";
                            }
                          }
                        } elseif ($total_no_of_pages > 10) {
                          if ($page_no <= 4) {
                            for ($counter = 1; $counter < 8; $counter++) {
                              if ($counter == $page_no) {
                                echo "<li class='active'><a>$counter</a></li>";
                              } else {
                                echo "<li><a href='?page_no=$counter&search=" . urlencode($search) . "'>$counter</a></li>";
                              }
                            }
                            echo "<li><a>...</a></li>";
                            echo "<li><a href='?page_no=$second_last&search=" . urlencode($search) . "'>$second_last</a></li>";
                            echo "<li><a href='?page_no=$total_no_of_pages&search=" . urlencode($search) . "'>$total_no_of_pages</a></li>";
                          } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                            echo "<li><a href='?page_no=1&search=" . urlencode($search) . "'>1</a></li>";
                            echo "<li><a href='?page_no=2&search=" . urlencode($search) . "'>2</a></li>";
                            echo "<li><a>...</a></li>";
                            for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                              if ($counter == $page_no) {
                                echo "<li class='active'><a>$counter</a></li>";
                              } else {
                                echo "<li><a href='?page_no=$counter&search=" . urlencode($search) . "'>$counter</a></li>";
                              }
                            }
                            echo "<li><a>...</a></li>";
                            echo "<li><a href='?page_no=$second_last&search=" . urlencode($search) . "'>$second_last</a></li>";
                            echo "<li><a href='?page_no=$total_no_of_pages&search=" . urlencode($search) . "'>$total_no_of_pages</a></li>";
                          } else {
                            echo "<li><a href='?page_no=1&search=" . urlencode($search) . "'>1</a></li>";
                            echo "<li><a href='?page_no=2&search=" . urlencode($search) . "'>2</a></li>";
                            echo "<li><a>...</a></li>";
                            for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                              if ($counter == $page_no) {
                                echo "<li class='active'><a>$counter</a></li>";
                              } else {
                                echo "<li><a href='?page_no=$counter&search=" . urlencode($search) . "'>$counter</a></li>";
                              }
                            }
                          }
                        }
                        ?>
                        <li <?php if ($page_no >= $total_no_of_pages) {
                              echo "class='disabled'";
                            } ?>>
                          <a <?php if ($page_no < $total_no_of_pages) {
                                echo "href='?page_no=$next_page&search=" . urlencode($search) . "'";
                              } ?>>Next</a>
                        </li>
                        <?php if ($page_no < $total_no_of_pages) {
                          echo "<li><a href='?page_no=$total_no_of_pages&search=" . urlencode($search) . "'>Last &rsaquo;&rsaquo;</a></li>";
                        } ?>
                      </ul>
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
      <script src="./vendors/chart.js/Chart.min.js"></script>
      <script src="./vendors/moment/moment.min.js"></script>
      <script src="./vendors/daterangepicker/daterangepicker.js"></script>
      <script src="./vendors/chartist/chartist.min.js"></script>
      <script src="js/off-canvas.js"></script>
      <script src="js/misc.js"></script>
      <script src="./js/dashboard.js"></script>
  </body>

  </html>
<?php } ?>