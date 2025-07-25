<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT ID FROM tblteacher WHERE UserName=:username and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $_SESSION['teachermsaid'] = $result->ID;
        }

        if (!empty($_POST["remember"])) {
            //COOKIES for username
            setcookie("teacher_login", $_POST["username"], time() + (10 * 365 * 24 * 60 * 60));
            //COOKIES for password
            setcookie("teacherpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60));
        } else {
            if (isset($_COOKIE["teacher_login"])) {
                setcookie("teacher_login", "");
                if (isset($_COOKIE["teacherpassword"])) {
                    setcookie("teacherpassword", "");
                }
            }
        }
        $_SESSION['teacherlogin'] = $_POST['username'];
        echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Student Management System || Teacher Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo" align="center" style="font-weight:bold">
                                Student Management System - Teacher
                            </div>
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            <form class="pt-3" id="login" method="post" name="login">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" placeholder="enter your username" required="true" name="username" value="<?php if (isset($_COOKIE["teacher_login"])) {
                                                                                                                                                                            echo $_COOKIE["teacher_login"];
                                                                                                                                                                        } ?>">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" placeholder="enter your password" name="password" required="true" value="<?php if (isset($_COOKIE["teacherpassword"])) {
                                                                                                                                                                                echo $_COOKIE["teacherpassword"];
                                                                                                                                                                            } ?>">
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-success btn-block loginbtn" name="login" type="submit">Login</button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" id="remember" class="form-check-input" name="remember" <?php if (isset($_COOKIE["teacher_login"])) { ?> checked <?php } ?> /> Keep me signed in </label>
                                    </div>
                                    <a href="forgot-password.php" class="auth-link text-black">Forgot password?</a>
                                </div>
                                <div class="mb-2">
                                    <a href="../index.php" class="btn btn-block btn-facebook auth-form-btn">
                                        <i class="icon-social-home mr-2"></i>Back Home </a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <!-- endinject -->
</body>

</html>