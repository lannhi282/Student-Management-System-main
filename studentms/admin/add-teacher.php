<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $teachername = $_POST['teachername'];
        $teacheremail = $_POST['teacheremail'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $mobilenumber = $_POST['mobilenumber'];

        // Check if username already exists
        $check_sql = "SELECT ID FROM tblteacher WHERE Username = :username OR Email = :email";
        $check_query = $dbh->prepare($check_sql);
        $check_query->bindParam(':username', $username, PDO::PARAM_STR);
        $check_query->bindParam(':email', $teacheremail, PDO::PARAM_STR);
        $check_query->execute();

        if ($check_query->rowCount() > 0) {
            echo '<script>alert("Username or Email already exists!")</script>';
        } else {
            $sql = "INSERT INTO tblteacher(TeacherName,Email,Username,Password,MobileNumber) VALUES(:teachername,:teacheremail,:username,:password,:mobilenumber)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':teachername', $teachername, PDO::PARAM_STR);
            $query->bindParam(':teacheremail', $teacheremail, PDO::PARAM_STR);
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            $query->bindParam(':mobilenumber', $mobilenumber, PDO::PARAM_STR);
            $query->execute();
            $LastInsertId = $dbh->lastInsertId();
            if ($LastInsertId > 0) {
                echo '<script>alert("Teacher has been added.")</script>';
                echo "<script>window.location.href ='manage-teachers.php'</script>";
            } else {
                echo '<script>alert("Something Went Wrong. Please try again")</script>';
            }
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Student Management System|| Add Teacher</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <!-- endinject -->
        <!-- Plugin css for this page -->
        <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
        <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
        <!-- End plugin css for this page -->
        <!-- inject:css -->
        <!-- endinject -->
        <!-- Layout styles -->
        <link rel="stylesheet" href="./css/style.css">
        <!-- End layout styles -->
    </head>

    <body>
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <?php include_once('includes/header.php'); ?>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <!-- partial:partials/_sidebar.html -->
                <?php include_once('includes/sidebar.php'); ?>
                <!-- partial -->
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="page-header">
                            <h3 class="page-title">Add Teacher </h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"> Add Teacher</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title" style="text-align: center;">Add Teacher</h4>
                                        <form class="forms-sample" method="post">
                                            <div class="form-group">
                                                <label for="exampleInputName1">Teacher Name</label>
                                                <input type="text" name="teachername" class="form-control" required='true'>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail3">Teacher Email</label>
                                                <input type="email" name="teacheremail" class="form-control" required='true'>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputName1">Username</label>
                                                <input type="text" name="username" class="form-control" required='true'>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputName1">Password</label>
                                                <input type="password" name="password" class="form-control" required='true'>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputName1">Mobile Number</label>
                                                <input type="text" name="mobilenumber" class="form-control" required='true' maxlength="10" pattern="[0-9]+">
                                            </div>
                                            <button type="submit" class="btn btn-primary mr-2" name="submit">Add</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <?php include_once('includes/footer.php'); ?>
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <!-- plugins:js -->
        <script src="vendors/js/vendor.bundle.base.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="vendors/chart.js/Chart.min.js"></script>
        <script src="vendors/moment/moment.min.js"></script>
        <script src="vendors/daterangepicker/daterangepicker.js"></script>
        <script src="vendors/chartist/chartist.min.js"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="js/off-canvas.js"></script>
        <script src="js/misc.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page -->
        <script src="js/dashboard.js"></script>
        <!-- End custom js for this page -->
    </body>

    </html>
<?php } ?>