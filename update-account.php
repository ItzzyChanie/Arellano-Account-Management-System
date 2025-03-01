<?php
require_once "config.php";
include ("session-checker.php");

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Fetch account details
    $sql = "SELECT * FROM tblaccounts WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $account = mysqli_fetch_array($result, MYSQLI_ASSOC);

            } else 
            {
                echo "<div class='alert alert-danger'>Account not found.</div>";
                header("location: accounts-management.php");
                exit();
            }
        } else {
            echo "<div class='alert alert-danger'>ERROR on fetching account details.</div>";
        }
    } else 
    {
        echo "<div class='alert alert-danger'>ERROR on preparing fetch statement.</div>";
    }
} else {
    echo "<div class='alert alert-warning'>No username specified for update.</div>";
    header("location: accounts-management.php");
    exit();
}

if (isset($_POST['btnsubmit'])) {
    // Update account details
    $sql = "UPDATE tblaccounts SET password = ?, usertype = ?, status = ? WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssss", $_POST['txtpassword'], $_POST['cmbtype'], $_POST['cmbstatus'], $username);
        
        if (mysqli_stmt_execute($stmt)) 
        {
            echo "<div class='alert alert-success'>Account updated successfully.</div>";
            header("location: accounts-management.php");
            exit();

        } else 
        {
            echo "<div class='alert alert-danger'>ERROR on updating account.</div>";
        }
    } else 
    {
        echo "<div class='alert alert-danger'>ERROR on preparing update statement.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title>Update Account Page - AU Technical Support Management System</title>

    <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel = "stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .form-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
<div class = "container">
    <div class = "row justify-content-center">
        <div class = "col-md-6 form-box">
            <h2 class = "text-center text-primary">Update Account</h2>

            <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?username=' . $username; ?>" method = "POST">

                <div class = "mb-3">
                    <label for = "txtusername" class = "form-label">Username</label>
                    <input type = "text" name = "txtusername" class = "form-control" id = "txtusername" value = "<?php echo $account['username']; ?>" disabled>
                </div>

                <div class = "mb-3">
                    <label for = "txtpassword" class = "form-label">Password</label>
                    <input type = "password" name = "txtpassword" class = "form-control" id = "txtpassword" value = "<?php echo $account['password']; ?>" required>
                </div>

                <div class = "mb-3">
                    <label for = "cmbtype" class = "form-label">Account Type</label>

                    <select name = "cmbtype" id = "cmbtype" class = "form-select" required>
                        <option value = "USER" <?php if ($account['usertype'] == 'USER') echo 'selected'; ?>>User</option>
                        <option value = "ADMINISTRATOR" <?php if ($account['usertype'] == 'ADMINISTRATOR') echo 'selected'; ?>>Administrator</option>
                        <option value = "TECHNICAL" <?php if ($account['usertype'] == 'TECHNICAL') echo 'selected'; ?>>Technical</option>
                        <option value = "STAFF" <?php if ($account['usertype'] == 'STAFF') echo 'selected'; ?>>Staff</option>
                    </select>
                </div>

                <div class = "mb-3">
                    <label for = "cmbstatus" class = "form-label">Status</label>

                    <select name = "cmbstatus" id = "cmbstatus" class = "form-select" required>
                        <option value = "ACTIVE" <?php if ($account['status'] == 'ACTIVE') echo 'selected'; ?>>Active</option>
                        <option value = "INACTIVE" <?php if ($account['status'] == 'INACTIVE') echo 'selected'; ?>>Inactive</option>
                    </select>
                </div>

                <div class = "d-flex justify-content-between">
                    <button type = "submit" name = "btnsubmit" class = "btn btn-success">Submit</button>
                    <a href = "accounts-management.php" class = "btn btn-secondary">Cancel</a>
                </div>
                
            </form>
        </div>
    </div>
</div>

<script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
