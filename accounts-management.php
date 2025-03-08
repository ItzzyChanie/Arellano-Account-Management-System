<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Accounts Management Page - AU Technical Support Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(213, 215, 218);
            padding: 20px;
            margin: 0;
        }
        .header {
            background-color: #0078d4;
            color: #ffffff;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            box-sizing: border-box;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .actions {
            display: flex;
            gap: 10px;
        }
        .header .welcome-text {
            font-size: 24px;
            font-weight: bold;
        }
        .header .account-type {
            font-size: 16px;
        }
        .header img {
            height: 50px;
            margin-right: 20px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #0078d4;
            color: #ffffff;
        }
        tr:nth-child(even) {
            background-color: #f3f3f3;
        }
        a.button {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }
        .create-btn {
            background-color: #0078d4;
            color: #ffffff;
        }
        .create-btn:hover {
            background-color: #005bb5;
        }
        .logout-btn {
            background-color:rgb(229, 66, 66);
            color: #ffffff;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .update-btn {
            background-color: #0078d4;
            color: #ffffff;
        }
        .update-btn:hover {
            background-color: #005bb5;
        }
        .delete-btn {
            background-color:rgba(214, 24, 24, 0.75);
            color: #ffffff;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
        .search-container {
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 0 auto;
        }
        .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
            width: 200px;
        }
        .search-container button {
            background-color:rgb(26, 209, 16);
            color: #ffffff;
            border: none;
            padding: 10px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-container button:hover {
            background-color: #1a9d10;
        }
        .search-container button i {
            font-size: 16px;
        }
        .icon-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .list-title {
            font-weight: bold;
            font-size: 25px;
            margin-top: 80px;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            border-radius: 10px;
            background: linear-gradient(270deg, #0078d4,rgb(127, 0, 181),rgb(236, 3, 7));
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>

<body>
    <div class = 'header'>
        <img src = 'picture/Arellano_University_Logo.png' alt='Logo'>
        <div>
            <div class = 'welcome-text'>Welcome, <?= $_SESSION['username'] ?></div>
            <div class = 'account-type'>Account type: <?= $_SESSION['usertype'] ?></div>
        </div>
        
        <form action = '<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>' method = 'POST' class = 'search-container'>
            <input type = 'text' name = 'txtsearch' placeholder = 'Search Account...' required>
            <button type = 'submit' name = 'btnsearch'><i class = 'fas fa-search'></i></button>
        </form>

        <div class = 'actions'>
            <a href = 'create-account.php' class = 'button create-btn icon-btn'><i class = 'fas fa-user-plus'></i> Create new account</a>
            <a href = 'logout.php' class = 'button logout-btn icon-btn'><i class = 'fas fa-sign-out-alt'></i> Logout</a>
        </div>
    </div>

    <div class = "list-title">List of Accounts</div>

    <div class = "container">
        <?php
        function buildtable($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<tr><th>Username</th><th>Usertype</th><th>Status</th><th>Created by</th><th>Date created</th><th>Action</th></tr>";

                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['usertype'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['createdby'] . "</td>";
                    echo "<td>" . $row['datecreated'] . "</td>";
                    echo "<td>";
                    echo "<a href='update-account.php?username=" . $row['username'] . "' class='button update-btn'><i class='fas fa-edit'></i></a>";
                    echo "<a href='#' onclick='confirmDelete(\"" . $row['username'] . "\")' class='button delete-btn'><i class='fas fa-trash'></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No record/s found.";
            }
        }

        require_once "config.php";
        if (isset($_POST['btnsearch'])) {
            $sql = "SELECT * FROM tblaccounts WHERE username LIKE ? OR usertype LIKE ? ORDER BY username";

            if ($stmt = mysqli_prepare($link, $sql)) {
                $searchvalue = '%' . $_POST['txtsearch'] . '%';

                mysqli_stmt_bind_param($stmt, "ss", $searchvalue, $searchvalue);

                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    buildtable($result);

                } else {
                    echo "ERROR on search.";
                }
            }
        } else {
            $sql = "SELECT * FROM tblaccounts ORDER BY username";
            if ($stmt = mysqli_prepare($link, $sql)) {

                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    buildtable($result);
                }
            } else {
                echo "ERROR on loading data.";
            }
        }
        ?>
    </div>

    <script>
    function confirmDelete(username) {
        if (confirm("Are you sure you want to delete this account?")) {
            window.location.href = "delete-account.php?username=" + username;
        }
    }
    </script>

    <!-- Font Awesome JS -->
    <script src = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
