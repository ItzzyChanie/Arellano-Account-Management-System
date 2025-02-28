<?php
require_once "config.php";
include ("session-checker.php");

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Delete the account
    $sql = "DELETE FROM tblaccounts WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success'>Account deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>ERROR on deleting account.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>ERROR on preparing delete statement.</div>";
    }
} else {
    echo "<div class='alert alert-warning'>No username specified for deletion.</div>";
}

header("location: accounts-management.php");
exit();
?>
