<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title>Accounts Management Page - AU Technical Support Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel = "stylesheet">

    <style>
        body {
            background: linear-gradient(270deg,rgba(65, 14, 233, 0.88),rgba(241, 16, 16, 0.87));
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .container {
            margin-top: 20px;
        }
        .welcome-box {
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php 
    session_start();
    if (!isset($_SESSION['username'])) {
        header("location: login.php");
        exit();
    }
?>

<div class = "container">
    <div class = "row justify-content-center">
        <div class = "col-md-6 welcome-box text-center">
            <h2 class = "text-primary">Welcome, <?= $_SESSION['username'] ?>!</h2>
            <h5 class = "text-muted">User Type: <?= $_SESSION['usertype'] ?></h5>
            
            <hr>

            <div class = "d-flex justify-content-center gap-2">
                <a href = "create-account.php" class = "btn btn-success">Add New Account</a>
                <a href = "logout.php" class = "btn btn-danger">Logout</a>
            </div>

            <hr>
            <form action = "<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">
                <div class = "input-group">
                    <input type = "text" name = "txtsearch" class = "form-control" placeholder = "Search account..." required>
                    <button type = "submit" name = "btnsearch" class = "btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class = "table-container">
        <?php
        function buildtable($result) {
            if (mysqli_num_rows($result) > 0) 
            {
                echo "<table class='table table-striped mt-4'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Username</th><th>Usertype</th><th>Status</th><th>Created by</th><th>Date created</th><th>Action</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                //display the data form in the result
                while ($row = mysqli_fetch_array($result))
                {
                    echo "<tr>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['usertype'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['createdby'] . "</td>";
                    echo "<td>" . $row['datecreated'] . "</td>";
                    echo "<td>";
                    echo "<a href='update-account.php?username=" . $row['username'] . "' class='btn btn-warning btn-sm' style = 'margin-right: 5px;'>Update</a>";
                    echo "<a href='#' onclick='confirmDelete(\"" . $row['username'] . "\")' class='btn btn-danger btn-sm'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            }
            else 
            {
                echo "<p class='text-center mt-4'>No record/s found.</p>";
            }
        }
        //display table
        require_once "config.php";
        //search
        if(isset($_POST['btnsearch']))
        {
            $sql = "SELECT * FROM tblaccounts WHERE username LIKE ? OR usertype LIKE ? ORDER BY username";
            if($stmt = mysqli_prepare($link, $sql))
            {
                $searchvalue = '%' . $_POST['txtsearch'] . '%';
                mysqli_stmt_bind_param($stmt, "ss", $searchvalue, $searchvalue);
                if(mysqli_stmt_execute($stmt))
                {
                    $result = mysqli_stmt_get_result($stmt);
                    buildtable($result);
                }
                else
                {
                    echo "<p class='text-center text-danger mt-4'>ERROR on search.</p>";
                }
            }
        }
        else {
            //display data
            $sql = "SELECT * FROM tblaccounts ORDER BY username";
            if($stmt = mysqli_prepare($link, $sql))
            {
                if(mysqli_stmt_execute($stmt))
                {
                    $result = mysqli_stmt_get_result($stmt);
                    buildtable($result);
                }
            }
            else
            {
                echo "<p class='text-center text-danger mt-4'>ERROR on loading data.</p>";
            }
        }
        ?>
    </div>
</div>

<script>
function confirmDelete(username) {
    if (confirm("Are you sure you want to delete this account?")) {
        window.location.href = "delete-account.php?username=" + username;
    }
}
</script>

<!-- Bootstrap JS (Optional) -->
<script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>