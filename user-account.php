<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title>User Account Page - AU Technical Support Management System</title>
    
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

            <h2 class = "text-primary">Welcome, <?= $_SESSION['username'] ?></h2>
            <h5 class = "text-muted">User Type: <?= $_SESSION['usertype'] ?></h5>
            
            <hr>

            <div class = "d-flex justify-content-center gap-2">
                <a href = "login.php" onclick="confirmLogout()" class="btn btn-danger">Logout</a>
            </div>

        </div>
    </div>
</div>

<script>
    function confirmLogout() {
        if (confirm("Are you sure you want to logout now?")) {
            window.location.href = "logout.php";
        }
    }
</script>

<!-- Bootstrap JS (Optional) -->
<script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
