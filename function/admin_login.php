<?php
include('../db/dbconn.php');

if (isset($_POST['enter'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the user from the database
    $result = $conn->query("SELECT * FROM admin WHERE username='$username'")
        or die ('cannot login' . mysqli_error($conn));
    $row = $result->fetch_array();
    $run_num_rows = $result->num_rows;

    if ($run_num_rows > 0) {
        // Verify the password
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['id'] = $row['adminid'];
            header("location:admin_feature.php");
        } else {
            echo 'Invalid username or password.';
        }
    } else {
        echo 'Invalid username or password.';
    }
}
?>
