
<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['teachermsaid']) == 0) {
    exit('Unauthorized');
} else {
    $teacherid = $_SESSION['teachermsaid'];
    $classid = $_GET['classid'];

    // Verify teacher has access to this class
    $verify_sql = "SELECT ClassID FROM tblteacherclass WHERE ClassID=:classid AND TeacherID=:teacherid";
    $verify_query = $dbh->prepare($verify_sql);
    $verify_query->bindParam(':classid', $classid, PDO::PARAM_INT);
    $verify_query->bindParam(':teacherid', $teacherid, PDO::PARAM_INT);
    $verify_query->execute();

    if ($verify_query->rowCount() > 0) {
        $sql = "SELECT ID, StudentName FROM tblstudent WHERE StudentClass = :classid ORDER BY StudentName";
        $query = $dbh->prepare($sql);
        $query->bindParam(':classid', $classid, PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        echo '<option value="">Chọn học sinh</option>';
        foreach ($results as $row) {
            echo '<option value="' . $row->ID . '">' . htmlentities($row->StudentName) . '</option>';
        }
    } else {
        echo '<option value="">Không có quyền truy cập</option>';
    }
}
?>
