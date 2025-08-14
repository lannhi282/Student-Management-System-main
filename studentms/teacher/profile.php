<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['teachermsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $teacherid = $_SESSION['teachermsaid'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $mobno = $_POST['mobno'];

        $sql = "UPDATE tblteacher SET FirstName=:fname, LastName=:lname, Email=:email, MobileNumber=:mobno WHERE ID=:teacherid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':lname', $lname, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobno', $mobno, PDO::PARAM_STR);
        $query->bindParam(':teacherid', $teacherid, PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Profile has been updated")</script>';
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Teacher Management System|| Teacher Profile</title>
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
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
                            <h3 class="page-title">Teacher Profile</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="row">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title" style="text-align: center;">Teacher Profile</h4>
                                        <?php
                                        $teacherid = $_SESSION['teachermsaid'];
                                        $sql = "SELECT * FROM tblteacher WHERE ID=:teacherid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':teacherid', $teacherid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                                <form class="forms-sample" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">First Name</label>
                                                        <input type="text" name="fname" value="<?php echo $row->FirstName; ?>" class="form-control" required='true'>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">Last Name</label>
                                                        <input type="text" name="lname" value="<?php echo $row->LastName; ?>" class="form-control" required='true'>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail3">Email</label>
                                                        <input type="email" name="email" value="<?php echo $row->Email; ?>" class="form-control" required='true'>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">Mobile Number</label>
                                                        <input type="text" name="mobno" value="<?php echo $row->MobileNumber; ?>" class="form-control" maxlength="10" required='true' pattern="[0-9]+">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputName1">Registration Date</label>
                                                        <input type="text" name="regdate" value="<?php echo $row->RegDate; ?>" class="form-control" readonly='true'>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                                                </form>
                                        <?php }
                                        } ?>
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