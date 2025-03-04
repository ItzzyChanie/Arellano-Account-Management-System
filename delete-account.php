<?php
require_once "config.php";
include "session-checker.php";

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Delete the account
    $sql = "DELETE FROM tblaccounts WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        if (mysqli_stmt_execute($stmt)) {
            // Log the deletion
            $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                $date = date("d/m/Y");
                $time = date("h:i:sa");
                $action = "delete";
                $module = "account-management";
                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, $username, $_SESSION['username']);
                if (mysqli_stmt_execute($stmt)) {
                    echo "User account deleted";
                } else {
                    echo "<font color='red'>Error on inserting logs.</font>";
                }
            }
        } else {
            echo "<font color='red'>Error on deleting account.</font>";
        }
    } else {
        echo "<font color='red'>Error on preparing delete statement.</font>";
    }
} else {
    echo "<font color='red'>No username specified for deletion.</font>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset = "utf-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <title>Delete Account</title>
</head>

<body>
    <form action = "<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method = "POST">
        <input type = "hidden" name = "txtusername" value = "<?php echo trim($_GET['username']) ?>">

        <p>Are you sure you want to delete account?</p>
        <input type = "submit" name = "btnsubmit" value = "Yes">
        <a href = "accounts-management.php">No</a>
    </form>
</body>
</html>

<?php
header("location: accounts-management.php");
exit();
?>
